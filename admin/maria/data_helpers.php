<?php
// Helpers para obtener datos de gráficos filtrados por periodo
// Ubicación: admin/maria/data_helpers.php

include_once __DIR__ . '/../includes/session.php';
include_once __DIR__ . '/../../maria_config.php';

function getPDO(){
    global $pdo;
    return $pdo->open();
}

function range_from_period($period, $date = null, $year = null){
    // Devuelve array[start, end] en formato Y-m-d
    $date = $date ?: date('Y-m-d');
    switch($period){
        case 'day':
            $start = date('Y-m-d', strtotime($date));
            $end = $start;
            break;
        case 'week':
            // semana: 7 días incluyendo la fecha
            $end = date('Y-m-d', strtotime($date));
            $start = date('Y-m-d', strtotime($end . ' -6 days'));
            break;
        case 'month':
            if($year){
                $start = date('Y-m-01', strtotime($year.'-01-01'));
                $end = date('Y-m-t', strtotime($year.'-'.$date.'-01'));
            } else {
                $start = date('Y-m-01', strtotime($date));
                $end = date('Y-m-t', strtotime($date));
            }
            break;
        case 'year':
            $y = $year ?: date('Y', strtotime($date));
            $start = $y.'-01-01';
            $end = $y.'-12-31';
            break;
        default:
            $start = date('Y-m-d', strtotime($date));
            $end = $start;
    }
    return [$start, $end];
}

function get_sales_between($start, $end){
    $conn = getPDO();
    try{
        $stmt = $conn->prepare("SELECT SUM(products.price * details.quantity) AS total
            FROM details
            LEFT JOIN sales ON sales.id = details.sales_id
            LEFT JOIN products ON products.id = details.product_id
            WHERE DATE(sales_date) BETWEEN :start AND :end");
        $stmt->execute(['start'=>$start, 'end'=>$end]);
        $row = $stmt->fetch();
        return $row['total'] ? floatval($row['total']) : 0;
    }catch(PDOException $e){
        return 0;
    }
}

function get_daily_series($start, $end){
    $conn = getPDO();
    $labels = [];
    $data = [];
    $begin = new DateTime($start);
    $endd = new DateTime($end);
    $interval = DateInterval::createFromDateString('1 day');
    $period = new DatePeriod($begin, $interval, $endd->modify('+1 day'));
    foreach($period as $dt){
        $d = $dt->format('Y-m-d');
        $labels[] = $dt->format('d/m');
        $conn = getPDO();
        $stmt = $conn->prepare("SELECT SUM(products.price * details.quantity) AS total
            FROM details
            LEFT JOIN sales ON sales.id = details.sales_id
            LEFT JOIN products ON products.id = details.product_id
            WHERE DATE(sales_date) = :date");
        $stmt->execute(['date'=>$d]);
        $row = $stmt->fetch();
        $data[] = $row['total'] ? floatval($row['total']) : 0;
    }
    return ['labels'=>$labels, 'data'=>$data];
}

function get_monthly_series_year($year){
    $conn = getPDO();
    $labels = [];
    $data = [];
    for($m=1;$m<=12;$m++){
        $labels[] = date('M', mktime(0,0,0,$m,1));
        $stmt = $conn->prepare("SELECT SUM(products.price * details.quantity) AS total
            FROM details
            LEFT JOIN sales ON sales.id=details.sales_id
            LEFT JOIN products ON products.id = details.product_id
            WHERE MONTH(sales_date)=:month AND YEAR(sales_date)=:year");
        $stmt->execute(['month'=>$m, 'year'=>$year]);
        $row = $stmt->fetch();
        $data[] = $row['total'] ? floatval($row['total']) : 0;
    }
    return ['labels'=>$labels, 'data'=>$data];
}

function get_top_products_between($start, $end, $limit=10){
    $conn = getPDO();
    $stmt = $conn->prepare("SELECT products.name, SUM(details.quantity) as total_sold
        FROM details
        LEFT JOIN sales ON sales.id=details.sales_id
        LEFT JOIN products ON products.id=details.product_id
        WHERE DATE(sales_date) BETWEEN :start AND :end
        GROUP BY details.product_id
        ORDER BY total_sold DESC
        LIMIT :lim");
    $stmt->bindValue(':start', $start);
    $stmt->bindValue(':end', $end);
    $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    $names = [];
    $vals = [];
    foreach($stmt as $row){
        $names[] = $row['name'];
        $vals[] = (int)$row['total_sold'];
    }
    return ['labels'=>$names, 'data'=>$vals];
}

function get_category_sales_between($start, $end){
    $conn = getPDO();
    $stmt = $conn->prepare("SELECT category.name, SUM(details.quantity * products.price) as total
        FROM details
        LEFT JOIN products ON products.id=details.product_id
        LEFT JOIN category ON category.id=products.category_id
        LEFT JOIN sales ON sales.id=details.sales_id
        WHERE DATE(sales_date) BETWEEN :start AND :end
        GROUP BY products.category_id");
    $stmt->execute(['start'=>$start, 'end'=>$end]);
    $labels = [];
    $data = [];
    foreach($stmt as $row){
        $labels[] = $row['name'];
        $data[] = floatval($row['total']);
    }
    return ['labels'=>$labels, 'data'=>$data];
}

function get_yearly_series_last_n($n=5, $year=null){
    $conn = getPDO();
    $labels = [];
    $data = [];
    $year = $year ?: date('Y');
    for($i=$n-1;$i>=0;$i--){
        $y = $year - $i;
        $stmt = $conn->prepare("SELECT SUM(products.price * details.quantity) AS total
            FROM details
            LEFT JOIN sales ON sales.id = details.sales_id
            LEFT JOIN products ON products.id = details.product_id
            WHERE YEAR(sales_date) = :year");
        $stmt->execute(['year'=>$y]);
        $row = $stmt->fetch();
        $labels[] = $y;
        $data[] = $row['total'] ? floatval($row['total']) : 0;
    }
    return ['labels'=>$labels, 'data'=>$data];
}

function get_product_views_top($limit=10){
    $conn = getPDO();
    $stmt = $conn->prepare("SELECT name, counter FROM products ORDER BY counter DESC LIMIT :lim");
    $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    $labels = [];
    $data = [];
    foreach($stmt as $row){
        $labels[] = $row['name'];
        $data[] = (int)$row['counter'];
    }
    return ['labels'=>$labels, 'data'=>$data];
}

function closePDO($conn){
    global $pdo;
    $pdo->close();
}

?>

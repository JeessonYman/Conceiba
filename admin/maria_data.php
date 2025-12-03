<?php
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/maria_data_helpers.php';

$type = isset($_GET['type']) ? $_GET['type'] : 'sales';
$period = isset($_GET['period']) ? $_GET['period'] : 'month';
$date = isset($_GET['date']) ? $_GET['date'] : null;
$year = isset($_GET['year']) ? $_GET['year'] : null;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;

try {
    switch ($type) {
        case 'sales_between':
            list($start, $end) = range_from_period($period, $date, $year);
            $total = get_sales_between($start, $end);
            echo json_encode(['ok' => true, 'start' => $start, 'end' => $end, 'total' => $total]);
            break;

        case 'daily_series':
            list($start, $end) = range_from_period($period, $date, $year);
            echo json_encode(['ok' => true] + get_daily_series($start, $end));
            break;

        case 'monthly_year':
            $y = $year ?: date('Y');
            echo json_encode(['ok' => true] + get_monthly_series_year($y));
            break;

        case 'top_products':
            list($start, $end) = range_from_period($period, $date, $year);
            echo json_encode(['ok' => true] + get_top_products_between($start, $end, $limit));
            break;

        case 'category_sales':
            list($start, $end) = range_from_period($period, $date, $year);
            echo json_encode(['ok' => true] + get_category_sales_between($start, $end));
            break;

        case 'inputs':
            list($start, $end) = range_from_period($period, $date, $year);
            // Reusar daily_series for inputs not implemented separately; fallback: return sales as proxy
            echo json_encode(['ok' => true] + get_daily_series($start, $end));
            break;

        case 'compare_years':
            // devuelve series mensuales para year and year-1
            $y = $year ?: date('Y');
            $current = get_monthly_series_year($y);
            $previous = get_monthly_series_year($y - 1);
            echo json_encode(['ok' => true, 'labels' => $current['labels'], 'current' => $current['data'], 'previous' => $previous['data']]);
            break;

        case 'yearly_series':
            $y = $year ?: date('Y');
            echo json_encode(['ok' => true] + get_yearly_series_last_n(5, $y));
            break;

        case 'views_top':
            echo json_encode(['ok' => true] + get_product_views_top($limit));
            break;

        default:
            // Por defecto devolver ventas del periodo en serie diaria
            list($start, $end) = range_from_period($period, $date, $year);
            echo json_encode(['ok' => true] + get_daily_series($start, $end));
    }
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}

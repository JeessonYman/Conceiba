<?php
include 'includes/session.php';
include_once __DIR__ . '/../includes/site_settings.php';
$conceibaSettings = getSiteSettings($pdo);

// Cargar TCPDF antes de usarla
require_once('../tcpdf/tcpdf.php');

// Clase personalizada para el PDF con diseño de Conceiba
class IncomeReportPDF extends TCPDF {
    public function Header() {
        global $conceibaSettings;
        $storeName = $conceibaSettings['store_name'] ?? 'Conceiba';
        $tagline = $conceibaSettings['store_tagline'] ?? '';
        $ruc = $conceibaSettings['company_ruc'] ?? '';
        $address = $conceibaSettings['company_address'] ?? '';
        $logo = $conceibaSettings['logo'] ?? 'logo.png';

        // Logo
        $image_file = '../images/'.$logo;
        $this->Image($image_file, 15, 10, 25, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        // Título de la empresa
        $this->SetFont('helvetica', 'B', 20);
        $this->SetTextColor(46, 125, 50);
        $this->SetXY(45, 12);
        $this->Cell(0, 10, strtoupper($storeName), 0, 1, 'L');
        
        // Subtítulo
        $this->SetFont('helvetica', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY(45, 20);
        $this->Cell(0, 5, $tagline, 0, 1, 'L');
        
        // Información de la empresa
        $this->SetFont('helvetica', '', 8);
        $this->SetXY(45, 26);
        $rucLine = trim($ruc !== '' ? 'RUC: '.$ruc.' | '.$address : $address);
        $this->Cell(0, 4, $rucLine, 0, 1, 'L');
        
        // Línea decorativa
        $this->SetDrawColor(46, 125, 50);
        $this->SetLineWidth(0.8);
        $this->Line(15, 35, 195, 35);
    }
    
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        global $conceibaSettings;
        $footerName = ($conceibaSettings['store_name'] ?? 'Conceiba').' - Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages();
        $this->Cell(0, 10, $footerName, 0, 0, 'C');
    }
}

function parseDateFlexible($dateStr){
    $dateStr = trim($dateStr);
    if(empty($dateStr)) return null;
    $formats = ['m/d/Y', 'd/m/Y', 'Y-m-d', 'F d, Y', 'M d, Y', 'Y/m/d'];
    foreach($formats as $fmt){
        $d = DateTime::createFromFormat($fmt, $dateStr);
        if($d && $d->format($fmt) === $dateStr){
            return $d;
        }
    }
    $ts = strtotime($dateStr);
    if($ts !== false && $ts > 0){
        return (new DateTime())->setTimestamp($ts);
    }
    return null;
}

if(isset($_POST['print'])){
    $rawRange = isset($_POST['date_range']) ? trim($_POST['date_range']) : '';
    $parts = preg_split('/\s+-\s+|\s+–\s+|\s+—\s+/', $rawRange);
    $startDT = parseDateFlexible($parts[0] ?? '');
    $endDT = parseDateFlexible($parts[1] ?? ($parts[0] ?? ''));
    
    if(!$startDT || !$endDT){
        $_SESSION['error'] = 'Formato de rango de fechas inválido. Use el selector de fechas.';
        header('location: Income.php');
        exit();
    }
    
    $from = $startDT->format('Y-m-d');
    $to = $endDT->format('Y-m-d');
    $from_title = $startDT->format('d/m/Y');
    $to_title = $endDT->format('d/m/Y');

    $conn = $pdo->open();

    require_once('../tcpdf/tcpdf.php');
    
    $pdf = new IncomeReportPDF('L', 'mm', 'A4', true, 'UTF-8', false); // Landscape para más columnas
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Conceiba SAC');
    $pdf->SetTitle('Reporte de Ingresos');
    $pdf->SetSubject('Reporte de Ingresos del '.$from_title.' al '.$to_title);
    
    $pdf->SetMargins(15, 40, 15);
    $pdf->SetAutoPageBreak(TRUE, 20);
    $pdf->AddPage();
    
    // Título del reporte
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor(231, 76, 60);
    $pdf->Cell(0, 10, 'REPORTE DE INGRESOS', 0, 1, 'C');
    
    // Rango de fechas
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 8, 'Período: '.$from_title.' - '.$to_title, 0, 1, 'C');
    $pdf->Ln(3);
    
    // Consulta de ingresos
    $sql = "SELECT di.id AS detail_id, i.id AS input_id, i.entry_date AS entry_date, 
            c.name AS category_name, CONCAT(u.firstname, ' ', u.lastname) AS user_name, 
            p.name AS provider_name, pr.name AS product_name, di.price AS unit_price, 
            di.quantity AS quantity, pr.cost AS cost, pr.price AS price_normal, 
            pr.stock AS stock, pr.stock_minimum AS stock_minimum 
            FROM details_inputs di 
            LEFT JOIN inputs i ON di.id_inputs = i.id 
            LEFT JOIN products pr ON pr.id = di.id_products 
            LEFT JOIN provider p ON i.provider_id = p.id 
            LEFT JOIN users u ON i.user_id = u.id 
            LEFT JOIN category c ON pr.category_id = c.id 
            WHERE i.entry_date BETWEEN :from AND :to 
            ORDER BY i.entry_date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute(['from' => $from, 'to' => $to]);
    
    // Encabezado de tabla
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetFillColor(46, 125, 50);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetDrawColor(46, 125, 50);
    
    $pdf->Cell(20, 7, 'Fecha', 1, 0, 'C', true);
    $pdf->Cell(22, 7, 'Categoría', 1, 0, 'C', true);
    $pdf->Cell(25, 7, 'Usuario', 1, 0, 'C', true);
    $pdf->Cell(25, 7, 'Proveedor', 1, 0, 'C', true);
    $pdf->Cell(50, 7, 'Producto', 1, 0, 'C', true);
    $pdf->Cell(20, 7, 'Precio', 1, 0, 'C', true);
    $pdf->Cell(18, 7, 'Costo', 1, 0, 'C', true);
    $pdf->Cell(20, 7, 'P. Normal', 1, 0, 'C', true);
    $pdf->Cell(15, 7, 'Cant.', 1, 0, 'C', true);
    $pdf->Cell(18, 7, 'Stock', 1, 0, 'C', true);
    $pdf->Cell(18, 7, 'S. Mín', 1, 0, 'C', true);
    $pdf->Cell(22, 7, 'Subtotal', 1, 1, 'C', true);
    
    // Datos de la tabla
    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetTextColor(60, 60, 60);
    $total = 0;
    $totalItems = 0;
    $alternate = false;
    
    foreach($stmt as $row){
        $totalItems++;
        $subtotal = $row['unit_price'] * $row['quantity'];
        $total += $subtotal;
        
        // Alternar color de fondo
        if($alternate){
            $pdf->SetFillColor(249, 249, 249);
        } else {
            $pdf->SetFillColor(255, 255, 255);
        }
        $alternate = !$alternate;
        
        $pdf->Cell(20, 6, date('d/m/Y', strtotime($row['entry_date'])), 1, 0, 'C', true);
        $pdf->Cell(22, 6, substr(htmlspecialchars($row['category_name']), 0, 12), 1, 0, 'L', true);
        $pdf->Cell(25, 6, substr(htmlspecialchars($row['user_name']), 0, 15), 1, 0, 'L', true);
        $pdf->Cell(25, 6, substr(htmlspecialchars($row['provider_name']), 0, 15), 1, 0, 'L', true);
        $pdf->Cell(50, 6, substr(htmlspecialchars($row['product_name']), 0, 30), 1, 0, 'L', true);
        $pdf->Cell(20, 6, 'S/ '.number_format($row['unit_price'], 2), 1, 0, 'R', true);
        $pdf->Cell(18, 6, 'S/ '.number_format($row['cost'], 2), 1, 0, 'R', true);
        $pdf->Cell(20, 6, 'S/ '.number_format($row['price_normal'], 2), 1, 0, 'R', true);
        $pdf->Cell(15, 6, intval($row['quantity']), 1, 0, 'C', true);
        $pdf->Cell(18, 6, intval($row['stock']), 1, 0, 'C', true);
        $pdf->Cell(18, 6, intval($row['stock_minimum']), 1, 0, 'C', true);
        
        // Subtotal en color rojo
        $pdf->SetTextColor(231, 76, 60);
        $pdf->Cell(22, 6, 'S/ '.number_format($subtotal, 2), 1, 1, 'R', true);
        $pdf->SetTextColor(60, 60, 60);
        
        // Verificar si necesita nueva página
        if($pdf->GetY() > 180){
            $pdf->AddPage();
            
            // Re-imprimir encabezado de tabla
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetFillColor(46, 125, 50);
            $pdf->SetTextColor(255, 255, 255);
            
            $pdf->Cell(20, 7, 'Fecha', 1, 0, 'C', true);
            $pdf->Cell(22, 7, 'Categoría', 1, 0, 'C', true);
            $pdf->Cell(25, 7, 'Usuario', 1, 0, 'C', true);
            $pdf->Cell(25, 7, 'Proveedor', 1, 0, 'C', true);
            $pdf->Cell(50, 7, 'Producto', 1, 0, 'C', true);
            $pdf->Cell(20, 7, 'Precio', 1, 0, 'C', true);
            $pdf->Cell(18, 7, 'Costo', 1, 0, 'C', true);
            $pdf->Cell(20, 7, 'P. Normal', 1, 0, 'C', true);
            $pdf->Cell(15, 7, 'Cant.', 1, 0, 'C', true);
            $pdf->Cell(18, 7, 'Stock', 1, 0, 'C', true);
            $pdf->Cell(18, 7, 'S. Mín', 1, 0, 'C', true);
            $pdf->Cell(22, 7, 'Subtotal', 1, 1, 'C', true);
            
            $pdf->SetFont('helvetica', '', 7);
            $pdf->SetTextColor(60, 60, 60);
        }
    }
    
    if($totalItems == 0){
        $pdf->SetFont('helvetica', 'I', 10);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 30, 'No hay registros de ingresos en el período seleccionado', 0, 1, 'C');
    } else {
        // Fila de total
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(46, 125, 50);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(251, 7, 'TOTAL GENERAL', 1, 0, 'R', true);
        $pdf->SetFillColor(231, 76, 60);
        $pdf->Cell(22, 7, 'S/ '.number_format($total, 2), 1, 1, 'R', true);
        
        // Resumen
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(46, 125, 50);
        $pdf->Cell(0, 8, 'Total de registros: '.$totalItems.' | Total invertido: S/ '.number_format($total, 2), 0, 1, 'C');
    }
    
    $pdf->Output('reporte_ingresos_'.date('Y-m-d').'.pdf', 'I');
    $pdo->close();
}
else{
    $_SESSION['error'] = 'Necesita rango de fechas para imprimir ingresos';
    header('location: Income.php');
}
?>
<?php
include 'includes/session.php';

// Cargar TCPDF primero
require_once('../tcpdf/tcpdf.php');

// Clase personalizada para el PDF con diseño de Conceiba
class SalesReportPDF extends TCPDF {
    public function Header() {
        // Logo
        $image_file = '../images/logo.png';
        $this->Image($image_file, 15, 10, 25, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        
        // Título de la empresa
        $this->SetFont('helvetica', 'B', 20);
        $this->SetTextColor(46, 125, 50);
        $this->SetXY(45, 12);
        $this->Cell(0, 10, 'CONCEIBA', 0, 1, 'L');
        
        // Subtítulo
        $this->SetFont('helvetica', '', 10);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY(45, 20);
        $this->Cell(0, 5, 'Economía verde', 0, 1, 'L');
        
        // Información de la empresa
        $this->SetFont('helvetica', '', 8);
        $this->SetXY(45, 26);
        $this->Cell(0, 4, 'RUC: 20603824874 | Av. Inca Garcilaso de la Vega 1236, Lima', 0, 1, 'L');
        
        // Línea decorativa
        $this->SetDrawColor(46, 125, 50);
        $this->SetLineWidth(0.8);
        $this->Line(15, 35, 195, 35);
    }
    
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 10, 'Conceiba SAC - https://conceibaviernes.rf.gd/ - Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 0, 'C');
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
        header('location: sales.php');
        exit();
    }
    
    $from = $startDT->format('Y-m-d');
    $to = $endDT->format('Y-m-d');
    $from_title = $startDT->format('d/m/Y');
    $to_title = $endDT->format('d/m/Y');

    $conn = $pdo->open();
    
    $pdf = new SalesReportPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Conceiba SAC');
    $pdf->SetTitle('Reporte de Ventas');
    $pdf->SetSubject('Reporte de Ventas del '.$from_title.' al '.$to_title);
    
    $pdf->SetMargins(15, 40, 15);
    $pdf->SetAutoPageBreak(TRUE, 20);
    $pdf->AddPage();
    
    // Título del reporte
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetTextColor(231, 76, 60);
    $pdf->Cell(0, 10, 'REPORTE DE VENTAS', 0, 1, 'C');
    
    // Rango de fechas
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 8, 'Período: '.$from_title.' - '.$to_title, 0, 1, 'C');
    $pdf->Ln(5);
    
    // Consulta de ventas
    $stmt = $conn->prepare("SELECT *, sales.id AS salesid 
                            FROM sales 
                            LEFT JOIN users ON users.id=sales.user_id 
                            WHERE sales_date BETWEEN :from AND :to 
                            ORDER BY sales_date DESC");
    $stmt->execute(['from' => $from, 'to' => $to]);
    
    $grandTotal = 0;
    $totalSales = 0;
    
    foreach($stmt as $row){
        $totalSales++;
        
        // Obtener detalles de productos de esta venta
        $stmtDetails = $conn->prepare("SELECT * FROM details 
                                       LEFT JOIN products ON products.id=details.product_id 
                                       WHERE details.sales_id=:id");
        $stmtDetails->execute(['id'=>$row['salesid']]);
        
        $saleTotal = 0;
        $products = [];
        
        foreach($stmtDetails as $detail){
            $subtotal = $detail['price'] * $detail['quantity'];
            $saleTotal += $subtotal;
            $products[] = [
                'name' => $detail['name'],
                'quantity' => $detail['quantity'],
                'price' => $detail['price'],
                'subtotal' => $subtotal
            ];
        }
        
        $grandTotal += $saleTotal;
        
        // Cuadro de venta individual
        $pdf->SetFillColor(249, 249, 249);
        $pdf->SetDrawColor(46, 125, 50);
        $pdf->SetLineWidth(0.3);
        $pdf->RoundedRect(15, $pdf->GetY(), 180, 8, 2, '1111', 'DF');
        
        // Información de la venta en el encabezado
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(46, 125, 50);
        $yPos = $pdf->GetY() + 2;
        $pdf->SetXY(20, $yPos);
        $pdf->Cell(50, 4, 'Fecha: '.date('d/m/Y', strtotime($row['sales_date'])), 0, 0, 'L');
        
        $pdf->SetXY(90, $yPos);
        $pdf->Cell(50, 4, 'Cliente: '.htmlspecialchars($row['firstname'].' '.$row['lastname']), 0, 0, 'L');
        
        $pdf->SetXY(150, $yPos);
        $pdf->SetTextColor(231, 76, 60);
        $pdf->Cell(40, 4, 'S/ '.number_format($saleTotal, 2), 0, 1, 'R');
        
        $pdf->Ln(2);
        
        // ID de transacción
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->SetX(20);
        $pdf->Cell(0, 4, 'ID PayPal: '.htmlspecialchars($row['pay_id']), 0, 1, 'L');
        
        $pdf->Ln(2);
        
        // Tabla de productos
        if(count($products) > 0){
            // Encabezado de tabla
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(46, 125, 50);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->SetDrawColor(46, 125, 50);
            
            $pdf->SetX(20);
            $pdf->Cell(15, 6, 'Cant.', 1, 0, 'C', true);
            $pdf->Cell(95, 6, 'Producto', 1, 0, 'L', true);
            $pdf->Cell(30, 6, 'Precio Unit.', 1, 0, 'R', true);
            $pdf->Cell(30, 6, 'Subtotal', 1, 1, 'R', true);
            
            // Productos
            $pdf->SetFont('helvetica', '', 8);
            $pdf->SetTextColor(60, 60, 60);
            $pdf->SetFillColor(255, 255, 255);
            
            foreach($products as $product){
                $pdf->SetX(20);
                $pdf->Cell(15, 5, $product['quantity'], 1, 0, 'C');
                $pdf->Cell(95, 5, htmlspecialchars($product['name']), 1, 0, 'L');
                $pdf->Cell(30, 5, 'S/ '.number_format($product['price'], 2), 1, 0, 'R');
                $pdf->Cell(30, 5, 'S/ '.number_format($product['subtotal'], 2), 1, 1, 'R');
            }
        }
        
        $pdf->Ln(8);
        
        // Verificar si necesita nueva página
        if($pdf->GetY() > 250){
            $pdf->AddPage();
        }
    }
    
    // Resumen final
    $pdf->SetDrawColor(231, 76, 60);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    $pdf->Ln(5);
    
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetTextColor(46, 125, 50);
    $pdf->Cell(0, 8, 'TOTAL DE VENTAS: '.$totalSales, 0, 1, 'R');
    
    $pdf->SetTextColor(231, 76, 60);
    $pdf->Cell(0, 8, 'TOTAL GENERAL: S/ '.number_format($grandTotal, 2), 0, 1, 'R');
    
    if($totalSales == 0){
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'I', 11);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 10, 'No hay ventas registradas en el período seleccionado', 0, 1, 'C');
    }
    
    $pdf->Output('reporte_ventas_'.date('Y-m-d').'.pdf', 'I');
    $pdo->close();
}
else{
    $_SESSION['error'] = 'Necesita rango de fechas para imprimir ventas';
    header('location: sales.php');
}
?>
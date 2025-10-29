<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once('tcpdf/tcpdf.php');
include 'includes/session.php';

// Clase personalizada para el PDF
class TicketPDF extends TCPDF {
    public function Header() {}
    public function Footer() {}
}

try {
    $conn = $pdo->open();
    
    if (isset($_GET['pay'])) {
        $payid = $_GET['pay'];
        $date = date('Y-m-d');
        $datetime = date('d/m/Y');
        $time = date('H:i:s');
        
        // Insertar la venta
        $stmt = $conn->prepare("INSERT INTO sales (user_id, pay_id, sales_date) VALUES (:user_id, :pay_id, :sales_date)");
        $stmt->execute(['user_id' => $user['id'], 'pay_id' => $payid, 'sales_date' => $date]);
        $salesid = $conn->lastInsertId();
        
        // Obtener datos del cliente
        $stmt = $conn->prepare("SELECT firstname, lastname, email FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $user['id']]);
        $userData = $stmt->fetch();
        $clientName = $userData['firstname'] . ' ' . $userData['lastname'];
        $clientEmail = $userData['email'];
        
        // Obtener productos
        $clientName = trim(($userData['firstname'] ?? '') . ' ' . ($userData['lastname'] ?? ''));
        if (empty($clientName)) {
            $clientName = 'Cliente';
        }
        $stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user_id");
        $stmt->execute(['user_id' => $user['id']]);
        
        $total = 0;
        $products = [];
        
        foreach ($stmt as $row) {
            $stmtDetail = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
            $stmtDetail->execute(['sales_id' => $salesid, 'product_id' => $row['product_id'], 'quantity' => $row['quantity']]);
            
            $updateStock = $conn->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :product_id");
            $updateStock->execute(['quantity' => $row['quantity'], 'product_id' => $row['product_id']]);
            
            $productSubtotal = $row['quantity'] * $row['price'];
            $total += $productSubtotal;
            
            $products[] = [
                'name' => $row['name'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'subtotal' => $productSubtotal
            ];
        }
        // Evitar pasar un array vacío a TCPDF (causa de max() en tcpdf.php)
        if (count($products) == 0) {
            $products[] = [
                'name' => 'Sin productos',
                'quantity' => 0,
                'price' => 0,
                'subtotal' => 0
            ];
        }
        
        $stmtDelete = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
        $stmtDelete->execute(['user_id' => $user['id']]);
        
        // Calcular IGV
        $subtotal = $total / 1.18;
        $igv = $total - $subtotal;
        
        // Crear PDF con tamaño personalizado (ticket 80mm de ancho)
        $pdf = new TicketPDF('P', 'mm', array(80, 297), true, 'UTF-8', false);
        $pdf->SetCreator('Conceiba SAC');
        $pdf->SetAuthor('Conceiba SAC');
        $pdf->SetTitle('Comprobante de Pago');
        
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(5, 5, 5);
        $pdf->SetAutoPageBreak(true, 5);
        $pdf->AddPage();
        
        // Borde decorativo superior (efecto de corte perforado)
        $pdf->SetDrawColor(231, 76, 60);
        for ($x = 8; $x < 72; $x += 4) {
            $pdf->Line($x, 2, $x + 2, 2);
        }
        
        // ============= ENCABEZADO CON GRADIENTE =============
        // Crear efecto de gradiente más suave con más pasos
        $headerHeight = 50;
        $steps = 100;
        for ($i = 0; $i < $steps; $i++) {
            $ratio = $i / $steps;
            // Interpolar entre color verde (46,125,50) y secundario (231,76,60)
            $r = 46 + ($ratio * (231 - 46));
            $g = 125 + ($ratio * (76 - 125));
            $b = 50 + ($ratio * (60 - 50));
            $pdf->SetFillColor($r, $g, $b);
            $pdf->Rect(0, ($i * $headerHeight / $steps), 80, ($headerHeight / $steps) + 0.1, 'F');
        }
        
        // Forma decorativa tipo flecha invertida en la parte inferior
        $pdf->SetFillColor(46, 125, 50);
        $points = array(
            0, $headerHeight,
            35, $headerHeight,
            40, $headerHeight + 5,
            45, $headerHeight,
            80, $headerHeight,
            80, $headerHeight + 5,
            0, $headerHeight + 5
        );
        $pdf->Polygon($points, 'F');
        
        // Logo de la empresa
        $logoUrl = 'https://conceibaviernes.rf.gd/images/logo.png';
        //$logoUrl = 'https://media.licdn.com/dms/image/v2/C4E0BAQFOqWDmJTtS1g/company-logo_200_200/company-logo_200_200/0/1630632934650?e=2147483647&v=beta&t=2HwTgM8xBvw2IErPxSUjRG4y6LCUT-CclUbt54TEeog';
        $pdf->Image($logoUrl, 28, 6, 24, 14, 'PNG', '', '', true, 150, '', false, false, 0);
        
        // Texto del encabezado
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetXY(5, 22);
        $pdf->Cell(70, 5, 'CONCEIBA', 0, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetXY(5, 28);
        $pdf->Cell(70, 3, 'Economía verde', 0, 1, 'C');
        
        $pdf->SetFont('helvetica', '', 6);
        $pdf->SetXY(5, 33);
        $pdf->MultiCell(70, 3, 
            "RUC: 20603824874\n" .
            "Av. Inca Garcilaso de la Vega 1236\n" .
            "Cercado de Lima, Lima, Perú\n" .
            "https://conceibaviernes.rf.gd/", 
            0, 'C', false);
        
        // ============= CUERPO DEL TICKET =============
        $yPos = $headerHeight + 10;
        $pdf->SetTextColor(0, 0, 0);
        
        // Grid de información de transacción (2 columnas)
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(231, 76, 60);
        
        // Fila 1: Ticket # y Fecha
        $pdf->SetXY(8, $yPos);
        $pdf->Cell(30, 3, 'TICKET #', 0, 0, 'L');
        $pdf->SetXY(42, $yPos);
        $pdf->Cell(30, 3, 'FECHA', 0, 1, 'L');
        
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(8, $yPos + 3);
        $pdf->Cell(30, 4, 'T-' . str_pad($salesid, 6, '0', STR_PAD_LEFT), 0, 0, 'L');
        $pdf->SetXY(42, $yPos + 3);
        $pdf->Cell(30, 4, $datetime, 0, 1, 'L');
        
        // Fila 2: Hora y Cajero (Cliente)
        $yPos += 10;
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(231, 76, 60);
        $pdf->SetXY(8, $yPos);
        $pdf->Cell(30, 3, 'HORA', 0, 0, 'L');
        $pdf->SetXY(42, $yPos);
        $pdf->Cell(30, 3, 'CLIENTE', 0, 1, 'L');
        
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(8, $yPos + 3);
        $pdf->Cell(30, 4, $time, 0, 0, 'L');
        $pdf->SetXY(42, $yPos + 3);
        
        // Ajustar tamaño de fuente si el nombre es muy largo
        if (strlen($clientName) > 20) {
            $pdf->SetFont('helvetica', '', 6);
        }
        $pdf->MultiCell(30, 4, $clientName, 0, 'L', false, 1, 42, $yPos + 3);
        
        // Restaurar fuente normal
        $pdf->SetFont('helvetica', '', 8);
        
        // ID PayPal (ocupa toda la fila)
        $yPos += 10;
        $pdf->SetFont('helvetica', 'B', 7);
        $pdf->SetTextColor(231, 76, 60);
        $pdf->SetXY(8, $yPos);
        $pdf->Cell(64, 3, 'ID PAYPAL', 0, 1, 'L');
        
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(8, $yPos + 3);
        $pdf->Cell(64, 4, substr($payid, 0, 30), 0, 1, 'L');
        
        // ============= TABLA DE PRODUCTOS =============
        $yPos += 12;
        
        // Encabezado de tabla
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(46, 125, 50);
        $pdf->SetXY(8, $yPos);
        $pdf->Cell(10, 4, 'Cant', 0, 0, 'C');
        $pdf->Cell(42, 4, 'Descripción', 0, 0, 'L');
        $pdf->Cell(12, 4, 'Total', 0, 1, 'R');
        
        // Línea bajo encabezado
        $yPos += 4;
        $pdf->SetDrawColor(231, 76, 60);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(8, $yPos, 72, $yPos);
        
        // Productos
        $yPos += 2;
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(0, 0, 0);
        
        foreach ($products as $product) {
            $yPos += 5;
            
            $pdf->SetXY(8, $yPos);
            $pdf->Cell(10, 4, $product['quantity'], 0, 0, 'C');
            
            // Mostrar nombre completo del producto (puede ocupar varias líneas si es necesario)
            $pdf->MultiCell(42, 4, $product['name'], 0, 'L', false, 0, 18, $yPos);
            
            // Total alineado a la derecha
            $pdf->SetXY(60, $yPos);
            $pdf->Cell(12, 4, 'S/ ' . number_format($product['subtotal'], 2), 0, 1, 'R');
            
            // Línea punteada (ajustar altura según si el nombre fue multilínea)
            $lineHeight = (strlen($product['name']) > 25) ? 8 : 4;
            $yPos += $lineHeight;
            $pdf->SetDrawColor(224, 224, 224);
            $pdf->SetLineStyle(array('width' => 0.1, 'dash' => '2,2'));
            $pdf->Line(8, $yPos, 72, $yPos);
        }
        
        // ============= TOTALES =============
        $yPos += 5;
        
        // Línea azul superior
        $pdf->SetDrawColor(52, 152, 219);
        $pdf->SetLineWidth(0.5);
        $pdf->SetLineStyle(array('width' => 0.5, 'dash' => 0));
        $pdf->Line(8, $yPos, 72, $yPos);
        
        $yPos += 5;
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(0, 0, 0);
        
        // Subtotal
        $pdf->SetXY(8, $yPos);
        $pdf->Cell(52, 4, 'Subtotal:', 0, 0, 'L');
        $pdf->Cell(12, 4, 'S/ ' . number_format($subtotal, 2), 0, 1, 'R');
        
        // IGV
        $yPos += 5;
        $pdf->SetXY(8, $yPos);
        $pdf->Cell(52, 4, 'IGV (18%):', 0, 0, 'L');
        $pdf->Cell(12, 4, 'S/ ' . number_format($igv, 2), 0, 1, 'R');
        
        // Línea punteada roja antes del total
        $yPos += 5;
        $pdf->SetDrawColor(231, 76, 60);
        $pdf->SetLineStyle(array('width' => 0.3, 'dash' => '2,2'));
        $pdf->Line(8, $yPos, 72, $yPos);
        
        // TOTAL
        $yPos += 5;
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetTextColor(231, 76, 60);
        $pdf->SetXY(8, $yPos);
        $pdf->Cell(52, 5, 'TOTAL:', 0, 0, 'L');
        $pdf->Cell(12, 5, 'S/ ' . number_format($total, 2), 0, 1, 'R');
        
        // ============= FOOTER =============
        $yPos += 10;
        
        // Fondo gris claro
        $pdf->SetFillColor(249, 249, 249);
        $pdf->Rect(0, $yPos, 80, 60, 'F');
        
        // Borde superior punteado
        $pdf->SetDrawColor(224, 224, 224);
        $pdf->SetLineStyle(array('width' => 0.1, 'dash' => '2,2'));
        $pdf->Line(8, $yPos, 72, $yPos);
        
        $yPos += 5;
        
        // Código QR con enlace directo al PDF
        $qrData = 'https://conceibaviernes.rf.gd/pdfs/comprobante_' . $salesid . '.pdf';
        $style = array(
            'border' => 1,
            'padding' => 1,
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => array(255, 255, 255)
        );
        $pdf->write2DBarcode($qrData, 'QRCODE,H', 27, $yPos, 25, 25, $style, 'N');
        
        $yPos += 28;
        
        // Mensaje de agradecimiento
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor(46, 125, 50);
        $pdf->SetXY(5, $yPos);
        $pdf->Cell(70, 4, '¡Gracias por su compra!', 0, 1, 'C');
        
        // Texto del footer
        $yPos += 6;
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetTextColor(51, 51, 51);
        $pdf->SetXY(5, $yPos);
        $pdf->MultiCell(70, 3,
            "Presente este ticket para cambios\n" .
            "dentro de los 15 días\n" .
            "Consulte nuestro catálogo en:\n" .
            "https://conceibaviernes.rf.gd/\n" .
            " CONCEIBA © 2025",
            0, 'C', false);
        
        // Guardar PDF
        $pdfFilename = 'comprobante_' . $salesid . '.pdf';
        $pdfPath = __DIR__ . '/pdfs/' . $pdfFilename;
        
        if (!file_exists(__DIR__ . '/pdfs/')) {
            mkdir(__DIR__ . '/pdfs/', 0777, true);
        }
        
        $pdf->Output($pdfPath, 'F');
        
        // Enviar email
        $pdfLink = 'https://conceibaviernes.rf.gd/pdfs/' . $pdfFilename;
        
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jeessonyman12@gmail.com';
        $mail->Password = 'iygevcfabazclxgq';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom('jeessonyman123@gmail.com', 'Conceiba SAC');
        $mail->addAddress($clientEmail, $clientName);
        $mail->addReplyTo('jeessonyman12@gmail.com', 'Conceiba SAC');
        
        $mail->isHTML(true);
        $mail->Subject = 'Comprobante de Pago - Conceiba SAC';
        $mail->Body = '
        <html>
        <body style="font-family: Arial, sans-serif; color: #333; background: #f5f5f5; padding: 20px;">
            <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="background: linear-gradient(135deg, #2E7D32, #E74C3C); padding: 30px; text-align: center;">
                    <h1 style="color: white; margin: 0; font-size: 28px;">CONCEIBA</h1>
                    <p style="color: white; margin: 10px 0 0 0; opacity: 0.9;">Soluciones profesionales</p>
                </div>
                <div style="padding: 30px;">
                    <h2 style="color: #2C3E50; margin-top: 0;">¡Gracias por tu compra!</h2>
                    <p style="color: #666; line-height: 1.6;">Hola <strong>' . htmlspecialchars($clientName) . '</strong>,</p>
                    <p style="color: #666; line-height: 1.6;">Tu compra ha sido procesada exitosamente.</p>
                    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #E74C3C;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; color: #E74C3C; font-weight: bold;">Ticket:</td>
                                <td style="padding: 8px 0; text-align: right;">T-' . str_pad($salesid, 6, '0', STR_PAD_LEFT) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #E74C3C; font-weight: bold;">ID PayPal:</td>
                                <td style="padding: 8px 0; text-align: right; font-size: 12px;">' . htmlspecialchars($payid) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #E74C3C; font-weight: bold;">Total:</td>
                                <td style="padding: 8px 0; text-align: right; font-size: 18px; font-weight: bold;">S/ ' . number_format($total, 2) . '</td>
                            </tr>
                        </table>
                    </div>
                    <p style="color: #666; line-height: 1.6;">Adjunto encontrarás tu comprobante de pago en formato PDF.</p>
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . $pdfLink . '" style="background: #E74C3C; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-size: 16px;">Descargar Comprobante</a>
                    </div>
                    <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">
                    <div style="text-align: center; color: #999; font-size: 12px; line-height: 1.6;">
                        <strong style="color: #2E7D32;">Conceiba SAC</strong><br>
                        RUC: 20603824874<br>
                        Avenida Inca Garcilaso de la Vega 1236<br>
                        Cercado de Lima, Lima, Perú<br>
                        <a href="https://conceibaviernes.rf.gd/" style="color: #E74C3C; text-decoration: none;">https://conceibaviernes.rf.gd/</a>
                    </div>
                </div>
            </div>
        </body>
        </html>';
        
        $mail->addAttachment($pdfPath, 'Comprobante_Conceiba.pdf');
        
        if ($mail->send()) {
            $_SESSION['success'] = 'Transacción exitosa. Se ha enviado el comprobante por correo electrónico.';
        } else {
            throw new Exception('Error al enviar el correo: ' . $mail->ErrorInfo);
        }
        
        header('Location: profile.php');
        exit();
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Error al procesar la transacción: ' . $e->getMessage();
    header('Location: profile.php');
    exit();
} finally {
    $pdo->close();
}
?>
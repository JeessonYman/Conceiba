<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once('tcpdf/tcpdf.php');
include 'includes/session.php';

try {
    $conn = $pdo->open();

    if (isset($_GET['pay'])) {
        $payid = $_GET['pay'];
        $date = date('Y-m-d');

        // Insertar la venta en la base de datos
        $stmt = $conn->prepare("INSERT INTO sales (user_id, pay_id, sales_date) VALUES (:user_id, :pay_id, :sales_date)");
        $stmt->execute(['user_id' => $user['id'], 'pay_id' => $payid, 'sales_date' => $date]);
        $salesid = $conn->lastInsertId();

        // Obtener los detalles de los productos comprados
        $stmt = $conn->prepare("SELECT * FROM cart 
                                LEFT JOIN products ON products.id=cart.product_id 
                                WHERE user_id=:user_id");
        $stmt->execute(['user_id' => $user['id']]);

        $total = 0;
        $html = '<h1>Comprobante de Pago</h1>';
        $html .= '<table border="1">';
        $html .= '<tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr>';

        foreach ($stmt as $row) {
            // Insertar detalles de venta en la base de datos
            $stmt = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
            $stmt->execute(['sales_id' => $salesid, 'product_id' => $row['product_id'], 'quantity' => $row['quantity']]);

            // Actualizar el stock de productos
            $updateStock = $conn->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :product_id");
            $updateStock->execute(['quantity' => $row['quantity'], 'product_id' => $row['product_id']]);

            // Eliminar los productos del carrito
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
            $stmt->execute(['user_id' => $user['id']]);

            $productSubtotal = $row['quantity'] * $row['price'];
            $total += $productSubtotal;

            $html .= '<tr>';
            $html .= '<td>' . $row['name'] . '</td>';
            $html .= '<td>' . $row['quantity'] . '</td>';
            $html .= '<td>' . 'S/ ' . number_format($row['price'], 2) . '</td>';
            $html .= '<td>' . 'S/ ' . number_format($productSubtotal, 2) . '</td>';
            $html .= '</tr>';
        }

        // Calcular el total de la venta
        $html .= '<tr>
                     <td colspan="3" align="right"><b>Total</b></td>
		             <td align="right"><b>S/ ' . number_format($total, 2) . '</b></td>
                  </tr>';
        $html .= '</table>';

        // Crear el PDF del comprobante de pago
        $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Conceiba');
        $pdf->SetTitle('Comprobante de Pago');
        $pdf->SetSubject('Comprobante de Pago');
        $pdf->SetKeywords('TCPDF, PDF, ejemplo, comprobante, pago');

        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdfFilename = 'comprobante_' . $salesid . '.pdf'; // Nombre único del PDF
        $pdfPath = __DIR__ . '/pdfs/' . $pdfFilename;
        $pdf->Output($pdfPath, 'F');

        // Enviar el comprobante por correo electrónico
        $stmt = $conn->prepare("SELECT email FROM users WHERE id = :user_id");
        $stmt->execute(['user_id' => $user['id']]);
        $userData = $stmt->fetch();
        $clientEmail = $userData['email'];

        $pdfLink = 'https://conceibaviernes.rf.gd/pdfs/' . $pdfFilename; // Enlace de descarga del PDF

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jeessonyman12@gmail.com';
        $mail->Password = 'iygevcfabazclxgq';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('jeessonyman123@gmail.com', 'Conceiba Prueba');
        $mail->addAddress($clientEmail);
        $mail->addReplyTo('jeessonyman12@gmail.com', 'Jeesson Yman');

        $mail->isHTML(true);
        $mail->Subject = 'Comprobante de Pago - ID: ' . $payid;
        $mail->Body = 'Adjunto encontrarás tu comprobante de pago con ID: ' . $payid . '. Puedes descargarlo <a href="' . $pdfLink . '">aquí</a>. Gracias por tu compra.';
        $mail->addAttachment($pdfPath, 'Comprobante de Pago.pdf');

        if ($mail->send()) {
            $_SESSION['success'] = 'Transacción exitosa. Se ha enviado el comprobante por el correo electrónico.';
            // Redireccionar al usuario a profile.php después de completar el pago
            header('Location: profile.php');
            exit();
        } else {
            throw new Exception('Error al enviar el correo electrónico con el comprobante: ' . $mail->ErrorInfo);
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'Error al procesar la transacción: ' . $e->getMessage();
} finally {
    $pdo->close();
    // Si el bloque if no se cumple, redireccionar al perfil también
    header('Location: profile.php');
    exit();
}
?>
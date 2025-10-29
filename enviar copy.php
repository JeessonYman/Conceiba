<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'includes/session.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    try {
        // Load PHPMailer
        require 'vendor/autoload.php';

        $mail = new PHPMailer(true);

        // Server settings
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jeessonyman12@gmail.com';
        $mail->Password = 'iygevcfabazclxgq';
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('jeessonyman123@gmail.com');
        $mail->addAddress($email);
        $mail->addReplyTo('jeessonyman123@gmail.com');

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();

        $_SESSION['success'] = 'Mensaje enviado correctamente.';
        header('location: contacto.php');
    } catch (Exception $e) {
        $_SESSION['error'] = 'El mensaje no pudo ser enviado. Error de correo: ' . $mail->ErrorInfo;
        header('location: contacto.php');
    }
} else {
    $_SESSION['error'] = 'Rellene el formulario primero.';
    header('location: contacto.php');
}
?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'includes/session.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $date = date('d/m/Y H:i:s');

    try {
        // Load PHPMailer
        require 'vendor/autoload.php';

        // ========== CORREO 1: PARA LA EMPRESA ==========
        $mailEmpresa = new PHPMailer(true);

        // Configuración del servidor
        $mailEmpresa->isSMTP();
        $mailEmpresa->Host = 'smtp.gmail.com';
        $mailEmpresa->SMTPAuth = true;
        $mailEmpresa->Username = 'jeessonyman12@gmail.com';
        $mailEmpresa->Password = 'iygevcfabazclxgq';
        $mailEmpresa->SMTPSecure = 'ssl';
        $mailEmpresa->Port = 465;
        $mailEmpresa->CharSet = 'UTF-8';

        $mailEmpresa->setFrom($email, $name);
        $mailEmpresa->addAddress('jeessonyman12@gmail.com', 'Conceiba SAC'); // Correo de la empresa
        $mailEmpresa->addReplyTo($email, $name);

        // Contenido del correo para la empresa
        $mailEmpresa->isHTML(true);
        $mailEmpresa->Subject = 'Nuevo mensaje de contacto: ' . $subject;
        $mailEmpresa->Body = '
        <html>
        <body style="font-family: Arial, sans-serif; color: #333; background: #f5f5f5; padding: 20px; margin: 0;">
            <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <!-- Encabezado con degradado verde a rojo -->
                <div style="background: linear-gradient(135deg, #2E7D32, #E74C3C); padding: 30px; text-align: center;">
                    <img src="https://conceibaviernes.rf.gd/images/logo.png" alt="Conceiba" style="width: 80px; height: auto; margin-bottom: 10px; background: white; padding: 10px; border-radius: 8px;">
                    <h1 style="color: white; margin: 10px 0 0 0; font-size: 28px;">CONCEIBA</h1>
                    <p style="color: white; margin: 5px 0 0 0; opacity: 0.9;">Economía verde</p>
                </div>
                
                <!-- Cuerpo del correo -->
                <div style="padding: 30px;">
                    <h2 style="color: #2E7D32; margin-top: 0; border-bottom: 2px solid #E74C3C; padding-bottom: 10px;">
                        📬 Nuevo Mensaje de Contacto
                    </h2>
                    
                    <p style="color: #666; line-height: 1.6;">Has recibido un nuevo mensaje desde el formulario de contacto de tu sitio web.</p>
                    
                    <!-- Información del remitente -->
                    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2E7D32;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; color: #2E7D32; font-weight: bold; width: 120px;">Nombre:</td>
                                <td style="padding: 8px 0;">' . htmlspecialchars($name) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #2E7D32; font-weight: bold;">Email:</td>
                                <td style="padding: 8px 0;"><a href="mailto:' . htmlspecialchars($email) . '" style="color: #E74C3C; text-decoration: none;">' . htmlspecialchars($email) . '</a></td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #2E7D32; font-weight: bold;">Asunto:</td>
                                <td style="padding: 8px 0;">' . htmlspecialchars($subject) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #2E7D32; font-weight: bold;">Fecha:</td>
                                <td style="padding: 8px 0;">' . $date . '</td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- Mensaje -->
                    <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e0e0e0; margin: 20px 0;">
                        <h3 style="color: #2E7D32; margin-top: 0; font-size: 16px;">Mensaje:</h3>
                        <p style="color: #333; line-height: 1.6; white-space: pre-wrap;">' . nl2br(htmlspecialchars($message)) . '</p>
                    </div>
                    
                    <!-- Botón de respuesta -->
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="mailto:' . htmlspecialchars($email) . '?subject=Re: ' . urlencode($subject) . '" style="background: #2E7D32; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-size: 16px;">Responder al Cliente</a>
                    </div>
                    
                    <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">
                    
                    <!-- Footer -->
                    <div style="text-align: center; color: #999; font-size: 12px; line-height: 1.6;">
                        <p style="margin: 5px 0;">Este mensaje fue enviado desde el formulario de contacto de</p>
                        <a href="https://conceibaviernes.rf.gd/" style="color: #E74C3C; text-decoration: none; font-weight: bold;">https://conceibaviernes.rf.gd/</a>
                    </div>
                </div>
            </div>
        </body>
        </html>';

        // Enviar correo a la empresa
        $mailEmpresa->send();

        // ========== CORREO 2: CONFIRMACIÓN PARA EL CLIENTE ==========
        $mailCliente = new PHPMailer(true);

        // Configuración del servidor
        $mailCliente->isSMTP();
        $mailCliente->Host = 'smtp.gmail.com';
        $mailCliente->SMTPAuth = true;
        $mailCliente->Username = 'jeessonyman12@gmail.com';
        $mailCliente->Password = 'iygevcfabazclxgq';
        $mailCliente->SMTPSecure = 'ssl';
        $mailCliente->Port = 465;
        $mailCliente->CharSet = 'UTF-8';

        $mailCliente->setFrom('jeessonyman123@gmail.com', 'Conceiba SAC');
        $mailCliente->addAddress($email, $name);
        $mailCliente->addReplyTo('jeessonyman12@gmail.com', 'Conceiba SAC');

        // Contenido del correo de confirmación para el cliente
        $mailCliente->isHTML(true);
        $mailCliente->Subject = 'Confirmación: Hemos recibido tu mensaje - Conceiba SAC';
        $mailCliente->Body = '
        <html>
        <body style="font-family: Arial, sans-serif; color: #333; background: #f5f5f5; padding: 20px; margin: 0;">
            <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <!-- Encabezado con degradado verde a rojo -->
                <div style="background: linear-gradient(135deg, #2E7D32, #E74C3C); padding: 30px; text-align: center;">
                    <img src="https://conceibaviernes.rf.gd/images/logo.png" alt="Conceiba" style="width: 80px; height: auto; margin-bottom: 10px; background: white; padding: 10px; border-radius: 8px;">
                    <h1 style="color: white; margin: 10px 0 0 0; font-size: 28px;">CONCEIBA</h1>
                    <p style="color: white; margin: 5px 0 0 0; opacity: 0.9;">Economía verde</p>
                </div>
                
                <!-- Cuerpo del correo -->
                <div style="padding: 30px;">
                    <h2 style="color: #2E7D32; margin-top: 0;">¡Gracias por contactarnos!</h2>
                    
                    <p style="color: #666; line-height: 1.6;">Hola <strong>' . htmlspecialchars($name) . '</strong>,</p>
                    
                    <p style="color: #666; line-height: 1.6;">Hemos recibido tu mensaje exitosamente y queremos agradecerte por ponerte en contacto con nosotros.</p>
                    
                    <!-- Resumen del mensaje enviado -->
                    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #E74C3C;">
                        <h3 style="color: #2E7D32; margin-top: 0; font-size: 16px;">📝 Resumen de tu mensaje:</h3>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; color: #2E7D32; font-weight: bold; width: 100px;">Asunto:</td>
                                <td style="padding: 8px 0;">' . htmlspecialchars($subject) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #2E7D32; font-weight: bold;">Fecha:</td>
                                <td style="padding: 8px 0;">' . $date . '</td>
                            </tr>
                        </table>
                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e0e0e0;">
                            <p style="color: #666; line-height: 1.6; margin: 0; white-space: pre-wrap;">' . nl2br(htmlspecialchars($message)) . '</p>
                        </div>
                    </div>
                    
                    <!-- Información de respuesta -->
                    <div style="background: white; padding: 20px; border-radius: 8px; border: 2px dashed #2E7D32; margin: 20px 0; text-align: center;">
                        <h3 style="color: #2E7D32; margin-top: 0; font-size: 18px;">⏱️ ¿Cuándo recibirás respuesta?</h3>
                        <p style="color: #666; line-height: 1.6;">Nuestro equipo revisará tu mensaje a la brevedad posible. Normalmente respondemos en un plazo de <strong style="color: #E74C3C;">24 a 48 horas hábiles</strong>.</p>
                    </div>
                    
                    <!-- Información de contacto adicional -->
                    <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;">
                        <h3 style="color: #2E7D32; margin-top: 0; font-size: 16px;">📞 Otras formas de contacto:</h3>
                        <p style="color: #666; line-height: 1.6; margin: 5px 0;">
                            <strong>Email:</strong> jeessonyman12@gmail.com<br>
                            <strong>Sitio web:</strong> <a href="https://conceibaviernes.rf.gd/" style="color: #E74C3C; text-decoration: none;">https://conceibaviernes.rf.gd/</a>
                        </p>
                    </div>
                    
                    <p style="color: #666; line-height: 1.6; text-align: center; margin-top: 30px;">
                        <strong>¡Gracias por confiar en Conceiba!</strong><br>
                        Tu opinión es muy importante para nosotros.
                    </p>
                    
                    <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">
                    
                    <!-- Footer -->
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

        // Enviar correo al cliente
        $mailCliente->send();

        $_SESSION['success'] = 'Mensaje enviado correctamente. Recibirás una confirmación en tu correo.';
        header('location: contacto.php');

    } catch (Exception $e) {
        $_SESSION['error'] = 'El mensaje no pudo ser enviado. Error: ' . $e->getMessage();
        header('location: contacto.php');
    }
} else {
    $_SESSION['error'] = 'Rellene el formulario primero.';
    header('location: contacto.php');
}
?>
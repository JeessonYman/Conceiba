<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = array('success' => false, 'message' => '');
    
    if(isset($_POST['subscriber_email'])) {
        $email = $_POST['subscriber_email'];
        
        // Validar email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Por favor, ingrese un correo electrónico válido.';
            echo json_encode($response);
            exit();
        }

        try {
            $conn = $pdo->open();

            // Verificar si el email ya existe
            $stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM subscribers WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $row = $stmt->fetch();

            if($row['numrows'] > 0) {
                $response['message'] = 'El correo electrónico ya está suscrito.';
            }
            else {
                // Insertar nuevo suscriptor
                $stmt = $conn->prepare("INSERT INTO subscribers (email, created_on) VALUES (:email, NOW())");
                $stmt->execute(['email' => $email]);
                
                $currentDate = date('d/m/Y H:i:s');
                
                // Crear mensaje HTML del correo de confirmación
                $message = '
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
                            <h2 style="color: #2E7D32; margin-top: 0;">¡Bienvenido a nuestra comunidad!</h2>
                            
                            <p style="color: #666; line-height: 1.6;">Hola,</p>
                            
                            <p style="color: #666; line-height: 1.6;">¡Gracias por suscribirte a nuestro boletín informativo! Estamos emocionados de tenerte como parte de la familia Conceiba.</p>
                            
                            <!-- Confirmación de suscripción -->
                            <div style="background: #E8F5E9; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2E7D32; text-align: center;">
                                <h3 style="color: #2E7D32; margin-top: 0; font-size: 20px;">✅ ¡Suscripción Confirmada!</h3>
                                <p style="color: #1B5E20; line-height: 1.6; margin: 10px 0;">
                                    <strong>Email registrado:</strong><br>
                                    '.htmlspecialchars($email).'
                                </p>
                                <p style="color: #1B5E20; line-height: 1.6; margin: 10px 0; font-size: 14px;">
                                    Fecha: '.$currentDate.'
                                </p>
                            </div>
                            
                            <!-- Beneficios de la suscripción -->
                            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;">
                                <h3 style="color: #2E7D32; margin-top: 0; font-size: 18px;">🎁 ¿Qué recibirás en tu bandeja de entrada?</h3>
                                <ul style="color: #666; line-height: 2; padding-left: 20px; margin: 10px 0;">
                                    <li><strong style="color: #2E7D32;">Ofertas exclusivas</strong> y descuentos especiales solo para suscriptores</li>
                                    <li><strong style="color: #2E7D32;">Nuevos productos</strong> antes que nadie</li>
                                    <li><strong style="color: #2E7D32;">Promociones por tiempo limitado</strong> con precios increíbles</li>
                                    <li><strong style="color: #2E7D32;">Noticias y novedades</strong> sobre productos ecológicos</li>
                                    <li><strong style="color: #2E7D32;">Tips y consejos</strong> para un estilo de vida más sostenible</li>
                                </ul>
                            </div>
                            
                            <!-- Llamado a la acción -->
                            <div style="background: white; padding: 20px; border-radius: 8px; border: 2px solid #2E7D32; margin: 20px 0; text-align: center;">
                                <h3 style="color: #2E7D32; margin-top: 0; font-size: 18px;">🛍️ Explora nuestro catálogo</h3>
                                <p style="color: #666; line-height: 1.6;">Descubre nuestra amplia selección de productos ecológicos y artesanales.</p>
                                <div style="margin: 20px 0;">
                                    <a href="https://conceibaviernes.rf.gd/" style="background: #2E7D32; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-size: 16px;">Ver Productos</a>
                                </div>
                            </div>
                            
                            <!-- Redes sociales -->
                            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
                                <h3 style="color: #2E7D32; margin-top: 0; font-size: 16px;">📱 Síguenos en redes sociales</h3>
                                <p style="color: #666; line-height: 1.6; margin-bottom: 15px;">Mantente conectado y no te pierdas ninguna novedad:</p>
                                <div style="margin: 15px 0;">
                                    <a href="https://www.facebook.com/conceiba.es" style="display: inline-block; margin: 5px; background: #3b5998; width: 40px; height: 40px; border-radius: 50%; text-decoration: none; line-height: 40px;" target="_blank">
                                        <span style="color: white; font-size: 18px;">f</span>
                                    </a>
                                    <a href="https://www.instagram.com/conceibaperu/" style="display: inline-block; margin: 5px; background: #e4405f; width: 40px; height: 40px; border-radius: 50%; text-decoration: none; line-height: 40px;" target="_blank">
                                        <span style="color: white; font-size: 18px;">📷</span>
                                    </a>
                                    <a href="https://api.whatsapp.com/send?phone=+51945472993" style="display: inline-block; margin: 5px; background: #25D366; width: 40px; height: 40px; border-radius: 50%; text-decoration: none; line-height: 40px;" target="_blank">
                                        <span style="color: white; font-size: 18px;">📱</span>
                                    </a>
                                    <a href="https://www.youtube.com/@conceiba" style="display: inline-block; margin: 5px; background: #FF0000; width: 40px; height: 40px; border-radius: 50%; text-decoration: none; line-height: 40px;" target="_blank">
                                        <span style="color: white; font-size: 18px;">▶</span>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Información de cancelación -->
                            <div style="background: #FFF3CD; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #FFC107;">
                                <p style="color: #856404; margin: 0; font-size: 13px;">
                                    <strong>💡 ¿Quieres dejar de recibir correos?</strong><br>
                                    Puedes cancelar tu suscripción en cualquier momento haciendo clic en el enlace "Cancelar suscripción" que encontrarás al final de cada correo que te enviemos.
                                </p>
                            </div>
                            
                            <p style="color: #666; line-height: 1.6; text-align: center; margin-top: 30px;">
                                <strong>¡Gracias por confiar en Conceiba!</strong><br>
                                Juntos construimos un futuro más verde y sostenible.
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
                
                // Enviar correo de confirmación
                require 'vendor/autoload.php';
		    		require_once __DIR__ . '/includes/mail_config.php';
                
                $mail = new PHPMailer(true);
                
                try {
                    // Configuración del servidor
                    $mail->isSMTP();
                    $mail->Host = SMTP_HOST;
                    $mail->SMTPAuth = true;
                    $mail->Username = SMTP_USERNAME;
                    $mail->Password = SMTP_PASSWORD;
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                    $mail->SMTPSecure = SMTP_SECURE;
                    $mail->Port = SMTP_PORT;
                    $mail->CharSet = 'UTF-8';
                    
                    $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
                    $mail->addAddress($email);
                    $mail->addReplyTo('jeessonyman12@gmail.com', 'Conceiba SAC');
                    
                    // Contenido del correo
                    $mail->isHTML(true);
                    $mail->Subject = '¡Bienvenido a Conceiba! Confirmación de suscripción';
                    $mail->Body = $message;
                    
                    $mail->send();
                    
                    $response['success'] = true;
                    $response['message'] = '¡Gracias por suscribirte! Revisa tu correo para confirmar.';
                    
                } catch (Exception $e) {
                    // Si falla el correo, aún así marcamos como exitoso porque se guardó en BD
                    $response['success'] = true;
                    $response['message'] = '¡Gracias por suscribirte! Recibirás nuestras actualizaciones.';
                }
            }
        }
        catch(PDOException $e) {
            $response['message'] = 'Ocurrió un error: ' . $e->getMessage();
        }

        $pdo->close();
    }
    else {
        $response['message'] = 'Por favor, ingrese su correo electrónico.';
    }

    echo json_encode($response);
}
?>
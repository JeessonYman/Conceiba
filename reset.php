<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	include 'includes/session.php';

	if(isset($_POST['reset'])){
		$email = $_POST['email'];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM users WHERE email=:email");
		$stmt->execute(['email'=>$email]);
		$row = $stmt->fetch();

		if($row['numrows'] > 0){
			//generate code
			$set='123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$code=substr(str_shuffle($set), 0, 15);
			
			try{
				$stmt = $conn->prepare("UPDATE users SET reset_code=:code WHERE id=:id");
				$stmt->execute(['code'=>$code, 'id'=>$row['id']]);
				
				$resetLink = 'https://conceibaviernes.rf.gd/password_reset.php?code='.$code.'&user='.$row['id'];
				$userName = $row['firstname'].' '.$row['lastname'];
				$currentDate = date('d/m/Y H:i:s');
				
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
							<h2 style="color: #2E7D32; margin-top: 0;">Recuperación de Contraseña</h2>
							
							<p style="color: #666; line-height: 1.6;">Hola <strong>'.htmlspecialchars($userName).'</strong>,</p>
							
							<p style="color: #666; line-height: 1.6;">Hemos recibido una solicitud para restablecer la contraseña de tu cuenta asociada con el correo electrónico <strong>'.htmlspecialchars($email).'</strong>.</p>
							
							<!-- Información de la solicitud -->
							<div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2E7D32;">
								<h3 style="color: #2E7D32; margin-top: 0; font-size: 16px;">📋 Detalles de la solicitud:</h3>
								<table style="width: 100%; border-collapse: collapse;">
									<tr>
										<td style="padding: 8px 0; color: #2E7D32; font-weight: bold; width: 140px;">Email:</td>
										<td style="padding: 8px 0;">'.htmlspecialchars($email).'</td>
									</tr>
									<tr>
										<td style="padding: 8px 0; color: #2E7D32; font-weight: bold;">Fecha y hora:</td>
										<td style="padding: 8px 0;">'.$currentDate.'</td>
									</tr>
									<tr>
										<td style="padding: 8px 0; color: #2E7D32; font-weight: bold;">Código de seguridad:</td>
										<td style="padding: 8px 0; font-family: monospace; color: #666;">'.htmlspecialchars($code).'</td>
									</tr>
								</table>
							</div>
							
							<!-- Advertencia de seguridad -->
							<div style="background: #FFF3CD; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #FFC107;">
								<p style="color: #856404; margin: 0; font-size: 14px;"><strong>⚠️ ¿No solicitaste este cambio?</strong></p>
								<p style="color: #856404; margin: 10px 0 0 0; font-size: 13px;">Si no solicitaste restablecer tu contraseña, puedes ignorar este correo de forma segura. Tu contraseña actual permanecerá sin cambios.</p>
							</div>
							
							<!-- Botón de restablecimiento -->
							<div style="background: white; padding: 20px; border-radius: 8px; border: 2px solid #E74C3C; margin: 20px 0; text-align: center;">
								<h3 style="color: #E74C3C; margin-top: 0; font-size: 18px;">🔐 Restablecer tu contraseña</h3>
								<p style="color: #666; line-height: 1.6;">Haz clic en el siguiente botón para crear una nueva contraseña:</p>
								<div style="margin: 25px 0;">
									<a href="'.$resetLink.'" style="background: #E74C3C; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-size: 16px;">Crear Nueva Contraseña</a>
								</div>
								<p style="color: #999; font-size: 12px; margin-top: 20px;">Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
								<a href="'.$resetLink.'" style="color: #E74C3C; word-break: break-all; font-size: 11px;">'.$resetLink.'</a></p>
							</div>
							
							<!-- Información de validez -->
							<div style="background: #E8F5E9; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2E7D32;">
								<h3 style="color: #2E7D32; margin-top: 0; font-size: 16px;">⏰ Tiempo de validez</h3>
								<p style="color: #1B5E20; line-height: 1.6; margin: 0;">Este enlace de restablecimiento es válido por <strong>24 horas</strong>. Después de ese tiempo, tendrás que solicitar un nuevo enlace.</p>
							</div>
							
							<!-- Consejos de seguridad -->
							<div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;">
								<h3 style="color: #2E7D32; margin-top: 0; font-size: 16px;">💡 Consejos para una contraseña segura:</h3>
								<ul style="color: #666; line-height: 1.8; padding-left: 20px; margin: 10px 0;">
									<li>Usa al menos 8 caracteres</li>
									<li>Combina letras mayúsculas y minúsculas</li>
									<li>Incluye números y símbolos especiales</li>
									<li>No uses información personal obvia</li>
									<li>No reutilices contraseñas de otras cuentas</li>
								</ul>
							</div>
							
							<p style="color: #666; line-height: 1.6; text-align: center; margin-top: 30px;">
								Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.
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

				//Load phpmailer
	    		require 'vendor/autoload.php';

	    		$mail = new PHPMailer(true);                             
			    try {
			        //Server settings
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
		        $mail->CharSet = 'UTF-8';

		        $mail->setFrom('jeessonyman123@gmail.com', 'Conceiba SAC');
		        
		        //Recipients
		        $mail->addAddress($email, $userName);              
		        $mail->addReplyTo('jeessonyman12@gmail.com', 'Conceiba SAC');
		       
		        //Content
		        $mail->isHTML(true);                                  
		        $mail->Subject = 'Recuperación de Contraseña - Conceiba SAC';
		        $mail->Body    = $message;

		        $mail->send();

		        $_SESSION['success'] = 'Se ha enviado un enlace de restablecimiento de contraseña a tu correo electrónico.';
		     
			    } 
			    catch (Exception $e) {
			        $_SESSION['error'] = 'El mensaje no pudo ser enviado. Error de correo: '.$mail->ErrorInfo;
			    }
			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}
		else{
			$_SESSION['error'] = 'El correo electrónico no está registrado en nuestro sistema.';
		}

		$pdo->close();

	}
	else{
		$_SESSION['error'] = 'Por favor, ingrese el correo electrónico asociado con su cuenta.';
	}

	header('location: password_forgot.php');

?>
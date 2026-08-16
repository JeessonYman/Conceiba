<!DOCTYPE html>
<html lang="es">
<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	include 'includes/session.php';

	if(isset($_POST['signup'])){
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];

		$_SESSION['firstname'] = $firstname;
		$_SESSION['lastname'] = $lastname;
		$_SESSION['email'] = $email;

		if(!isset($_SESSION['captcha'])){
			require('recaptcha/src/autoload.php');		
			$recaptcha = new \ReCaptcha\ReCaptcha('6LevO1IUAAAAAFCCiOHERRXjh3VrHa5oywciMKcw', new \ReCaptcha\RequestMethod\SocketPost());
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

			if (!$resp->isSuccess()){
				$_SESSION['captcha'] = time() + (10*60);
		  		
		  	}	
		  	else{
				$_SESSION['error'] = 'Por favor conteste recaptcha correctamente';
				header('location: signup.php');	
				exit();	
		  	}
		}

		if($password != $repassword){
			$_SESSION['error'] = 'Las contraseñas no coinciden';
			header('location: signup.php');
		}
		else{
			$conn = $pdo->open();

			$stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM users WHERE email=:email");
			$stmt->execute(['email'=>$email]);
			$row = $stmt->fetch();
			if($row['numrows'] > 0){
				$_SESSION['error'] = 'Correo electrónico ya tomado';
				header('location: signup.php');
			}
			else{
				$now = date('Y-m-d');
				$password_hash = password_hash($password, PASSWORD_DEFAULT);

				//generate code
				$set='123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$code=substr(str_shuffle($set), 0, 12);

				try{
					$stmt = $conn->prepare("INSERT INTO users (email,
															   password, 
															   firstname, 
															   lastname, 
															   activate_code, 
															   created_on) 
														VALUES (:email,
																:password,
																:firstname, 
																:lastname, 
																:code, 
																:now)");
					$stmt->execute(['email'=>$email,
									 'password'=>$password_hash, 
									 'firstname'=>$firstname, 
									 'lastname'=>$lastname, 
									 'code'=>$code, 
									 'now'=>$now]);
					$userid = $conn->lastInsertId();

					$activationLink = 'https://conceibaviernes.rf.gd/activate.php?code='.$code.'&user='.$userid;

					// Mensaje HTML con diseño moderno
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
								<h2 style="color: #2E7D32; margin-top: 0;">¡Bienvenido a Conceiba!</h2>
								
								<p style="color: #666; line-height: 1.6;">Hola <strong>'.htmlspecialchars($firstname.' '.$lastname).'</strong>,</p>
								
								<p style="color: #666; line-height: 1.6;">Gracias por registrarte en nuestra plataforma. Estamos emocionados de tenerte con nosotros.</p>
								
								<!-- Datos de la cuenta -->
								<div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2E7D32;">
									<h3 style="color: #2E7D32; margin-top: 0; font-size: 16px;">📋 Datos de tu cuenta:</h3>
									<table style="width: 100%; border-collapse: collapse;">
										<tr>
											<td style="padding: 8px 0; color: #2E7D32; font-weight: bold; width: 140px;">Email:</td>
											<td style="padding: 8px 0;">'.htmlspecialchars($email).'</td>
										</tr>
										<tr>
											<td style="padding: 8px 0; color: #2E7D32; font-weight: bold; vertical-align: top; padding-top: 15px;">Contraseña:</td>
											<td style="padding: 8px 0;">
												<!-- Contraseña oculta (por defecto) -->
												<div style="margin-bottom: 10px;">
													<div style="background: #f0f0f0; padding: 12px 15px; border-radius: 5px; font-family: monospace; font-size: 16px; letter-spacing: 4px; display: inline-block; min-width: 150px;">
														••••••••••
													</div>
												</div>
												<!-- Botón para revelar contraseña -->
												<details style="margin-top: 10px;">
													<summary style="cursor: pointer; color: #2E7D32; font-weight: bold; padding: 8px 15px; background: #E8F5E9; border-radius: 5px; display: inline-block; user-select: none;">
														🔒 Haz clic aquí para ver tu contraseña
													</summary>
													<div style="margin-top: 15px; padding: 15px; background: #FFF3CD; border-left: 4px solid #FFC107; border-radius: 5px;">
														<p style="margin: 0 0 10px 0; color: #856404; font-size: 13px;"><strong>Tu contraseña es:</strong></p>
														<div style="background: white; padding: 12px 15px; border-radius: 5px; font-family: monospace; font-size: 16px; letter-spacing: 2px; color: #2E7D32; font-weight: bold; border: 2px solid #2E7D32;">
															'.htmlspecialchars($password).'
														</div>
														<p style="margin: 10px 0 0 0; color: #856404; font-size: 12px;">⚠️ Guarda esta contraseña en un lugar seguro</p>
													</div>
												</details>
											</td>
										</tr>
										<tr>
											<td style="padding: 8px 0; color: #2E7D32; font-weight: bold;">Fecha de registro:</td>
											<td style="padding: 8px 0;">'.date('d/m/Y H:i:s').'</td>
										</tr>
									</table>
								</div>
								
								<!-- Advertencia de seguridad -->
								<div style="background: #FFF3CD; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #FFC107;">
									<p style="color: #856404; margin: 0; font-size: 14px;"><strong>⚠️ Importante:</strong> Por tu seguridad, te recomendamos cambiar tu contraseña después de activar tu cuenta y no compartirla con nadie.</p>
								</div>
								
								<!-- Paso de activación -->
								<div style="background: white; padding: 20px; border-radius: 8px; border: 2px solid #2E7D32; margin: 20px 0; text-align: center;">
									<h3 style="color: #2E7D32; margin-top: 0; font-size: 18px;">🔐 Activa tu cuenta</h3>
									<p style="color: #666; line-height: 1.6;">Para comenzar a disfrutar de todos nuestros servicios, necesitas activar tu cuenta haciendo clic en el siguiente botón:</p>
									<div style="margin: 25px 0;">
										<a href="'.$activationLink.'" style="background: #2E7D32; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-size: 16px;">Activar mi cuenta</a>
									</div>
									<p style="color: #999; font-size: 12px; margin-top: 20px;">Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
									<a href="'.$activationLink.'" style="color: #E74C3C; word-break: break-all; font-size: 11px;">'.$activationLink.'</a></p>
								</div>
								
								<!-- Información adicional -->
								<div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;">
									<h3 style="color: #2E7D32; margin-top: 0; font-size: 16px;">💡 ¿Qué sigue?</h3>
									<ol style="color: #666; line-height: 1.8; padding-left: 20px;">
										<li>Haz clic en el botón de activación arriba</li>
										<li>Inicia sesión con tu email y contraseña</li>
										<li>Explora nuestro catálogo de productos</li>
										<li>Realiza tu primera compra</li>
									</ol>
								</div>
								
								<p style="color: #666; line-height: 1.6; text-align: center; margin-top: 30px;">
									<strong>¡Gracias por unirte a Conceiba!</strong><br>
									Juntos construimos un futuro más verde.
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
		    		require_once __DIR__ . '/includes/mail_config.php';

		    		$mail = new PHPMailer(true);                             
				    try {
				        //Server settings
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
				        
				        //Recipients
				        $mail->addAddress($email, $firstname.' '.$lastname);              
				        $mail->addReplyTo('jeessonyman12@gmail.com', 'Conceiba SAC');
				       
				        //Content
				        $mail->isHTML(true);                                  
				        $mail->Subject = '¡Bienvenido a Conceiba! - Activa tu cuenta';
				        $mail->Body    = $message;

				        $mail->send();

				        unset($_SESSION['firstname']);
				        unset($_SESSION['lastname']);
				        unset($_SESSION['email']);

				        $_SESSION['success'] = 'Cuenta creada exitosamente. Revisa tu correo electrónico para activar tu cuenta.';
				        header('location: signup.php');

				    } 
				    catch (Exception $e) {
				        $_SESSION['error'] = 'El mensaje no pudo ser enviado. Error de correo: '.$mail->ErrorInfo;
				        header('location: signup.php');
				    }

				}
				catch(PDOException $e){
					$_SESSION['error'] = $e->getMessage();
					header('location: signup.php');
				}

				$pdo->close();
			}
		}
	}
	else{
		$_SESSION['error'] = 'Rellene el formulario de registro primero';
		header('location: signup.php');
	}
?>
</html>
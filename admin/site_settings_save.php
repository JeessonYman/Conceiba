<?php
	include 'includes/session.php';

	function saveSetting($conn, $key, $value){
		$stmt = $conn->prepare("INSERT INTO site_settings (setting_key, setting_value) 
								VALUES (:key, :value) 
								ON DUPLICATE KEY UPDATE setting_value=:value2");
		$stmt->execute(['key'=>$key, 'value'=>$value, 'value2'=>$value]);
	}

	function handleUpload($fileField, $prefix){
		if(!empty($_FILES[$fileField]['name'])){
			$ext = pathinfo($_FILES[$fileField]['name'], PATHINFO_EXTENSION);
			$new_filename = $prefix.'_'.time().'.'.$ext;
			move_uploaded_file($_FILES[$fileField]['tmp_name'], '../images/'.$new_filename);
			return $new_filename;
		}
		return null;
	}

	if(isset($_POST['save'])){
		$conn = $pdo->open();

		try{
			saveSetting($conn, 'store_name', $_POST['store_name']);
			saveSetting($conn, 'store_tagline', $_POST['store_tagline']);
			saveSetting($conn, 'color_primary', $_POST['color_primary']);
			saveSetting($conn, 'color_primary_dark', $_POST['color_primary_dark']);
			saveSetting($conn, 'color_accent', $_POST['color_accent']);
			saveSetting($conn, 'company_ruc', $_POST['company_ruc']);
			saveSetting($conn, 'company_address', $_POST['company_address']);
			saveSetting($conn, 'company_phone', $_POST['company_phone']);

			$logo = handleUpload('logo', 'logo');
			if($logo){
				saveSetting($conn, 'logo', $logo);
			}

			$_SESSION['success'] = 'Configuración de la tienda actualizada correctamente';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}
	elseif(isset($_POST['save_assistant'])){
		$conn = $pdo->open();

		try{
			saveSetting($conn, 'assistant_name', $_POST['assistant_name']);
			saveSetting($conn, 'assistant_tagline', $_POST['assistant_tagline']);
			saveSetting($conn, 'assistant_tts_backend_url', trim($_POST['assistant_tts_backend_url']));

			$avatar = handleUpload('assistant_avatar', 'assistant_avatar');
			if($avatar){
				saveSetting($conn, 'assistant_avatar', $avatar);
			}

			$_SESSION['success'] = 'Identidad del asistente actualizada correctamente';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}

	header('location: site_settings.php');
?>

<?php
	include 'includes/session.php';

	if(isset($_POST['save'])){
		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("INSERT INTO contact_content (setting_key, setting_value) 
									VALUES ('intro_title', :intro_title) 
									ON DUPLICATE KEY UPDATE setting_value=:intro_title2");
			$stmt->execute(['intro_title'=>$_POST['intro_title'], 'intro_title2'=>$_POST['intro_title']]);

			$stmt = $conn->prepare("INSERT INTO contact_content (setting_key, setting_value) 
									VALUES ('map_embed_url', :map_embed_url) 
									ON DUPLICATE KEY UPDATE setting_value=:map_embed_url2");
			$stmt->execute(['map_embed_url'=>$_POST['map_embed_url'], 'map_embed_url2'=>$_POST['map_embed_url']]);

			$_SESSION['success'] = 'Contenido de Contacto actualizado';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}

	header('location: contact_content.php');
?>

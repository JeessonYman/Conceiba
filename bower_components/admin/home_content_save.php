<?php
	include 'includes/session.php';

	function saveHomeSetting($conn, $key, $value){
		$stmt = $conn->prepare("INSERT INTO home_content (setting_key, setting_value) 
								VALUES (:key, :value) 
								ON DUPLICATE KEY UPDATE setting_value=:value2");
		$stmt->execute(['key'=>$key, 'value'=>$value, 'value2'=>$value]);
	}

	if(isset($_POST['save_text'])){
		$conn = $pdo->open();

		try{
			saveHomeSetting($conn, 'about_title', $_POST['about_title']);
			saveHomeSetting($conn, 'about_text', $_POST['about_text']);
			saveHomeSetting($conn, 'impact_title', $_POST['impact_title']);
			saveHomeSetting($conn, 'allies_title', $_POST['allies_title']);

			if(!empty($_FILES['about_image']['name'])){
				$ext = pathinfo($_FILES['about_image']['name'], PATHINFO_EXTENSION);
				$new_filename = 'about_'.time().'.'.$ext;
				move_uploaded_file($_FILES['about_image']['tmp_name'], '../images/'.$new_filename);
				saveHomeSetting($conn, 'about_image', $new_filename);
			}

			$_SESSION['success'] = 'Contenido de Inicio actualizado';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}
	elseif(isset($_POST['add_stat'])){
		$conn = $pdo->open();

		$filename = '';
		if(!empty($_FILES['image']['name'])){
			$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
			$filename = 'impact_'.time().'.'.$ext;
			move_uploaded_file($_FILES['image']['tmp_name'], '../images/'.$filename);
		}

		try{
			$stmt = $conn->prepare("INSERT INTO impact_stats (label, image) VALUES (:label, :image)");
			$stmt->execute(['label'=>$_POST['label'], 'image'=>$filename]);
			$_SESSION['success'] = 'Estadística de impacto agregada';
		}
		catch(PDOException $e){
			if($e->getCode() == 23000){
				$_SESSION['error'] = 'Ya existe una estadística con ese mismo texto exacto.';
			}
			else{
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}
	elseif(isset($_POST['delete_stat'])){
		$conn = $pdo->open();
		$stmt = $conn->prepare("DELETE FROM impact_stats WHERE id=:id");
		$stmt->execute(['id'=>$_POST['id']]);
		$_SESSION['success'] = 'Estadística eliminada';
		$pdo->close();
	}
	elseif(isset($_POST['add_ally'])){
		$conn = $pdo->open();

		$filename = '';
		if(!empty($_FILES['image']['name'])){
			$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
			$filename = 'ally_'.time().'.'.$ext;
			move_uploaded_file($_FILES['image']['tmp_name'], '../images/'.$filename);
		}

		try{
			$stmt = $conn->prepare("INSERT INTO allies (image, facebook_url, twitter_url, instagram_url) 
									VALUES (:image, :facebook_url, :twitter_url, :instagram_url)");
			$stmt->execute([
				'image'=>$filename,
				'facebook_url'=>$_POST['facebook_url'],
				'twitter_url'=>$_POST['twitter_url'],
				'instagram_url'=>$_POST['instagram_url'],
			]);
			$_SESSION['success'] = 'Aliado agregado';
		}
		catch(PDOException $e){
			if($e->getCode() == 23000){
				$_SESSION['error'] = 'Ya subiste este mismo archivo de logo antes. Si es un aliado nuevo, sube una imagen distinta (aunque sea renombrada).';
			}
			else{
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}
	elseif(isset($_POST['delete_ally'])){
		$conn = $pdo->open();
		$stmt = $conn->prepare("DELETE FROM allies WHERE id=:id");
		$stmt->execute(['id'=>$_POST['id']]);
		$_SESSION['success'] = 'Aliado eliminado';
		$pdo->close();
	}

	header('location: home_content.php');
?>

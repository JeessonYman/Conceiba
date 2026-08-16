<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$age = !empty($_POST['age']) ? $_POST['age'] : null;
		$community = $_POST['community'];
		$bio = $_POST['bio'];
		$active = isset($_POST['active']) ? 1 : 0;
		$filename = $_FILES['photo']['name'];

		$conn = $pdo->open();

		if(!empty($filename)){
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			$new_filename = 'artesano_'.time().'.'.$ext;
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$new_filename);

			try{
				$stmt = $conn->prepare("UPDATE artisans 
										SET name=:name, age=:age, community=:community, bio=:bio, photo=:photo, active=:active 
										WHERE id=:id");
				$stmt->execute(['name'=>$name, 'age'=>$age, 'community'=>$community, 'bio'=>$bio, 'photo'=>$new_filename, 'active'=>$active, 'id'=>$id]);
				$_SESSION['success'] = 'Artesano actualizado exitosamente';
			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}
		else{
			try{
				$stmt = $conn->prepare("UPDATE artisans 
										SET name=:name, age=:age, community=:community, bio=:bio, active=:active 
										WHERE id=:id");
				$stmt->execute(['name'=>$name, 'age'=>$age, 'community'=>$community, 'bio'=>$bio, 'active'=>$active, 'id'=>$id]);
				$_SESSION['success'] = 'Artesano actualizado exitosamente';
			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}

	header('location: artisans.php');
?>

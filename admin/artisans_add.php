<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
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
		}
		else{
			$new_filename = '';
		}

		try{
			$stmt = $conn->prepare("INSERT INTO artisans (name, age, community, bio, photo, active) 
									VALUES (:name, :age, :community, :bio, :photo, :active)");
			$stmt->execute(['name'=>$name, 'age'=>$age, 'community'=>$community, 'bio'=>$bio, 'photo'=>$new_filename, 'active'=>$active]);
			$_SESSION['success'] = 'Artesano agregado exitosamente';
		}
		catch(PDOException $e){
			if($e->getCode() == 23000){
				$_SESSION['error'] = 'Ya existe un artesano con ese nombre exacto. Si es una persona distinta, agrega un apellido o inicial para diferenciarlo.';
			}
			else{
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}

	header('location: artisans.php');
?>

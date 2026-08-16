<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$filename = '';
		if(!empty($_FILES['photo']['name'])){
			$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
			$filename = 'yarn_'.time().'.'.$ext;
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);
		}

		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("INSERT INTO yarn_specs 
									(title, photo, description, composition, yarn_title, presentation, production, needles, weight, uses, care_instructions) 
									VALUES (:title, :photo, :description, :composition, :yarn_title, :presentation, :production, :needles, :weight, :uses, :care_instructions)");
			$stmt->execute([
				'title'=>$_POST['title'], 'photo'=>$filename, 'description'=>$_POST['description'],
				'composition'=>$_POST['composition'], 'yarn_title'=>$_POST['yarn_title'],
				'presentation'=>$_POST['presentation'], 'production'=>$_POST['production'],
				'needles'=>$_POST['needles'], 'weight'=>$_POST['weight'],
				'uses'=>$_POST['uses'], 'care_instructions'=>$_POST['care_instructions'],
			]);
			$_SESSION['success'] = 'Ficha de hilado agregada';
		}
		catch(PDOException $e){
			if($e->getCode() == 23000){
				$_SESSION['error'] = 'Ya existe una ficha de hilado con ese título exacto. Usa un título distinto (ej. agrega el color o variante).';
			}
			else{
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}

	header('location: yarn_specs.php');
?>

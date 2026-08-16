<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$conn = $pdo->open();

		$photoSql = '';
		$params = [
			'title'=>$_POST['title'], 'description'=>$_POST['description'],
			'composition'=>$_POST['composition'], 'yarn_title'=>$_POST['yarn_title'],
			'presentation'=>$_POST['presentation'], 'production'=>$_POST['production'],
			'needles'=>$_POST['needles'], 'weight'=>$_POST['weight'],
			'uses'=>$_POST['uses'], 'care_instructions'=>$_POST['care_instructions'],
			'id'=>$id,
		];

		if(!empty($_FILES['photo']['name'])){
			$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
			$filename = 'yarn_'.time().'.'.$ext;
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);
			$photoSql = ', photo=:photo';
			$params['photo'] = $filename;
		}

		try{
			$stmt = $conn->prepare("UPDATE yarn_specs SET 
									title=:title, description=:description, composition=:composition, 
									yarn_title=:yarn_title, presentation=:presentation, production=:production, 
									needles=:needles, weight=:weight, uses=:uses, care_instructions=:care_instructions
									".$photoSql." 
									WHERE id=:id");
			$stmt->execute($params);
			$_SESSION['success'] = 'Ficha de hilado actualizada';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}

	header('location: yarn_specs.php');
?>

<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("DELETE FROM yarn_specs WHERE id=:id");
			$stmt->execute(['id'=>$id]);
			$_SESSION['success'] = 'Ficha de hilado eliminada';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}

	header('location: yarn_specs.php');
?>

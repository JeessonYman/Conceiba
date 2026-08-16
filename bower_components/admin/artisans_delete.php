<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$id = $_POST['id'];

		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("DELETE FROM artisans WHERE id=:id");
			$stmt->execute(['id'=>$id]);

			$_SESSION['success'] = 'Artesano eliminado correctamente';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Seleccione el artesano para eliminar primero';
	}

	header('location: artisans.php');
?>

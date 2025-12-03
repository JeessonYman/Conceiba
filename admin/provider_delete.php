<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		
		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("DELETE FROM provider WHERE id=:id");
			$stmt->execute(['id'=>$id]);

			$_SESSION['success'] = 'Proveedor eliminado correctamente';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Seleccione el Proveedor para eliminar primero';
	}

	header('location: provider.php');
	
?>
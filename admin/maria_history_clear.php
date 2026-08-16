<?php
	include 'includes/session.php';

	if(isset($_POST['clear_all'])){
		$conn = $pdo->open();

		try{
			$conn->exec("DELETE FROM maria_conversations");
			$_SESSION['success'] = 'Historial de conversaciones eliminado correctamente';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}

	header('location: maria_history.php');
?>

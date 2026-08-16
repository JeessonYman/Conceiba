<?php
	include 'includes/session.php';

	if(isset($_POST['adjust'])){
		$id = $_POST['id'];
		$new_stock = $_POST['new_stock'];
		$reason = $_POST['reason'];

		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("SELECT name, stock FROM products WHERE id=:id");
			$stmt->execute(['id'=>$id]);
			$product = $stmt->fetch();

			$stmt = $conn->prepare("UPDATE products SET stock=:stock WHERE id=:id");
			$stmt->execute(['stock'=>$new_stock, 'id'=>$id]);

			$_SESSION['success'] = 'Stock de "'.$product['name'].'" actualizado de '.$product['stock'].' a '.$new_stock.' unidades'.(!empty($reason) ? ' (Motivo: '.$reason.')' : '');
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}

	header('location: stock.php');
?>

<?php
	include 'includes/session.php';

	$conn = $pdo->open();

	$output = array('error'=>false);

	$id = $_POST['id'];
	$qty = $_POST['qty'];

	if(isset($_SESSION['user'])){
		try{
			$stmt_stock = $conn->prepare("SELECT stock FROM products WHERE id IN (SELECT product_id FROM cart WHERE id=:id)");
			$stmt_stock->execute(['id' => $id]);
			$product_stock = $stmt_stock->fetchColumn();
			// Validar si la cantidad solicitada es mayor que el stock disponible
			if($qty <= $product_stock) {
			$stmt = $conn->prepare("UPDATE cart SET quantity=:quantity WHERE id=:id");
			$stmt->execute(['quantity'=>$qty, 'id'=>$id]);
			$output['message'] = 'Actualizado';
			}
		}
		catch(PDOException $e){
			$output['message'] = $e->getMessage();
		}
	}
	else{
		foreach($_SESSION['cart'] as $key => $row){
			if($row['productid'] == $id){
				// Obtener el stock disponible del producto
				$stmt_stock = $conn->prepare("SELECT stock FROM products WHERE id=:product_id");
				$stmt_stock->execute(['product_id' => $row['productid']]);
				$product_stock = $stmt_stock->fetchColumn();
				// Validar si la cantidad solicitada es mayor que el stock disponible
				if($qty <= $product_stock) {
				$_SESSION['cart'][$key]['quantity'] = $qty;
				$output['message'] = 'Actualizado';
				}
			}
		}
	}

	$pdo->close();
	echo json_encode($output);

?>
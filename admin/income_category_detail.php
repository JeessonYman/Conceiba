<?php
	include 'includes/session.php';

	if(isset($_POST['category_id'])){
		$category_id = $_POST['category_id'];

		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("SELECT pr.name AS product_name, di.quantity, di.price,
									(di.quantity * di.price) AS subtotal, i.entry_date AS input_date
									FROM details_inputs di
									LEFT JOIN products pr ON pr.id = di.id_products
									LEFT JOIN inputs i ON i.id = di.id_inputs
									WHERE pr.category_id = :category_id
									ORDER BY i.entry_date DESC");
			$stmt->execute(['category_id'=>$category_id]);
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

			echo json_encode(['success'=>true, 'rows'=>$rows]);
		}
		catch(PDOException $e){
			echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
		}

		$pdo->close();
	}
	else{
		echo json_encode(['success'=>false, 'message'=>'Falta el ID de categoría']);
	}
?>

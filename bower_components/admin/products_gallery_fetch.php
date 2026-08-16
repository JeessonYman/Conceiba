<?php
	include 'includes/session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT * 
								FROM product_images 
								WHERE product_id=:id 
								ORDER BY sort_order ASC, id ASC");
		$stmt->execute(['id'=>$id]);
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$pdo->close();

		echo json_encode($rows);
	}
	else{
		echo json_encode([]);
	}
?>

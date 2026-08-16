<?php
	include 'includes/session.php';

	if(isset($_POST['image_id'])){
		$image_id = $_POST['image_id'];

		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("SELECT image FROM product_images WHERE id=:id");
			$stmt->execute(['id'=>$image_id]);
			$img = $stmt->fetch();

			if($img && file_exists('../images/'.$img['image'])){
				unlink('../images/'.$img['image']);
			}

			$stmt = $conn->prepare("DELETE FROM product_images WHERE id=:id");
			$stmt->execute(['id'=>$image_id]);

			echo json_encode(['success'=>true]);
		}
		catch(PDOException $e){
			echo json_encode(['success'=>false, 'message'=>$e->getMessage()]);
		}

		$pdo->close();
	}
	else{
		echo json_encode(['success'=>false, 'message'=>'Falta el ID de la imagen']);
	}
?>

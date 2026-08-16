<?php
	include 'includes/session.php';

	if(isset($_POST['upload_gallery'])){
		$id = $_POST['id'];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT * 
								FROM products
								WHERE id=:id");
		$stmt->execute(['id'=>$id]);
		$row = $stmt->fetch();

		$uploaded = 0;

		if(!empty($_FILES['gallery']['name'][0])){
			foreach($_FILES['gallery']['name'] as $key => $filename){
				if(empty($filename)) continue;

				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$new_filename = $row['slug'].'_gallery_'.time().'_'.$key.'.'.$ext;
				move_uploaded_file($_FILES['gallery']['tmp_name'][$key], '../images/'.$new_filename);

				try{
					$stmt = $conn->prepare("INSERT INTO product_images (product_id, image, sort_order) 
											VALUES (:product_id, :image, :sort_order)");
					$stmt->execute(['product_id'=>$id, 'image'=>$new_filename, 'sort_order'=>$key]);
					$uploaded++;
				}
				catch(PDOException $e){
					$_SESSION['error'] = $e->getMessage();
				}
			}
		}

		if($uploaded > 0){
			$_SESSION['success'] = $uploaded.' imagen(es) agregada(s) a la galería';
		}
		elseif(!isset($_SESSION['error'])){
			$_SESSION['error'] = 'Selecciona al menos una imagen para subir';
		}

		$pdo->close();
	}
	elseif(isset($_POST['delete_gallery_image'])){
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
			$_SESSION['success'] = 'Imagen eliminada de la galería';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'No se recibió ninguna acción de galería';
	}

	header('location: products.php');
?>

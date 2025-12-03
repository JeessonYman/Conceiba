<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	if(isset($_POST['add'])){
		$name = $_POST['name'];
		$slug = slugify($name);
		$category = $_POST['category'];
		$price = $_POST['price'];
		$price_normal = $_POST['price_normal'];
		$stock = $_POST['stock'];
		$stock_minimum = $_POST['stock_minimum'];
		$description = $_POST['description'];
		$filename = $_FILES['photo']['name'];
		$provider = $_POST['provider'];
		$cost = $_POST['cost'];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM products WHERE slug=:slug");
		$stmt->execute(['slug'=>$slug]);
		$row = $stmt->fetch();

		if($row['numrows'] > 0){
			$_SESSION['error'] = 'Ingreso ya existe';
		}
		else{
			if(!empty($filename)){
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$new_filename = $slug.'.'.$ext;
				move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$new_filename);	
			}
			else{
				$new_filename = '';
			}

			try{
				$stmt = $conn->prepare("INSERT INTO products (category_id, name, description, slug, price, price_normal, stock, stock_minimum, photo, provider_id, cost) VALUES (:category, :name, :description, :slug, :price, :price_normal, :stock, :stock_minimum, :photo, :provider, :cost)");
				$stmt->execute(['category'=>$category, 'name'=>$name, 'description'=>$description, 'slug'=>$slug, 'price'=>$price, 'price_normal'=>$price_normal, 'stock'=>$stock, 'stock_minimum'=>$stock_minimum, 'photo'=>$new_filename, 'provider'=>$provider, 'cost'=>$cost]);
				$_SESSION['success'] = 'Ingreso agregado exitosamente';

			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Rellene el formulario del ingreso primero';
	}

	header('location: inputs.php');
?>

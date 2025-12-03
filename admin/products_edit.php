<?php
    include 'includes/session.php';
    include 'includes/slugify.php';

    if(isset($_POST['edit'])){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $slug = slugify($name);
        $category = $_POST['category'];
		$provider = $_POST['provider'];
        $price = $_POST['price'];
        $price_normal = $_POST['price_normal'];
        $stock = $_POST['stock'];
        $stock_minimum = $_POST['stock_minimum'];
        $description = $_POST['description'];
        $cost = $_POST['cost'];

        $conn = $pdo->open();

        try{
            $stmt = $conn->prepare("UPDATE products SET 
                                                        name=:name, 
                                                        slug=:slug, 
                                                        category_id=:category, 
                                                        price=:price, 
                                                        price_normal=:price_normal, 
                                                        stock=:stock, 
                                                        stock_minimum=:stock_minimum, 
                                                        description=:description, 
                                                        provider_id=:provider,
                                                        cost=:cost 
                                                    WHERE id=:id");
            $stmt->execute([
                'name'=>$name, 
                'slug'=>$slug, 
                'category'=>$category, 
				'provider'=>$provider,
                'price'=>$price, 
                'price_normal'=>$price_normal,
                'stock'=>$stock,
                'stock_minimum'=>$stock_minimum,
                'description'=>$description, 
                'cost'=>$cost,
                'id'=>$id
            ]);
            $_SESSION['success'] = 'Producto actualizado con éxito';
        }
        catch(PDOException $e){
            $_SESSION['error'] = $e->getMessage();
        }
        
        $pdo->close();
    }
    else{
        $_SESSION['error'] = 'Rellene el formulario de edición del producto primero';
    }

	header('location: products.php');

?>
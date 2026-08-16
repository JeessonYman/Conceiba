<?php
	include 'includes/session.php';
	
	// Abrir nueva conexión después de que session.php la cerró
	$conn = $pdo->open();

	$output = '';

	if(isset($_SESSION['user'])){
		if(isset($_SESSION['cart'])){
			foreach($_SESSION['cart'] as $row){
				$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM cart WHERE user_id=:user_id AND product_id=:product_id");
				$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$row['productid']]);
				$crow = $stmt->fetch();
				if($crow['numrows'] < 1){
					$stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
					$stmt->execute(['user_id'=>$user['id'], 'product_id'=>$row['productid'], 'quantity'=>$row['quantity']]);
				}
				else{
					$stmt = $conn->prepare("UPDATE cart SET quantity=:quantity WHERE user_id=:user_id AND product_id=:product_id");
					$stmt->execute(['quantity'=>$row['quantity'], 'user_id'=>$user['id'], 'product_id'=>$row['productid']]);
				}
			}
			unset($_SESSION['cart']);
		}

		try{
			$total = 0;
			// CORREGIDO: user_id en lugar de user
			$stmt = $conn->prepare("SELECT *, cart.id AS cartid, products.stock AS stock FROM cart 
                        LEFT JOIN products ON products.id=cart.product_id 
                        WHERE user_id=:user_id");
			$stmt->execute(['user_id'=>$user['id']]);
			
			foreach($stmt as $row){
				$image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
				$subtotal = $row['price'] * $row['quantity'];
				$availableStock = $row['stock'];
				$cartQuantity = $row['quantity'];
				if ($cartQuantity > $availableStock) {
					$cartQuantity = $availableStock;
				}
			
				$total += $subtotal;
				$output .= "
					<tr>
						<td><button type='button' data-id='".$row['cartid']."' class='btn btn-sm btn-outline-danger cart_delete'><i class='fa fa-remove'></i></button></td>
						<td><img src='".$image."' width='40' height='40' class='rounded-2' style='object-fit:cover;'></td>
						<td>".$row['name']."</td>
						<td>S/ ".number_format($row['price'], 2)."</td>
						<td>
							<div class='input-group input-group-sm' style='width:130px;'>
								<button type='button' class='btn btn-outline-secondary minus' data-id='".$row['cartid']."'><i class='fa fa-minus'></i></button>
								<input type='text' class='form-control text-center' value='".$cartQuantity."' id='qty_".$row['cartid']."' oninput='validateQuantity(this, ".$row['stock'].")'>
								<button type='button' class='btn btn-outline-secondary add' data-id='".$row['cartid']."'><i class='fa fa-plus'></i></button>
							</div>
						</td>
						<td>S/ ".number_format($subtotal, 2)."</td>
					</tr>
				";
			}
			$output .= "
				<tr>
					<td colspan='5' align='right'><b>Total</b></td>
					<td><b>S/ ".number_format($total, 2)."</b></td>
				</tr>
			";

		}
		catch(PDOException $e){
			$output .= $e->getMessage();
		}
	}
	else{
		if(isset($_SESSION['cart']) && count($_SESSION['cart']) != 0){
			$total = 0;
			foreach($_SESSION['cart'] as $row){
				$stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM products LEFT JOIN category ON category.id=products.category_id WHERE products.id=:id");
				$stmt->execute(['id'=>$row['productid']]);
				$product = $stmt->fetch();
				$image = (!empty($product['photo'])) ? 'images/'.$product['photo'] : 'images/noimage.jpg';
				
				$availableStock = $product['stock'];
				$cartQuantity = $row['quantity'];
				if ($cartQuantity > $availableStock) {
					$cartQuantity = $availableStock;
				}
	
				$subtotal = $product['price'] * $cartQuantity;
				$total += $subtotal;
	
				$output .= "
					<tr>
						<td><button type='button' data-id='".$row['productid']."' class='btn btn-sm btn-outline-danger cart_delete'><i class='fa fa-remove'></i></button></td>
						<td><img src='".$image."' width='40' height='40' class='rounded-2' style='object-fit:cover;'></td>
						<td>".$product['name']."</td>
						<td>S/ ".number_format($product['price'], 2)."</td>
						<td>
							<div class='input-group input-group-sm' style='width:130px;'>
								<button type='button' id='minus' class='btn btn-outline-secondary minus' data-id='".$row['productid']."'><i class='fa fa-minus'></i></button>
								<input type='text' class='form-control text-center' value='".$cartQuantity."' id='qty_".$row['productid']."' oninput='validateQuantity(this, ".$availableStock.")'>
								<button type='button' id='add' class='btn btn-outline-secondary add' data-id='".$row['productid']."'><i class='fa fa-plus'></i></button>
							</div>
						</td>
						<td>S/ ".number_format($subtotal, 2)."</td>
					</tr>
				";
			}
	
			$output .= "
				<tr>
					<td colspan='5' align='right'><b>Total</b></td>
					<td><b>S/ ".number_format($total, 2)."</b></td>
				</tr>
			";
		}
		else{
			$output .= "
				<tr>
					<td colspan='6' align='center'>Carrito de compras vacío</td>
				</tr>
			";
		}
	}
	
	$pdo->close();
	
	// Asegurar que no haya output antes de los headers
	if (ob_get_length()) ob_clean();
	
	header('Content-Type: application/json');
	header('Cache-Control: no-cache, must-revalidate');
	
	// Enviar el HTML como una cadena JSON válida
	echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
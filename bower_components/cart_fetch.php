<?php
	include 'includes/session.php';
	$conn = $pdo->open();

	$output = array('list'=>'','count'=>0);

	if(isset($_SESSION['user'])){
		try{
			$stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM cart LEFT JOIN products ON products.id=cart.product_id LEFT JOIN category ON category.id=products.category_id WHERE user_id=:user_id");
			$stmt->execute(['user_id'=>$user['id']]);
			foreach($stmt as $row){
				$output['count']++;
				$image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
				$productname = (strlen($row['prodname']) > 30) ? substr_replace($row['prodname'], '...', 27) : $row['prodname'];
				$output['list'] .= "
					<li class='clearfix'>
						<a href='product.php?product=".$row['slug']."' class='d-flex align-items-center gap-2 text-decoration-none'>
							<img src='".$image."' style='width:45px; height:45px; object-fit:cover; border-radius:8px; flex-shrink:0;' alt=''>
							<span>
								<span class='d-block fw-bold'>".$row['catname']." <small>&times; ".$row['quantity']."</small></span>
								<span class='d-block small'>".$productname."</span>
							</span>
						</a>
					</li>
				";
			}
		}
		catch(PDOException $e){
			$output['message'] = $e->getMessage();
		}
	}
	else{
		if(!isset($_SESSION['cart'])){
			$_SESSION['cart'] = array();
		}

		if(empty($_SESSION['cart'])){
			$output['count'] = 0;
		}
		else{
			foreach($_SESSION['cart'] as $row){
				$output['count']++;
				$stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname FROM products LEFT JOIN category ON category.id=products.category_id WHERE products.id=:id");
				$stmt->execute(['id'=>$row['productid']]);
				$product = $stmt->fetch();
				$image = (!empty($product['photo'])) ? 'images/'.$product['photo'] : 'images/noimage.jpg';
				$output['list'] .= "
					<li class='clearfix'>
						<a href='product.php?product=".$product['slug']."' class='d-flex align-items-center gap-2 text-decoration-none'>
							<img src='".$image."' style='width:45px; height:45px; object-fit:cover; border-radius:50%; flex-shrink:0;' alt=''>
							<span>
								<span class='d-block fw-bold'>".$product['catname']." <small>&times; ".$row['quantity']."</small></span>
								<span class='d-block small'>".$product['prodname']."</span>
							</span>
						</a>
					</li>
				";
				
			}
		}
	}

	$pdo->close();
	echo json_encode($output);

?>


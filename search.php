<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green layout-top-nav" >
<div class="wrapper" >

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper"  >
	    <div class="container" style="border-radius:20px;">

	      <!-- Main content -->
	      <section class="content" >
	        <div class="row" style="border-radius:20px;">
	        	<div class="col-sm-9" >
	            <?php
	       			
	       			$conn = $pdo->open();

	       			$stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM products WHERE name LIKE :keyword");
	       			$stmt->execute(['keyword' => '%'.$_POST['keyword'].'%']);
	       			$row = $stmt->fetch();
	       			if($row['numrows'] < 1){
	       				echo '<h1 class="page-header">No se encontraron resultados para <i>'.$_POST['keyword'].'</i></h1>';
						   echo '<center><img src="images/pandatriste.png" alt="No se encontraron resultados" style="width: 40%; border-radius: 20px;"></center>';
	       			}
	       			else{
	       				echo '<h1 class="page-header">Resultados de búsqueda para <i>'.$_POST['keyword'].'</i></h1>';
		       			try{
		       			 	$inc = 3;	
						    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE :keyword");
						    $stmt->execute(['keyword' => '%'.$_POST['keyword'].'%']);
					 
						    foreach ($stmt as $row) {
						    	$highlighted = preg_filter('/' . preg_quote($_POST['keyword'], '/') . '/i', '<b>$0</b>', $row['name']);
						    	$image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
						    	$inc = ($inc == 3) ? 1 : $inc + 1;
	       						if($inc == 1) echo "<div class='row'>";
	       						echo "
	       							<div class='col-sm-4' style='border-radius:20px;' >
	       								<div class='box box-solid' style='border-radius:20px;' >
		       								<div class='box-body prod-body' style='border-radius:20px;' >
		       									<img src='".$image."'  style='border-radius:20px;' width='100%' height='230px' class='thumbnail'>
		       									<h5><a href='product.php?product=".$row['slug']."'>".$highlighted."</a></h5>
		       								</div>
		       								<div class='box-footer' style='border-radius:20px;' >
		       									<b>S/ ".number_format($row['price'], 2)."</b>
		       								</div>
	       								</div>
	       							</div>
	       						";
	       						if($inc == 3) echo "</div>";
						    }
						    if($inc == 1) echo "<div  style='border-radius:20px;'  class='col-sm-4'></div><div  style='border-radius:20px;'  class='col-sm-4'></div></div>"; 
							if($inc == 2) echo "<div style='border-radius:20px;'  class='col-sm-4'></div></div>";
							
						}
						catch(PDOException $e){
							echo "Hay algún problema en la conexión.: " . $e->getMessage();
						}
					}

					$pdo->close();

	       		?> 
				                    <div class="fb-comments" style="border-radius:20px;" data-href="index.php?product=<?php echo $slug; ?>" data-numposts="10" width="100%"></div> 
	        	</div>
	        	<div class="col-sm-3" style="border-radius:20px;">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
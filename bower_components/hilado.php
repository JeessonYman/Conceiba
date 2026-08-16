<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container-fluid px-4">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-9">
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='alert alert-danger'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}
	        		?>
					<br>
					<center><h2><b>Especificaciones Hilado</b></h2></center>
					<br>
					<?php
						$conn = $pdo->open();
						$stmt = $conn->prepare("SELECT * FROM yarn_specs ORDER BY sort_order ASC, id ASC");
						$stmt->execute();
						$yarns = $stmt->fetchAll(PDO::FETCH_ASSOC);

						foreach($yarns as $yarn){
							$yarnPhoto = (!empty($yarn['photo']) && file_exists('images/'.$yarn['photo'])) ? 'images/'.$yarn['photo'] : 'images/noimage.jpg';
							echo '
								<div class="glass p-4 mb-4">
									<div class="row g-4 align-items-start">
										<div class="col-md-5">
											<img src="'.$yarnPhoto.'" class="rounded-3 w-100" style="object-fit:cover; height:280px;" alt="'.htmlspecialchars($yarn['title']).'">
										</div>
										<div class="col-md-7">
											<h3 class="fw-bold mb-3">'.htmlspecialchars($yarn['title']).'</h3>
											<p class="mb-3">'.nl2br(htmlspecialchars($yarn['description'])).'</p>
											<div class="row g-3">
												<div class="col-6"><b>Composición</b><br>'.htmlspecialchars($yarn['composition']).'</div>
												<div class="col-6"><b>Título hilado</b><br>'.htmlspecialchars($yarn['yarn_title']).'</div>
												<div class="col-6"><b>Presentación</b><br>'.htmlspecialchars($yarn['presentation']).'</div>
												<div class="col-6"><b>Producción</b><br>'.htmlspecialchars($yarn['production']).'</div>
												<div class="col-6"><b>Agujas sugeridas</b><br>'.nl2br(htmlspecialchars($yarn['needles'])).'</div>
												<div class="col-6"><b>Peso</b><br>'.htmlspecialchars($yarn['weight']).'</div>
											</div>
											<hr>
											<p class="mb-1"><b>Usos:</b> '.htmlspecialchars($yarn['uses']).'</p>
											<p class="mb-0"><b>Cuidados:</b> '.htmlspecialchars($yarn['care_instructions']).'</p>
										</div>
									</div>
								</div>
							';
						}
					?>
					<br>
					<center><h2><b>CONOCE A NUESTRAS ARTESANAS CONCEIBA </b></h2></center>
					<br>
					<?php
						$stmt = $conn->prepare("SELECT * FROM artisans WHERE active=1 ORDER BY sort_order ASC, id ASC");
						$stmt->execute();
						$artisans = $stmt->fetchAll(PDO::FETCH_ASSOC);

						foreach($artisans as $artisan){
							$artisanPhoto = (!empty($artisan['photo']) && file_exists('images/'.$artisan['photo'])) ? 'images/'.$artisan['photo'] : 'images/noimage.jpg';
							$ageLine = (!empty($artisan['age'])) ? $artisan['name'].' tiene '.$artisan['age'].' años, ' : $artisan['name'].', ';
							$communityLine = (!empty($artisan['community'])) ? 'vive en '.$artisan['community'].'.' : '';
							echo '
								<div class="glass p-4 mb-4">
									<div class="row g-4 align-items-center">
										<div class="col-md-4 text-center">
											<img src="'.$artisanPhoto.'" class="rounded-circle" style="width:220px; height:220px; object-fit:cover; border:3px solid var(--accent, #e0ac2b);" alt="'.htmlspecialchars($artisan['name']).'">
										</div>
										<div class="col-md-8">
											<h3 class="fw-bold">'.htmlspecialchars($artisan['name']).'</h3>
											<hr style="border-color: var(--accent, #e0ac2b); opacity:1; max-width:150px; margin-left:0;">
											<p>'.htmlspecialchars($ageLine.$communityLine).'</p>
											<p style="white-space: pre-line;">'.nl2br(htmlspecialchars($artisan['bio'])).'</p>
										</div>
									</div>
								</div>
							';
						}
					?>
					<br>
					<center><h2><b>Productos mas solicitados </b></h2></center>
					<?php
$month = date('m');

try {
    $inc = 3;
    $stmt = $conn->prepare("SELECT *, 
                                    SUM(quantity) AS total_qty 
                            FROM details 
                            LEFT JOIN sales ON sales.id=details.sales_id 
                            LEFT JOIN products ON products.id=details.product_id 
                            WHERE MONTH(sales_date) = '$month' 
                            GROUP BY details.product_id 
                            ORDER BY total_qty DESC LIMIT 6");
    $stmt->execute();
    $rowCount = $stmt->rowCount(); // Obtener el número de filas recuperadas
    if ($rowCount > 0) { // Verificar si se encontraron datos
        foreach ($stmt as $row) {
            $image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
            $inc = ($inc == 3) ? 1 : $inc + 1;
            // Agregar el ribbon para productos agotados o con últimas unidades
            $ribbon = '';
            $stockClass = '';
            if ($row['stock'] <= 0) {
                $ribbon = '<div class="ribbon red"><span>Agotado</span></div>';
                $stockClass = 'disabled';
            } elseif ($row['stock'] <= $row['stock_minimum']) {
                $ribbon = '<div class="ribbon yellow  low-stock"><span>Últimas Unidades</span></div>';
            }

            if ($inc == 1) echo "<div class='row'>";
            echo "
                <div class='col-sm-4 mb-4'>
                    <div class='product-card h-100 p-3 text-center ".(($row['stock'] <= 0) ? 'disabled' : '')."' style='position: relative;'>
                        $ribbon
                        <img src='".$image."' class='rounded-3 mb-2' style='width:100%; height:150px; object-fit:contain; background:rgba(0,0,0,0.04);'>
                        <h6 class='fw-bold'>".(($row['stock'] > 0) ? "<a href='product.php?product=".$row['slug']."'>".$row['name']."</a>" : $row['name'])."</h6>
                        <p class='mb-1'>Stock: <span class='badge rounded-pill ".(($row['stock'] > $row['stock_minimum']) ? 'bg-success' : (($row['stock'] > 0) ? 'bg-warning text-dark' : 'bg-danger'))."'>".$row['stock']."</span></p>
                        <p class='price-old mb-0'>Antes: S/ ".number_format($row['price_normal'], 2)."</p>
                        <p class='price-now mb-0'>S/ ".number_format($row['price'], 2)."</p>
                    </div>
                </div>
            ";
            if ($inc == 3) echo "</div>";
        }
        if($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>"; 
        if($inc == 2) echo "<div class='col-sm-4'></div></div>";
    } else { // Si no se encontraron datos
        echo '<h1 class="page-header">No hay productos para mostrar <i>'.'</i></h1>';
        echo '<center><img src="images/pandatriste.png" alt="No se encontraron resultados" style="width: 40%; border-radius: 20px;"></center>';
    }
} catch(PDOException $e) {
    echo "Hay algún problema en la conexión: " . $e->getMessage();
}

$pdo->close();
?>
                      <div class="fb-comments" style="border-radius:20px;" data-href="hilado.php?product=<?php echo $slug; ?>" data-numposts="10" width="100%"></div> 
	        	</div>
	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>

	  <style>
/* Estilo adicional para deshabilitar la redirección */
.product-card.disabled {
    pointer-events: none;
    opacity: 0.7;
    cursor: no-drop	;
}
</style>
  
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</html>

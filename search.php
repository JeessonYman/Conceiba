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

					// Protección básica: validar que la búsqueda tenga al menos 3 caracteres
					$keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
					if(empty($keyword) || mb_strlen($keyword) < 3){
						echo '<h1 class="page-header">Introduce al menos 3 caracteres para buscar</h1>';
						echo '<center><img src="images/pandatriste.png" alt="Introduce más caracteres" style="width: 40%; border-radius: 20px;"></center>';
					} else {
						echo '<h1 class="page-header">Resultados de búsqueda para <i>'.htmlspecialchars($keyword).'</i></h1>';
						try{
							// Limitar resultados para evitar escaneos completos y reducir carga en MySQL
							// También protegemos contra tiempos de ejecución largos
							set_time_limit(20);
							$inc = 3;
							// Máximo de filas devueltas por página
							$limit = 60;
							$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE :keyword LIMIT :limit");
							$like = '%'.$keyword.'%';
							// PDO requiere bindParam explícito para enteros
							$stmt->bindParam(':keyword', $like, PDO::PARAM_STR);
							$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
							$stmt->execute();
							$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
							// Si no hay resultados, mostrar la imagen del panda y un mensaje amigable
							if(empty($results)){
								echo "<div class='text-center' style='padding:20px;'>";
								echo "<img src='images/pandatriste.png' alt='No encontrado' style='width: 40%; border-radius: 20px;'>";
								echo "<p class='text-muted' style='margin-top:10px;'>Lo siento, no se encontró lo que estás buscando";
								if(!empty($keyword)){
									echo ": <strong>".htmlspecialchars($keyword)."</strong>";
								}
								echo "</p></div>";
							} else {
								foreach ($results as $row) {
									$highlighted = preg_replace('/' . preg_quote($keyword, '/') . '/i', '<b>$0</b>', $row['name']);
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
												<div class='box-footer price-container' style='border-radius:20px; background-color: #333333; color: #ffffff;'>
													<b style='color: inherit;'>S/ ".number_format($row['price'], 2)."</b>
												</div>
											</div>
										</div>
									";
									if($inc == 3) echo "</div>";
								}
								if($inc == 1) echo "<div  style='border-radius:20px;'  class='col-sm-4'></div><div  style='border-radius:20px;'  class='col-sm-4'></div></div>"; 
								if($inc == 2) echo "<div style='border-radius:20px;'  class='col-sm-4'></div></div>";
							}
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para actualizar los colores del precio
    function updatePriceColors() {
		// Detectar tema leyendo el atributo data-theme del elemento <html>
		const theme = document.documentElement.getAttribute('data-theme') || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
		const isDarkMode = theme === 'dark';
		const priceContainers = document.querySelectorAll('.price-container');

		priceContainers.forEach(container => {
			if (isDarkMode) {
				container.style.backgroundColor = '#333333';
				container.style.color = '#ffffff';
			} else {
				container.style.backgroundColor = '#ffffff';
				container.style.color = '#333333';
			}
			// Asegurar contraste consistente y transición
			container.style.transition = 'background-color 0.15s ease, color 0.15s ease';
		});
    }

	// Observar cambios en el atributo data-theme del elemento <html>
	const observer = new MutationObserver(function(mutations) {
		mutations.forEach(function(mutation) {
			if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
				updatePriceColors();
			}
		});
	});

	// Configurar el observer sobre documentElement para escuchar cambios en data-theme
	observer.observe(document.documentElement, {
		attributes: true,
		attributeFilter: ['data-theme']
	});

    // Ejecutar inicialmente
    updatePriceColors();
});
</script>
</body>
</html>
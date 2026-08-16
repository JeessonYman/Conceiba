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
	        <?php
	          $contactContent = getKeyValueSettings($pdo, 'contact_content', [
	            'intro_title' => 'Envíanos un mensaje',
	            'map_embed_url' => '',
	          ]);
	        ?>
	        <div class="row">
	        	<div class="col-sm-9">
				<?php
      if(isset($_SESSION['error'])){
        echo "
          <div class='callout callout-danger text-center' style='border-radius:20px;'>
            <p>".$_SESSION['error']."</p> 
          </div>
        ";
        unset($_SESSION['error']);
      }
      if(isset($_SESSION['success'])){
        echo "
          <div class='callout callout-success text-center' style='border-radius:20px;'>
            <p>".$_SESSION['success']."</p> 
          </div>
        ";
        unset($_SESSION['success']);
      }
    ?>
					<!-- Formulario mensaje-->
                    <div class="row">
						<div class="col-md-8 offset-md-2">
							<div class="glass p-4 mb-4">
								<h2><?php echo htmlspecialchars($contactContent['intro_title']); ?></h2>
								<form action="enviar.php" method="POST">
									<div class="form-group">
										<label>Nombre</label>
										<input type="text" name="name" class="form-control" id="name" placeholder="Tu nombre" style="border-radius:20px;">
									</div>
									<div class="form-group">
										<label>Correo Electronico</label>
										<input type="text" name="email" class="form-control" id="mail" placeholder="Tu email" style="border-radius:20px;">
									</div>
									<div class="form-group">
										<label>Asunto</label>
										<input type="text" name="subject" class="form-control" id="subject" placeholder="Asunto del Mensaje" style="border-radius:20px;">
									</div>
									<div class="form-group">
										<label>Mensaje</label>
										<textarea name="message" rows="3" id="message" placeholder="Escribe tu mensaje" required class="form-control" style="border-radius:20px;"></textarea>
									</div>
									<button type="submit" class="btn btn-default" name="submit" style="border-radius:20px;">Enviar mensaje</button>
								</form>
							</div>
						</div>
					</div>
                    <div class="text-center">
						<br>
                       <h1 style="color:black;">NUESTRA UBICACIÓN</h1>
					   <br>
                       <iframe id="conceiba-map" src="<?php echo htmlspecialchars($contactContent['map_embed_url']); ?>" width="750" height="300" style="border:0 ; width:100%; max-width:700px; border-radius: 20px;" class="glass" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                       <script>
                         (function(){
                           function applyMapTheme(){
                             var map = document.getElementById('conceiba-map');
                             if(!map) return;
                             var theme = document.documentElement.getAttribute('data-theme');
                             map.style.filter = (theme === 'dark') ? 'invert(90%) hue-rotate(180deg) brightness(95%) contrast(90%)' : 'none';
                           }
                           document.addEventListener('DOMContentLoaded', applyMapTheme);
                           // Re-aplicar cuando el usuario cambia el tema con el botón del navbar
                           const origToggle = window.toggleThemeConceiba;
                           if(origToggle){
                             window.toggleThemeConceiba = function(){
                               origToggle();
                               applyMapTheme();
                             };
                           }
                         })();
                       </script>
                    </div>
					<br>
					<br>
					<center><h2><b>Productos mas solicitados </b></h2></center>
					<br>
          <?php
$month = date('m');
$conn = $pdo->open();

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
                      <div class="fb-comments" style="border-radius:20px;" data-href="contacto.php?product=<?php echo $slug; ?>" data-numposts="10" width="100%"></div> 

	        	</div>
	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<style>
/* Estilo adicional para deshabilitar la redirección */
.product-card.disabled {
    pointer-events: none;
    opacity: 0.7;
    cursor: no-drop	;
}
</style>
<script>
$(function () {
	function showAlert(message) {
        $('#callout').removeClass('callout-success callout-danger').addClass('callout-danger');
        $('#callout .message').html(message);
        $('#callout').fadeIn();
    }

    // Cerrar la alerta al hacer clic en el botón de cierre
    $('.close').click(function () {
        $('#callout').fadeOut();
    });
});
</script>

</html>

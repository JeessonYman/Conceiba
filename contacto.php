<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-9">
				<?php
      if(isset($_SESSION['error'])){
        echo "
          <div class='callout callout-danger style='border-radius:20px;' text-center'>
            <p>".$_SESSION['error']."</p> 
          </div>
        ";
        unset($_SESSION['error']);
      }
      if(isset($_SESSION['success'])){
        echo "
          <div class='callout callout-success  style='border-radius:20px;' text-center'>
            <p>".$_SESSION['success']."</p> 
          </div>
        ";
        unset($_SESSION['success']);
      }
    ?>
					<!-- Formulario mensaje-->
                    <div class="row" style="width:'750'; height:'550';">
						<div class="col-md-8 col-md-offset-2" style='width:"750"; height:"550";'>
							<h2>Envianos un mensaje</h2>
							<form style='width:"750"; height:"550";' action="enviar.php" method="POST">
								<div class="form-group">
									<label>Nombre</label>
									<input type="text" name="name" class="form-control" id="name" placeholder="Tu nombre" name="Nombres"  style="border-radius:20px;">
								</div>
								<label>Correo Electronico</label>
								<div class="form-group">
								<input type="text" name="email" class="form-control" id="mail" placeholder="Tu email" style="border-radius:20px;">
								</div>
								<label>Asunto</label>
								<div class="form-group">
									<input type="text" name="subject" class="form-control" id="subject" placeholder="Asunto del Mensaje" name="Asunto"  style="border-radius:20px;"> 
								</div>
								<div class="form-group">
									<label>Mensaje</label>
									<textarea name="message" rows="3"  id="message" placeholder="Escribe tu mensaje" required class="form-control" name="Mesaje"  style="border-radius:20px;"></textarea>
								</div>
								
								<button type="submit" class="btn btn-default" name="submit"  style="border-radius:20px;">Enviar mensaje</button>
							</form>
					</div>
				</div>
        <DIV ALIGN="center">
                    <body>
						<br>
                       <h1 style="color:black;">NUESTRA UBICACIÓN</h1>
					   <br>
                       <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3904.711830214369!2d-77.04075408255615!3d-11.855433499999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105d7d4c0fdb70d%3A0xdf26579b5a250cc7!2sCONCEIBA!5e0!3m2!1ses-419!2spe!4v1660168626871!5m2!1ses-419!2spe" width="750" height="450" style="border:0 ; width:100%; border-radius: 20px;"   allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </body></DIV>
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
                <div class='col-sm-4 mb-4 product-card $stockClass' style='border-radius:20px;'>
                    <div class='box box-solid h-100' style='border-radius:20px;'>
                        <div class='box-body prod-body text-center' style='border-radius:20px; position: relative;'>
                            $ribbon
                            <img src='".$image."' style='border-radius:20px;' width='100%' height='150px' class='thumbnail'>
                            <h5><a style='border-radius:20px; font-weight: bold' href='product.php?product=".$row['slug']."'>".$row['name']."</a></h5>
                            <p>Stock: <span class='badge badge-pill ".(($row['stock'] > $row['stock_minimum']) ? 'badge-success' : (($row['stock'] > 0) ? 'badge-warning' : 'badge-danger'))."'>".$row['stock']."</span></p>
                            <p class='mb-1'><b>Antes:</b> <s>S/ ".number_format($row['price_normal'], 2)."</s></p>
                            <p><b>Ahora:</b><span style='font-weight: bold'>S/ ".number_format($row['price'], 2)." </span></p>
                        </div>
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
    /* Estilo para colores de stock */
.badge-success { background-color: #28a745; }
.badge-warning { background-color: #ffc107; }
.badge-danger { background-color: #dc3545; }
/* Estilo para el ribbon */
/* Estilo mejorado para el ribbon */
.ribbon {
  position: absolute;
  right: -5px;
  top: -5px;
  z-index: 1;
  overflow: hidden;
  width: 93px;
  height: 93px;
  text-align: right;
}
.ribbon span {
  font-size: 0.9rem;
  color: #fff;
  text-transform: uppercase;
  text-align: center;
  font-weight: bold;
  line-height: 32px;
  transform: rotate(45deg);
  width: 125px;
  display: block;
  background: #79a70a;
  background: linear-gradient(#9bc90d 0%, #79a70a 100%);
  box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
  position: absolute;
  top: 17px; 
  right: -29px; 
}

.ribbon span::before {
   content: '';
   position: absolute; 
   left: 0px; top: 100%;
   z-index: -1;
   border-left: 3px solid #79A70A;
   border-right: 3px solid transparent;
   border-bottom: 3px solid transparent;
   border-top: 3px solid #79A70A;
}
.ribbon span::after {
   content: '';
   position: absolute; 
   right: 0%; top: 100%;
   z-index: -1;
   border-right: 3px solid #79A70A;
   border-left: 3px solid transparent;
   border-bottom: 3px solid transparent;
   border-top: 3px solid #79A70A;
}

.red span {
  background: linear-gradient(#f70505 0%, #8f0808 100%);
}
.red span::before {
  border-left-color: #8f0808;
  border-top-color: #8f0808;
}
.red span::after {
  border-right-color: #8f0808;
  border-top-color: #8f0808;
}

.yellow  span {
    background: linear-gradient(#ffc107 0%, #ffc10d 100%);
}
.yellow  span::before {
  border-left-color: #ffc107;
  border-top-color: #ffc107;
}
.yellow  span::after {
  border-right-color: #ffc107;
  border-top-color: #ffc107;
}

.foo {
  clear: both;
}

.bar {
  content: "";
  left: 0px;
  top: 100%;
  z-index: -1;
  border-left: 3px solid #79a70a;
  border-right: 3px solid transparent;
  border-bottom: 3px solid transparent;
  border-top: 3px solid #79a70a;
}

.baz {
  font-size: 1rem;
  color: #fff;
  text-transform: uppercase;
  text-align: center;
  font-weight: bold;
  line-height: 2em;
  transform: rotate(45deg);
  width: 100px;
  display: block;
  background: #79a70a;
  background: linear-gradient(#9bc90d 0%, #79a70a 100%);
  box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
  position: absolute;
  top: 100px;
  left: 1000px;
}
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

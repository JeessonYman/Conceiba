<!DOCTYPE html>
<html lang="es">

<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-green layout-top-nav">
    <div class="wrapper">

        <?php include 'includes/navbar.php'; ?>
        <div class="content-wrapper ">
            <div class="container">

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
                            <div class="text-center mb-4">
                                <h2 class="producto-titulo"><b>LOS MEJORES PRODUCTOS</b></h2>
                            </div>
                            
                            <div class="carousel-container">
                                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="border-radius:20px; overflow:hidden; margin-bottom:20px;">
                                    <ol class="carousel-indicators">
                                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                        <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                                        <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                                    </ol>
                                    <div class="carousel-inner">
                                        <div class="item active">
                                            <img src="images/carrusel1.png" alt="First slide" style="width:100%; height:auto;" loading="lazy">
                                        </div>
                                        <div class="item">
                                            <img src="images/carrusel2.png" alt="Second slide" style="width:100%; height:auto;" loading="lazy">
                                        </div>
                                        <div class="item">
                                            <img src="images/carrusel3.png" alt="Third slide" style="width:100%; height:auto;" loading="lazy">
                                        </div>
                                    </div>
                                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                        <span class="fa fa-angle-left"></span>
                                    </a>
                                    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                        <span class="fa fa-angle-right"></span>
                                    </a>
                                </div>
                            </div>
                            </center>
                            <br>
                            <br>
                            <center>
                                <h2><b>¿Quiénes somos?</b></h2>
                            </center>
                            <br>
                            <center>
                                <h4>Somos una empresa que aprovecha sosteniblemente la fibra vegetal de kapok, para
                                    elaborar artículos textiles. Generamos ingresos en comunidades, y contribuimos a la
                                    preservación de bosques secos.</h4>
                                <br>
                            <center><img src="images/relleno1.jpg" alt="Imagen de relleno" width="" style="width:40%; border-radius: 20px;" loading="lazy"></center>
                                <br>
                                <h2> <b>NUESTRO IMPACTO</b></h2>

                            </center>
                            <section>
                                <div class="container">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <h3 class="text-center text-black">28 familias productoras</h3>
                                            <br>
                                            <center>
                                                <img src="images/impacto.png" alt="" width="180"
                                                    style="border-radius:20px;">
                                            </center>
                                            <br>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <h3 class="text-center text-black">Cientos de árboles puestos en valor</h3>
                                            <br>
                                            <center>
                                                <img src="images/impacto2.png" alt="" width="160"
                                                    style="border-radius:20px;">
                                            </center>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <br>
                            <center>
                                <h2> <b>NUESTROS ALIADOS</b></h2>
                                <br>
                                <article class="bg-light py-3 my-5">
                                    <div class="container">
                                    </div>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <img src="images/aliado1.png" alt="" width="150"
                                                            style="border-radius:20px;">
                                                        <div class="redes d-flex justify-content-center">
                                                            <i class="fa-brands fa-facebook m-2"></i>
                                                            <i class="fa-brands fa-twitter m-2"></i>
                                                            <i class="fa-brands fa-instagram m-2"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <img src="images/aliado2.png" alt="" width="180"
                                                            style="border-radius:20px;">
                                                        <div class="redes d-flex justify-content-center">
                                                            <i class="fa-brands fa-facebook m-2"></i>
                                                            <i class="fa-brands fa-twitter m-2"></i>
                                                            <i class="fa-brands fa-instagram m-2"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <img src="images/aliado3.png" alt="" width="180"
                                                            style="border-radius:20px;">
                                                        <div class="redes d-flex justify-content-center">
                                                            <i class="fa-brands fa-facebook m-2"></i>
                                                            <i class="fa-brands fa-twitter m-2"></i>
                                                            <i class="fa-brands fa-instagram m-2"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </center>
                            <br>
                            <br>
                            <center>
                                <h3> <b>RECONOCIMIENTO</b></h3>
                                <br>
                                <center>
                                    <img src="images/reconocimiento.png" alt="" style="border-radius:20px;" width="180">
                                </center>
                

                                <br>
                                <center>
                                    <h2><b>Productos mas solicitados </b></h2>
                                </center>
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

                    <br>
                    <div class="fb-comments" style="border-radius:20px;" data-href="index.php?product=<?php echo $slug; ?>" data-numposts="10" width="100%"></div> 
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
    <style>
    /* Estilos del carrusel */
    .carousel-container {
        margin-bottom: 30px;
    }
    .carousel {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .carousel-inner {
        border-radius: 20px;
    }
    .carousel-indicators {
        bottom: 10px;
    }
    .carousel-indicators li {
        border: 1px solid #fff;
        background: rgba(255,255,255,0.5);
    }
    .carousel-indicators .active {
        background: #fff;
    }
    .carousel-control {
        background-image: none !important;
        opacity: 0.8;
    }
    .carousel-control:hover {
        opacity: 1;
    }
    .carousel-control .fa {
        position: absolute;
        top: 50%;
        margin-top: -15px;
        font-size: 30px;
        color: #fff;
        text-shadow: 0 1px 2px rgba(0,0,0,0.6);
    }
    .carousel-control.left .fa {
        left: 20px;
    }
    .carousel-control.right .fa {
        right: 20px;
    }
       
    /* Estilo para colores de stock */
.badge-success { background-color: #28a745; }
.badge-warning { background-color: #ffc107; }
.badge-danger { background-color: #dc3545; }

    /*== Block policy ==*/
    .block-policy4 {
        border: 1px solid #ebebeb;
        border-radius: 3px;
        padding: 13px 0 16px 0;
        margin: 40px 0;
        display: inline-block;
        width: 100%;
    }

    .block-policy4 ul li {
        float: left;
        padding: 0 15px;
        text-align: center;
        width: 20%;
        position: relative;
    }

    .block-policy4 ul li:before {
        background: #ebebeb none repeat scroll 0 0;
        content: "";
        height: 50px;
        position: absolute;
        right: 0;
        top: 3px;
        width: 1px;
    }

    .block-policy4 ul li:last-child:before {
        display: none;
    }

    .block-policy4 ul li .item-inner {
        display: inline-block;
    }

    .block-policy4 ul li .item-inner .icon {
        width: 60px;
        height: 52px;
        float: left;
        margin-right: 10px;
    }

    .block-policy4 ul li .item-inner .content {
        float: left;
        text-align: left;
        margin-top: 6px;
    }

    .block-policy4 ul li .item-inner .content a {
        color: #333;
        font-weight: 700;
        font-size: 16px;
    }

    .block-policy4 ul li .item-inner .content a:hover {
        color: #2677e7;
    }

    .block-policy4 ul li .item-inner .content p {
        line-height: 100%;
        margin-top: 5px;
        margin: 0;
        text-transform: capitalize;
        font-size: 14px;
    }

    .block-policy4 ul li:last-child .item-inner:before {
        display: none;
    }

    .layout-4 .block-policy4,
    .layout-4 .banners7,
    .layout-4 .banner-8,
    .layout-4 .single-baner {
        display: none;
    }
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
    <?php include 'includes/scripts.php'; ?>
    

</body>
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
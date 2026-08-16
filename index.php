<!DOCTYPE html>
<html lang="es">

<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="">
    <div class="wrapper">

        <?php include 'includes/navbar.php'; ?>
        <div class="content-wrapper ">
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
                            <div class="text-center mb-4">
                                <h2 class="producto-titulo"><b>LOS MEJORES PRODUCTOS</b></h2>
                            </div>
                            
                            <div class="carousel-container">
                                <div id="carousel-example-generic" class="carousel slide carousel-fade glass" data-bs-ride="carousel" style="border-radius:16px; overflow:hidden; margin-bottom:20px;">
                                    <div class="carousel-indicators">
                                        <button type="button" data-bs-target="#carousel-example-generic" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Diapositiva 1"></button>
                                        <button type="button" data-bs-target="#carousel-example-generic" data-bs-slide-to="1" aria-label="Diapositiva 2"></button>
                                        <button type="button" data-bs-target="#carousel-example-generic" data-bs-slide-to="2" aria-label="Diapositiva 3"></button>
                                    </div>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img src="images/carrusel1.png" class="d-block" alt="Peluches a base de Kapok" loading="lazy" style="width:100%; aspect-ratio:21/9; object-fit:cover; object-position:center top;">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="images/carrusel2.png" class="d-block" alt="Productos Conceiba" loading="lazy" style="width:100%; aspect-ratio:21/9; object-fit:cover; object-position:center top;">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="images/carrusel3.png" class="d-block" alt="Productos Conceiba" loading="lazy" style="width:100%; aspect-ratio:21/9; object-fit:cover; object-position:center top;">
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-example-generic" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Anterior</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-example-generic" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Siguiente</span>
                                    </button>
                                </div>
                            </div>
                            <br>
                            <br>
                            <?php
                                $home = getKeyValueSettings($pdo, 'home_content', [
                                    'about_title' => '¿Quiénes somos?',
                                    'about_text' => 'Somos una empresa que aprovecha sosteniblemente la fibra vegetal de kapok.',
                                    'about_image' => 'relleno1.jpg',
                                    'impact_title' => 'NUESTRO IMPACTO',
                                    'allies_title' => 'NUESTROS ALIADOS',
                                ]);
                            ?>
                            <center>
                                <h2><b><?php echo htmlspecialchars($home['about_title']); ?></b></h2>
                            </center>
                            <br>
                            <center>
                                <h4><?php echo nl2br(htmlspecialchars($home['about_text'])); ?></h4>
                                <br>
                            <center><img src="images/<?php echo htmlspecialchars($home['about_image']); ?>" alt="Imagen de relleno" style="width:40%; border-radius: 20px;" loading="lazy"></center>
                                <br>
                                <h2> <b><?php echo htmlspecialchars($home['impact_title']); ?></b></h2>

                            </center>
                            <section>
                                <div class="container">
                                    <div class="row d-flex justify-content-center">
                                        <?php
                                            $conn = $pdo->open();
                                            $stmt = $conn->prepare("SELECT * FROM impact_stats ORDER BY sort_order ASC, id ASC");
                                            $stmt->execute();
                                            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $stat){
                                                $statImg = (!empty($stat['image']) && file_exists('images/'.$stat['image'])) ? 'images/'.$stat['image'] : 'images/noimage.jpg';
                                                echo '
                                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                                        <h3 class="text-center">'.htmlspecialchars($stat['label']).'</h3>
                                                        <br>
                                                        <center>
                                                            <img src="'.$statImg.'" alt="" width="180" style="border-radius:20px;">
                                                        </center>
                                                        <br>
                                                    </div>
                                                ';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </section>
                            <br>
                            <center>
                                <h2> <b><?php echo htmlspecialchars($home['allies_title']); ?></b></h2>
                                <br>
                                <article class="py-3 my-5">
                                    <div class="container">
                                        <div class="row">
                                            <?php
                                                $stmt = $conn->prepare("SELECT * FROM allies ORDER BY sort_order ASC, id ASC");
                                                $stmt->execute();
                                                foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $ally){
                                                    $allyImg = (!empty($ally['image']) && file_exists('images/'.$ally['image'])) ? 'images/'.$ally['image'] : 'images/noimage.jpg';
                                                    echo '
                                                        <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <img src="'.$allyImg.'" alt="" width="150" style="border-radius:20px;">
                                                                    <div class="redes d-flex justify-content-center">
                                                                        '.(!empty($ally['facebook_url']) ? '<a href="'.htmlspecialchars($ally['facebook_url']).'" target="_blank"><i class="fa-brands fa-facebook m-2"></i></a>' : '<i class="fa-brands fa-facebook m-2"></i>').'
                                                                        '.(!empty($ally['twitter_url']) ? '<a href="'.htmlspecialchars($ally['twitter_url']).'" target="_blank"><i class="fa-brands fa-twitter m-2"></i></a>' : '<i class="fa-brands fa-twitter m-2"></i>').'
                                                                        '.(!empty($ally['instagram_url']) ? '<a href="'.htmlspecialchars($ally['instagram_url']).'" target="_blank"><i class="fa-brands fa-instagram m-2"></i></a>' : '<i class="fa-brands fa-instagram m-2"></i>').'
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ';
                                                }
                                            ?>
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
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
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
    .carousel-img {
        height: 380px;
        object-fit: cover;
    }
    @media (max-width: 767px) {
        .carousel-img {
            height: 220px;
        }
    }
    .carousel-indicators [data-bs-target] {
        background-color: rgba(255,255,255,0.6);
    }
    .carousel-indicators .active {
        background-color: #fff;
    }
    .carousel-control-prev,
    .carousel-control-next {
        opacity: 0.8;
        width: 8%;
    }
    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        opacity: 1;
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
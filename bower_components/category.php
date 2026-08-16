<!DOCTYPE html>
<html lang="es">
    <?php include 'includes/session.php'; ?>
    <?php
        $slug = $_GET['category'];
        $conn = $pdo->open();

        try {
            $stmt = $conn->prepare("SELECT * FROM category WHERE cat_slug = :slug");
            $stmt->execute(['slug' => $slug]);
            $cat = $stmt->fetch();
            $catid = $cat['id'];
        } catch(PDOException $e) {
            echo "Hay algún problema en la conexión: " . $e->getMessage();
        }
        $pdo->close();
    ?>
    <?php include 'includes/header.php'; ?>
</head>
<body class="">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <div class="content-wrapper">
        <div class="container-fluid px-4">
            <section class="content" style="border-radius:10px;">
                <div class="row" style="border-radius:10px;">
                    <div class="col-sm-9" style="border-radius:10px;">
                        <h1 class="page-header" style="border-radius:10px;  font-weight: bold"><?php echo $cat['name']; ?></h1>
                        <?php
                            $conn = $pdo->open();
                            try {
                                $inc = 3;
                                $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid");
                                $stmt->execute(['catid' => $catid]);
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
             if ($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>";
                                if ($inc == 2) echo "<div class='col-sm-4'></div></div>";
                            } catch(PDOException $e) {
                                echo "Hay algún problema en la conexión: " . $e->getMessage();
                            }
                            $pdo->close();
                        ?>
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
</body>
</html>
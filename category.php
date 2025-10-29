<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="wasathpp.css">
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
<body class="hold-transition skin-green layout-top-nav" style="border-radius:20px;">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <div class="content-wrapper">
        <div class="container" style="border-radius:10px;">
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
                <body>
                    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

                </body>
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
</body>
</html>
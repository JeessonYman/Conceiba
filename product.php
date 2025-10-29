<!DOCTYPE html>
<html lang="es">
<?php include 'includes/session.php'; ?>
<?php
    $conn = $pdo->open();

    $slug = $_GET['product'];

    try{
        $stmt = $conn->prepare("SELECT *,
                                    products.name AS prodname,
                                    category.name AS catname, 
                                    products.id AS prodid, 
                                    products.stock, 
                                    products.price_normal 
                            FROM products 
                            LEFT JOIN category ON category.id=products.category_id 
                            WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        $product = $stmt->fetch();
    }
    catch(PDOException $e){
        echo "Hay un problema en la conexión: " . $e->getMessage();
    }

    //page view
    $now = date('Y-m-d');
    if($product['date_view'] == $now){
        $stmt = $conn->prepare("UPDATE products SET counter=counter+1 WHERE id=:id");
        $stmt->execute(['id'=>$product['prodid']]);
    }
    else{
        $stmt = $conn->prepare("UPDATE products SET counter=1, date_view=:now WHERE id=:id");
        $stmt->execute(['id'=>$product['prodid'], 'now'=>$now]);
    }

    $pdo->close();
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green layout-top-nav">
<script>
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12';
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
     
    <div class="content-wrapper">
        <div class="container" style="border-radius:20px;">
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
          <!-- Main content -->
          <section class="content" style="border-radius:20px;">
            <div class="row" style="border-radius:20px;">
                <div class="col-sm-9" style="border-radius:20px;">
                    <div class="callout" id="callout" style="display:none" style="border-radius:20px;">
                        <button type="button" class="close" style="border-radius:20px;"><span aria-hidden="true" style="border-radius:20px;">&times;</span></button>
                        <span class="message" style="border-radius:20px;"></span>
                    </div>
                    <div class="row" style="border-radius:20px;">
                        <div class="col-sm-6" style="border-radius:20px;">
                            <img src="<?php echo (!empty($product['photo'])) ? 'images/'.$product['photo'] : 'images/noimage.jpg'; ?>" width="100%" style="border-radius:20px;" class="zoom"  data-magnify-src="images/large-<?php echo $product['photo']; ?>">
                            <br>
                            <br>
                            <form class="form-inline" id="productForm" style="border-radius:20px;">
							<div class="form-group" style="border-radius:20px;">
							<div class="input-group col-sm-5" style="border-radius:20px;">
                                        <span class="input-group-btn" style="border-radius:20px;">
                                            <button type="button" id="minus"  style="border-radius:20px;" class="btn btn-default btn-flat btn-lg"><i class="fa fa-minus"></i></button>
                                        </span>
                                        <input type="text" name="quantity" id="quantity"  style="border-radius:20px;" class="form-control input-lg" value="1">
                                        <span class="input-group-btn">
                                            <button type="button"  style="border-radius:20px;" id="add" class="btn btn-default btn-flat btn-lg"><i class="fa fa-plus"></i>
                                            </button>
                                        </span>
                                        <input type="hidden" style="border-radius:20px;" value="<?php echo $product['prodid']; ?>" name="id">
                                        <?php if($product['stock'] <1) { ?> 
                                    </div>
                                    <button type="submit" style="border-radius:20px;" class="btn btn-primary btn-lg btn-flat" id="btn" disabled><i class="fa fa-shopping-cart"></i> Añadir al carrito</button>
                                </div>
                                <?php } elseif ($product['stock'] == 0 && $product['stock'] < $product['quantity']) { ?>
                                    </div> 
                                    <button type="submit" style="border-radius:20px;" class="btn btn-primary btn-lg btn-flat" id="btn" disabled><i class="fa fa-shopping-cart"></i> Añadir al carrito</button>
                                </div>
                                <?php }else {?>
                                    </div>
                                    <button type="submit" style="border-radius:20px;" class="btn btn-primary btn-lg btn-flat" id="btn"><i class="fa fa-shopping-cart"></i> Añadir al carrito</button>
                                </div>  
                                <?php }?>
                            </form>
                        </div>
                        <div class="col-sm-6" style="border-radius:20px;">
                            <h1 class="page-header" style="border-radius:20px; font-size: 30px;"><?php echo $product['prodname']; ?></h1>
                            <h3 ><b>S/ <?php echo number_format($product['price'], 2); ?></b></h3>
                            <p><b>Categoría:</b> <a href="category.php?category=<?php echo $product['cat_slug']; ?>"><?php echo $product['catname']; ?></a></p>
                            <p><b>Descripción:</b></p>
                            <p><?php echo $product['description']; ?></p>
                            <p name="stock" id="stock"><b><?php echo number_format($product['stock']); ?></b></p>
                        
						</div>

                    </div>
                    <br>
                    <div class="fb-comments" style="border-radius:20px;" data-href="product.php?product=<?php echo $slug; ?>" data-numposts="10" width="100%"></div> 
                </div>
				
                <div class="col-sm-3" style="border-radius:20px;">
                    <?php include 'includes/sidebar.php'; ?>
                </div>
            </div>
          </section>
         
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php';?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Verificar y deshabilitar botones si el stock es cero
        checkStockAndDisableButtons();

        function checkStockAndDisableButtons() {
            var stock = parseInt($('#stock').text());
            var quantityInput = $('#quantity');
            var addButton = $('#add');
            var minusButton = $('#minus');
            var btn = $('#btn');
            var stockMessage = $('#stockMessage');

            if (stock <= 0) {
                quantityInput.prop('disabled', true);
                addButton.prop('disabled', true);
                minusButton.prop('disabled', true);
                btn.prop('disabled', true);
                showAlert('Stock no disponible', 'alert-danger');
            }
        }

        $('#add').click(function (e) {
            e.preventDefault();
            incrementQuantity();
            validateQuantityInput();
            updateButtonState();
        });

        $('#quantity').on('input', function () {
            validateQuantityInput();
            updateButtonState();
        });

        $('#minus').click(function (e) {
            e.preventDefault();
            decrementQuantity();
            validateQuantityInput();
            updateButtonState();
        });

        function incrementQuantity() {
            var quantity = parseInt($('#quantity').val());
            $('#quantity').val(quantity + 1);
        }

        function decrementQuantity() {
            var quantity = parseInt($('#quantity').val());
            if (quantity > 1) {
                $('#quantity').val(quantity - 1);
            } else {
                showAlert('La cantidad no puede ser menor a 1', 'alert-danger');
            }
        }

        function validateQuantityInput() {
            var quantity = parseInt($('#quantity').val());
            var stock = parseInt($('#stock').text());
            if (isNaN(quantity) || quantity < 1) {
                showAlert('La cantidad no puede ser menor a 1', 'alert-danger');
                $('#quantity').val(1);
            } else if (quantity > stock) {
                showAlert('La cantidad no está disponible', 'alert-danger');
                $('#quantity').val(stock);
            }
}

        function updateButtonState() {
            var quantity = parseInt($('#quantity').val());
            var stock = parseInt($('#stock').text());
            var btn = $('#btn');

            if (!isNaN(quantity) && quantity > 0 && quantity <= stock) {
                btn.prop('disabled', false);
            } else {
                btn.prop('disabled', true);
            }
        }

        function showAlert(message, alertClass) {
            $('#callout').removeClass('alert-success alert-danger').addClass('alert ' + alertClass);
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

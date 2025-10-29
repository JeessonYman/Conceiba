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
			<section class="content" style='border-radius:10px;'>
				<div class="row" style='border-radius:10px;'>
					<div class="col-sm-9" style='border-radius:10px;'>
						<h1 class="page-header" style='border-radius:10px;'>Su Carrito:</h1>
						<div class="box box-solid" style='border-radius:10px;'>
							<div class="box-body table-responsive" style='border-radius:10px; overflow-x: auto;'>
								<table class="table table-bordered" style='border-radius:10px;'>
									<thead>
										<th></th>
										<th>Foto</th>
										<th>Nombre</th>
										<th>Precio</th>
										<th width="20%">Cantidad</th>
										<th>Subtotal</th>
									</thead>
									<tbody id="tbody" style='border-radius:10px;'>
									</tbody>
								</table>
							</div>
						</div>
						<?php
							if(isset($_SESSION['user'])){
								echo "
									<div id='paypal-button' style='border-radius:20px;'></div>
									<div id='empty-cart-message' style='display:none;'>
										<h4>Tu carrito está vacío. <a href='index.php' style='border-radius:10px;'>Agrega productos</a> para continuar.</h4>
									</div>
								";
							}
							else{
								echo "
									<h4>Necesitas <a href='login.php' style='border-radius:10px;'>Iniciar sesión</a> para revisar.</h4>
								";
							}
						?>
					</div>
					<div class="col-sm-3" style='border-radius:10px;'>
						<?php include 'includes/sidebar.php'; ?>
					</div>
				</div>
			</section>
		 
		</div>
	</div>
	<?php $pdo->close(); ?>
	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
var total = 0;
$(function(){
	$(document).on('click', '.cart_delete', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		$.ajax({
			type: 'POST',
			url: 'cart_delete.php',
			data: {id:id},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	$(document).on('click', '.minus', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var qty = $('#qty_'+id).val();
		if(qty>1){
			qty--;
		}
		$('#qty_'+id).val(qty);
		$.ajax({
			type: 'POST',
			url: 'cart_update.php',
			data: {
				id: id,
				qty: qty,
			},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	$(document).on('click', '.add', function(e){
		e.preventDefault();
		var id = $(this).data('id');
		var qty = $('#qty_'+id).val();
		qty++;
		$('#qty_'+id).val(qty);
		$.ajax({
			type: 'POST',
			url: 'cart_update.php',
			data: {
				id: id,
				qty: qty,
			},
			dataType: 'json',
			success: function(response){
				if(!response.error){
					getDetails();
					getCart();
					getTotal();
				}
			}
		});
	});

	getDetails();
	getTotal();
	
	// Verificar carrito vacío al cargar la página (con más tiempo de espera)
	setTimeout(function(){
		checkCartEmpty();
	}, 1500);

});

function getDetails(){
	$.ajax({
		type: 'POST',
		url: 'cart_details.php',
		dataType: 'json',
		success: function(response){
			try {
				// Si la respuesta ya es un string, úsala directamente
				const htmlContent = typeof response === 'string' ? response : response;
				$('#tbody').html(htmlContent);
				getCart();
				setTimeout(function(){
					checkCartEmpty();
				}, 200);
			} catch(e) {
				console.error('Error al procesar la respuesta:', e);
				$('#tbody').html("<tr><td colspan='6' align='center' style='color:red;'>Error al cargar el carrito. Por favor recarga la página.</td></tr>");
			}
		},
		error: function(xhr, status, error) {
			console.error('Error en cart_details.php:', error);
			console.error('Status:', status);
			console.error('Response Text:', xhr.responseText);
			console.error('Status Code:', xhr.status);
			
			// Mostrar en el tbody que hay un error
			$('#tbody').html("<tr><td colspan='6' align='center' style='color:red;'>Error al cargar el carrito. Por favor recarga la página.</td></tr>");
		}
	});
}

function getTotal(){
	$.ajax({
		type: 'POST',
		url: 'cart_total.php',
		dataType: 'json',
		success:function(response){
			total = parseFloat(response) || 0;
			checkCartEmpty();
		}
	});
}

function checkCartEmpty(){
	// Esperar un momento para que el DOM se actualice completamente
	setTimeout(function(){
		var rowCount = $('#tbody tr').length;
		var hasContent = $('#tbody').html().trim().length > 0;
		
		// Buscar específicamente si hay filas de productos (no solo la fila del total)
		var productRows = $('#tbody tr:has(button.cart_delete)').length;
		
		// El carrito está vacío si: total es 0 o menor, O no hay filas de productos
		var cartIsEmpty = (total <= 0) || (productRows === 0);
		
		console.log('Verificando carrito - Total:', total, 'Filas totales:', rowCount, 'Filas de productos:', productRows, 'Vacío:', cartIsEmpty);
		
		if(cartIsEmpty){
			$('#paypal-button').css('display', 'none');
			$('#empty-cart-message').css('display', 'block');
		} else {
			$('#paypal-button').css('display', 'block');
			$('#empty-cart-message').css('display', 'none');
		}
	}, 300);
}
</script>

<!-- Paypal Express (API Original) -->
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
// Asegurarse de que PayPal solo se inicialice una vez
if(document.getElementById('paypal-button') && typeof paypal !== 'undefined' && !window.paypalButtonRendered){
	window.paypalButtonRendered = true;
	
	paypal.Button.render({
		env: 'sandbox', // change for production if app is live

		client: {
			sandbox: 'AbHeU2AwplDY61A2vLAd4rU9_SaKFqtRkoSNFvpvQ8ytqLddsdsn70GbN3tbBEKNRrBx9V1Z_Sl23lN5',
			//production: 'AboApoK8TMnZBhQjwWRC48ZH3HL3e_6iENY7NIPfVLIQwd40PpaKeNagHhy1_vagx-k4tVS4BRs1NPhg'
		},

		commit: true, // Show a 'Pay Now' button

		style: {
			shape: 'rect',
			color: 'gold',
			size: 'small',
			label: 'paypal'
		},

		payment: function(data, actions) {
			return actions.payment.create({
				payment: {
					transactions: [
						{
							// Total purchase
							amount: { 
								total: total, 
								currency: 'USD'
							}
						}
					]
				}
			});
		},

		onAuthorize: function(data, actions) {
			return actions.payment.execute().then(function(payment) {
				window.location = 'sales.php?pay=' + payment.id;
			});
		}

	}, '#paypal-button');
}
</script>

</body>
</html>
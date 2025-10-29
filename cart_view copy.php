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
		        		<table class="table table-bordered" style='border-radius:10px;' >
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

});

function getDetails(){
	$.ajax({
		type: 'POST',
		url: 'cart_details.php',
		dataType: 'json',
		success: function(response){
			$('#tbody').html(response);
			getCart();
		}
	});
}

function getTotal(){
	$.ajax({
		type: 'POST',
		url: 'cart_total.php',
		dataType: 'json',
		success:function(response){
			total = response;
		}
	});
}
</script>
<!-- Paypal Express -->
<script>
paypal.Button.render({
    env: 'sandbox', // change for production if app is live,

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
                    	//total purchase
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
			window.location = 'sales.php?pay='+payment.id;
		})
    },


}, '#paypal-button');
</script>
</body>
</html>
<div id="smart-button-container">
      <div style="text-align: center;">
        <div id="paypal-button-container"></div>
      </div>
    </div>
  <script src="https://sandbox.paypal.com/sdk/js?client-id=AaaA6ma8dSYjO6ap3tM7zAcDiqtRAq4Q7ivaoWu_2W5MtNypX2oJ9joiTz5mpfMSEUJ5fsKuWeqk_WeT&currency=USD" data-sdk-integration-source="button-factory"></script>
  <script>
    function initPayPalButton() {
      paypal.Buttons({
        style: {
          shape: 'rect',
          color: 'gold',
          layout: 'vertical',
          label: 'paypal',
          
        },

        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{"amount":{"currency_code":"USD","value":1}}]
          });
        },

        onApprove: function(data, actions) {
          return actions.order.capture().then(function(orderData) {
            
            // Full available details
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
			actions.redirect('profile.php');

            // Show a success message within this page, e.g.
            const element = document.getElementById('paypal-button-container');
            element.innerHTML = '';
            element.innerHTML = '<h3>¡Gracias por tu pago!</h3>';
            actions.redirect('profile.php');
            
          });
        },


        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container');
    }
    initPayPalButton();
  </script>
 
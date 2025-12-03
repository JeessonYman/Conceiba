<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
<script>
  $(function () {
    // DataTable inicialización con opciones modernas
    if ($.fn.DataTable.isDataTable('#example1')) {
      $('#example1').DataTable().destroy();
    }
    $('#example1').DataTable({
      "responsive": true,
      "autoWidth": false,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json"
      }
    });

    // CKEditor 5 inicialización con verificación
    const editorElement = document.querySelector('#editor1');
    if (editorElement) {
      ClassicEditor
        .create(editorElement)
        .catch(error => {
          console.error('Error al inicializar CKEditor:', error);
        });
    }
  });
</script>
<!--Magnify -->
<script src="magnify/magnify.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.zoom').magnify();
});
</script>
<!-- Custom Scripts -->
<script>
$(function(){
  $('#navbar-search-input').focus(function(){
    $('#searchBtn').show();
  });

  $('#navbar-search-input').focusout(function(){
    $('#searchBtn').hide();
  });

  getCart();

  $('#productForm').submit(function(e){
  	e.preventDefault();
  	var product = $(this).serialize();
  	$.ajax({
  		type: 'POST',
  		url: 'cart_add.php',
  		data: product,
  		dataType: 'json',
  		success: function(response){
  			$('#callout').show();
  			$('.message').html(response.message);
  			if(response.error){
  				$('#callout').removeClass('callout-success').addClass('callout-danger');
  			}
  			else{
				$('#callout').removeClass('callout-danger').addClass('callout-success');
				getCart();
  			}
  		}
  	});
  });

  $(document).on('click', '.close', function(){
  	$('#callout').hide();
  });

});

function getCart(){
	$.ajax({
		type: 'POST',
		url: 'cart_fetch.php',
		dataType: 'json',
		success: function(response){
			$('#cart_menu').html(response.list);
			$('.cart_count').html(response.count);
		}
	});
}
</script>

<!-- ========================================== -->
<!-- M.A.R.I.A - SISTEMA DE IA CON VOZ -->
<!-- ========================================== -->
<?php include 'includes/maria_interface.php'; ?>
    <script>
    // Configuración de DataTables con responsividad
    $('#example1').DataTable({
    responsive: true,
    language: {
    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
    },
    autoWidth: false,
    columnDefs: [{
    responsivePriority: 1,
    targets: 0
    },
    {
    responsivePriority: 2,
    targets: -1
    }
    ]
    });

    // Select2 con responsividad
    $('.select2').select2({
    width: '100%'
    });

    // DatePicker en español
    $('.datepicker').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'es'
    });

    // Magnific Popup para imágenes
    $('.image-popup').magnificPopup({
    type: 'image',
    closeOnContentClick: true,
    closeBtnInside: false,
    fixedContentPos: true,
    mainClass: 'mfp-no-margins mfp-with-zoom',
    image: {
    verticalFit: true
    },
    zoom: {
    enabled: true,
    duration: 300
    }
    });
    </script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo $path_prefix; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="<?php echo $path_prefix; ?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo $path_prefix; ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="<?php echo $path_prefix; ?>bower_components/select2/dist/js/select2.full.min.js"></script>
    <!-- Magnific Popup 
<script src="../plugins/magnific-popup/jquery.magnific-popup.min.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <!-- ChartJS 
<script src="../bower_components/chart.js/Chart.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <!-- daterangepicker -->
    <script src="<?php echo $path_prefix; ?>bower_components/moment/min/moment.min.js"></script>
    <script src="<?php echo $path_prefix; ?>bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="<?php echo $path_prefix; ?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <!-- bootstrap time picker -->
    <script src="<?php echo $path_prefix; ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <!-- Slimscroll -->
    <script src="<?php echo $path_prefix; ?>bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo $path_prefix; ?>bower_components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo $path_prefix; ?>dist/js/adminlte.min.js"></script>

    <!-- Custom Responsive Scripts -->
    <!--<script src="assets/js/responsive.js"></script> -->
    <!-- Nueva línea -->
    <script src="assets/js/responsive-fixed.js"></script>

    <!-- jQuery 3 -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- Magnific Popup 
<script src="../plugins/magnific-popup/jquery.magnific-popup.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<!-- ChartJS 
<script src="../bower_components/chart.js/Chart.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<!-- daterangepicker -->
<script src="../bower_components/moment/min/moment.min.js"></script>
<script src="../bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="../bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- Slimscroll -->
<script src="../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>

<!-- Custom Responsive Scripts -->
<!--<script src="assets/js/responsive.js"></script> -->
 <!-- Nueva línea -->
 <script src="assets/js/responsive-fixed.js"></script>

<script>
$(function(){
  // Sidebar active menu
  var url = window.location;
  
  // for sidebar menu but not for treeview submenu
  $('ul.sidebar-menu a').filter(function() {
     return this.href == url;
  }).parent().addClass('active');

  // for sidebar menu treeview
  $('ul.treeview-menu a').filter(function() {
     return this.href == url;
  }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');

});
</script>

<!-- DataTables Responsive Configuration -->
<script>
$(function(){
  // Configuración de DataTables con responsividad
  $('#example1').DataTable({
    responsive: true,
    language: {
      url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
    },
    autoWidth: false,
    columnDefs: [
      { responsivePriority: 1, targets: 0 },
      { responsivePriority: 2, targets: -1 }
    ]
  });

  // Select2 con responsividad
  $('.select2').select2({
    width: '100%'
  });

  // DatePicker en español
  $('.datepicker').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd',
    language: 'es'
  });

  // Magnific Popup para imágenes
  $('.image-popup').magnificPopup({
    type: 'image',
    closeOnContentClick: true,
    closeBtnInside: false,
    fixedContentPos: true,
    mainClass: 'mfp-no-margins mfp-with-zoom',
    image: {
      verticalFit: true
    },
    zoom: {
      enabled: true,
      duration: 300
    }
  });
});
</script>

<!-- Configuración responsiva adicional -->
<script>
$(document).ready(function() {
  
  // ===========================
  // CONFIGURACIÓN DE CHART.JS RESPONSIVO
  // ===========================
  if (typeof Chart !== 'undefined') {
    // Configuración global de Chart.js
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;
    Chart.defaults.global.legend.display = true;
    Chart.defaults.global.legend.position = 'bottom';
    
    // Ajustar tamaño de fuente según el dispositivo
    if ($(window).width() < 768) {
      Chart.defaults.global.defaultFontSize = 10;
      Chart.defaults.global.legend.labels.fontSize = 10;
      Chart.defaults.global.legend.labels.padding = 5;
    } else {
      Chart.defaults.global.defaultFontSize = 12;
    }
  }
  
  // ===========================
  // SIDEBAR RESPONSIVO
  // ===========================
  function handleSidebar() {
    var windowWidth = $(window).width();
    
    if (windowWidth < 768) {
      $('body').addClass('sidebar-collapse');
    } else {
      // Restaurar estado guardado
      var savedState = localStorage.getItem('sidebarState');
      if (savedState === 'collapsed') {
        $('body').addClass('sidebar-collapse');
      } else {
        $('body').removeClass('sidebar-collapse');
      }
    }
  }
  
  // Ejecutar al cargar
  handleSidebar();
  
  // Ejecutar al redimensionar
  var resizeTimeout;
  $(window).resize(function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(handleSidebar, 200);
  });
  
  // Cerrar sidebar al hacer clic en un enlace (solo móviles)
  $('.sidebar-menu a').on('click', function() {
    if ($(window).width() < 768) {
      setTimeout(function() {
        $('body').addClass('sidebar-collapse');
      }, 100);
    }
  });
  
  // ===========================
  // GUARDAR ESTADO DEL SIDEBAR
  // ===========================
  $('.sidebar-toggle').on('click', function() {
    setTimeout(function() {
      var sidebarState = $('body').hasClass('sidebar-collapse') ? 'collapsed' : 'expanded';
      localStorage.setItem('sidebarState', sidebarState);
    }, 300);
  });
  
  // ===========================
  // DETECTAR CAMBIO DE ORIENTACIÓN
  // ===========================
  $(window).on('orientationchange', function() {
    setTimeout(function() {
      if (typeof Chart !== 'undefined') {
        Chart.helpers.each(Chart.instances, function(instance) {
          instance.resize();
        });
      }
      handleSidebar();
    }, 300);
  });
  
  // ===========================
  // RESIZE RESPONSIVO DE GRÁFICOS
  // ===========================
  $(window).on('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function() {
      if (typeof Chart !== 'undefined') {
        Chart.helpers.each(Chart.instances, function(instance) {
          instance.resize();
        });
      }
    }, 250);
  });
  
  console.log('✓ Scripts del sistema cargados correctamente');
});
</script>

<!-- Estilos adicionales para los scripts -->
<style>
/* Toast notifications */
.toast {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #333;
  color: white;
  padding: 15px 20px;
  border-radius: 4px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.3);
  opacity: 0;
  transform: translateY(50px);
  transition: all 0.3s ease;
  z-index: 9999;
  max-width: 300px;
}

.toast.show {
  opacity: 1;
  transform: translateY(0);
}

.toast.toast-success {
  background: #00a65a;
}

.toast.toast-error {
  background: #dd4b39;
}

.toast.toast-warning {
  background: #f39c12;
}

.toast.toast-info {
  background: #00c0ef;
}

.toast i {
  margin-right: 10px;
}

/* Button active state */
.btn-active {
  transform: scale(0.95);
  transition: transform 0.1s;
}

/* Header scrolled */
.header-scrolled {
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Mobile sidebar */
@media (max-width: 767px) {
  .sidebar-mobile {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
  }
  
  body:not(.sidebar-collapse) .sidebar-mobile {
    transform: translateX(0);
  }
  
  .toast {
    right: 10px;
    bottom: 10px;
    max-width: calc(100% - 20px);
  }
}
</style>
<!-- Theme Switch Button -->
<button class="theme-switch" onclick="toggleTheme()" title="Cambiar tema">
    <i class="fa fa-adjust"></i>
</button>

<!-- Theme Switch Script -->
<script>
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}

// Aplicar el tema guardado al cargar
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
});
//Verificar que jQuery está cargado
if (typeof jQuery === 'undefined') {
    console.error('✗ CRÍTICO: jQuery no está cargado!');
    alert('Error: jQuery no está cargado. La página no funcionará correctamente.');
} else {
    console.log('✓ jQuery versión: ' + jQuery.fn.jquery);
}

// Verificar que DataTables está cargado
if (typeof $.fn.DataTable === 'undefined') {
    console.warn('⚠ ADVERTENCIA: DataTables no está cargado');
} else {
    console.log('✓ DataTables cargado correctamente');
}

// Verificar que Bootstrap está cargado
if (typeof $.fn.modal === 'undefined') {
    console.error('✗ CRÍTICO: Bootstrap no está cargado!');
} else {
    console.log('✓ Bootstrap cargado correctamente');
}
</script>

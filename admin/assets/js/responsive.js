/**
 * Script para mejorar la responsividad del dashboard
 */

$(document).ready(function() {
  
  // ===========================
  // NAVBAR STICKY AL HACER SCROLL
  // ===========================
  var header = $('.main-header');
  var headerOffset = header.offset().top;
  
  $(window).scroll(function() {
    if ($(window).scrollTop() > headerOffset) {
      header.addClass('navbar-fixed-top');
    } else {
      header.removeClass('navbar-fixed-top');
    }
  });

  // ===========================
  // AUTO-CERRAR SIDEBAR EN MÓVILES
  // ===========================
  if ($(window).width() < 768) {
    $('body').addClass('sidebar-collapse');
    
    // Cerrar sidebar al hacer clic en un enlace
    $('.sidebar-menu a').on('click', function() {
      if ($(window).width() < 768) {
        $('body').addClass('sidebar-collapse');
      }
    });
  }

  // ===========================
  // DETECTAR CAMBIO DE ORIENTACIÓN
  // ===========================
  var originalOrientation = window.orientation;
  
  $(window).on('orientationchange', function() {
    var newOrientation = window.orientation;
    
    // Recargar gráficos después del cambio de orientación
    setTimeout(function() {
      if (typeof Chart !== 'undefined') {
        Chart.helpers.each(Chart.instances, function(instance) {
          instance.resize();
        });
      }
    }, 300);
    
    // Ajustar sidebar
    if ($(window).width() < 768) {
      $('body').addClass('sidebar-collapse');
    }
  });

  // ===========================
  // RESIZE RESPONSIVO DE GRÁFICOS
  // ===========================
  var resizeTimer;
  $(window).on('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(function() {
      
      // Ajustar sidebar
      if ($(window).width() >= 768) {
        $('body').removeClass('sidebar-collapse');
      } else {
        $('body').addClass('sidebar-collapse');
      }
      
      // Redimensionar gráficos
      if (typeof Chart !== 'undefined') {
        Chart.helpers.each(Chart.instances, function(instance) {
          instance.resize();
        });
      }
      
      // Ajustar tablas responsivas
      adjustResponsiveTables();
      
    }, 250);
  });

  // ===========================
  // AJUSTAR TABLAS RESPONSIVAS
  // ===========================
  function adjustResponsiveTables() {
    $('.table-responsive').each(function() {
      var table = $(this).find('table');
      var containerWidth = $(this).width();
      var tableWidth = table.width();
      
      if (tableWidth > containerWidth) {
        $(this).css('overflow-x', 'auto');
      } else {
        $(this).css('overflow-x', 'visible');
      }
    });
  }

  // Ejecutar al cargar
  adjustResponsiveTables();

  // ===========================
  // MEJORAR DROPDOWN EN MÓVILES
  // ===========================
  if ($(window).width() < 768) {
    $('.dropdown-toggle').on('click', function(e) {
      e.preventDefault();
      $(this).parent().toggleClass('open');
    });
    
    // Cerrar dropdown al hacer clic fuera
    $(document).on('click', function(e) {
      if (!$(e.target).closest('.dropdown').length) {
        $('.dropdown').removeClass('open');
      }
    });
  }

  // ===========================
  // LAZY LOADING PARA GRÁFICOS
  // ===========================
  var chartsLoaded = false;
  
  function loadCharts() {
    if (!chartsLoaded && isElementInViewport($('.chart-responsive').first())) {
      chartsLoaded = true;
      // Los gráficos se cargarán automáticamente
    }
  }
  
  function isElementInViewport(el) {
    if (el.length === 0) return false;
    var rect = el[0].getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }
  
  $(window).on('scroll', loadCharts);
  loadCharts(); // Ejecutar al cargar

  // ===========================
  // MEJORAR TOOLTIPS EN MÓVILES
  // ===========================
  if ('ontouchstart' in window) {
    $('[data-toggle="tooltip"]').tooltip({
      trigger: 'click',
      container: 'body'
    });
  } else {
    $('[data-toggle="tooltip"]').tooltip();
  }

  // ===========================
  // PREVENIR ZOOM EN iOS EN INPUTS
  // ===========================
  if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {
    $('input, select, textarea').on('focus', function() {
      var fontSize = parseInt($(this).css('font-size'));
      if (fontSize < 16) {
        $(this).css('font-size', '16px');
      }
    });
  }

  // ===========================
  // OPTIMIZAR RENDIMIENTO DE SCROLL
  // ===========================
  var scrolling = false;
  
  $(window).on('scroll', function() {
    scrolling = true;
  });
  
  setInterval(function() {
    if (scrolling) {
      scrolling = false;
      // Ejecutar funciones de scroll optimizadas aquí
    }
  }, 100);

  // ===========================
  // DETECTAR CONEXIÓN LENTA
  // ===========================
  if ('connection' in navigator) {
    var connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    
    if (connection) {
      var type = connection.effectiveType;
      
      if (type === 'slow-2g' || type === '2g') {
        // Reducir calidad de gráficos en conexiones lentas
        console.log('Conexión lenta detectada, optimizando...');
        $('canvas').css('image-rendering', 'pixelated');
      }
    }
  }

  // ===========================
  // GUARDAR ESTADO DEL SIDEBAR
  // ===========================
  $('.sidebar-toggle').on('click', function() {
    setTimeout(function() {
      var sidebarState = $('body').hasClass('sidebar-collapse') ? 'collapsed' : 'expanded';
      localStorage.setItem('sidebarState', sidebarState);
    }, 300);
  });
  
  // Restaurar estado del sidebar
  var savedSidebarState = localStorage.getItem('sidebarState');
  if (savedSidebarState === 'collapsed' && $(window).width() >= 768) {
    $('body').addClass('sidebar-collapse');
  }

  // ===========================
  // MEJORAR PERFORMANCE DE ANIMACIONES
  // ===========================
  if ($(window).width() < 768) {
    // Reducir animaciones en móviles
    $.fn.modal.Constructor.TRANSITION_DURATION = 150;
    $.fn.collapse.Constructor.TRANSITION_DURATION = 150;
  }

  // ===========================
  // DETECTAR SI ES DISPOSITIVO TÁCTIL
  // ===========================
  function isTouchDevice() {
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
  }
  
  if (isTouchDevice()) {
    $('body').addClass('touch-device');
    
    // Mejorar experiencia táctil
    $('.small-box').on('touchstart', function() {
      $(this).addClass('touch-active');
    }).on('touchend', function() {
      $(this).removeClass('touch-active');
    });
  }

  // ===========================
  // ACTUALIZAR GRÁFICOS AL CAMBIAR TAB
  // ===========================
  $('a[data-toggle="tab"]').on('shown.bs.tab', function() {
    if (typeof Chart !== 'undefined') {
      Chart.helpers.each(Chart.instances, function(instance) {
        instance.update();
      });
    }
  });

  // ===========================
  // LOADER PARA GRÁFICOS
  // ===========================
  function showLoader(element) {
    $(element).prepend('<div class="chart-loader"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
  }
  
  function hideLoader(element) {
    $(element).find('.chart-loader').remove();
  }
  
  // Mostrar loader mientras cargan los gráficos
  $('.chart-responsive').each(function() {
    showLoader(this);
  });
  
  // Ocultar loader cuando los gráficos estén listos
  setTimeout(function() {
    $('.chart-responsive').each(function() {
      hideLoader(this);
    });
  }, 1000);

  // ===========================
  // MEJORAR ACCESIBILIDAD
  // ===========================
  // Permitir navegación con teclado
  $('.sidebar-menu a').on('keypress', function(e) {
    if (e.which === 13) { // Enter
      $(this)[0].click();
    }
  });
  
  // Agregar aria-labels
  $('.sidebar-toggle').attr('aria-label', 'Toggle navigation');
  $('.small-box-footer').attr('aria-label', 'Ver más información');

  // ===========================
  // REFRESH AUTOMÁTICO DE DATOS
  // ===========================
  // Descomentar si quieres auto-refresh cada 5 minutos
  /*
  setInterval(function() {
    if (!document.hidden) {
      location.reload();
    }
  }, 300000); // 5 minutos
  */

  // ===========================
  // NOTIFICACIONES PUSH (OPCIONAL)
  // ===========================
  if ('Notification' in window && Notification.permission === 'granted') {
    // Código para notificaciones push
  }

  // ===========================
  // SERVICE WORKER (OPCIONAL)
  // ===========================
  if ('serviceWorker' in navigator) {
    // Registrar service worker para PWA
    // navigator.serviceWorker.register('/sw.js');
  }

  // ===========================
  // ESTADÍSTICAS DE PERFORMANCE
  // ===========================
  if (window.performance) {
    var perfData = window.performance.timing;
    var pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
    console.log('Tiempo de carga: ' + pageLoadTime + 'ms');
  }

  // ===========================
  // MANEJO DE ERRORES GLOBAL
  // ===========================
  window.addEventListener('error', function(e) {
    console.error('Error detectado:', e.message);
    // Aquí puedes enviar el error a un servidor de logs
  });

  // ===========================
  // OPTIMIZACIÓN DE IMÁGENES
  // ===========================
  if ('IntersectionObserver' in window) {
    var imageObserver = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          var img = entry.target;
          img.src = img.dataset.src;
          imageObserver.unobserve(img);
        }
      });
    });
    
    $('img[data-src]').each(function() {
      imageObserver.observe(this);
    });
  }

  // ===========================
  // COMPATIBILIDAD CON CHART.JS 3.x
  // ===========================
  if (typeof Chart !== 'undefined' && Chart.version) {
    var chartVersion = parseInt(Chart.version.split('.')[0]);
    if (chartVersion >= 3) {
      // Configuración para Chart.js 3.x
      Chart.defaults.responsive = true;
      Chart.defaults.maintainAspectRatio = false;
    }
  }

  console.log('✓ Scripts responsivos cargados correctamente');
});

// ===========================
// FUNCIONES AUXILIARES GLOBALES
// ===========================

// Función para formatear números
function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Función para detectar modo oscuro
function isDarkMode() {
  return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
}

// Aplicar tema según preferencia
if (isDarkMode()) {
  $('body').addClass('dark-mode');
}

// Detectar cambios en preferencia de tema
if (window.matchMedia) {
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
    if (e.matches) {
      $('body').addClass('dark-mode');
    } else {
      $('body').removeClass('dark-mode');
    }
  });
}
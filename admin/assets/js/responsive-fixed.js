/* ===========================
   RESPONSIVE.JS - FIXED VERSION
   Manejo correcto del sidebar y header
   =========================== */

$(document).ready(function() {
    console.log('🚀 Iniciando scripts responsive...');
    
    // ===========================
    // 1. MANEJO DEL SIDEBAR EN MÓVIL
    // ===========================
    
    // Cerrar sidebar al hacer clic en el overlay (móvil)
    function addOverlayClickHandler() {
        if ($(window).width() < 768) {
            $('body').on('click.overlay', function(e) {
                if (!$(e.target).closest('.main-sidebar, .sidebar-toggle').length) {
                    if (!$('body').hasClass('sidebar-collapse')) {
                        $('.sidebar-toggle').trigger('click');
                    }
                }
            });
        } else {
            $('body').off('click.overlay');
        }
    }
    
    // ===========================
    // 2. SIDEBAR TOGGLE MEJORADO
    // ===========================
    $('.sidebar-toggle').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        $('body').toggleClass('sidebar-collapse');
        
        // Guardar estado solo en desktop
        if ($(window).width() >= 768) {
            const state = $('body').hasClass('sidebar-collapse') ? 'collapsed' : 'expanded';
            localStorage.setItem('sidebarState', state);
        }
    });
    
    // ===========================
    // 3. MENÚS DESPLEGABLES (TREEVIEW)
    // ===========================
    $('.sidebar-menu .treeview > a').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $parent = $(this).parent();
        const $menu = $parent.find('> .treeview-menu');
        
        // Cerrar otros menús abiertos
        $('.sidebar-menu .treeview').not($parent).removeClass('active');
        $('.sidebar-menu .treeview-menu').not($menu).slideUp(300);
        
        // Toggle del menú actual
        $parent.toggleClass('active');
        $menu.slideToggle(300);
        
        console.log('📂 Menú toggle:', $parent.hasClass('active') ? 'abierto' : 'cerrado');
    });
    
    // Prevenir que los submenús cierren al hacer clic en ellos
    $('.sidebar-menu .treeview-menu a').on('click', function(e) {
        e.stopPropagation();
        
        // En móvil, cerrar sidebar después de seleccionar
        if ($(window).width() < 768) {
            setTimeout(function() {
                $('body').addClass('sidebar-collapse');
            }, 300);
        }
    });
    
    // ===========================
    // 4. RESTAURAR ESTADO DEL SIDEBAR
    // ===========================
    function initSidebarState() {
        const width = $(window).width();
        
        if (width < 768) {
            // Móvil: siempre colapsado por defecto
            $('body').addClass('sidebar-collapse');
        } else {
            // Desktop: restaurar estado guardado
            const savedState = localStorage.getItem('sidebarState');
            if (savedState === 'collapsed') {
                $('body').addClass('sidebar-collapse');
            } else {
                $('body').removeClass('sidebar-collapse');
            }
        }
        
        console.log('📱 Ancho:', width, '| Sidebar:', 
            $('body').hasClass('sidebar-collapse') ? 'colapsado' : 'expandido');
    }
    
    // ===========================
    // 5. MANEJO DE RESIZE
    // ===========================
    let resizeTimeout;
    $(window).on('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            initSidebarState();
            addOverlayClickHandler();
            
            // Redimensionar gráficos si existen
            if (typeof Chart !== 'undefined') {
                Chart.helpers.each(Chart.instances, function(instance) {
                    instance.resize();
                });
            }
        }, 250);
    });
    
    // ===========================
    // 6. MANEJO DE ORIENTACIÓN
    // ===========================
    $(window).on('orientationchange', function() {
        setTimeout(function() {
            initSidebarState();
            addOverlayClickHandler();
            
            if (typeof Chart !== 'undefined') {
                Chart.helpers.each(Chart.instances, function(instance) {
                    instance.resize();
                });
            }
        }, 300);
    });
    
    // ===========================
    // 7. SCROLL HEADER (OPCIONAL)
    // ===========================
    let lastScroll = 0;
    $(window).on('scroll', function() {
        const currentScroll = $(this).scrollTop();
        
        if (currentScroll > 50) {
            $('.main-header').addClass('header-scrolled');
        } else {
            $('.main-header').removeClass('header-scrolled');
        }
        
        lastScroll = currentScroll;
    });
    
    // ===========================
    // 8. INICIALIZACIÓN
    // ===========================
    initSidebarState();
    addOverlayClickHandler();
    
    // Marcar que es un dispositivo táctil
    if ('ontouchstart' in window) {
        $('body').addClass('touch-device');
    }
    
    console.log('✅ Scripts responsive cargados correctamente');
});

// ===========================
// 9. FUNCIONES AUXILIARES
// ===========================

// Detectar si es iOS
function isIOS() {
    return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
}

// Prevenir zoom en iOS en inputs
if (isIOS()) {
    $('input, select, textarea').on('focus', function() {
        $(this).css('font-size', '16px');
    });
}

// Logging para debugging
function debugLog(message, data) {
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        console.log('🔧 DEBUG:', message, data || '');
    }
}
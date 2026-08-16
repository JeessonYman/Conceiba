    <!-- ============================================================ -->
    <!-- LIBRERÍAS (orden importa: jQuery primero, luego sus plugins)  -->
    <!-- ============================================================ -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5.3 Bundle (incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <!-- DataTables (build BS5) -->
    <script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js"></script>
    <!-- Select2 (build BS5) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>
    <!-- Magnific Popup -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-daterangepicker@3.1.0/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
    <!-- bootstrap time picker -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-timepicker@0.5.5/js/bootstrap-timepicker.min.js"></script>
    <!-- Slimscroll (compatibilidad con vistas antiguas) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-slimscroll@1.3.8/jquery.slimscroll.min.js"></script>
    <!-- jQuery UI (para widgets propios que aún lo usen) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.13.2/jquery-ui.min.js"></script>
    <!-- AdminLTE 3 App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script>
    // SHIM: Bootstrap 5 eliminó el plugin jQuery $(...).modal('show'/'hide'),
    // pero decenas de páginas del admin todavía lo usan así (de cuando el
    // proyecto era Bootstrap 3). En vez de reescribir cada archivo, revivimos
    // esa sintaxis apoyándonos en la API nativa real de Bootstrap 5.
    if (window.jQuery && window.bootstrap) {
        jQuery.fn.modal = function (action) {
            return this.each(function () {
                const instance = bootstrap.Modal.getOrCreateInstance(this);
                if (action === 'show') instance.show();
                else if (action === 'hide') instance.hide();
                else if (action === 'toggle') instance.toggle();
            });
        };
    }
    </script>
    <!-- Toast Alerts glass -->
    <script src="../js/toast-alerts.js"></script>
    <!-- Scripts propios de responsividad -->
    <script src="assets/js/responsive-fixed.js"></script>

    <script>
    // Resolver conflicto entre jQuery UI tooltip y Bootstrap tooltip
    if (window.jQuery && $.widget && $.ui) {
        $.widget.bridge('uibutton', $.ui.button);
    }
    </script>

    <!-- ============================================================ -->
    <!-- INICIALIZACIÓN (todo junto, una sola vez, tras cargar libs)   -->
    <!-- ============================================================ -->
    <script>
    $(function () {

        // --- DataTables con responsividad ---
        if ($.fn.DataTable && $('#example1').length) {
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
        }

        // --- Select2 ---
        if ($.fn.select2) {
            $('.select2').select2({ width: '100%' });
        }

        // --- DatePicker en español ---
        if ($.fn.datepicker) {
            $('.datepicker').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                language: 'es'
            });
        }

        // --- Magnific Popup para imágenes ---
        if ($.fn.magnificPopup) {
            $('.image-popup').magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                closeBtnInside: false,
                fixedContentPos: true,
                mainClass: 'mfp-no-margins mfp-with-zoom',
                image: { verticalFit: true },
                zoom: { enabled: true, duration: 300 }
            });
        }

        // --- Menú activo en el sidebar ---
        var url = window.location.href;
        $('ul.sidebar-menu a, .nav-sidebar a').filter(function () {
            return this.href === url;
        }).parent().addClass('active');

        $('ul.treeview-menu a, .nav-treeview a').filter(function () {
            return this.href === url;
        }).parentsUntil('.sidebar-menu, .nav-sidebar').addClass('active');

        // --- Chart.js: configuración global compatible con v4 ---
        if (typeof Chart !== 'undefined') {
            Chart.defaults.responsive = true;
            Chart.defaults.maintainAspectRatio = false;
            Chart.defaults.plugins.legend.display = true;
            Chart.defaults.plugins.legend.position = 'bottom';

            if ($(window).width() < 768) {
                Chart.defaults.font.size = 10;
            } else {
                Chart.defaults.font.size = 12;
            }
        }

        function resizeAllCharts() {
            if (typeof Chart === 'undefined' || !Chart.instances) return;
            Object.values(Chart.instances).forEach(function (instance) {
                instance.resize();
            });
        }

        // --- Sidebar responsivo ---
        function handleSidebar() {
            var windowWidth = $(window).width();
            if (windowWidth < 768) {
                $('body').addClass('sidebar-collapse');
            } else {
                var savedState = localStorage.getItem('sidebarState');
                if (savedState === 'collapsed') {
                    $('body').addClass('sidebar-collapse');
                } else {
                    $('body').removeClass('sidebar-collapse');
                }
            }
        }
        handleSidebar();

        var resizeTimeout;
        $(window).on('resize', function () {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function () {
                handleSidebar();
                resizeAllCharts();
            }, 200);
        });

        $(window).on('orientationchange', function () {
            setTimeout(function () {
                resizeAllCharts();
                handleSidebar();
            }, 300);
        });

        // Cerrar sidebar al hacer clic en un enlace (solo móviles)
        $('.sidebar-menu a, .nav-sidebar a').on('click', function () {
            if ($(window).width() < 768) {
                setTimeout(function () {
                    $('body').addClass('sidebar-collapse');
                }, 100);
            }
        });

        // Guardar estado del sidebar al usar el toggle
        $('.sidebar-toggle, [data-widget="pushmenu"]').on('click', function () {
            setTimeout(function () {
                var sidebarState = $('body').hasClass('sidebar-collapse') ? 'collapsed' : 'expanded';
                localStorage.setItem('sidebarState', sidebarState);
            }, 300);
        });

        console.log('✓ Scripts del sistema cargados correctamente');
    });
    </script>

    <!-- Estilos adicionales para los scripts -->
    <style>
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
    .toast.show { opacity: 1; transform: translateY(0); }
    .toast.toast-success { background: #00a65a; }
    .toast.toast-error   { background: #dd4b39; }
    .toast.toast-warning { background: #f39c12; }
    .toast.toast-info    { background: #00c0ef; }
    .toast i { margin-right: 10px; }

    .btn-active { transform: scale(0.95); transition: transform 0.1s; }
    .header-scrolled { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }

    @media (max-width: 767px) {
        .sidebar-mobile { transform: translateX(-100%); transition: transform 0.3s ease; }
        body:not(.sidebar-collapse) .sidebar-mobile { transform: translateX(0); }
        .toast { right: 10px; bottom: 10px; max-width: calc(100% - 20px); }
    }
    </style>

    <script>
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        const icon = document.getElementById('theme-icon');
        if (icon) {
            icon.className = newTheme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // No sobrescribir el tema que admin/includes/header.php ya detectó
        // (guardado o preferencia del sistema); solo reforzarlo si por algún
        // motivo el atributo se perdió.
        if (!document.documentElement.getAttribute('data-theme')) {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        }
        const icon = document.getElementById('theme-icon');
        if (icon) {
            const theme = document.documentElement.getAttribute('data-theme');
            icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        }
    });

    // Verificaciones de diagnóstico (solo consola, sin alert() que interrumpe al usuario)
    if (typeof jQuery === 'undefined') {
        console.error('✗ CRÍTICO: jQuery no está cargado!');
    } else {
        console.log('✓ jQuery versión: ' + jQuery.fn.jquery);
    }

    if (typeof $ !== 'undefined' && typeof $.fn.DataTable === 'undefined') {
        console.warn('⚠ ADVERTENCIA: DataTables no está cargado');
    } else if (typeof $ !== 'undefined') {
        console.log('✓ DataTables cargado correctamente');
    }

    if (typeof bootstrap === 'undefined') {
        console.error('✗ CRÍTICO: Bootstrap 5 no está cargado!');
    } else {
        console.log('✓ Bootstrap 5 cargado correctamente');
    }
    </script>

<link rel="stylesheet" href="wasathpp.css">
<footer class="main-footer">
    <div class="container">
        <div class="pull-right hidden-xs">
            <b>Todos los derechos reservados</b>
        </div>
        <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="https://www.facebook.com/conceiba.es" target="_blank">Conceiba</a></strong>
    </div>

    <!-- Cargar SDK de Facebook para JavaScript -->
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                xfbml: true,
                version: 'v14.0'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/es_LA/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <!-- Su código de complemento de chat -->
    <div class="fb-customerchat"
        attribution=setup_tool
        page_id="245283805339115"
        theme_color="#0084ff"
        greeting_dialog_display="fade"
        logged_in_greeting="Hola Mucho Gusto ¿Cuál es su consulta?"
        logged_out_greeting="Hola Mucho Gusto ¿Cuál es su consulta?" style="border-radius:20px;">
    </div>
</footer>

<!-- WhatsApp Floating Button -->
<a href="https://api.whatsapp.com/send?phone=+51945472993&text=Hola%21%20Quisiera%20m%C3%A1s%20informaci%C3%B3n%20sobre%20Los%20Productos." class="float" target="_blank" style="bottom: 85px;" >
    <i class="fab fa-whatsapp my-float"></i> 
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

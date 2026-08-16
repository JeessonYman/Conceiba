<footer class="main-footer">
    <div class="container-fluid px-4 d-flex flex-wrap justify-content-between align-items-center gap-1">
        <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="https://www.facebook.com/conceiba.es" target="_blank"><?php echo htmlspecialchars($settings['store_name'] ?? 'Conceiba'); ?></a></strong>
        <span>Todos los derechos reservados</span>
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

<?php
    // Mensaje de WhatsApp dinámico según la página en la que esté el cliente
    $waMessage = '¡Hola! Quisiera más información sobre los productos.';

    if(isset($cat['name'])){
        $waMessage = '¡Hola! Quisiera información sobre la categoría '.$cat['name'].'.';
    }
    elseif(isset($product['prodname'])){
        $waMessage = 'He visto el producto '.$product['prodname'].' (S/ '.number_format($product['price'], 2).'), necesito más información / ver si hay más modelos.';
    }
?>
<!-- WhatsApp Floating Button -->
<a href="https://api.whatsapp.com/send?phone=+51945472993&text=<?php echo rawurlencode($waMessage); ?>" class="float" target="_blank" style="bottom: 85px;" >
    <i class="fab fa-whatsapp my-float"></i> 
</a>

<!-- Bootstrap 5 y Font Awesome ya se cargan una sola vez en includes/header.php -->
<!-- (antes aquí se cargaba Bootstrap 5.3.2 duplicado junto al Bootstrap 3 del header, causando conflicto de versiones) -->

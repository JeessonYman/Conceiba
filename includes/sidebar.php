<div class="row">
    <div class="box box-solid" style="border-radius: 15px;">
        <div class="box-header with-border" style="border-radius: 15px 15px 0 0;">
            <h3 class="box-title"><b>Más vistos hoy</b></h3>
        </div>
        <div class="box-body">
            <ul id="trending">
            <?php
                $now = date('Y-m-d');
                $conn = $pdo->open();

                $stmt = $conn->prepare("SELECT * 
                                        FROM products 
                                        WHERE date_view=:now 
                                        ORDER BY counter DESC LIMIT 10");
                $stmt->execute(['now'=>$now]);
                $row_count = $stmt->rowCount();
                if($row_count > 0) {
                    foreach($stmt as $row){
                        echo "<li><a href='product.php?product=".$row['slug']."'>".$row['name']."</a></li>";
                    }
                } else {
                    echo "<h4 class='text-center'>No hay productos vistos hoy</h4>";
                    echo "<div class='text-center'><img src='./images/pandatriste.png' alt='Panda Triste' class='img-responsive' style='width: 60%; margin: 0 auto;'></div>";
                }

                $pdo->close();
            ?>
            </ul>
        </div>
    </div>
</div>

<div class="row">
    <div class="box box-solid" style="border-radius: 15px;">
        <div class="box-header with-border" style="border-radius: 15px 15px 0 0;">
            <h3 class="box-title"><b>Hazte suscriptor</b></h3>
        </div>
        <div class="box-body">
            <p>Obtenga actualizaciones gratuitas sobre los últimos productos y descuentos, directamente en su bandeja de entrada.</p>
            <form method="POST" action="subscribe.php" id="subscribeForm">
                <div class="input-group">
                    <input type="email" name="subscriber_email" class="form-control" placeholder="Tu correo electrónico" required>
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success" style="border-radius: 0 4px 4px 0;"><i class="far fa-envelope"></i></button>
                    </span>
                </div>
                <div id="subscribe-message" class="mt-2"></div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="box box-solid" style="border-radius: 15px;">
        <div class="box-header with-border" style="border-radius: 15px 15px 0 0;">
            <h3 class="box-title"><b>Síguenos en las redes sociales</b></h3>
        </div>
        <div class="box-body text-center">
            <div style="margin-bottom: 10px;">
                <a class="btn btn-social-icon" style="margin: 5px; background: #3b5998; border-radius: 50%; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;" href="https://www.facebook.com/conceiba.es" target="_blank"><i class="fab fa-facebook-f" style="color: white; font-size: 18px;"></i></a>
                <a class="btn btn-social-icon" style="margin: 5px; background: #e4405f; border-radius: 50%; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;" href="https://www.instagram.com/conceibaperu/" target="_blank"><i class="fab fa-instagram" style="color: white; font-size: 20px;"></i></a>
                <a class="btn btn-social-icon" style="margin: 5px; background: #25D366; border-radius: 50%; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;" href="https://api.whatsapp.com/send?phone=+51945472993&text=Hola%21%20Quisiera%20m%C3%A1s%20informaci%C3%B3n%20sobre%20Los%20Productos/" target="_blank"><i class="fab fa-whatsapp" style="color: white; font-size: 20px;"></i></a>
                <a class="btn btn-social-icon" style="margin: 5px; background: #0088cc; border-radius: 50%; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;" href="https://t.me/conceiba" target="_blank"><i class="fab fa-telegram-plane" style="color: white; font-size: 20px;"></i></a>
                <a class="btn btn-social-icon" style="margin: 5px; background: #FF0000; border-radius: 50%; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;" href="https://www.youtube.com/@conceiba" target="_blank"><i class="fab fa-youtube" style="color: white; font-size: 20px;"></i></a>
                <a class="btn btn-social-icon" style="margin: 5px; background: #000000; border-radius: 50%; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;" href="https://twitter.com/conceiba" target="_blank"><i class="fab fa-x-twitter" style="color: white; font-size: 18px;"></i></a>
                <a class="btn btn-social-icon" style="margin: 5px; background: #000000; border-radius: 50%; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;" href="https://www.tiktok.com/@conceiba" target="_blank"><i class="fab fa-tiktok" style="color: white; font-size: 20px;"></i></a>
                <a class="btn btn-social-icon" style="margin: 5px; background: #0A66C2; border-radius: 50%; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;" href="https://www.linkedin.com/company/conceiba" target="_blank"><i class="fab fa-linkedin-in" style="color: white; font-size: 18px;"></i></a>
                <a class="btn btn-social-icon" style="margin: 5px; background: #E60023; border-radius: 50%; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;" href="https://www.pinterest.com/conceiba" target="_blank"><i class="fab fa-pinterest-p" style="color: white; font-size: 20px;"></i></a>
            </div>
            <p>¡Síguenos para estar al tanto de nuestras últimas novedades y ofertas exclusivas!</p>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#subscribeForm').on('submit', function(e){
        e.preventDefault();
        var form = $(this);
        var email = form.find('input[name="subscriber_email"]').val();
        
        $.ajax({
            type: 'POST',
            url: 'subscribe.php',
            data: {subscriber_email: email},
            dataType: 'json',
            success: function(response){
                var messageDiv = $('#subscribe-message');
                if(response.success){
                    messageDiv.html('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                    form.find('input[name="subscriber_email"]').val('');
                } else {
                    messageDiv.html('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                }
                setTimeout(function(){
                    messageDiv.find('.alert').fadeOut('slow');
                }, 5000);
            },
            error: function(){
                $('#subscribe-message').html('<div class="alert alert-danger" role="alert">Error al procesar la solicitud. Por favor, intente nuevamente.</div>');
            }
        });
    });
});
</script>

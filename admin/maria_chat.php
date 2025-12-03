<?php
include 'includes/session.php';
$path_prefix = '../';
include 'includes/header.php';
?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>Conversación con M.A.R.Í.A</h1>
                <ol class="breadcrumb">
                    <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li>M.A.R.Í.A</li>
                    <li class="active">Chat</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary direct-chat direct-chat-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Conversación con M.A.R.Í.A</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" onclick="location.reload()">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="box-body">
                                <div class="direct-chat-messages" id="chat-messages" style="height: 400px;">
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-left">M.A.R.Í.A</span>
                                            <span class="direct-chat-timestamp pull-right"><?php echo date('H:i'); ?></span>
                                        </div>
                                        <img class="direct-chat-img" src="../images/maria-avatar.png" alt="María" onerror="this.src='../images/profile.jpg'">
                                        <div class="direct-chat-text">
                                            ¡Hola! Soy M.A.R.Í.A, tu asistente inteligente. ¿En qué puedo ayudarte hoy?
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <form id="chat-form">
                                    <div class="input-group">
                                        <input type="text" id="message-input" placeholder="Escribe tu mensaje..." class="form-control">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary btn-flat">Enviar</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-info">
                            <div class="box-header">
                                <h3 class="box-title">Sugerencias de Consultas</h3>
                            </div>
                            <div class="box-body">
                                <button class="btn btn-default btn-sm quick-question" data-question="¿Cuáles fueron las ventas de hoy?">
                                    <i class="fa fa-money"></i> Ventas de hoy
                                </button>
                                <button class="btn btn-default btn-sm quick-question" data-question="¿Qué productos tienen stock bajo?">
                                    <i class="fa fa-warning"></i> Stock bajo
                                </button>
                                <button class="btn btn-default btn-sm quick-question" data-question="Genera un reporte de ventas mensuales">
                                    <i class="fa fa-file-text"></i> Reporte mensual
                                </button>
                                <button class="btn btn-default btn-sm quick-question" data-question="¿Cuál es el producto más vendido?">
                                    <i class="fa fa-line-chart"></i> Producto top
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>
    <?php include 'includes/scripts.php'; ?>
    <script>
        $(function() {
            $('.quick-question').click(function() {
                var question = $(this).data('question');
                $('#message-input').val(question);
                $('#chat-form').submit();
            });

            $('#chat-form').submit(function(e) {
                e.preventDefault();
                var message = $('#message-input').val().trim();
                if (message === '') return;

                // Agregar mensaje del usuario
                var userMsg = '<div class="direct-chat-msg right">' +
                    '<div class="direct-chat-info clearfix">' +
                    '<span class="direct-chat-name pull-right">Tú</span>' +
                    '<span class="direct-chat-timestamp pull-left">' + new Date().toLocaleTimeString('es-ES', {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + '</span>' +
                    '</div>' +
                    '<img class="direct-chat-img" src="<?php echo (!empty($admin['photo'])) ? '../images/' . $admin['photo'] : '../images/profile.jpg'; ?>" alt="User">' +
                    '<div class="direct-chat-text">' + message + '</div>' +
                    '</div>';

                $('#chat-messages').append(userMsg);
                $('#message-input').val('');
                $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);

                // Call API
                fetch('maria_chat_api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        var reply = data.reply || 'Lo siento, hubo un error.';
                        // Fix for nested quotes in onerror
                        var profileImg = '../images/profile.jpg';
                        var mariaMsg = '<div class="direct-chat-msg">' +
                            '<div class="direct-chat-info clearfix">' +
                            '<span class="direct-chat-name pull-left">M.A.R.Í.A</span>' +
                            '<span class="direct-chat-timestamp pull-right">' + new Date().toLocaleTimeString('es-ES', {
                                hour: '2-digit',
                                minute: '2-digit'
                            }) + '</span>' +
                            '</div>' +
                            '<img class="direct-chat-img" src="../images/maria-avatar.png" alt="María" onerror="this.src=\'' + profileImg + '\'">' +
                            '<div class="direct-chat-text">' + reply + '</div>' +
                            '</div>';
                        $('#chat-messages').append(mariaMsg);
                        $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        var profileImg = '../images/profile.jpg';
                        var errorMsg = '<div class="direct-chat-msg">' +
                            '<div class="direct-chat-info clearfix">' +
                            '<span class="direct-chat-name pull-left">M.A.R.Í.A</span>' +
                            '<span class="direct-chat-timestamp pull-right">' + new Date().toLocaleTimeString('es-ES', {
                                hour: '2-digit',
                                minute: '2-digit'
                            }) + '</span>' +
                            '</div>' +
                            '<img class="direct-chat-img" src="../images/maria-avatar.png" alt="María" onerror="this.src=\'' + profileImg + '\'">' +
                            '<div class="direct-chat-text">Error de conexión.</div>' +
                            '</div>';
                        $('#chat-messages').append(errorMsg);
                        $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
                    });
            });
        });
    </script>
</body>

</html>
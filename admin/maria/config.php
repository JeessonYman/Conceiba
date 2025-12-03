<?php
include '../includes/session.php';
$path_prefix = '../../';
include '../includes/header.php';

// Ruta al archivo de configuración
$configFile = '../../maria_config.php';

function getMariaConfig($file)
{
    if (!file_exists($file)) {
        return [
            'MARIA_ENABLED' => false,
            'OPENROUTER_API_KEY' => '',
            'OPENROUTER_MODEL' => '',
            'MARIA_MAX_MESSAGE_LENGTH' => 500,
            'MARIA_RATE_LIMIT' => 20
        ];
    }
    $content = file_get_contents($file);
    $config = [];

    // Extraer constantes
    preg_match("/define\('MARIA_ENABLED',\s*(true|false)\);/", $content, $matches);
    $config['MARIA_ENABLED'] = isset($matches[1]) && $matches[1] === 'true';

    preg_match("/define\('OPENROUTER_API_KEY',\s*'([^']*)'\);/", $content, $matches);
    $config['OPENROUTER_API_KEY'] = $matches[1] ?? '';

    preg_match("/define\('OPENROUTER_MODEL',\s*'([^']*)'\);/", $content, $matches);
    $config['OPENROUTER_MODEL'] = $matches[1] ?? '';

    preg_match("/define\('MARIA_MAX_MESSAGE_LENGTH',\s*(\d+)\);/", $content, $matches);
    $config['MARIA_MAX_MESSAGE_LENGTH'] = $matches[1] ?? 500;

    preg_match("/define\('MARIA_RATE_LIMIT',\s*(\d+)\);/", $content, $matches);
    $config['MARIA_RATE_LIMIT'] = $matches[1] ?? 20;

    return $config;
}

// Procesar formulario
$msg = '';
if (isset($_POST['save_config'])) {
    if (file_exists($configFile)) {
        $content = file_get_contents($configFile);
    } else {
        $content = "<?php\ndefine('MARIA_ENABLED', false);\ndefine('OPENROUTER_API_KEY', '');\ndefine('OPENROUTER_MODEL', '');\ndefine('MARIA_MAX_MESSAGE_LENGTH', 500);\ndefine('MARIA_RATE_LIMIT', 20);\n";
    }

    // Actualizar MARIA_ENABLED
    $enabled = isset($_POST['MARIA_ENABLED']) ? 'true' : 'false';
    $content = preg_replace(
        "/define\('MARIA_ENABLED',\s*(true|false)\);/",
        "define('MARIA_ENABLED', $enabled);",
        $content
    );

    // Actualizar API KEY
    $apiKey = $_POST['OPENROUTER_API_KEY'];
    $content = preg_replace(
        "/define\('OPENROUTER_API_KEY',\s*'[^']*'\);/",
        "define('OPENROUTER_API_KEY', '$apiKey');",
        $content
    );

    // Actualizar MODEL
    $model = $_POST['OPENROUTER_MODEL'];
    $content = preg_replace(
        "/define\('OPENROUTER_MODEL',\s*'[^']*'\);/",
        "define('OPENROUTER_MODEL', '$model');",
        $content
    );

    // Actualizar Límites
    $maxLength = intval($_POST['MARIA_MAX_MESSAGE_LENGTH']);
    $content = preg_replace(
        "/define\('MARIA_MAX_MESSAGE_LENGTH',\s*\d+\);/",
        "define('MARIA_MAX_MESSAGE_LENGTH', $maxLength);",
        $content
    );

    $rateLimit = intval($_POST['MARIA_RATE_LIMIT']);
    $content = preg_replace(
        "/define\('MARIA_RATE_LIMIT',\s*\d+\);/",
        "define('MARIA_RATE_LIMIT', $rateLimit);",
        $content
    );

    if (file_put_contents($configFile, $content)) {
        $msg = '<div class="alert alert-success">Configuración guardada correctamente.</div>';
    } else {
        $msg = '<div class="alert alert-danger">Error al guardar la configuración. Asegúrate de que el archivo maria_config.php sea escribible.</div>';
    }
}

// Cargar configuración actual
$currentConfig = getMariaConfig($configFile);
?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include '../includes/navbar.php'; ?>
        <?php include '../includes/menubar.php'; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Configuración de M.A.R.I.A
                </h1>
                <ol class="breadcrumb">
                    <li><a href="../home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li>M.A.R.I.A</li>
                    <li class="active">Configuración</li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php echo $msg; ?>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Ajustes Generales</h3>
                            </div>
                            <form method="POST" action="">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="MARIA_ENABLED" <?php echo $currentConfig['MARIA_ENABLED'] ? 'checked' : ''; ?>>
                                            Habilitar Asistente M.A.R.I.A
                                        </label>
                                        <p class="help-block">Si se desactiva, el chat no estará disponible para los clientes ni administradores.</p>
                                    </div>

                                    <div class="form-group">
                                        <label>API Key (OpenRouter)</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="OPENROUTER_API_KEY" value="<?php echo $currentConfig['OPENROUTER_API_KEY']; ?>" required>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default reveal-password" type="button"><i class="fa fa-eye"></i></button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Modelo de IA</label>
                                        <select class="form-control" name="OPENROUTER_MODEL">
                                            <option value="gemma3:1b" <?php echo $currentConfig['OPENROUTER_MODEL'] == 'gemma3:1b' ? 'selected' : ''; ?>>Gemma 3 (1B) - Rápido</option>
                                            <option value="deepseek/deepseek-r1:free" <?php echo $currentConfig['OPENROUTER_MODEL'] == 'deepseek/deepseek-r1:free' ? 'selected' : ''; ?>>DeepSeek R1 (Free)</option>
                                            <option value="google/gemini-2.0-flash-lite-preview-02-05:free" <?php echo $currentConfig['OPENROUTER_MODEL'] == 'google/gemini-2.0-flash-lite-preview-02-05:free' ? 'selected' : ''; ?>>Gemini 2.0 Flash Lite (Free)</option>
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Longitud Máxima de Mensaje (caracteres)</label>
                                                <input type="number" class="form-control" name="MARIA_MAX_MESSAGE_LENGTH" value="<?php echo $currentConfig['MARIA_MAX_MESSAGE_LENGTH']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Límite de Mensajes por Hora</label>
                                                <input type="number" class="form-control" name="MARIA_RATE_LIMIT" value="<?php echo $currentConfig['MARIA_RATE_LIMIT']; ?>">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="box-footer">
                                    <button type="submit" name="save_config" class="btn btn-primary"><i class="fa fa-save"></i> Guardar Cambios</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include '../includes/footer.php'; ?>
    </div>
    <?php include '../includes/scripts.php'; ?>
    <script>
        $(function() {
            $('.reveal-password').click(function() {
                var input = $(this).closest('.input-group').find('input');
                if (input.attr('type') == 'password') {
                    input.attr('type', 'text');
                    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
</body>

</html>
<?php
include '../includes/session.php';
$path_prefix = '../../';
include '../includes/header.php';
?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include '../includes/navbar.php'; ?>
        <?php include '../includes/menubar.php'; ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1><i class="fa fa-sliders"></i> Ajustes Avanzados M.A.R.Í.A</h1>
                <ol class="breadcrumb">
                    <li><a href="../home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li>M.A.R.Í.A</li>
                    <li class="active">Ajustes Avanzados</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-bs-toggle="tab">Modelo IA</a></li>
                                <li><a href="#tab_2" data-bs-toggle="tab">Contexto</a></li>
                                <li><a href="#tab_3" data-bs-toggle="tab">Integraciones</a></li>
                                <li><a href="#tab_4" data-bs-toggle="tab">Límites</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <h4>Configuración del Modelo de IA</h4>
                                    <form>
                                        <div class="form-group">
                                            <label>Modelo a Utilizar</label>
                                            <select class="form-control">
                                                <option value="gpt-3.5">GPT-3.5 Turbo (Rápido)</option>
                                                <option value="gpt-4" selected>GPT-4 (Recomendado)</option>
                                                <option value="claude">Claude 3</option>
                                                <option value="local">Modelo Local</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Temperatura (Creatividad)</label>
                                            <input type="range" class="form-control" min="0" max="1" step="0.1" value="0.7">
                                            <small class="text-muted">0 = Preciso, 1 = Creativo</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Tokens Máximos</label>
                                            <input type="number" class="form-control" value="2000">
                                        </div>

                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </form>
                                </div>

                                <div class="tab-pane" id="tab_2">
                                    <h4>Contexto Personalizado</h4>
                                    <form>
                                        <div class="form-group">
                                            <label>Instrucciones del Sistema</label>
                                            <textarea class="form-control" rows="5">Eres M.A.R.Í.A, un asistente de IA para el sistema Conceiba. Ayudas con análisis de ventas, inventario y reportes.</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Datos a Incluir</label>
                                            <div class="checkbox">
                                                <label><input type="checkbox" checked> Datos de ventas recientes</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input type="checkbox" checked> Inventario actual</label>
                                            </div>
                                            <div class="checkbox">
                                                <label><input type="checkbox"> Historial del usuario</label>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Guardar Contexto</button>
                                    </form>
                                </div>

                                <div class="tab-pane" id="tab_3">
                                    <h4>Integraciones con Módulos</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                            <th>Módulo</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Ventas</td>
                                                <td><span class="label label-success">Activo</span></td>
                                                <td><button class="btn btn-xs btn-warning">Configurar</button></td>
                                            </tr>
                                            <tr>
                                                <td>Inventario</td>
                                                <td><span class="label label-success">Activo</span></td>
                                                <td><button class="btn btn-xs btn-warning">Configurar</button></td>
                                            </tr>
                                            <tr>
                                                <td>Clientes</td>
                                                <td><span class="label label-default">Inactivo</span></td>
                                                <td><button class="btn btn-xs btn-primary">Activar</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="tab-pane" id="tab_4">
                                    <h4>Límites y Cuotas</h4>
                                    <form>
                                        <div class="form-group">
                                            <label>Consultas por Usuario (por día)</label>
                                            <input type="number" class="form-control" value="100">
                                        </div>

                                        <div class="form-group">
                                            <label>Tiempo Máximo de Respuesta (segundos)</label>
                                            <input type="number" class="form-control" value="30">
                                        </div>

                                        <div class="form-group">
                                            <label>Costo Estimado Mensual</label>
                                            <p class="form-control-static">S/ 120.00</p>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Guardar Límites</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include '../includes/footer.php'; ?>
    </div>
    <?php include '../includes/scripts.php'; ?>
</body>

</html>

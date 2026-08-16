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
                <h1><i class="fa fa-bar-chart"></i> Reportes IA</h1>
                <ol class="breadcrumb">
                    <li><a href="../home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li>M.A.R.Í.A</li>
                    <li class="active">Reportes IA</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Análisis Predictivo de Ventas</h3>
                            </div>
                            <div class="box-body">
                                <p><i class="fa fa-line-chart text-primary"></i> <strong>Predicción para próximos 30 días:</strong></p>
                                <p class="text-muted">Basado en el comportamiento histórico y tendencias actuales.</p>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success" style="width: 75%">75%</div>
                                </div>
                                <p><strong>Ventas Estimadas:</strong> S/ 15,450.00</p>
                                <button class="btn btn-primary btn-sm">Generar Reporte Completo</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title">Detección de Anomalías</h3>
                            </div>
                            <div class="box-body">
                                <p><i class="fa fa-exclamation-triangle text-warning"></i> <strong>Alertas Detectadas:</strong></p>
                                <ul>
                                    <li>Caída inusual en ventas de "Cojines" (-15% vs. mes anterior)</li>
                                    <li>Incremento de stock de "Hilado" sin ventas correspondientes</li>
                                </ul>
                                <button class="btn btn-warning btn-sm">Ver Detalles</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title">Recomendaciones de Stock</h3>
                            </div>
                            <div class="box-body">
                                <table class="table table-sm">
                                    <thead>
                                        <th>Producto</th>
                                        <th>Acción</th>
                                        <th>Cantidad Sugerida</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Sombrero de Kapok</td>
                                            <td><span class="label label-success">Reabastecer</span></td>
                                            <td>50 unidades</td>
                                        </tr>
                                        <tr>
                                            <td>Peluche Coneja</td>
                                            <td><span class="label label-warning">Reducir</span></td>
                                            <td>-20 unidades</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-success btn-sm">Aplicar Sugerencias</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Insights de Clientes</h3>
                            </div>
                            <div class="box-body">
                                <p><i class="fa fa-users text-info"></i> <strong>Segmentación Inteligente:</strong></p>
                                <ul>
                                    <li><strong>Clientes Frecuentes:</strong> 45% compran semanalmente</li>
                                    <li><strong>Ticket Promedio:</strong> S/ 125.00</li>
                                    <li><strong>Producto Favorito:</strong> Cojines bordados</li>
                                </ul>
                                <button class="btn btn-info btn-sm">Ver Análisis Completo</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Historial de Reportes Generados</h3>
                            </div>
                            <div class="box-body">
                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <th>Tipo Reporte</th>
                                        <th>Fecha Generación</th>
                                        <th>Generado Por</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center">No hay reportes generados aún</td>
                                        </tr>
                                    </tbody>
                                </table>
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
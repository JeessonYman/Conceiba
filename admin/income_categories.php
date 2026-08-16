<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Ingresos por Categorías</h1>
                <ol class="breadcrumb">
                    <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li class="active">Ingresos por Categorías</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <div class="float-end">
                                    <form method="POST" class="form-inline">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input type="text" class="form-control" id="reservation" name="date_range">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm btn-flat" name="filter"><span class="bi bi-filter"></span> Filtrar</button>
                                        <button type="button" class="btn btn-success btn-sm btn-flat" onclick="window.print()"><span class="bi bi-print"></span> Imprimir</button>
                                    </form>
                                </div>
                            </div>
                            <div class="box-body">
                                <?php
                                $conn = $pdo->open();
                                try {
                                    $stmt = $conn->prepare("SELECT c.name AS category_name, c.id AS category_id,
                                         COUNT(di.id) AS total_items,
                                         SUM(di.quantity) AS total_quantity,
                                         SUM(di.price * di.quantity) AS total_amount
                                         FROM category c
                                         LEFT JOIN products pr ON pr.category_id = c.id
                                         LEFT JOIN details_inputs di ON di.id_products = pr.id
                                         LEFT JOIN inputs i ON i.id = di.id_inputs
                                         GROUP BY c.id, c.name
                                         ORDER BY total_amount DESC");
                                    $stmt->execute();
                                ?>
                                    <table id="example1" class="table table-bordered">
                                        <thead>
<tr>
                                            <th>Categoría</th>
                                            <th>Total Items</th>
                                            <th>Total Cantidad</th>
                                            <th>Monto Total</th>
                                            <th>Acciones</th>
                                        </tr>
</thead>
                                        <tbody>
                                            <?php
                                            $grand_total = 0;
                                            foreach ($stmt as $row) {
                                                $grand_total += $row['total_amount'];
                                                echo "
                        <tr>
                          <td data-label='Categoría'>" . $row['category_name'] . "</td>
                          <td data-label='Total Items'>" . $row['total_items'] . "</td>
                          <td data-label='Total Cantidad'>" . $row['total_quantity'] . "</td>
                          <td data-label='Monto Total'>S/ " . number_format($row['total_amount'], 2) . "</td>
                          <td data-label='Acciones'>
                            <button type='button' class='btn btn-info btn-sm btn-flat details' data-id='" . $row['category_id'] . "'>
                              <i class='fa fa-eye'></i> Ver Detalle
                            </button>
                          </td>
                        </tr>
                      ";
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right">Total General:</th>
                                                <th>S/ <?php echo number_format($grand_total, 2); ?></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                <?php
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }
                                $pdo->close();
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- Detalle de categoría -->
    <div class="modal fade" id="category_detail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title"><b>Detalle de ingresos: </b><span id="category_detail_name"></span></h4>
                </div>
                <div class="modal-body">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Fecha</th>
                          <th>Producto</th>
                          <th>Cantidad</th>
                          <th>Precio</th>
                          <th>Subtotal</th>
                        </tr>
                      </thead>
                      <tbody id="category_detail_body">
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default btn-flat" data-bs-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>
    <script>
        $(function() {
            $('#reservation').daterangepicker();

            $(document).on('click', '.details', function () {
                var id = $(this).data('id');
                var categoryName = $(this).closest('tr').find('td').first().text();
                $('#category_detail_name').text(categoryName);
                $('#category_detail_body').html('<tr><td colspan="5" class="text-center">Cargando...</td></tr>');
                $('#category_detail').modal('show');

                $.ajax({
                    type: 'POST',
                    url: 'income_category_detail.php',
                    data: { category_id: id },
                    dataType: 'json',
                    success: function (response) {
                        if (!response.success || !response.rows || response.rows.length === 0) {
                            $('#category_detail_body').html('<tr><td colspan="5" class="text-center">Sin ingresos registrados para esta categoría.</td></tr>');
                            return;
                        }
                        var html = '';
                        response.rows.forEach(function (row) {
                            html += '<tr>' +
                                '<td>' + row.input_date + '</td>' +
                                '<td>' + row.product_name + '</td>' +
                                '<td>' + row.quantity + '</td>' +
                                '<td>S/ ' + parseFloat(row.price).toFixed(2) + '</td>' +
                                '<td>S/ ' + parseFloat(row.subtotal).toFixed(2) + '</td>' +
                                '</tr>';
                        });
                        $('#category_detail_body').html(html);
                    },
                    error: function () {
                        $('#category_detail_body').html('<tr><td colspan="5" class="text-center">Error al cargar el detalle.</td></tr>');
                    }
                });
            });
        });
    </script>
</body>

</html>
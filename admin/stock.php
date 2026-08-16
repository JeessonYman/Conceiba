<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <?php include 'includes/menubar.php'; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Control de Stock</h1>
                <ol class="breadcrumb">
                    <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                    <li class="active">Control de Stock</li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Productos y Niveles de Stock</h3>
                                <div class="float-end">
                                    <button type="button" class="btn btn-warning btn-sm btn-flat" id="show-low-stock">
                                        <i class="fa fa-exclamation-triangle"></i> Solo Stock Bajo
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                <?php
                                $conn = $pdo->open();
                                try {
                                    $stmt = $conn->prepare("SELECT p.*, c.name AS category_name 
                                         FROM products p 
                                         LEFT JOIN category c ON c.id = p.category_id 
                                         ORDER BY p.stock ASC");
                                    $stmt->execute();
                                ?>
                                    <table id="stock-table" class="table table-bordered">
                                        <thead>
<tr>
                                            <th>Producto</th>
                                            <th>Categoría</th>
                                            <th>Stock Actual</th>
                                            <th>Stock Mínimo</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
</thead>
                                        <tbody>
                                            <?php
                                            foreach ($stmt as $row) {
                                                $stock_percentage = ($row['stock_minimum'] > 0) ? ($row['stock'] / $row['stock_minimum']) * 100 : 100;
                                                $status_class = '';
                                                $status_text = '';

                                                if ($row['stock'] <= 0) {
                                                    $status_class = 'danger';
                                                    $status_text = 'Sin Stock';
                                                } elseif ($row['stock'] < $row['stock_minimum']) {
                                                    $status_class = 'warning';
                                                    $status_text = 'Stock Bajo';
                                                } else {
                                                    $status_class = 'success';
                                                    $status_text = 'Stock Normal';
                                                }

                                                echo "
                        <tr class='stock-row' data-status='$status_class'>
                          <td data-label='Producto'>" . $row['name'] . "</td>
                          <td data-label='Categoría'>" . $row['category_name'] . "</td>
                          <td data-label='Stock Actual'>
                            <span class='badge bg-" . $status_class . "'>" . $row['stock'] . "</span>
                          </td>
                          <td data-label='Stock Mínimo'>" . $row['stock_minimum'] . "</td>
                          <td data-label='Estado'>
                            <span class='label label-" . $status_class . "'>" . $status_text . "</span>
                          </td>
                          <td data-label='Acciones'>
                            <button type='button' class='btn btn-primary btn-sm btn-flat adjust-stock' data-id='" . $row['id'] . "'>
                              <i class='fa fa-edit'></i> Ajustar
                            </button>
                          </td>
                        </tr>
                      ";
                                            }
                                            ?>
                                        </tbody>
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

    <!-- Ajustar stock -->
    <div class="modal fade" id="adjust_stock">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title"><b>Ajustar stock: </b><span id="adjust_stock_name"></span></h4>
                </div>
                <div class="modal-body">
                  <form method="POST" action="stock_adjust.php">
                    <input type="hidden" name="id" id="adjust_stock_id">
                    <div class="mb-3">
                      <label class="form-label">Stock actual</label>
                      <input type="text" class="form-control" id="adjust_stock_current" disabled>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Nuevo stock</label>
                      <input type="number" class="form-control" name="new_stock" id="adjust_stock_new" min="0" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Motivo del ajuste (opcional)</label>
                      <textarea class="form-control" name="reason" rows="2" placeholder="Ej. corrección de inventario, producto dañado, conteo físico..."></textarea>
                    </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat float-start" data-bs-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-flat" name="adjust"><i class="fa fa-save"></i> Guardar ajuste</button>
                  </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/scripts.php'; ?>
    <script>
        $(function() {
            $(document).on('click', '.adjust-stock', function () {
                var id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: 'products_row.php',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {
                        $('#adjust_stock_id').val(response.id);
                        $('#adjust_stock_name').text(response.name);
                        $('#adjust_stock_current').val(response.stock);
                        $('#adjust_stock_new').val(response.stock);
                        $('#adjust_stock').modal('show');
                    }
                });
            });

            $('#show-low-stock').click(function() {
                var rows = $('.stock-row');
                if ($(this).hasClass('active')) {
                    rows.show();
                    $(this).removeClass('active');
                    $(this).html('<i class="fa fa-exclamation-triangle"></i> Solo Stock Bajo');
                } else {
                    rows.each(function() {
                        if ($(this).data('status') === 'danger' || $(this).data('status') === 'warning') {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                    $(this).addClass('active');
                    $(this).html('<i class="fa fa-list"></i> Mostrar Todos');
                }
            });
        });
    </script>
</body>

</html>
<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-green sidebar-mini">
  <div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>
    <!-- Contenedor de contenido. Contiene contenido de la pÃ¡gina -->
    <div class="content-wrapper">
      <!-- Encabezado de contenido (encabezado de pÃ¡gina) -->
      <section class="content-header">
        <h1>
          Historial de Ingresos
        </h1>
        <ol class="breadcrumb">
          <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
          <li class="active">Ingresos</li>
        </ol>
      </section>

      <!-- Contenido principal -->
      <section class="content">
        <div class="row">
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header with-border">
                <div class="pull-right">
                  <form method="POST" class="form-inline" action="sales_print.php">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right col-sm-8" id="reservation" name="date_range">
                    </div>
                    <button type="submit" class="btn btn-success btn-sm btn-flat" name="print"><span class="glyphicon glyphicon-print"></span> Impresión</button>
                  </form>
                </div>
              </div>
              <div class="box-body">
                <table id="example1" class="table table-bordered">
                  <thead>
                    <th>Categoría</th>
                    <th>Usuario</th>
                    <th>Proveedor</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Costo</th>
                    <th>Precio normal</th>
                    <th>Stock</th>
                    <th>Stock mínimo</th>
                    <th>Fecha de ingreso</th>
                    <th>Estado</th>
                    <th>Detalles completos</th>
                  </thead>
                  <tbody>
                    <?php
                    $conn = $pdo->open();

                    try {
                      /*
                       * Usamos las tablas `inputs` y `details_inputs` de la base de datos
                       * para listar las entradas (ingresos) por producto.
                       */
                      $stmt = $conn->prepare("SELECT di.id AS detail_id, i.id AS input_id, c.name AS category_name, CONCAT(u.firstname, ' ', u.lastname) AS user_name, p.name AS provider_name, pr.name AS product_name, di.price AS unit_price, di.quantity AS quantity, pr.cost AS cost, pr.price AS price_normal, pr.stock AS stock, pr.stock_minimum AS stock_minimum, i.entry_date AS entry_date, i.code AS code, i.total AS input_total FROM details_inputs di LEFT JOIN inputs i ON di.id_inputs = i.id LEFT JOIN products pr ON di.id_products = pr.id LEFT JOIN provider p ON i.provider_id = p.id LEFT JOIN users u ON i.user_id = u.id LEFT JOIN category c ON pr.category_id = c.id ORDER BY i.entry_date DESC");
                      $stmt->execute();
                      foreach ($stmt as $row) {
                        $subtotal = $row['unit_price'] * $row['quantity'];
                        // show rows and add data-label attributes for mobile responsive layout
                        echo "
                          <tr>
                            <td class='hidden'></td>
                            <td data-label='Categoría'>" . htmlspecialchars($row['category_name']) . "</td>
                            <td data-label='Usuario'>" . htmlspecialchars($row['user_name']) . "</td>
                            <td data-label='Proveedor'>" . htmlspecialchars($row['provider_name']) . "</td>
                            <td data-label='Producto'>" . htmlspecialchars($row['product_name']) . "</td>
                            <td data-label='Precio'>S/ " . number_format($row['unit_price'], 2) . "</td>
                            <td data-label='Costo'>S/ " . number_format($row['cost'], 2) . "</td>
                            <td data-label='Precio normal'>S/ " . number_format($row['price_normal'], 2) . "</td>
                            <td data-label='Cantidad'>" . intval($row['quantity']) . "</td>
                            <td data-label='Stock mínimo'>" . intval($row['stock_minimum']) . "</td>
                            <td data-label='Fecha de ingreso'>" . date('d/m/Y', strtotime($row['entry_date'])) . "</td>
                            <td data-label='Estado'>Registrado</td>
                            <td data-label='Detalles completos'><button type='button' class='btn btn-info btn-sm btn-flat transact' data-id='" . $row['input_id'] . "'><i class='fa fa-search'></i> Ver</button></td>
                          </tr>
                        ";
                      }
                    } catch (PDOException $e) {
                      echo $e->getMessage();
                    }

                    $pdo->close();
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
    </section>

  </div>
  <?php include 'includes/footer.php'; ?>
  <?php include '../includes/profile_modal.php'; ?>

  </div>
  <!-- ./envoltura -->

  <?php include 'includes/scripts.php'; ?>
  <!-- Selector de fechas -->
  <style>
    /* Responsive adjustments for Income page */
    .table-responsive {
      overflow-x: auto;
    }

    /* Desktop / large screens */
    @media (min-width: 992px) {

      .table th,
      .table td {
        font-size: 14px;
        padding: 10px;
      }
    }

    /* Tablet */
    @media (min-width: 576px) and (max-width: 991px) {

      .table th,
      .table td {
        font-size: 13px;
        padding: 8px;
      }
    }

    /* Mobile: stacked rows using data-label */
    @media (max-width: 575px) {
      .table {
        border: 0;
      }

      .table thead {
        display: none;
      }

      .table,
      .table tbody,
      .table tr,
      .table td {
        display: block;
        width: 100%;
      }

      .table tr {
        margin-bottom: 12px;
        border: 1px solid #e6e6e6;
        padding: 8px;
        border-radius: 4px;
      }

      .table td {
        text-align: right;
        padding: 8px 10px;
        position: relative;
      }

      .table td:before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        text-transform: capitalize;
      }

      .table td[data-label="Precio"],
      .table td[data-label="Costo"],
      .table td[data-label="Precio normal"],
      .table td[data-label="Cantidad"] {
        white-space: nowrap;
      }
    }

    /* Very large screens */
    @media (min-width: 1920px) {
      .content-wrapper {
        max-width: 1800px;
        margin: 0 auto;
      }

      .table th,
      .table td {
        font-size: 16px;
      }
    }

    /* Small visual tweak for the print button area on tiny screens */
    @media (max-width: 420px) {
      .box-header .form-inline {
        display: block;
      }

      .box-header .form-inline .input-group {
        width: 100%;
        margin-bottom: 6px;
      }

      .box-header .form-inline .btn {
        width: 100%;
      }
    }
  </style>
  <script>
    $(function() {
      //Selector de fechas
      $('#datepicker_add').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
      })
      $('#datepicker_edit').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
      })

      //Timepicker
      $('.timepicker').timepicker({
        showInputs: false
      })

      //Date range picker
      $('#reservation').daterangepicker()
      //Date range picker with time picker
      $('#reservationtime').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        format: 'MM/DD/YYYY h:mm A'
      })
      //Date range as a button
      $('#daterange-btn').daterangepicker({
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function(start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
      )

    });
  </script>
  <script>
    $(function() {
      $(document).on('click', '.transact', function(e) {
        e.preventDefault();
        $('#transaction').modal('show');
        var id = $(this).data('id');
        $.ajax({
          type: 'POST',
          url: 'income_transact.php',
          data: {
            id: id
          },
          dataType: 'json',
          success: function(response) {
            $('#date').html(response.date);
            $('#transid').html(response.transaction);
            $('#detail').prepend(response.list);
            $('#total').html(response.total);
          }
        });
      });

      $("#transaction").on("hidden.bs.modal", function() {
        $('.prepend_items').remove();
      });
    });
  </script>
</body>

</html>
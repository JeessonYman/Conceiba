<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <h1>
                Nuevo Ingreso
            </h1>
            <ol class="breadcrumb">
                <li><a href="home.php"><i class="fa fa-dashboard"></i> Casa</a></li>
                <li><a href="inputs.php">Ingresos</a></li>
                <li class="active">Nuevo Ingreso</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php
            if(isset($_SESSION['error'])){
                echo "
                    <div class='alert alert-danger alert-dismissible'>
                        <button type='button' class='close' data-bs-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-warning'></i> Error!</h4>
                        ".$_SESSION['error']."
                    </div>
                ";
                unset($_SESSION['error']);
            }
            if(isset($_SESSION['success'])){
                echo "
                    <div class='alert alert-success alert-dismissible'>
                        <button type='button' class='close' data-bs-dismiss='alert' aria-hidden='true'>&times;</button>
                        <h4><i class='icon fa fa-check'></i> ¡Éxito!</h4>
                        ".$_SESSION['success']."
                    </div>
                ";
                unset($_SESSION['success']);
            }
            ?>
            <div class="row">
                <div class="col-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <a href="inputs.php" class="btn btn-sm btn-primary btn-flat">
                                <i class="fa fa-arrow-left"></i> Volver a Ingresos
                            </a>
                        </div>
                        <div class="box-body">
                            <!-- Formulario de información del ingreso -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Código de Ingreso:</label>
                                        <input type="text" class="form-control" id="input_code" value="<?php echo 'INV-' . date('Ymd') . '-' . time(); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Proveedor:</label>
                                        <select class="form-control" id="provider_id" required>
                                            <option value="">Seleccionar Proveedor</option>
                                            <?php
                                            $conn = $pdo->open();
                                            try {
                                                $stmt = $conn->prepare("SELECT * FROM provider ORDER BY name");
                                                $stmt->execute();
                                                foreach ($stmt as $row) {
                                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                                }
                                            } catch (PDOException $e) {
                                                echo $e->getMessage();
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón para agregar productos -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                        <i class="fa fa-plus"></i> Agregar Producto
                                    </button>
                                </div>
                            </div>
                            <br>

                            <!-- Tabla de productos temporales -->
                            <table id="tempProductsTable" class="table table-bordered">
                                <thead>
<tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
</thead>
                                <tbody id="tempProducts">
                                    <!-- Los productos se agregarán aquí dinámicamente -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                        <td><strong>S/ <span id="totalAmount">0.00</span></strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>

                            <!-- Botón para guardar el ingreso -->
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary" id="saveInput">
                                        <i class="fa fa-save"></i> Guardar Ingreso
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>

    <!-- Modal para agregar productos -->
    <div class="modal fade" id="addProductModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Agregar Producto al Ingreso</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Producto:</label>
                        <select class="form-control" id="product_id" required>
                            <option value="">Seleccionar Producto</option>
                            <?php
                            try {
                                $stmt = $conn->prepare("SELECT * FROM products ORDER BY name");
                                $stmt->execute();
                                foreach ($stmt as $row) {
                                    echo "<option value='" . $row['id'] . "' data-price='" . $row['cost'] . "'>" . $row['name'] . "</option>";
                                }
                            } catch (PDOException $e) {
                                echo $e->getMessage();
                            }
                            $pdo->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Precio de Costo:</label>
                        <input type="number" class="form-control" id="product_price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Cantidad:</label>
                        <input type="number" class="form-control" id="product_quantity" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="addProductToList">Agregar</button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<script>
$(function () {
    let tempProducts = [];
    let productCounter = 0;

    // Cuando se selecciona un producto, actualizar el precio
    $('#product_id').change(function() {
        const selectedOption = $(this).find(':selected');
        const price = selectedOption.data('price');
        $('#product_price').val(price);
    });

    // Agregar producto a la lista temporal
    $('#addProductToList').click(function() {
        const productId = $('#product_id').val();
        const productName = $('#product_id option:selected').text();
        const price = parseFloat($('#product_price').val());
        const quantity = parseInt($('#product_quantity').val());

        if (!productId || !price || !quantity) {
            mariaAlert('Por favor complete todos los campos', 'warning');
            return;
        }

        const subtotal = price * quantity;
        productCounter++;

        // Agregar a la lista temporal
        tempProducts.push({
            id: productCounter,
            product_id: productId,
            name: productName,
            price: price,
            quantity: quantity,
            subtotal: subtotal
        });

        // Agregar fila a la tabla
        const row = `
            <tr data-temp-id="${productCounter}">
                <td>${productName}</td>
                <td>S/ ${price.toFixed(2)}</td>
                <td>${quantity}</td>
                <td>S/ ${subtotal.toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-product" data-temp-id="${productCounter}">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#tempProducts').append(row);

        // Actualizar total
        updateTotal();

        // Limpiar formulario y cerrar modal
        $('#product_id').val('');
        $('#product_price').val('');
        $('#product_quantity').val('');
        $('#addProductModal').modal('hide');
    });

    // Eliminar producto de la lista
    $(document).on('click', '.remove-product', function() {
        const tempId = $(this).data('temp-id');
        
        // Eliminar de la lista temporal
        tempProducts = tempProducts.filter(product => product.id !== tempId);
        
        // Eliminar fila de la tabla
        $(this).closest('tr').remove();
        
        // Actualizar total
        updateTotal();
    });

    // Actualizar total
    function updateTotal() {
        let total = 0;
        tempProducts.forEach(product => {
            total += product.subtotal;
        });
        $('#totalAmount').text(total.toFixed(2));
    }

    // Guardar ingreso
    $('#saveInput').click(function() {
        const code = $('#input_code').val();
        const providerId = $('#provider_id').val();
        
        if (!providerId) {
            mariaAlert('Por favor seleccione un proveedor', 'warning');
            return;
        }

        if (tempProducts.length === 0) {
            mariaAlert('Por favor agregue al menos un producto', 'warning');
            return;
        }

        // Preparar datos para enviar
        const inputData = {
            code: code,
            provider_id: providerId,
            products: tempProducts
        };

        // Enviar datos via AJAX
        $.ajax({
            type: 'POST',
            url: 'inputs_save.php',
            data: JSON.stringify(inputData),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    localStorage.setItem('message', response.message);
                    window.location.href = 'inputs.php';
                } else {
                    mariaAlert('Error: ' + response.message, 'danger');
                }
            },
            error: function() {
                mariaAlert('Error al guardar el ingreso', 'danger');
            }
        });
    });
});
</script>
</body>
</html>
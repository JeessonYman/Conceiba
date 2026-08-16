
<!-- Añadir -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Agregar nuevo Ingreso</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="inputs_add.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" class="col-sm-1 control-label">Nombre</label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <label for="category" class="col-sm-1 control-label">Categoría</label>

                        <div class="col-sm-5">
                            <select class="form-control" id="category" name="category" required>
                                <option value="" selected>- Seleccione -</option>
                            </select>
                        </div>
                        <br>
                        <br>
                        <br>
                        <label for="provider" class="col-sm-1 control-label">Proveedor</label>

                        <div class="col-sm-5">
                            <select class="form-control" id="provider" name="provider" required>
                                <option value="" selected>- Seleccione -</option>
                            </select>
                        </div>

                        <label for="price" class="col-sm-1 control-label">Precio</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="price" name="price" required>
                        </div>
                        <br>
                        <br>
                        <br>
                        <label for="cost" class="col-sm-1 control-label">Precio Compra</label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="cost" name="cost" required>
                        </div>

                        <label for="price_normal" class="col-sm-1 control-label">Precio Normal</label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="price_normal" name="price_normal" required>
                        </div>
                        <br>
                        <br>
                        <br>
                        <label for="stock" class="col-sm-1 control-label">Stock</label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="stock" name="stock" required>
                        </div>

                        <label for="stock_minimum" class="col-sm-1 control-label">Stock Mínimo</label>

                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="stock_minimum" name="stock_minimum" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="photo" class="col-sm-1 control-label">Foto</label>

                        <div class="col-sm-5">
                            <input type="file" id="photo" name="photo">
                        </div>
                    </div>
                    <p><b>Descripción</b></p>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea id="editor1" name="description" rows="10" cols="80" required></textarea>
                        </div>

                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat float-start" data-bs-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
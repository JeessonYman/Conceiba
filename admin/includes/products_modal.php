<!-- Description -->
<div class="modal fade" id="description">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b><span class="name"></span></b></h4>
            </div>
            <div class="modal-body">
                <p id="desc"></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat float-start" data-bs-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Añadir -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Agregar nuevo producto</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="products_add.php" enctype="multipart/form-data">
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
                    <input type="text" class="form-control" id="stock" name="stock" required disabled>
                    <small class="text-muted">El stock se actualiza a través de la sección de Ingresos</small>
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

<!-- Editar producto -->
<div class="modal fade" id="edit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Editar producto: </b><span class="name"></span></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="products_edit.php" enctype="multipart/form-data">
                <input type="hidden" class="prodid" name="id">
                <div class="form-group">
                  <label for="edit_name" class="col-sm-1 control-label">Nombre</label>

                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="edit_name" name="name" required>
                  </div>

                  <label for="edit_category" class="col-sm-1 control-label">Categoría</label>

                  <div class="col-sm-5">
                    <select class="form-control" id="edit_category" name="category" required>
                      <option value="" id="catselected" selected></option>
                    </select>
                  </div>
                  <br>
                  <br>
                  <br>
                  <label for="edit_provider" class="col-sm-1 control-label">Proveedor</label>

                  <div class="col-sm-5">
                    <select class="form-control" id="edit_provider" name="provider" required>
                      <option value="" id="proselected" selected></option>
                    </select>
                  </div>

                  <label for="edit_price" class="col-sm-1 control-label">Precio</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="edit_price" name="price" required>
                  </div>
                  <br>
                  <br>
                  <br>
                  <label for="edit_cost" class="col-sm-1 control-label">Precio Compra</label>

                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="edit_cost" name="cost" required>
                  </div>

                  <label for="edit_price_normal" class="col-sm-1 control-label">Precio Normal</label>

                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="edit_price_normal" name="price_normal" required>
                  </div>
                  <br>
                  <br>
                  <br>
                  <label for="edit_stock" class="col-sm-1 control-label">Stock</label>

                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="edit_stock" name="stock" required disabled>
                    <small class="text-muted">El stock se actualiza a través de la sección de Ingresos</small>
                  </div>

                  <label for="edit_stock_minimum" class="col-sm-1 control-label">Stock Mínimo</label>

                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="edit_stock_minimum" name="stock_minimum" required>
                  </div>
                </div>
                <p><b>Descripción</b></p>
                <div class="form-group">
                  <div class="col-sm-12">
                    <textarea id="editor2" name="description" class="form-control" rows="8" required></textarea>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat float-start" data-bs-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
              <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Actualizar</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Eliminar producto -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Eliminando ...</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="products_delete.php">
                <input type="hidden" class="prodid" name="id">
                <div class="text-center">
                    <p>BORRAR producto</p>
                    <h2 class="bold name"></h2>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat float-start" data-bs-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
              <button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Eliminar</button>
              </form>
            </div>
        </div>
    </div>
</div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b><span class="name"></span></b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="products_photo.php" enctype="multipart/form-data">
                <input type="hidden" class="prodid" name="id">
                <div class="form-group">
                    <label for="photo" class="col-sm-3 control-label">Foto</label>

                    <div class="col-sm-9">
                      <input type="file" id="photo" name="photo" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat float-start" data-bs-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="upload"><i class="fa fa-check-square-o"></i> Actualizar</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Galería de fotos del producto -->
<div class="modal fade" id="gallery_photos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Galería de fotos del producto</b></h4>
            </div>
            <div class="modal-body">
              <p class="text-muted small">Estas imágenes adicionales se muestran como miniaturas en la página del producto, para que el cliente pueda ver distintos ángulos/colores (la foto principal se administra aparte, con el ícono de lápiz).</p>

              <div id="gallery-thumbs" class="mb-4">
                <p class="text-center text-muted py-3">Cargando...</p>
              </div>

              <hr>

              <form method="POST" action="products_gallery.php" enctype="multipart/form-data">
                <input type="hidden" class="gallery-prodid" name="id">
                <div class="mb-3">
                  <label for="gallery" class="form-label">Agregar imágenes nuevas (puedes seleccionar varias a la vez)</label>
                  <input type="file" class="form-control" id="gallery" name="gallery[]" multiple accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-success btn-flat" name="upload_gallery"><i class="fa fa-upload"></i> Subir a la galería</button>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat" data-bs-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
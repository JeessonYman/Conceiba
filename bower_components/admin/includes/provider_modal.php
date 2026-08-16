<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Añadir nuevo proveedor</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="provider_add.php">
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">Nombre</label>
                   
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="name" name="name" required>
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

<!-- Edit -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Editar proveedor</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="provider_edit.php">
                <input type="hidden" class="proid" name="id">
                <div class="form-group">
                    <label for="edit_name" class="col-sm-3 control-label">Nombre</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_name" name="name">
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

<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Eliminando ...</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="provider_delete.php">
                <input type="hidden" class="proid" name="id">
                <div class="text-center">
                    <p>BORRAR proveedor</p>
                    <h2 class="bold proname"></h2>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat float-start" data-bs-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Eliminar</button>
              </form>
            </div>
        </div>
    </div>
</div>

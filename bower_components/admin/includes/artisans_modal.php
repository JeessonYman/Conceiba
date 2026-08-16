<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Añadir nuevo artesano</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="artisans_add.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Edad</label>
                    <input type="number" class="form-control" id="age" name="age">
                </div>
                <div class="mb-3">
                    <label for="community" class="form-label">Comunidad / Ubicación</label>
                    <input type="text" class="form-control" id="community" name="community" placeholder="Ej. Comunidad de Bolívar, Cajamarca">
                </div>
                <div class="mb-3">
                    <label for="bio" class="form-label">Historia / Descripción</label>
                    <textarea class="form-control" id="bio" name="bio" rows="5" placeholder="Cuenta un poco sobre este artesano, su trayectoria, su relación con Conceiba, etc."></textarea>
                </div>
                <div class="mb-3">
                    <label for="photo" class="form-label">Foto (solo la foto de la persona, sin diseño ni texto encima)</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                    <label class="form-check-label" for="active">Visible en el sitio público</label>
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
              <h4 class="modal-title"><b>Editar artesano</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="artisans_edit.php" enctype="multipart/form-data">
                <input type="hidden" class="artisanid" name="id">
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="edit_name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="edit_age" class="form-label">Edad</label>
                    <input type="number" class="form-control" id="edit_age" name="age">
                </div>
                <div class="mb-3">
                    <label for="edit_community" class="form-label">Comunidad / Ubicación</label>
                    <input type="text" class="form-control" id="edit_community" name="community">
                </div>
                <div class="mb-3">
                    <label for="edit_bio" class="form-label">Historia / Descripción</label>
                    <textarea class="form-control" id="edit_bio" name="bio" rows="5"></textarea>
                </div>
                <div class="mb-3">
                    <label for="edit_photo" class="form-label">Cambiar foto (opcional)</label>
                    <input type="file" class="form-control" id="edit_photo" name="photo" accept="image/*">
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="edit_active" name="active">
                    <label class="form-check-label" for="edit_active">Visible en el sitio público</label>
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
              <form class="form-horizontal" method="POST" action="artisans_delete.php">
                <input type="hidden" class="artisanid" name="id">
                <div class="text-center">
                    <p>BORRAR artesano</p>
                    <h2 class="bold artisanname"></h2>
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

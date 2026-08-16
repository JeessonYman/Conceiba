<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Nueva ficha de hilado</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="yarn_specs_add.php" enctype="multipart/form-data">
                <div class="mb-3"><label class="form-label">Título</label><input type="text" class="form-control" name="title" required></div>
                <div class="mb-3"><label class="form-label">Foto del hilado</label><input type="file" class="form-control" name="photo" accept="image/*"></div>
                <div class="mb-3"><label class="form-label">Descripción</label><textarea class="form-control" name="description" rows="2"></textarea></div>
                <div class="row">
                  <div class="col-6 mb-3"><label class="form-label">Composición</label><input type="text" class="form-control" name="composition"></div>
                  <div class="col-6 mb-3"><label class="form-label">Título hilado</label><input type="text" class="form-control" name="yarn_title"></div>
                  <div class="col-6 mb-3"><label class="form-label">Presentación</label><input type="text" class="form-control" name="presentation"></div>
                  <div class="col-6 mb-3"><label class="form-label">Producción</label><input type="text" class="form-control" name="production"></div>
                  <div class="col-6 mb-3"><label class="form-label">Agujas sugeridas</label><input type="text" class="form-control" name="needles"></div>
                  <div class="col-6 mb-3"><label class="form-label">Peso</label><input type="text" class="form-control" name="weight"></div>
                </div>
                <div class="mb-3"><label class="form-label">Usos</label><textarea class="form-control" name="uses" rows="2"></textarea></div>
                <div class="mb-3"><label class="form-label">Cuidados</label><textarea class="form-control" name="care_instructions" rows="2"></textarea></div>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Editar ficha de hilado</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="yarn_specs_edit.php" enctype="multipart/form-data">
                <input type="hidden" class="yarnid" name="id">
                <div class="mb-3"><label class="form-label">Título</label><input type="text" class="form-control" id="edit_title" name="title" required></div>
                <div class="mb-3"><label class="form-label">Cambiar foto (opcional)</label><input type="file" class="form-control" name="photo" accept="image/*"></div>
                <div class="mb-3"><label class="form-label">Descripción</label><textarea class="form-control" id="edit_description" name="description" rows="2"></textarea></div>
                <div class="row">
                  <div class="col-6 mb-3"><label class="form-label">Composición</label><input type="text" class="form-control" id="edit_composition" name="composition"></div>
                  <div class="col-6 mb-3"><label class="form-label">Título hilado</label><input type="text" class="form-control" id="edit_yarn_title" name="yarn_title"></div>
                  <div class="col-6 mb-3"><label class="form-label">Presentación</label><input type="text" class="form-control" id="edit_presentation" name="presentation"></div>
                  <div class="col-6 mb-3"><label class="form-label">Producción</label><input type="text" class="form-control" id="edit_production" name="production"></div>
                  <div class="col-6 mb-3"><label class="form-label">Agujas sugeridas</label><input type="text" class="form-control" id="edit_needles" name="needles"></div>
                  <div class="col-6 mb-3"><label class="form-label">Peso</label><input type="text" class="form-control" id="edit_weight" name="weight"></div>
                </div>
                <div class="mb-3"><label class="form-label">Usos</label><textarea class="form-control" id="edit_uses" name="uses" rows="2"></textarea></div>
                <div class="mb-3"><label class="form-label">Cuidados</label><textarea class="form-control" id="edit_care_instructions" name="care_instructions" rows="2"></textarea></div>
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
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Eliminando ...</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="yarn_specs_delete.php">
                <input type="hidden" class="yarnid" name="id">
                <div class="text-center">
                    <p>BORRAR ficha de hilado</p>
                    <h2 class="bold yarnname"></h2>
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

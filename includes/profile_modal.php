<!-- Transaction History -->
<div class="modal fade" id="transaction"  >
    <div class="modal-dialog" style="border-radius:20px;">
        <div class="modal-content" style="border-radius:20px;">
            <div class="modal-header" style="border-radius:20px;">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" style='border-radius:10px;'>
                <b>Detalles completos de la transacción</b>
              </h4>
            </div>
            <div class="modal-body" style='border-radius:10px;'>
              <p>
                Date: <span id="date"></span>
                <span class="pull-right">Transaction#: <span id="transid"></span></span> 
              </p>
              <table class="table table-bordered" style="border-radius:20px;">
                <thead>
                  <th>Producto</th>
                  <th>Precio</th>
                  <th>Cantidad</th>
                  <th>Subtotal</th>
                </thead>
                <tbody id="detail" style='border-radius:10px;'>
                  <tr>
                    <td colspan="3" align="right" style='border-radius:10px;'><b>Total</b></td>
                    <td><span id="total"></span></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer" style='border-radius:20px;'>
              <button type="button" class="btn btn-default btn-flat pull-left" style='border-radius:20px;' data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile -->
<div class="modal fade" id="edit" >
    <div class="modal-dialog" style='border-radius:20px;'>
        <div class="modal-content" style='border-radius:20px;'>
            <div class="modal-header" style='border-radius:20px;'>
              <button type="button" class="close" data-dismiss="modal" style='border-radius:20px;' aria-label="Close">
                  <span aria-hidden="true" style='border-radius:20px;'>&times;</span></button>
              <h4 class="modal-title" style='border-radius:20px;'><b>Actualizar cuenta</b></h4>
            </div>
            <div class="modal-body" style='border-radius:20px;'>
              <form class="form-horizontal"  style='border-radius:20px;'method="POST" action="profile_edit.php" enctype="multipart/form-data">
                <div class="form-group" style='border-radius:20px;'>
                    <label for="firstname" class="col-sm-3 control-label" style='border-radius:20px;'>Primer nombre</label>

                    <div class="col-sm-9" style='border-radius:20px;'>
                      <input type="text" class="form-control" id="firstname" name="firstname" style='border-radius:20px;' value="<?php echo $user['firstname']; ?>">
                    </div>
                </div>
                <div class="form-group" style='border-radius:20px;'>
                    <label for="lastname" class="col-sm-3 control-label" style='border-radius:20px;' >Apellido</label>

                    <div class="col-sm-9" style='border-radius:20px;'>
                      <input type="text" class="form-control" id="lastname" name="lastname" style='border-radius:20px;' value="<?php echo $user['lastname']; ?>">
                    </div>
                </div>
                <div class="form-group" style='border-radius:20px;'>
                    <label for="email" class="col-sm-3 control-label" style='border-radius:20px;'>Email</label>

                    <div class="col-sm-9" style='border-radius:20px;'>
                      <input type="text" class="form-control" id="email" name="email" style='border-radius:20px;' value="<?php echo $user['email']; ?>">
                    </div>
                </div>
                <div class="form-group" style='border-radius:20px;'>
                    <label for="password" class="col-sm-3 control-label" style='border-radius:20px;'>Password</label>

                    <div class="col-sm-9" style='border-radius:20px;'>
                      <input type="password" class="form-control" id="password" style='border-radius:20px;' name="password" value="<?php echo $user['password']; ?>">
                    </div>
                </div>
                <div class="form-group" style='border-radius:20px;'>
                    <label for="contact" class="col-sm-3 control-label" style='border-radius:20px;'>Datos de contacto</label>

                    <div class="col-sm-9" style='border-radius:20px;'>
                      <input type="text" class="form-control" id="contact" name="contact" style='border-radius:20px;' value="<?php echo $user['contact_info']; ?>">
                    </div>
                </div>
                <div class="form-group" style='border-radius:20px;'>
                    <label for="address" class="col-sm-3 control-label" style='border-radius:20px;'>Direcciòn</label>

                    <div class="col-sm-9" style='border-radius:20px;'>
                      <textarea class="form-control" id="address" name="address" style='border-radius:20px;'><?php echo $user['address']; ?></textarea>
                    </div>
                </div>
                <div class="form-group" style='border-radius:20px;'>
                    <label for="photo" class="col-sm-3 control-label" style='border-radius:20px;'>Foto</label>

                    <div class="col-sm-9" style='border-radius:20px;'>
                      <input type="file" id="photo" name="photo"  >
                    </div>
                </div>
                <hr>
                
                <div class="form-group" style='border-radius:20px;'>
                    <label for="curr_password" class="col-sm-3 control-label" style='border-radius:20px;'>Contraseña actual</label>

                    <div class="col-sm-9" style='border-radius:10px;'>
                      <input type="password" class="form-control" id="curr_password" name="curr_password" style='border-radius:10px;' placeholder="input current password to save changes" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style='border-radius:20px;'>
              <button type="button" class="btn btn-default btn-flat pull-left" style='border-radius:20px;' data-dismiss="modal"><i class="fa fa-close" style='border-radius:20px;'></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="edit" style='border-radius:20px;'><i class="fa fa-check-square-o" style='border-radius:20px;'></i> Actualizar</button>
              </form>
            </div>
        </div>
    </div>
</div>
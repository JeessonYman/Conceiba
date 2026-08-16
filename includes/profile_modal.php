<!-- Transaction History -->
<div class="modal fade" id="transaction">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"><b>Detalles completos de la transacción</b></h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>
                Fecha: <span id="date"></span>
                <span class="float-end">Transacción#: <span id="transid"></span></span>
              </p>
              <div class="table-responsive">
                <table class="table table-bordered align-middle">
                  <thead>
                    <tr>
                      <th>Producto</th>
                      <th>Precio</th>
                      <th>Cantidad</th>
                      <th>Subtotal</th>
                    </tr>
                  </thead>
                  <tbody id="detail">
                    <tr>
                      <td colspan="3" align="right"><b>Total</b></td>
                      <td><span id="total"></span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="profile_edit.php" enctype="multipart/form-data">
                <div class="modal-header">
                  <h4 class="modal-title"><b>Actualizar cuenta</b></h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="photo-preview" src="<?php echo (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>" class="rounded-circle" style="width:100px; height:100px; object-fit:contain; background:rgba(0,0,0,0.15); border:2px solid var(--accent, #e0ac2b);">
                    </div>

                    <div class="mb-3">
                        <label for="firstname" class="form-label">Primer nombre</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $user['firstname']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="lastname" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="<?php echo $user['password']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="contact" class="form-label">Datos de contacto</label>
                        <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $user['contact_info']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <textarea class="form-control" id="address" name="address"><?php echo $user['address']; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label for="curr_password" class="form-label">Contraseña actual</label>
                        <input type="password" class="form-control" id="curr_password" name="curr_password" placeholder="Escribe tu contraseña actual para guardar cambios" required>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
                  <button type="submit" class="btn btn-accent" name="edit"><i class="fa fa-check-square-o"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Previsualizar la foto elegida antes de subirla (no cambia la lógica de envío del formulario)
document.addEventListener('DOMContentLoaded', function () {
    const photoInput = document.getElementById('photo');
    const preview = document.getElementById('photo-preview');
    if (photoInput && preview) {
        photoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});
</script>

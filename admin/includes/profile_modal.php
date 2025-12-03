<!-- Add -->
<div class="modal fade" id="profile">
    <div class="modal-dialog" >
        <div class="modal-content" style='border-radius:10px;'>
          	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
              		<span aria-hidden="true">&times;</span></button>
            	<h4 class="modal-title"><b>Perfil de administrador</b></h4>
          	</div>
          	<div class="modal-body">
            	<form class="form-horizontal" method="POST" action="profile_update.php?return=<?php echo basename($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
          		  <div class="form-group">
                  	<label for="email" class="col-sm-3 control-label">Correo electrónico</label>

                  	<div class="col-sm-9" >
                    	<input style='border-radius:10px;' type="text" class="form-control" id="email" name="email" value="<?php echo $admin['email']; ?>">
                  	</div>
                </div>
                <div class="form-group">
                    <label for="password" style='border-radius:10px;' class="col-sm-3 control-label">Contraseña</label>

                    <div class="col-sm-9"> 
                      <input type="password" style='border-radius:10px;' class="form-control" id="password" name="password" value="<?php echo $admin['password']; ?>">
                    </div>
                </div>
                <div class="form-group">
                  	<label for="firstname" style='border-radius:10px;' class="col-sm-3 control-label">Primer nombre</label>

                  	<div class="col-sm-9">
                    	<input type="text" style='border-radius:10px;' class="form-control" id="firstname" name="firstname" value="<?php echo $admin['firstname']; ?>">
                  	</div>
                </div>
                <div class="form-group">
                  	<label for="lastname" style='border-radius:10px;' class="col-sm-3 control-label">Apellido</label>

                  	<div class="col-sm-9">
                    	<input type="text" style='border-radius:10px;' class="form-control" id="lastname" name="lastname" value="<?php echo $admin['lastname']; ?>">
                  	</div>
                </div>
                <div class="form-group">
                    <label for="photo" style='border-radius:10px;'class="col-sm-3 control-label">Foto:</label>

                    <div class="col-sm-9">
                      <input style='border-radius:10px;' type="file" id="photo" name="photo">
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="curr_password" class="col-sm-3 control-label">contraseña actual:</label>

                    <div class="col-sm-9">
                      <input type="password" style='border-radius:10px;' class="form-control" id="curr_password" name="curr_password" placeholder="ingrese la contraseña actual para guardar los cambios" required>
                    </div>
                </div>
          	</div>
          	<div class="modal-footer">
            	<button type="button" style='border-radius:10px;' class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
            	<button type="submit" style='border-radius:10px;' class="btn btn-success btn-flat" name="save"><i class="fa fa-check-square-o"></i> Guardar</button>
            	</form>
          	</div>
        </div>
    </div>
</div>
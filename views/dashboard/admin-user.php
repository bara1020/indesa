<?php 
 require '../../admin/functions.php';

include('../../includes/header.php');
include('../../includes/navbar.php');
?>


<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#registerModal">
        Agregar usuario
      </button>
    </div>
  </div>
  <div id="alert-row" class="row">
    <div class="col-md-12">
      <div class="alert alert-success" role="alert">
        <strong>EL usuario se registró correctamente</strong>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
    <iframe id="frame1" style="width:150px;height:150px"></iframe>
     <!-- <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <button type="button" name="" id="ver" class="btn btn-primary" btn-lg btn-block>ver archivo</button>
      </form>-->
      <a href="../../admin/download.php?file=fichero.pdf">Descargar fichero</a>

    <table id="example" class="table table-striped table-bordered" width="100%"></table>
    </div>
  </div>
</div>


<!--BEGIN: Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registerModalLabel">Formulario de registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="register-form" class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-label-group <?php echo (!empty($nit_err)) ? 'has-error' : ''; ?>">
            <label for="inputNit">Número de documento</label>
            <input type="text" id="inputNit" class="form-control" autofocus
              value="<?php echo $nit; ?>" name="nit">
            <span id="nit-error" class="help-block text-danger"><?php echo $nit_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label for="inputName">Nombre</label>
            <input type="text" id="inputName" class="form-control"  autofocus
              value="<?php echo $username; ?>" name="username">
            <span id="username-error"class="help-block text-danger"><?php echo $username_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
            <label for="inputLastName">Apellidos</label>
            <input type="text" id="inputLastName" class="form-control"  autofocus
              value="<?php echo $lastname; ?>" name="lastname">
            <span id="lastname-error"class="help-block text-danger"><?php echo $lastname_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label for="inputEmail">Email</label>
            <input type="email" id="inputEmail" class="form-control"  autofocus
              value="<?php echo $email; ?>" name="email" required>
            <span id="email-error"class="help-block text-danger"><?php echo $email_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($phonenumber_err)) ? 'has-error' : ''; ?>">
            <label for="inputPhoneNumber">Teléfono</label>
            <input type="phonenumber" id="inputPhoneNumber" class="form-control"  autofocus
              value="<?php echo $phonenumber; ?>" name="phonenumber">
            <span id="phonenumber-error"class="help-block text-danger"><?php echo $phonenumber_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            <span id="password-error" class="help-block text-danger"><?php echo $password_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Confirma la contraseña</label>
            <input type="password" name="confirm_password" class="form-control"
              value="<?php echo $confirm_password; ?>">
            <span id="confirm-password-error" class="help-block text-danger"><?php echo $confirm_password_err; ?></span>
          </div>
          <div class="form-label-group">
            <label>Selecciona el rol</label>
            <br>
            <select class="selectpicker">
                <option value="2">Usuario</option>
                <option value="1">Administrador</option>
            </select>
          </div>
          <hr>
          <button id="register" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" value="registrar">Registrar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!--END: Register Modal -->

<!--BEGIN: Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateModalLabel">Actualización de datos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="update-form" class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-label-group d-none">
            <input type="text" id="inputId-update" class="form-control" autofocus
              value="<?php echo $id; ?>" name="id" >
          </div>
          <div class="form-label-group <?php echo (!empty($nit_err)) ? 'has-error' : ''; ?>">
            <label for="inputNit-update">Número de documento</label>
            <input type="text" id="inputNit-update" class="form-control" autofocus
              value="<?php echo $nit; ?>" name="nit" disabled>
            <span id="nit-error" class="help-block text-danger"><?php echo $nit_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label for="inputName-update">Nombre</label>
            <input type="text" id="inputName-update" class="form-control"  autofocus
              value="<?php echo $username; ?>" name="username">
            <span id="username-error"class="help-block text-danger"><?php echo $username_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
            <label for="inputLastName-update">Apellidos</label>
            <input type="text" id="inputLastName-update" class="form-control"  autofocus
              value="<?php echo $lastname; ?>" name="lastname">
            <span id="lastname-error"class="help-block text-danger"><?php echo $lastname_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label for="inputEmail-update">Email</label>
            <input type="email" id="inputEmail-update" class="form-control"  autofocus
              value="<?php echo $email; ?>" name="email" required>
            <span id="email-error"class="help-block text-danger"><?php echo $email_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($phonenumber_err)) ? 'has-error' : ''; ?>">
            <label for="inputPhoneNumber-update">Teléfono</label>
            <input type="phonenumber" id="inputPhoneNumber-update" class="form-control"  autofocus
              value="<?php echo $phonenumber; ?>" name="phonenumber">
            <span id="phonenumber-error"class="help-block text-danger"><?php echo $phonenumber_err; ?></span>
          </div>
          <div class="form-label-group">
            <label>Selecciona el rol</label>
            <br>
            <select id="selectpicker" class="selectpicker">
                <option value="2">Usuario</option>
                <option value="1">Administrador</option>
            </select>
          </div>
          <hr>
          <button id="update" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" value="actualizar">Actualizar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!--END: Update Modal -->

<!--BEGIN: Update Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Eliminar usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <h6>Estas seguro que deseas eliminar este usuario?</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button id="delete" type="button" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>
<!--END: Update Modal -->

<?php 
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>
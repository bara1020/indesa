<?php 
 require '../../admin/functions.php'; 
 if (session_status() == PHP_SESSION_NONE) {
   session_start();
 }

 if(!$_SESSION['loggedin']){
   header('Location: ../login');
 }
 $user = $_SESSION['user'];

 $role = $user['role'];


include('../../includes/header.php');
include('../../includes/navbar.php');
?>


<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <!-- Button trigger modal -->
      <button id="btn-register" type="button" class="btn btn-primary" data-toggle="modal" data-target="#registerModal">
        Agregar tiquetera
      </button>
    </div>
  </div>
    <input type="text"
      class="form-control" name="roleuser" id="roleuser" aria-describedby="helpId" placeholder="" value="<?php echo $role?>" style="display:none">
  <div id="alert-row" class="row">
    <div class="col-md-12">
      <div class="alert alert-success" role="alert">
        <strong>La tiquetera se ha registrado correctamente</strong>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
    <table id="tiqueteras" class="table table-striped table-bordered" width="100%"></table>
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
            <label for="descriptionTiqueteras">Descripción de la tiquetera</label>
            <input type="text" id="descriptionTiqueteras" class="form-control" autofocus
              value="<?php echo $descriptionTiqueteras; ?>" name="descriptionTiqueteras">
            <span id="descriptionTiqueteras-error" class="help-block text-danger"><?php echo $descriptionTiqueteras_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label for="daysTiqueteras">Número de días</label>
            <input type="number" id="daysTiqueteras" class="form-control" autofocus
              value="<?php echo $daysTiqueteras; ?>" name="daysTiqueteras">
            <span id="daysTiqueteras-error"class="help-block text-danger"><?php echo $daysTiqueteras_err; ?></span>
          </div>
          <hr>
          <button id="register-tiquetera" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" value="registrar">Registrar</button>
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
            <input type="text" id="id-update" class="form-control" autofocus
              name="id" >
          </div>
          <div class="form-label-group <?php echo (!empty($nit_err)) ? 'has-error' : ''; ?>">
            <label for="descriptionTiqueterasUpdate">Descripción de la tiquetera</label>
            <input type="text" id="descriptionTiqueterasUpdate" class="form-control" autofocus
              value="<?php echo $descriptionTiqueterasUpdate; ?>" name="descriptionTiqueterasUpdate">
            <span id="descriptionTiqueterasUpdate-error" class="help-block text-danger"><?php echo $descriptionTiqueterasUpdate_err; ?></span>
          </div>
          <div class="form-label-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label for="daysTiqueterasUpdate">Número de días</label>
            <input type="number" id="daysTiqueterasUpdate" class="form-control" autofocus
              value="<?php echo $daysTiqueterasUpdate; ?>" name="daysTiqueterasUpdate">
            <span id="daysTiqueterasUpdate-error"class="help-block text-danger"><?php echo $daysTiqueterasUpdate_err; ?></span>
          </div>
          <hr>
          <button id="update-tiquetera" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" value="actualizar">Actualizar</button>
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
          <h6>Estas seguro que deseas eliminar este tiquetera?</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button id="delete-tiquetera" type="button" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>
<!--END: Update Modal -->

<?php 

include('../../includes/scripts.php');
echo '  <script src="../../js/dashboard.js"></script>';
include('../../includes/footer.php');
?>
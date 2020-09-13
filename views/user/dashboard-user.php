<?php
    require "../../admin/functions.php";
    if(!$_SESSION['loggedin']){
      header('Location: ./login');
    }
    $user = $_SESSION['user'];
    $username = $user['username'];
    $nit = $user['nit'];
    $lastname = $user['lastname'];
    $phonenumber = $user['phonenumber'];
    $email = $user['email'];
    $password = $user['password'];
    $confirm_password = $user['password'];
    include('../../includes/header.php');
    include('../../includes/navbar-user.php');
?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title text-center">Bienvenido <?php echo $user['username'];?></h1>
                        <form id="update-form" class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
              method="post" enctype="multipart/form-data">
              <input type="text" class="form-control process" autofocus value="<?php echo $_SESSION["id"]; ?>" name="id"
                style="display:none">
              <div class="form-label-group mt-3 <?php echo (!empty($nit_err)) ? 'has-error' : ''; ?>">
                <label for="inputNit">Número de documento</label>
                <input type="number" id="inputNit" class="form-control" autofocus value="<?php echo $nit; ?>" name="nit" disabled>
              </div>
              <div class="form-label-group mt-3 <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label for="inputName">Nombre</label>
                <input type="text" id="inputName" class="form-control" autofocus value="<?php echo $username; ?>"
                  name="username">
                <span id="username-error" class="help-block text-danger"><?php echo $username_err; ?></span>
              </div>
              <div class="form-label-group mt-3 <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
                <label for="inputLastName">Apellidos</label>
                <input type="text" id="inputLastName" class="form-control" autofocus value="<?php echo $lastname; ?>"
                  name="lastname">
                <span id="lastname-error" class="help-block text-danger"><?php echo $lastname_err; ?></span>
              </div>
              <div class="form-label-group mt-3 <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label for="inputEmail">Email</label>
                <input type="email" id="inputEmail" class="form-control" autofocus value="<?php echo $email; ?>"
                  name="email">
                <span id="email-error" class="help-block text-danger"><?php echo $email_err; ?></span>
              </div>
              <div class="form-label-group mt-3 <?php echo (!empty($phonenumber_err)) ? 'has-error' : ''; ?>">
                <label for="inputPhoneNumber">Teléfono</label>
                <input type="number" id="inputPhoneNumber" class="form-control" autofocus
                  value="<?php echo $phonenumber; ?>" name="phonenumber">
                <span id="phonenumber-error" class="help-block text-danger"><?php echo $phonenumber_err; ?></span>
              </div>
              <div class="form-label-group mt-3 <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label for="password">password</label>
                <input type="password" id="password" class="form-control" autofocus value="<?php echo $password; ?>"
                  name="password">
                <span id="password-error" class="help-block text-danger"><?php echo $password_err; ?></span>
              </div>
              <div class="form-label-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirma la contraseña</label>
                <input type="password" name="confirm_password" class="form-control"
                  value="<?php echo $confirm_password; ?>">
                <span id="confirm-password-error" class="help-block text-danger"><?php echo $confirm_password_err; ?></span>
              </div>
              <hr>
              <div id="response-message" class="alert alert-success" role="alert">
                  
              </div>
              <button id="#update-perfil" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit"
                value="actualizar">Actualizar información
              </button>
                </form>

                    </div>
                </div>
                
            </div>
        </div>
    </div>

<?php 
echo '  <script src="../../js/dashboard-user.js"></script>';
include('../../includes/scripts.php');
include('../../includes/footer.php');
?>

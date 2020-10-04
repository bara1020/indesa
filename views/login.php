<?php
 require '../admin/login.php';
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
if(isset($_SESSION['loggedin'])){

  if($_SESSION['loggedin']){
    $user = $_SESSION['user'];
    if($user['role'] == 'Usuario'){
      header('Location: user/dashboard-user');
    } else if($user['role'] == 'Entrenador'){
       header('Location: dashboard/admin-booking'); 
    } else {    
      header('Location: dashboard/admin-user');
    }
  }
}

$_SESSION['loginType'] = "admin"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<link rel="icon" 
      type="image/png" 
      href="../img/icon.png">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

</head>
<body class="text-center">
   <!-- <?php //require 'views/navbar.view.php'?>-->
   <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
        <div class="text-center">
            <img  id="banner" src="../img/banner_register-350.jpg" alt="Bienvenidos a Indesa" >
          </div>
          <div class="card-body">
            <h5 class="card-title text-center">Inicio de sesión</h5>
            
            <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <div class="form-label-group <?php echo (!empty($nit_err)) ? 'has-error' : ''; ?>">
                <input type="text" id="inputEmail" 
                class="form-control" 
                placeholder="Número de documento" 
                autofocus
                value="<?php echo $nit; ?>"
                name="nit">
                <label for="inputEmail">Número de documento</label>
                <span class="help-block"><?php echo $nit_err; ?></span>
              </div>

              <div class="form-label-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input type="password" id="password" class="form-control" placeholder="Contraseña" name="password">
                <label for="password">Contraseña</label>
                <span class="help-block"><?php echo $password_err; ?></span>
              </div>
              <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" value="login">Ingresar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
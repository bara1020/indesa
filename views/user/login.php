<?php
 require '../../admin/login.php';
 if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$_SESSION['loginType'] = "user";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
<link rel="icon" 
      type="image/png" 
      href="../../img/icon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="text-center">
   <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="text-center">
            <img  id="banner" src="../../img/banner_register-350.jpg" alt="Bienvenidos a Indesa" >
          </div>
          <div class="card-body">
            <h5 class="card-title text-center">Inicio de sesión</h5>
            <?php
                
            if(isset($_GET['success'])){
              echo '<div class="alert alert-warning" role="alert">
                      <strong>Revisa tu correo para completar el registro </strong>
                    </div>';
            }
            
            if(isset($_GET['success-password'])){
              echo '<div class="alert alert-success" role="alert">
                      <strong>Registro completado. Ingresa tus datos para acceder a tu cuenta personal. </strong>
                    </div>';
            }
            ?>

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
         
              <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" id="customCheck1">
              </div>
              <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" value="login">Ingresar</button>
              <hr>
              <a href="remember-password.php">No recuerdas la contraseña?</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <footer class="footer">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4 col-sm-6" style="min-width:350">
          <iframe width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
            src="https://maps.google.com/maps?q=sabaneta&amp;t=m&amp;z=16&amp;output=embed&amp;iwloc=near"
            aria-label="sabaneta"></iframe>
        </div>
        <div class="col-md-4 col-sm-6 pr-3 pl-3">
          <p style="color:#000000"><strong>Teléfono(s):&nbsp;</strong>314 685 80 09 – 321 255 80 29.<br> <strong>Correo
              electrónico:&nbsp;</strong><a
              title="Pulse para enviar un correo electrónico a la entidad, abre el cliente de correo de su computador"
              href="mailto:usuario@indesa.gov.co">usuario@indesa.gov.co</a><br> <strong>Dirección
              física:&nbsp;</strong>Calle 76 E Sur #46 B 82<br> <strong>Horario de atención en oficinas:</strong><br>
            Lunes a jueves de 8:00 a.m. a 12:00 m. y de 2:00 p.m. a 6:00 p.m. Viernes de 8:00 a.m. a 12:00 m. y de 2:00
            p.m. a 5:00 p.m.<br> <strong>Correo Electrónico para Notificaciones Judiciales:</strong><br> <a
              title="Pulse para enviar un correo electrónico a la entidad, abre el cliente de correo de su computador"
              href="mailto:notificacionesjudiciales@indesa.gov.co">notificacionesjudiciales@indesa.gov.co</a></p>
          <div class="text-center">
            <a href="https://www.indesa.gov.co/">
            <img width="250" height="96"
              src="https://www.indesa.gov.co/wp-content/uploads/2020/03/logo-nuevo-footer.png"
              class="aux-attachment aux-featured-image aux-attachment-id-1952" alt="logo nuevo footer" data-ratio="2.6"
              data-original-w="250">
            </a>
          </div>
        </div>
        <div class="col-md-4 col-sm-6">
          <div class="container text-center">
            <div class="row mb-5  pr-4">
              <div class="col-md-12  pr-4">
                <img width="100%" height="50"
                  src="https://www.indesa.gov.co/wp-content/uploads/2020/03/Logos-footer-alcaldia.png"
                  class="aux-attachment aux-featured-image aux-attachment-id-1953" alt="Logos footer alcaldia"
                  data-ratio="7" data-original-w="350">
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-md-12 pr-4">
                <img width="199" height="133"
                  src="https://www.indesa.gov.co/wp-content/uploads/2019/07/Logo-icontec-INDESA-NUEVO_Mesa-de-trabajo-1_Mesa-de-trabajo-1.png"
                  class="aux-attachment aux-featured-image aux-attachment-id-1648"
                  alt="Logo icontec INDESA NUEVO_Mesa de trabajo 1_Mesa de trabajo 1"
                  srcset="https://www.indesa.gov.co/wp-content/uploads/2019/07/Logo-icontec-INDESA-NUEVO_Mesa-de-trabajo-1_Mesa-de-trabajo-1-150x133.png 150w,https://www.indesa.gov.co/wp-content/uploads/2019/07/Logo-icontec-INDESA-NUEVO_Mesa-de-trabajo-1_Mesa-de-trabajo-1.png 199w"
                  data-ratio="1" data-original-w="199"
                 >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </footer>
</body>
</html>
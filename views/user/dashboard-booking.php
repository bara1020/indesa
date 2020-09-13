<?php
    require "../../admin/functions_register.php";
    require "../../admin/register-scheduler.php";
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

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
          <h1 class="card-title text-center text-secundary">Hola <?php echo $user['username'];?></h1>
          <h2 class="card-title text-center text-secundary">Aquí puedes reservar tu cita</h2>
          <form id="update-form" class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
            method="post" enctype="multipart/form-data">
            <input type="number" id="inputNit" class="form-control" autofocus value="<?php echo $nit; ?>" name="nit" style="display:none">
            <input type="type" id="dayupdate" class="form-control" autofocus value="<?php echo $day; ?>" name="day" style="display:none">
            <div class="form-label-group mt-3">
              <label for="dateQuoteUpdate">Fecha de reserva</label>
              <h5 id="labelDateQuoteUpdate" class="text-primary"></h5>
            <?php  
                if(isset($_SESSION['success'])){
                  if($_SESSION['success'] != ""){
                    echo '
                            <div class="alert alert-success" role="alert">
                                <strong>'.$_SESSION['success'].'</strong>
                            </div>';
                  }
                } else {
                  echo '<input id="dateQuoteUpdate" 
                              name="dateQuoteUpdate" 
                              type="date" 
                              class="form-control" 
                              placeholder="Fecha"
                              >
                      <span id="date-error-update" class="help-block text-danger "><?php echo $date_err; ?></span>

                      </div>
                      <hr>
                      <div class="form-label-group mt-3 ">
                          <p>Pulsa click sobre "Descargar Consentimiento" para descargar el consentimiento de bioseguridad el cual podras descargar y cargarlo diligenciado pulsando click sobre "Cargar Archivo ", o si lo deseas, puedes diligenciarlo directamente en el gimnasio. Rercuerda que es un requisito para poder ingresar al gimnasio</p>
                          <a class="btn btn-info btn-block" href="../../admin/download.php?file=Consentimiento_firmado.pdf">Descargar
                            Consentimiento</a>
                        </div>

                      <div class="form-label-group mt-3">
                          <label for="uploadedFile">Cargar Consentimiento Firmado</label>
                          <br>
                          <input style="width:100%" id="uploadedFile" type="file" name="uploadedFile"
                            placeholder="Selecciona un archivo" />
                          <br>
                          <span id="file_err" class="help-block text-danger "><?php echo $file_err; ?></span>
                        </div>
                      <hr>
                      <div class="form-label-group mt-4">
                        <h6 id="scheduler-title" style="display:none">Horarios disponibles</h6>
                        <div class="list-groupupdate">

                        </div>
                        <span id="scheduler_err" class="help-block text-danger "><?php echo $scheduler_err; ?></span>
                      </div>

                      <input type="text" id="dayupdate" class="form-control" autofocus value="<?php echo $day; ?>"
                        name="dayupdate" style="display:none">
                      <input type="text" id="schedulerFromupdate" class="form-control" autofocus
                        value="<?php echo $schedulerFrom; ?>" name="schedulerFromUpdate" style="display:none">
                      <input type="text" id="schedulerToupdate" class="form-control" autofocus value="<?php echo $schedulerTo; ?>"
                        name="schedulerToUpdate" style="display:none">
                      <input type="text" id="dateupdate" class="form-control" autofocus value="<?php echo $date; ?>"
                        name="dateupdate" style="display:none">';
                }
               ?>
            <hr>
            <button id="updatebooking" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit"
              value="update">Registrar
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<?php 
echo  '<script src="../../vendor/timepicker/wickedpicker.min.js"></script>';
include('../../includes/scripts.php');
echo '  <script src="../../js/dashboard-user.js"></script>';
echo '  <script src="../../js/register.js"></script>';
include('../../includes/footer.php');
?>
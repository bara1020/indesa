<?php
      require "../../admin/functions_register.php";
      require "../../admin/register-scheduler.php";
      require "../../admin/updateFile.php";
      require '../../admin/functions_register_form.php'; 

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
            <div class="card">
          <div class="card-body">
            <form id="update-form-consent" class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
              method="post" enctype="multipart/form-data">
              <input type="number" id="inputNit" class="form-control" autofocus value="<?php echo $nitConsent; ?>" name="nitConsent" style="display:none">
              <div class="form-label-group mt-3 ">
                  <p>Pulsa click sobre "Descargar Consentimiento" para descargar el consentimiento de bioseguridad el cual podras descargar y cargarlo diligenciado pulsando click sobre "Cargar Archivo ", o si lo deseas, puedes diligenciarlo directamente en el gimnasio. Rercuerda que es un requisito para poder ingresar al gimnasio</p>
                  <a class="btn btn-info btn-block" href="../../admin/download.php?file=Consentimiento_firmado.pdf">Descargar
                    Consentimiento</a>
              </div>
              <div class="form-label-group mt-3">
                  <label for="uploadedFile">Cargar Consentimiento</label>
                  <br>
                  <input style="width:100%" id="uploadedFile" type="file" name="uploadedFile"
                    placeholder="Selecciona un archivo" />
                  <br>
                  <span id="file_err" class="help-block text-danger "><?php echo $file_err; ?></span>
              </div>
              <hr>
              <div id="message-ok" class="alert alert-success" role="alert">
              </div>
              <button id="updateconsent" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit"
                value="updateconsent">Cargar consentimiento
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
          <div class="card">
            <div class="card-body">

            <?php
              if(isset($_SESSION["form-ok"])){
                echo '<div class="alert alert-info" role="alert">
                  <strong>Ya diligenciaste el formulario el día de hoy!</strong>
                </div>';
              }
            ?>
            <div id="response-message" class="alert alert-success" role="alert">
              <strong>Formulario de asistencia registrado correctamente!</strong>
            </div>
            
            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#insertModal" style="background-color:#D58402">
            Llenar Formulario de asistencia
          </button>
            </div>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
            <div class="card-body">
              <h1 class="card-title text-center text-secundary">Hola <?php echo $user['username'];?></h1>
              <h2 class="card-title text-center text-secundary">Aquí puedes reservar tu cita</h2>
              <div class="card">
            <div class="card-body">
            <form id="update-form" class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
              method="post" enctype="multipart/form-data">
              <input type="number" id="inputNit" class="form-control" autofocus value="<?php echo $nit; ?>" name="nit" style="display:none">
              <input type="type" id="dayupdate" class="form-control" autofocus value="<?php echo $day; ?>" name="day" style="display:none">
              <div class="form-label-group mt-3">
                <label for="dateQuoteUpdate">Fecha de reserva</label>
                <h5 id="labelDateQuoteUpdate" class="text-primary"></h5>
              <?php 
                
                if($user['aviable'] == 1 ){
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
                            <p>Recuerda diligenciar el formato de registro de asistencia para poder ingresar al gimnasio.</p>
                            <a class="btn btn-info btn-block" href=" https://docs.google.com/forms/d/e/1FAIpQLSffcwg7e776cRXt2LSUGs1LMbi1TQU1xuEKKDsg3IO56d9peQ/viewform">Diligenciar Formulario</a>
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
                          name="dateupdate" style="display:none">
                          <hr>
                          <button id="updatebooking" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit"
                            value="update">Registrar
                          </button>';
                    }
                  } else {
                    echo '<div class="alert alert-danger" role="alert">
                      <strong>Tu plan ha caducado, por lo que no puedes acceder a esta función.Te invitamos a acercate al área de recaudo para adquirir un nuevo plan.</strong>
                    </div>';
                  } 
                ?>
            </form>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>


  <!--BEGIN: Update Modal -->
  <div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="insertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="insertModalLabel">Formulario de registro de asistencia</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="asistencia-form" class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-label-group d-none">
              <input type="text" id="id-update" class="form-control" autofocus
                name="id" >
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($sexo_err)) ? 'has-error' : ''; ?>">
              <label for="sexo">Sexo</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="sexo" id="sexoM" value="Masculino">
                <label class="form-check-label" for="sexoM">
                  Masculino
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="sexo" id="sexoF" value="Femenino">
                <label class="form-check-label" for="sexoF">
                  Femenino
                </label>
              </div>
              <span id="sexo-error"class="help-block text-danger"><?php echo $sexo_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($fechaNacimiento_err)) ? 'has-error' : ''; ?>">
              <label for="fechaNacimiento">Fecha nacimiento</label>
              <input type="date" id="fechaNacimiento" class="form-control" autofocus
                value="<?php echo $fechaNacimiento; ?>" name="fechaNacimiento">
              <span id="fechaNacimiento-error" class="help-block text-danger"><?php echo $fechaNacimiento_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($nacionalidad_err)) ? 'has-error' : ''; ?>">
              <label for="nacionalidad">Nacionalidad</label>
              <input type="text" id="nacionalidad" class="form-control" autofocus
                value="<?php echo $nacionalidad; ?>" name="nacionalidad">
              <span id="nacionalidad-error" class="help-block text-danger"><?php echo $nacionalidad_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($telefono_err)) ? 'has-error' : ''; ?>">
              <label for="telefono">Teléfono fijo</label>
              <input type="number" id="telefono" class="form-control" autofocus
                value="<?php echo $telefono; ?>" name="telefono">
              <span id="telefono-error" class="help-block text-danger"><?php echo $telefono_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($direccion_err)) ? 'has-error' : ''; ?>">
              <label for="direccion">Dirección</label>
              <input type="text" id="direccion" class="form-control" autofocus
                value="<?php echo $direccion; ?>" name="direccion">
              <span id="direccion-error" class="help-block text-danger"><?php echo $direccion_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($departamento_err)) ? 'has-error' : ''; ?>">
              <label for="departamento">Departamento</label>
              <input type="text" id="departamento" class="form-control" autofocus
                value="<?php echo $departamento; ?>" name="departamento">
              <span id="departamento-error" class="help-block text-danger"><?php echo $departamento_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($municipio_err)) ? 'has-error' : ''; ?>">
              <label for="municipio">Municipio</label>
              <input type="text" id="municipio" class="form-control" autofocus
                value="<?php echo $municipio; ?>" name="municipio">
              <span id="municipio-error" class="help-block text-danger"><?php echo $municipio_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($contactoCovid_err)) ? 'has-error' : ''; ?>">
              <label for="contactoCovid">Tuvo contacto con alguien confirmado de COVID-19 *
              </label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="contactoCovid" id="contactoCovidS" value="Si">
                <label class="form-check-label" for="contactoCovidS">
                  Si
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="contactoCovid" id="contactoCovidN" value="No">
                <label class="form-check-label" for="contactoCovidN">
                  No
                </label>
              </div>
              <span id="contactoCovid-error"class="help-block text-danger"><?php echo $contactoCovid_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($contactoCovidSospechoso_err)) ? 'has-error' : ''; ?>">
              <label for="contactoCovidSospechoso">Tuvo contacto con alguien que sospechoso de COVID-19*
              </label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="contactoCovidSospechoso" id="contactoCovidSospechosoS" value="Si">
                <label class="form-check-label" for="contactoCovidSospechosoS">
                  Si
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="contactoCovidSospechoso" id="contactoCovidSospechosoN" value="No">
                <label class="form-check-label" for="contactoCovidSospechosoN">
                  No
                </label>
              </div>
              <span id="contactoCovidSospechoso-error"class="help-block text-danger"><?php echo $contactoCovidSospechoso_err; ?></span>
            </div>
            <hr>
            <h2>ANTECEDENTES MÉDICOS</h2>
            <div class="form-label-group mt-2 <?php echo (!empty($enfermedades_err)) ? 'has-error' : ''; ?>">
              <label for="enfermedades">Presenta alguna de las siguientes enfermedades *
  </label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="asma" value="asma">
                <label class="form-check-label" for="asma">
                  Asma
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="EnfermedadPulmonarCrónica" value="EnfermedadPulmonarCrónica">
                <label class="form-check-label" for="EnfermedadPulmonarCrónica">
                  Enfermedad pulmonar crónica
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="TrastornoNeurológicoCronico" value="TrastornoNeurológicoCronico">
                <label class="form-check-label" for="TrastornoNeurológicoCronico">
                Trastorno neurológico crónico
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="EnfermedadRenalCronica" value="EnfermedadRenalCronica">
                <label class="form-check-label" for="EnfermedadRenalCronica">
                Enfermedad renal crónica
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="EnfermedadHematológicaCronica" value="EnfermedadHematológicaCronica">
                <label class="form-check-label" for="EnfermedadHematológicaCronica">
                Enfermedad hematológica crónica
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="Diabetes" value="Diabetes">
                <label class="form-check-label" for="Diabetes">
                Diabetes
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="Obesidad" value="Obesidad">
                <label class="form-check-label" for="Obesidad">
                Obesidad
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="EnfermedadHepaticaCronica" value="EnfermedadHepaticaCronica">
                <label class="form-check-label" for="EnfermedadHepaticaCronica">
                Enfermedad hepática crónica
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="Tabaquismo" value="Tabaquismo">
                <label class="form-check-label" for="Tabaquismo">
                Tabaquismo
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="Alcoholismo" value="Alcoholismo">
                <label class="form-check-label" for="Alcoholismo">
                Alcoholismo
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="TrastornoReumatologico" value="TrastornoReumatologico">
                <label class="form-check-label" for="TrastornoReumatologico">
                Trastorno reumatológico
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="enfermedades" id="Ninguno" value="Ninguno">
                <label class="form-check-label" for="Ninguno">
                Ninguno
                </label>
              </div>
              <div class="form-check">
                <div class="row">
                  <div class="col-md-1">
                  <input class="form-check-input" type="radio" name="enfermedades" id="Otro" value="Otro">
                  <label class="form-check-label" for="Otro">
                  Otro
                  </label>
                  </div>
                  <div class="col-md-11">
                    <div class="form-label-group mt-2 <?php echo (!empty($otroDescripcion_err)) ? 'has-error' : ''; ?>">
                      <input type="text" id="otroDescripcion" class="form-control" autofocus
                        value="<?php echo $otroDescripcion; ?>" name="otroDescripcion" style="width:150px">
                      <span id="otroDescripcion-error" class="help-block text-danger"><?php echo $otroDescripcion_err; ?></span>
                    </div>
                  </div>
                </div>
              </div>
              <span id="enfermedades-error"class="help-block text-danger"><?php echo $enfermedades_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($embarazada_err)) ? 'has-error' : ''; ?>">
              <label for="embarazada">Esta embarazada?
  </label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="embarazada" id="embarazadaS" value="Si">
                <label class="form-check-label" for="embarazadaS">
                  Si
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="embarazada" id="embarazadaN" value="No">
                <label class="form-check-label" for="embarazadaN">
                  No
                </label>
              </div>
              <span id="embarazada-error"class="help-block text-danger"><?php echo $embarazada_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($semanasGestacion_err)) ? 'has-error' : ''; ?>">
              <label for="semanasGestacion">Cuantas semanas de Gestación</label>
              <input type="number" id="semanasGestacion" class="form-control" autofocus
                value="<?php echo $semanasGestacion; ?>" name="semanasGestacion">
              <span id="semanasGestacion-error" class="help-block text-danger"><?php echo $semanasGestacion_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($tomaMedicamentos_err)) ? 'has-error' : ''; ?>">
              <label for="tomaMedicamentos">Toma actualmente algún medicamento? *</label>
              <input type="text" id="tomaMedicamentos" class="form-control" autofocus
                value="<?php echo $tomaMedicamentos; ?>" name="tomaMedicamentos">
              <span id="tomaMedicamentos-error" class="help-block text-danger"><?php echo $tomaMedicamentos_err; ?></span>
            </div>
            <hr>
            <h2>ESTADO DE SALUD</h2>
            <div class="form-label-group mt-2 <?php echo (!empty($sintomas_err)) ? 'has-error' : ''; ?>">
              <label for="sintomas">Ha presentado alguno de los siguientes síntomas
  </label>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="Fiebre" id="Fiebre">
                  <label class="form-check-label" for="Fiebre">
                  Fiebre
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="TosSeca" id="Tos Seca">
                  <label class="form-check-label" for="TosSeca">
                  Tos seca
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="DificultadRespirar" id="Dificultad Respirar">
                  <label class="form-check-label" for="DificultadRespirar">
                  Dificultad para respirar
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="Taquipnea" id="Taquipnea">
                  <label class="form-check-label" for="Taquipnea">
                  Taquipnea
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="DolorGarganta" id="Dolor Garganta">
                  <label class="form-check-label" for="DolorGarganta">
                  Dolor de garganta
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="Escalofrios" id="Escalo Frios">
                  <label class="form-check-label" for="Escalofrios">
                  Escalofríos
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="Nauseas" id="Nauseas">
                  <label class="form-check-label" for="Nauseas">
                  Nauseas
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="Vomito" id="Vomito">
                  <label class="form-check-label" for="Vomito">
                  Vomito
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="DolorToracico" id="Dolor Toracico">
                  <label class="form-check-label" for="DolorToracico">
                  Dolor torácico
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="Mialgia" id="Mialgia">
                  <label class="form-check-label" for="Mialgia">
                  Mialgia
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="Diarrea" id="Diarrea">
                  <label class="form-check-label" for="Diarrea">
                  Diarrea
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="DolorAbdominal" id="Dolor Abdominal">
                  <label class="form-check-label" for="DolorAbdominal">
                  Dolor abdominal
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="DolorCabeza" id="Dolor Cabeza">
                  <label class="form-check-label" for="DolorCabeza">
                  Dolor de cabeza
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="MalestarGeneral" id="Malestar General">
                  <label class="form-check-label" for="MalestarGeneral">
                  Malestar general
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="sintomas" value="Ninguno" id="Ninguno">
                  <label class="form-check-label" for="Ninguno">
                  Ninguno
                  </label>
                </div>
                <div class="form-check">
                  <div class="row">
                    <div class="col-md-1">
                      <input class="form-check-input" type="checkbox" name="sintomas" value="OtroSintoma" id="Otro Sintoma">
                      <label class="form-check-label" for="OtroSintoma">
                      Otro
                      </label>
                    </div>
                    <div class="col-md-11">
                      <div class="form-label-group mt-2 <?php echo (!empty($otroSintoma_err)) ? 'has-error' : ''; ?>">
                        <input type="text" id="otroSintoma" class="form-control" autofocus
                          value="<?php echo $otroSintoma; ?>" name="otroSintoma" style="width:150px">
                        <span id="otroSintoma-error" class="help-block text-danger"><?php echo $otroSintoma_err; ?></span>
                      </div>
                    </div>
                  </div>
                </div>
              <span id="sintomas-error" class="help-block text-danger"><?php echo $sintomas_err; ?></span>

            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($temperatura_err)) ? 'has-error' : ''; ?> mb-4">
              <label for="temperatura">temperatura</label>
              <input type="number" id="temperatura" class="form-control" autofocus
                value="<?php echo $temperatura; ?>" name="temperatura">
              <span id="temperatura-error" class="help-block text-danger"><?php echo $temperatura_err; ?></span>
            </div>
            <div class="form-label-group mt-2 <?php echo (!empty($observaciones_err)) ? 'has-error' : ''; ?> mb-4">
              <label for="observaciones">observaciones</label>
              <input type="text" id="observaciones" class="form-control" autofocus
                value="<?php echo $observaciones; ?>" name="observaciones">
              <span id="observaciones-error" class="help-block text-danger"><?php echo $observaciones_err; ?></span>
            </div>
            <button id="insert-asistencia" class="btn btn-lg btn-primary btn-block text-uppercase" type="button" value="actualizar">Actualizar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--END: Update Modal -->

  <?php 
  echo  '<script src="../../vendor/timepicker/wickedpicker.min.js"></script>';
  include('../../includes/scripts.php');
  echo '  <script src="../../js/dashboard-user.js"></script>';
  echo '  <script src="../../js/register.js"></script>';
  include('../../includes/footer.php');
  ?>
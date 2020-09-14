Parte del formlario de registro que de quitó 
              <div class="form-label-group mt-3 <?php echo (!empty($phonenumber_err)) ? 'has-error' : ''; ?>">
                <label for="uploadedFile">Cargar Consentimiento Firmado</label>
                <br>
                <input style="width:100%" id="uploadedFile" type="file" name="uploadedFile"
                  placeholder="Selecciona un archivo" />
                <br>

                <span id="file_err" class="help-block text-danger "><?php echo $file_err; ?></span>
              </div>
              <div class="form-label-group mt-3">
                <label for="dateQuote">Fecha de reserva</label>
                <input id="dateQuote" name="dateQuote" type="date" class="form-control" placeholder="Fecha">
                <span id="date_err" class="help-block text-danger "><?php echo $date_err; ?></span>

              </div>
              <div class="form-label-group mt-4">
                <h6 id="scheduler-title" style="display:none">Horarios disponibles</h6>
                <div class="list-group">

                </div>
                <span id="scheduler_err" class="help-block text-danger "><?php echo $scheduler_err; ?></span>
              </div>

Formulario de update que se tenía:
<form id="update-form" class="form-signin" action="<?php echo htmlspecialchars('./admin/upload.php'); ?>"
              method="post" target="dummyframe">
              <input type="text" id="dayupdate" class="form-control" autofocus value="<?php echo $day; ?>" name="dayupdate"
                style="display:none">
              <input type="text" id="schedulerFromupdate" class="form-control" autofocus value="<?php echo $schedulerFrom; ?>"
                name="schedulerFromUpdate" style="display:none">
              <input type="text" id="schedulerToupdate" class="form-control" autofocus value="<?php echo $schedulerTo; ?>"
                name="schedulerToUpdate" style="display:none">
              <input type="text" id="dateupdate" class="form-control" autofocus value="<?php echo $date; ?>" name="dateupdate"
                style="display:none">
                <div class="form-label-group mt-3 <?php echo (!empty($nit_err)) ? 'has-error-update' : ''; ?>">
                  <label for="inputNit">Número de documento</label>
                  <input type="number" id="inputNit" class="form-control" autofocus value="<?php echo $nit; ?>" name="nit">
                  <span id="nit-error-update" class="help-block text-danger"></span>
                </div>
                <div class="form-label-group mt-3">
                <label for="dateQuote">Fecha de reserva</label>
                <input id="dateQuoteUpdate" name="dateQuoteUpdate" type="date" class="form-control" placeholder="Fecha">
                <span id="date-error-update" class="help-block text-danger "></span>

              </div>
              <div class="form-label-group mt-4">
                <h6 id="scheduler-title" style="display:none">Horarios disponibles</h6>
                <div class="list-group list-groupupdate">

                </div>
                <span id="scheduler-error-update" class="help-block text-danger "><?php echo $scheduler_err; ?></span>
              </div>
              <hr>
              <button id="update" class="btn btn-lg btn-primary btn-block text-uppercase" type="submit"
                value="update">Registrar
              </button>
              </form>
              




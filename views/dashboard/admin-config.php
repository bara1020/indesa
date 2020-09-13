<?php 
 require '../../admin/functions.php';
include('../../includes/header.php');
include('../../includes/navbar.php');
?>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Horarios</h4>
                    <form id="update-scheduler-form" class="form-signin"
                        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-label-group">
                            <h5 for="label-monday">Lunes</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Horario de la mañana</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-monday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-monday">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-monday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-monday">
                                        </div>
                                        <span id="monday_err"
                                            class="help-block text-danger"><?php echo $monday_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Horario de la tarde</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-monday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-monday-after">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-monday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-monday-after">
                                        </div>
                                        <span id="mondayafter_err"
                                            class="help-block text-danger"><?php echo $monday_err; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-label-group">
                            <h5 for="label-tuesday">Martes</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="label-tuesday">Horario de la mañana</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-tuesday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-tuesday">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-tuesday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-tuesday">
                                        </div>
                                        <span id="tuesday_err"
                                            class="help-block text-danger"><?php echo $tuesday_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="label-tuesday">Horario de la tarde</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-tuesday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-tuesday-after">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-tuesday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-tuesday-after">
                                        </div>
                                    </div>
                                    <span id="tuesdayafter_err"
                                        class="help-block text-danger"><?php echo $tuesday_err; ?></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-label-group">
                            <h5 for="label-webnesday">Miercoles</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="label-tuesday">Horario de la mañana</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-webnesday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-webnesday">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-webnesday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-webnesday">
                                        </div>
                                        <span id="webnesday_err"
                                            class="help-block text-danger"><?php echo $webnesday_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="label-tuesday">Horario de la tarde</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-webnesday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-webnesday-after">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-webnesday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-webnesday-after">
                                        </div>
                                        <span id="webnesdayafter_err"
                                            class="help-block text-danger"><?php echo $webnesdayafter_err; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-label-group">
                            <h5 for="label-thursday">Jueves</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="label-thursday">Horario de la mañana</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-thursday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-thursday">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-thursday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-thursday">
                                        </div>
                                        <span id="thursday_err"
                                            class="help-block text-danger"><?php echo $thursday_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="label-thursday">Horario de la tarde</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-thursday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-thursday-after">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-thursday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-thursday-after">
                                        </div>
                                        <span id="thursdayafter_err"
                                            class="help-block text-danger"><?php echo $thursdayafter_err; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-label-group">
                            <h5 for="label-friday">Viernes</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="label-friday">Horario de la mañana</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-friday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-friday">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-friday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-friday">
                                        </div>
                                        <span id="friday_err"
                                            class="help-block text-danger"><?php echo $friday_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="label-friday">Horario de la tarde</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-friday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-friday-after">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-friday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-friday-after">
                                        </div>
                                        <span id="fridayafter_err"
                                            class="help-block text-danger"><?php echo $fridayafter_err; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-label-group">
                            <h5 for="label-saturday">Sabado</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="label-saturday">Horario de la mañana</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-saturday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-saturday">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-saturday" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-saturday">
                                        </div>
                                        <span id="saturday_err"
                                            class="help-block text-danger"><?php echo $saturday_err; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="label-saturday">Horario de la tarde</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="time" id="from-saturday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="from-saturday-after">
                                        </div>
                                        <div class="col">
                                            <input type="time" id="to-saturday-after" class="form-control" autofocus
                                                value="<?php echo $id; ?>" name="to-saturday-after">
                                        </div>
                                        <span id="saturdayafter_err"
                                            class="help-block text-danger"><?php echo $saturdayafter_err; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-label-group">
                            <h5 for="label-sunday">Domingo</h5s>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="label-sunday">Horario de la mañana</label>
                                        <div class="row">
                                            <div class="col">
                                                <input type="time" id="from-sunday" class="form-control" autofocus
                                                    value="<?php echo $id; ?>" name="from-sunday">
                                            </div>
                                            <div class="col">
                                                <input type="time" id="to-sunday" class="form-control" autofocus
                                                    value="<?php echo $id; ?>" name="to-sunday">
                                            </div>
                                            <span id="sunday_err"
                                                class="help-block text-danger"><?php echo $sunday_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="label-sunday">Horario de la tarde</label>
                                        <div class="row">
                                            <div class="col">
                                                <input type="time" id="from-sunday-after" class="form-control" autofocus
                                                    value="<?php echo $id; ?>" name="from-sunday-after">
                                            </div>
                                            <div class="col">
                                                <input type="time" id="to-sunday-after" class="form-control" autofocus
                                                    value="<?php echo $id; ?>" name="to-sunday-after">
                                            </div>
                                            <span id="sundayafter_err"
                                                class="help-block text-danger"><?php echo $sundayafter_err; ?></span>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div id="alert-row-scheduler" class="row mt-4">
                            <div class="col-md-12">
                                <div class="alert alert-success" role="alert">
                                    <strong>Horario actualizado correctamente</strong>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button id="update-scheduler" class="btn btn-lg btn-primary btn-block text-uppercase"
                            type="submit" value="actualizar-scheduler">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="row mt-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Pico y cédula</h4>
                    <form id="update-pico-cedula-form" class="form-signin"
                        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-label-group <?php echo (!empty($mondayPicoCedula_err)) ? 'has-error' : ''; ?>">
                            <label for="label-monday">Lunes</label>
                            <input type="text" id="mondayPicoCedula" class="form-control" autofocus
                                value="<?php echo $mondayPicoCedula; ?>" name="mondayPicoCedula">
                            <span id="mondayPicoCedula-error"
                                class="help-block text-danger"><?php echo $mondayPicoCedula_err; ?></span>
                        </div>
                        <div
                            class="form-label-group <?php echo (!empty($tuesdayPicoCedula_err)) ? 'has-error' : ''; ?>">
                            <label for="label-tuesday">Martes</label>
                            <input type="text" id="tuesdayPicoCedula" class="form-control" autofocus
                                value="<?php echo $tuesdayPicoCedula; ?>" name="tuesdayPicoCedula">
                            <span id="tuesdayPicoCedula-error"
                                class="help-block text-danger"><?php echo $tuesdayPicoCedula_err; ?></span>
                        </div>
                        <div
                            class="form-label-group <?php echo (!empty($webnesdayPicoCedula_err)) ? 'has-error' : ''; ?>">
                            <label for="label-webnesday">Miercoles</label>
                            <input type="text" id="webnesdayPicoCedula" class="form-control" autofocus
                                value="<?php echo $webnesdayPicoCedula; ?>" name="webnesdayPicoCedula">
                            <span id="webnesdayPicoCedula-error"
                                class="help-block text-danger"><?php echo $webnesdayPicoCedula_err; ?></span>
                        </div>
                        <div
                            class="form-label-group <?php echo (!empty($thursdayPicoCedula_err)) ? 'has-error' : ''; ?>">
                            <label for="label-thursday">Jueves</label>
                            <input type="text" id="thursdayPicoCedula" class="form-control" autofocus
                                value="<?php echo $thursdayPicoCedula; ?>" name="thursdayPicoCedula">
                            <span id="thursdayPicoCedula-error"
                                class="help-block text-danger"><?php echo $thursdayPicoCedula_err; ?></span>
                        </div>
                        <div class="form-label-group <?php echo (!empty($fridayPicoCedula_err)) ? 'has-error' : ''; ?>">
                            <label for="label-friday">Viernes</label>
                            <input type="text" id="fridayPicoCedula" class="form-control" autofocus
                                value="<?php echo $fridayPicoCedula; ?>" name="fridayPicoCedula">
                            <span id="fridayPicoCedula-error"
                                class="help-block text-danger"><?php echo $fridayPicoCedula_err; ?></span>
                        </div>
                        <div
                            class="form-label-group <?php echo (!empty($saturdayPicoCedula_err)) ? 'has-error' : ''; ?>">
                            <label for="label-saturday">Sabado</label>
                            <input type="text" id="saturdayPicoCedula" class="form-control" autofocus
                                value="<?php echo $saturdayPicoCedula; ?>" name="saturdayPicoCedula">
                            <span id="saturdayPicoCedula-error"
                                class="help-block text-danger"><?php echo $saturdayPicoCedula_err; ?></span>
                        </div>
                        <div class="form-label-group <?php echo (!empty($sundayPicoCedula_err)) ? 'has-error' : ''; ?>">
                            <label for="label-sunday">Domingo</label>
                            <input type="text" id="sundayPicoCedula" class="form-control" autofocus
                                value="<?php echo $sundayPicoCedula; ?>" name="sundayPicoCedula">
                            <span id="sundayPicoCedula-error"
                                class="help-block text-danger"><?php echo $sundayPicoCedula_err; ?></span>
                        </div>
                        <div id="alert-row" class="row mt-4">
                            <div class="col-md-12">
                                <div class="alert alert-success" role="alert">
                                    <strong>Pico y cédula actualizado correctamente</strong>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button id="update-pico-cedula" class="btn btn-lg btn-primary btn-block text-uppercase"
                            type="submit" value="actualizar">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Limite de usuarios</h4>
                    <form id="update-limit-form" class="form-signin"
                        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-label-group">
                            <input type="number" id="userLimit" class="form-control" autofocus
                                value="<?php echo $userLimit; ?>" name="userLimit">
                        </div>
                        <div id="alert-row-user-limit" class="row mt-4">
                            <div class="col-md-12">
                                <div class="alert alert-success" role="alert">
                                    <strong>Limite de usuarios actualizado correctamente</strong>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button id="update-user-limit" class="btn btn-lg btn-primary btn-block text-uppercase"
                            type="submit" value="actualizar">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include('../../includes/scripts.php');
echo '  <script src="../../js/dashboard.js"></script>';

include('../../includes/footer.php');
?>
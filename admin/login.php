<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if($_SESSION['role'] == 'Usuario'){
        header("location: ../../views/user/dashboard-user.php");
    } else if($_SESSION['role'] == 'Entrenador'){
        
         header("location: ../../views/dashboard/admin-booking.php");
    } else{
            header("location: ../views/dashboard/admin-user.php");
       // header("location: ../../views/dashboard/admin-user.php");
    }
        exit;
}

 
// Include config file
require_once "config.php";
$pdo = getConnection();
// Define variables and initialize with empty values
$nit = $password = $param_nit ="";
$nit_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if nit is empty
    if(empty(trim($_POST["nit"]))){
        $nit_err = "Por favor ingresa el número de documento";
    } else{
        $nit = trim($_POST["nit"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor ingresa la contraseña";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($nit_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT u.id, u.nit, u.username, u.lastname, u.phonenumber, u.email, u.password, r.name as role, u.id_tiquetera, t.description_tiquetera, t.days, p.aviable_days, p.aviable
                ,p.date
                FROM users u
                INNER JOIN roles r
                on u.role = r.id
                LEFT JOIN tiqueteras t
                on u.id_tiquetera = t.ID
                LEFT JOIN purchases p
                ON u.id = p.ID_USER
                
                WHERE
                u.nit = :nit";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":nit", $param_nit, PDO::PARAM_STR);
           
            // Set parameters
            $param_nit = trim($_POST["nit"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if nit exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $nit = $row["nit"];
                        $role = $row["role"];
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            if (session_status() == PHP_SESSION_NONE) {
                                session_start();
                              }
                          
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["nit"] = $nit;  
                            $_SESSION["role"] = $role;                           
                            $_SESSION["user"] = $row;
                            getSchedulerDoed($id);
                            validateFormularioAsistencia($id);
                            if($role == 'Usuario'){
                                header("location: ../../views/user/dashboard-user.php");
                            } else if($role == 'Entrenador'){
                                 header("location: ../views/dashboard/admin-booking.php");
                             } else {
                                 if($_SESSION['loginType'] == 'user')
                                    header("location: ../dashboard/admin-user.php"); 
                                 else
                                    header("location: ../views/dashboard/admin-user.php");
                            }
                            // Redirect user to welcome page
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "El usuario y/o contraseña no son validos";
                        }
                    }
                } else{
                    // Display an error message if nit doesn't exist
                    $nit_err = "El usuario y/o contraseña no son validos";
                }
            } else{
                echo "Ocurrio un error inesperado. Por favor intentalo de nuevo.";
            }

            // Close statement
            unset($stmt);
        }
    }
    
    // Close connection
    unset($pdo);
}

function validateFormularioAsistencia($id){
    $pdo = getConnection();
    // Prepare a select statement
    $sql = "SELECT id FROM `attendance_registration` WHERE user_id = :id and currentDate >  CURDATE()";

    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Check if nit exists, if yes then verify password
            if($stmt->rowCount() > 0){
                $_SESSION["form-ok"] = true;
            }
        }
    }
}

function getSchedulerDoed($id){
   
    $pdo = getConnection();
    // Prepare a select statement
    $sql = "SELECT schedulerFrom, schedulerTo FROM booking WHERE userId = :id and date = :date";
    $nextDay = date('Y-m-d', strtotime(' +1 day'));
    //$nextDay = date("Y/m/d");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if($stmt = $pdo->prepare($sql)){
        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->bindParam(":date", $nextDay , PDO::PARAM_STR);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $stmt = $stmt->fetch();
            if($stmt['schedulerFrom'] != "")
                $_SESSION['success'] = 'Tienes la reserva el '. $nextDay . ' de '. $stmt['schedulerFrom'] . ' a ' . $stmt['schedulerTo']; 
        } else{
            echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
        }
    }
}


?>
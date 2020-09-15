<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if($_SESSION['role'] == 'Usuario')
        header("location: ../../views/user/dashboard-user.php");
    else
         header("location: ../views/dashboard/admin-user.php");
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
        $sql = "SELECT u.id, u.nit, u.username, u.lastname, u.phonenumber, u.email, u.password, r.name as role
                FROM users u, roles r
                WHERE u.role = r.id and nit = :nit";
        
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
                            if($role == 'Usuario'){
                                header("location: ../../views/user/dashboard-user.php");
                            } else {
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
?>
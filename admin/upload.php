<?php
// Include config file
require_once "config.php";
class Response {
    public $status = false;
    public $message = array();
}
 
// Define variables and initialize with empty values
	// Define variables and initialize with empty values
	$nit = "";
	$id ="";
    $message ="";
    $day = "";
    $schedulerFrom = "";
    $schedulerTo = "";
    $date = "";
    $success ="";
    $loader = false;
    $process = "";

	$nit_err = "";
    $date_err = "";
    $scheduler_err = "";
    
    $scheduler = getScheduler();
    
 


 // Validate password
function validateFields(){
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor ingrese el nombre.";     
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor ingresa la contraseña.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "La contraseña debe tener minimo 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor confirme la contraseña";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Las contraseñas no coinciden.";
        }
    }
}



function validarDisponibilidad(){
    $pdo = getConnection();
    $maxUser;

    // Prepare a select statement
    $sql = "SELECT user_limit FROM configuration";
            
    if($stmt = $pdo->prepare($sql)){

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $stmt  = $stmt ->fetch();
            $maxUser = $stmt['user_limit'];
        } else{
            echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
        }
    }

    // Prepare a select statement
    $sql = "SELECT count(date) as count FROM booking WHERE date = :date";
        
    if($stmtSelect = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmtSelect->bindParam(":date", $date, PDO::PARAM_STR);

        // Set parameters
        $param_nit = limpiarDatos($_POST["date"]);

        // Attempt to execute the prepared statement
        if($stmtSelect->execute()){
            $stmtSelect  = $stmtSelect ->fetch();
            $counter = $stmtSelect['count'];
            if($counter >=$user_limit){
                echo false;
            } else {
                echo true;
            }
        } else{
            echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
        }
    }
    unset($stmtSelect);
    
}

# Funcion para limpiar y convertir datos como espacios en blanco, barras y caracteres especiales en entidades HTML.
# Return: los datos limpios y convertidos en entidades HTML.
function limpiarDatos($datos){
	// Eliminamos los espacios en blanco al inicio y final de la cadena
	$datos = trim($datos);

	// Quitamos las barras / escapandolas con comillas
	$datos = stripslashes($datos);

	// Convertimos caracteres especiales en entidades HTML (&, "", '', <, >)
	$datos = htmlspecialchars($datos);
	return $datos;
}

function validateLenght($data, $lenght){
	if(strlen($data) > intval($lenght))
		return 0;
	return 1;
}


function validateEmail($email){
    $mail_correcto = 0;
    //compruebo unas cosas primeras
    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
       if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
          //miro si tiene caracter .
          if (substr_count($email,".")>= 1){
             //obtengo la terminacion del dominio
             $term_dom = substr(strrchr ($email, '.'),1);
             //compruebo que la terminación del dominio sea correcta
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
                //compruebo que lo de antes del dominio sea correcto
                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
                if ($caracter_ult != "@" && $caracter_ult != "."){
                   $mail_correcto = 1;
                }
             }
          }
       }
    }
    if ($mail_correcto)
       return 1;
    else
       return 0;
}


##Captura horarios scheduler
function getScheduler(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT id, day, restriction, schedulerTo, schedulerFrom FROM pico_cedula"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

	return $result;
}


?>
 
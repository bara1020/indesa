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
    $password = "";
    $confirm_password = "";
    $token = "";
	$nit_err = "";
    $password_err = "";
    $confirm_password_err = "";
    
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $loader = true;
    $pdo = getConnection();
	$response = new Response();
	$response->status = false;
	$token = limpiarDatos($_POST['token']);

    // Validate nit
    if(empty(limpiarDatos($_POST["nit"]))){
		$nit_err = "Por favor ingrese el número de documento.";
		array_push($response->message,array('id' => "nit-error", 'message' => $nit_err ));
    } else{
        // Prepare a select statement
        $sql = "SELECT id, token FROM users WHERE nit = :nit and token = :token";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":nit", $param_nit, PDO::PARAM_STR);
            $stmt->bindParam(":token", $token, PDO::PARAM_STR);

            // Set parameters
            $param_nit = limpiarDatos($_POST["nit"]);
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() != 1){
					$nit_err = "El número de documento no se encuentra registrado.";
					array_push($response->message,array('id' => "nit-error", 'message' => $nit_err ));
                } else if (validateLenght(limpiarDatos($_POST["nit"]),13) < 1){
					$nit_err = "El número de documento no es valido";    
					array_push($response->message,array('id' => "nit-error", 'message' => $nit_err ));
				} else if($stmt->rowCount() == 1){
					if($row = $stmt->fetch()){
						$nit = limpiarDatos($_POST["nit"]);
						$token = limpiarDatos($row["token"]);
						$id = limpiarDatos($row["id"]);
					}
                }
            } else{
                $message = "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
            }

            // Close statement
            unset($stmt);
        }
	}
	
     // Validate password
    if(!isset($_POST["password"])){
		$password_err = "Por favor ingresa la contraseña.";  
		array_push($response->message,array('id' => "password-error", 'message' => $password_err ));
    } else {
        if(empty(limpiarDatos($_POST["password"]))){
            $password_err = "Por favor ingresa la contraseña.";  
            array_push($response->message,array('id' => "password-error", 'message' => $password_err ));

        } else if(strlen(limpiarDatos($_POST["password"])) < 6){
            $password_err = "La contraseña debe tener minimo 6 caracteres.";
            array_push($response->message,array('id' => "password-error", 'message' => $password_err ));
        } else{
            $password = limpiarDatos($_POST["password"]);
        }
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
        
	// Check input errors before inserting in database

    if(empty($nit_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "UPDATE users set password = :password where id = :id ";
         
        if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);

			// Attempt to execute the prepared statement
            if($stmt->execute()){

                header("location: ../../views/user/login.php?success-password=true");
            } else{
                echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
            }
            // Close statement
            unset($stmt);

        }
    } else {
		json_encode($response);
    }
    // Close connection
	unset($pdo);
}


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
 
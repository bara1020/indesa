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
	$username = "";
	$lastname = "";
	$email = "";
	$phonenumber = "";
	$id ="";
    $message ="";
    $day = "";
    $schedulerFrom = "";
    $schedulerTo = "";
    $date = "";
    $success ="";
    $loader = false;
    $process = "";
    $password = "";
    $readedProtocol =false;
    $confirm_password = "";
    
	$nit_err = "";
	$username_err = "";
	$lastname_err = "";
	$email_err = "";
	$phonenumber_err = "";
    $file_err = "";
    $date_err = "";
    $scheduler_err = "";
    $readedProtocol_err = "";
    $password_err = "";
    $confirm_password_err = "";
    
    //$scheduler = getScheduler();
    
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $loader = true;
    $pdo = getConnection();
	$response = new Response();
    $response->status = false;
    $process = $_POST['process'];
    
    // validate file
    if(empty($_FILES['uploadedFile']['name'])){
        $file_err = "Debes cargar el archivo firmado";
    }

   /* if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK ) {
        // get details of the uploaded file
        $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
        $fileName = $_FILES['uploadedFile']['name'];
        $fileSize = $_FILES['uploadedFile']['size'];
        $fileType = $_FILES['uploadedFile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = array('jpg', 'png', 'pdf');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // directory in which the uploaded file will be moved
            $uploadFileDir = './uploaded_files/';
            $dest_path = $uploadFileDir . $newFileName;
            
            if(move_uploaded_file($fileTmpPath, $dest_path))
            {
            $message ='File is successfully uploaded.';
            }
            else
            {
            $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            }
        } else {
            $file_err = 'Solo se admiten archivo en formato jpg, png y pdf';
        }
    }*/

    // Validate nit
    if(empty(limpiarDatos($_POST["nit"]))){
		$nit_err = "Por favor ingrese el número de documento.";
		array_push($response->message,array('id' => "nit-error", 'message' => $nit_err ));
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE nit = :nit";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":nit", $param_nit, PDO::PARAM_STR);

            // Set parameters
            $param_nit = limpiarDatos($_POST["nit"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
					$nit_err = "El número de documento ya se encuentra registrado.";
					array_push($response->message,array('id' => "nit-error", 'message' => $nit_err ));
                } else if (validateLenght(limpiarDatos($_POST["nit"]),13) < 1){
					$nit_err = "El número de documento no es valido";    
					array_push($response->message,array('id' => "nit-error", 'message' => $nit_err ));
				} else{
                    $nit = limpiarDatos($_POST["nit"]);
                    $username = limpiarDatos($_POST["username"]);
                }
            } else{
                $message = "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
            }

            // Close statement
            unset($stmt);
        }
	}
	
	if(empty(limpiarDatos($_POST["username"]))){
		$username_err = "Por favor ingrese el nombre.";    
		array_push($response->message,array('id' => "username-error", 'message' => $username_err ));
    } else {
        $username = limpiarDatos($_POST["username"]);
	}
	
	if(empty(limpiarDatos($_POST["lastname"]))){
		$lastname_err = "Por favor ingrese el apellido.";    
		array_push($response->message,array('id' => "lastname-error", 'message' => $lastname_err ));
    } else {
        $lastname = limpiarDatos($_POST["lastname"]);
	}
	
	if(empty(limpiarDatos($_POST["email"]))){
		$email_err = "Por favor ingrese el email.";    
		array_push($response->message,array('id' => "email-error", 'message' => $email_err ));
    } else {
		if(validateEmail(limpiarDatos($_POST["email"]))){
			$email = limpiarDatos($_POST["email"]);
		} else {
			$email_err = "El email no es valido.";    
			array_push($response->message,array('id' => "email-error", 'message' => $email_err ));
		}
	}
	
	//validate phonenumber
	if(empty(limpiarDatos($_POST["phonenumber"]))){
		$phonenumber_err = "Por favor ingrese el número de teléfono.";    
		array_push($response->message,array('id' => "phonenumber-error", 'message' => $phonenumber_err ));
    } else if (validateLenght(limpiarDatos($_POST["phonenumber"]),10) < 1){
		$phonenumber_err = "El número no es valido";    
		array_push($response->message,array('id' => "phonenumber-error", 'message' => $phonenumber_err ));
	} else {
        $phonenumber = limpiarDatos($_POST["phonenumber"]);
    }


    //validate checkbox protocol
    if(!isset($_POST["readedProtocol"])) {
        $readedProtocol_err = "Debes confirmar la lectura del portocolo.";    
        array_push($response->message,array('id' => "readedProtocol-error", 'message' => $readedProtocol_err ));
    } else {
        $readedProtocol = true;
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
        
    /*//validate date
	if(empty(limpiarDatos($_POST["dateQuote"]))){
		$date_err = "Por favor selecciona la fecha de la reserva.";    
		array_push($response->message,array('id' => "date-error", 'message' => $date_err ));
    } else if (validateLenght(limpiarDatos($_POST["date"]),10) < 1){
		$date_err = "El número no es valido";    
		array_push($response->message,array('id' => "date-error", 'message' => $date_err ));
	} else {
        //validate date
        if(empty(limpiarDatos($_POST["schedulerTo"])) || empty(limpiarDatos($_POST["schedulerFrom"]))){
            $scheduler_err = "Por favor selecciona la hora de la reserva.";    
            array_push($response->message,array('id' => "scheduler-error", 'message' => $scheduler_err ));
        }
    }*/


    

	// Check input errors before inserting in database

    if(empty($nit_err) && empty($email_err) && empty($phonenumber_err) && empty($lastname_err) &&
       empty($readedProtocol_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (nit, password, username, lastname, email, phonenumber, role, consent) VALUES (:nit, :password , :username, :lastname, :email, :phonenumber, :role, :consent);";
         
        if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$param_username = $_POST["username"];
			$param_lastname = $_POST["lastname"];
            $param_email = $_POST["email"];
            $param_phonenumber = $_POST["phonenumber"];
            $param_role = '2';//Usuario
			$param_nit = $nit;

            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            $stmt->bindParam(":nit", $param_nit, PDO::PARAM_STR);
			$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":lastname", $param_lastname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":phonenumber", $param_phonenumber, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":role", $param_role, PDO::PARAM_STR);
            $stmt->bindParam(":consent", $readedProtocol, PDO::PARAM_STR);

			// Attempt to execute the prepared statement
            if($stmt->execute()){
				
				// Prepare a select statement
				$sql = "SELECT id, nit, password, username, lastname, email, phonenumber, role FROM users WHERE nit = :nit";
        
				if($stmtSelect = $pdo->prepare($sql)){
					// Bind variables to the prepared statement as parameters
					$stmtSelect->bindParam(":nit", $param_nit, PDO::PARAM_STR);
		
					// Set parameters
					$param_nit = limpiarDatos($_POST["nit"]);
		
					// Attempt to execute the prepared statement
					if($stmtSelect->execute()){
						$stmtSelect  = $stmtSelect ->fetch();
                        $id = $stmtSelect['id'];
                        session_start();
                        $_SESSION["user"] = $stmtSelect;
                        // Redirect user to welcome page
                        header("location: views/user/dashboard-user.php");
					} else{
						echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
					}
				}
              
                
                //echo " Registro satisfactorio. Te esperamos!";
            } else{
                echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
            }
            // Close statement
            unset($stmt);

            // Prepare an insert statement
            /*$sql = "INSERT INTO booking (day, schedulerFrom, schedulerTo, userId, date) VALUES (:day, :schedulerFrom, :schedulerTo, :userId, :date);";
            
            if($stmtBooking = $pdo->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $param_role = 2;

                $stmtBooking->bindParam(":day", $_POST["day"], PDO::PARAM_STR);
                $stmtBooking->bindParam(":schedulerFrom", $_POST["schedulerFrom"], PDO::PARAM_STR);
                $stmtBooking->bindParam(":schedulerTo", $_POST["schedulerTo"], PDO::PARAM_STR);
                $stmtBooking->bindParam(":userId", $id, PDO::PARAM_STR);
                $stmtBooking->bindParam(":date", $_POST["date"], PDO::PARAM_STR);

                // Attempt to execute the prepared statement
                if($stmtBooking->execute()){
                    $_FILES['uploadedFile']['name']  = "";
                    $day = "";
                    $schedulerFrom = "";
                    $schedulerTo = "";
                    $date = "";
                    $nit = "";
                    $username = "";
                    $lastname = "";
                    $email = "";
                    $phonenumber = "";
                    $id ="";
                    $success = "Preinscripción satisfactoria";
                    $loader = false;
                   // header("location: ./views/success.php");
                } else{
                    echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
                }
                // Close statement
                unset($stmtBooking);
            }*/
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
 
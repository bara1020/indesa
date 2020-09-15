<?php 
	require 'config.php';

	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	  }
	$security = 0;
	class Response {
		public $status = false;
		public $message = array();
	}
	if(isset($_SESSION["role"]) && $_SESSION["role"] == "Administrador"){
		$security = 1;
	} else {
		$response = new Response();
		$response->status = false;
		array_push($response->message,array('id' => "message-error", 'message' => "No tienes los permisos suficientes para ejecutar estar tarea"));
	}
	
	// Define variables and initialize with empty values
	$nit = "";
	$password = "";
	$confirm_password = "";
	$username = "";
	$lastname = "";
	$email = "";
	$phonenumber = "";
	$id ="";
	$role = "";
	$consent_path;

	$nit_err = "";
	$password_err = "";
	$confirm_password_err = "";
	$username_err = "";
	$lastname_err = "";
	$email_err = "";
	$phonenumber_err = "";
	$role_err = "";
	$file_err = "";

	
	$response = new Response();
	$response->status = false;
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$pdo = getConnection();

    // Validate id
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
                if($stmt->rowCount() != 1){
					$nit_err = "El número de documento no se encuentra en el sistema";
					array_push($response->message,array('id' => "nit-error", 'message' => $nit_err ));
                }
            } else{
                echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
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
	if(empty(limpiarDatos($_POST["phonenumber"]))){
		$phonenumber_err = "Por favor ingrese el número de teléfono.";    
		array_push($response->message,array('id' => "phonenumber-error", 'message' => $phonenumber_err ));
	} else if (validateLenght(limpiarDatos($_POST["phonenumber"]),10) < 1){
		$phonenumber_err = "El número no es valido";    
		array_push($response->message,array('id' => "phonenumber-error", 'message' => $phonenumber_err ));
	} else {
        $phonenumber = limpiarDatos($_POST["phonenumber"]);
    }

  
    // Check input errors before inserting in database
    if(empty($nit_err) && empty($password_err) && empty($confirm_password_err) && empty($phonenumber_err)){
		
		$uploadFile = uploadFiles();
		// Prepare an insert statement
		if($uploadFile != ""){
			if(isset($_POST["idRole"])){
				$param_role = $_POST["idRole"];
				$sql = "UPDATE users set  username = :username, lastname = :lastname, email = :email, phonenumber = :phonenumber, role = :roleId, estado = :estado, consent = :consent where id = :id";
			} else {
				$sql = "UPDATE users set  username = :username, lastname = :lastname, email = :email, phonenumber = :phonenumber, estado = 'Activo', consent = :consent where id = :id";
			}
		
		} else {
			if(isset($_POST["idRole"])){
				$param_role = $_POST["idRole"];
				$sql = "UPDATE users set  username = :username, lastname = :lastname, email = :email, phonenumber = :phonenumber, role = :roleId, estado = :estado where id = :id";
			} else {
				$sql = "UPDATE users set  username = :username, lastname = :lastname, email = :email, phonenumber = :phonenumber, estado = 'Activo' where id = :id";
			}
		} 
         
        if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$param_username = $_POST["username"];
			$param_lastname = $_POST["lastname"];
			$param_email = $_POST["email"];
			$param_estado = $_POST["estado"];
			
			
			$param_phonenumber = $_POST["phonenumber"];
			$param_id = $_POST["id"];
			$stmt->bindParam(":id", $param_id, PDO::PARAM_STR);
			$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":lastname", $param_lastname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
			$stmt->bindParam(":phonenumber", $param_phonenumber, PDO::PARAM_STR);
			$stmt->bindParam(":estado", $param_estado, PDO::PARAM_STR);
			if(isset($_POST["idRole"])){
				$stmt->bindParam(":roleId", intval($param_role), PDO::PARAM_INT);
			}

			if($uploadFile != "")
				$stmt->bindParam(":consent", $uploadFile, PDO::PARAM_STR);
			
            // Set parameters
            $param_nit = $_POST["nit"];
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
				// Redirect to login page
				$response->status = true;
				if(isset($_POST["idRole"])){
					$data = array(	"id" => $param_id,
									"nit" => $param_nit,
									"username" => $param_username,
									"lastname" => $param_lastname,
									"email" => $param_email,
									"phonenumber" => $param_phonenumber,
									"role" => $_POST['role'],
									"estado" => $_POST['estado'],
									"consent" => $uploadFile,
									"id_role" => $param_role,
								);
					
				} else {
					$data = array(	"id" => $param_id,
									"nit" => $param_nit,
									"username" => $param_username,
									"lastname" => $param_lastname,
									"consent" => $uploadFile,
									"email" => $param_email,
									"phonenumber" => $param_phonenumber,
								);
				}
				updateSession();
				array_push($response->message,array('id' => "response-message", 'message' => "El usuario se actualizó correctamente!", "data" => $data ));
				echo json_encode($response);
            } else{
                echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
            }

            // Close statement
            unset($stmt);
        }
    } else {
		echo json_encode($response);
    }
    // Close connection
	unset($pdo);
}


function uploadFiles(){
	if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK ) {
        // get details of the uploaded file
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = array('jpg', 'png', 'pdf');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // directory in which the uploaded file will be moved
            $uploadFileDir = '../uploaded_files/';
            $consent_path = $uploadFileDir . $newFileName;
            if(move_uploaded_file($fileTmpPath, $consent_path)){
            	return $consent_path;
            } else {
            	return "";
            }
        } else {
            $file_err = 'Solo se admiten archivo en formato jpg, png y pdf';
        }
	}
	return "";
}

# Funcion para comprobar la session del admin
function validateSession(){
	// Comprobamos si la session esta iniciada
	if (!isset($_SESSION['user'])) {
		header('Location: ' . RUTA);
	}
}

function validateLenght($data, $lenght){
	if(strlen($data) > intval($lenght))
		return 0;
	return 1;
}

# Funcion para cerrar la sesion abierta
function destroySession(){
	// Comprobamos si la session esta iniciada
	session_start();
	if (isset($_SESSION['user'])) {
		session_destroy();
		echo true;
	} else {
		echo false;
	}
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


function updateSession(){
	$user = $_SESSION['user'];
	$user['username'] = $_POST["username"];
	$user['lastname'] = $_POST["lastname"];
	$user['email'] = $_POST["email"];
	$user['phonenumber'] = $_POST["phonenumber"];
	$_SESSION['user'] = $user;
}


 ?>
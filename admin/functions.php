<?php 
	require 'config.php';
	session_start();
	$security = 0;
	if(isset($_SESSION["role"]) && ($_SESSION["role"] == "Administrador" || $_SESSION["role"] == "Usuario" || $_SESSION["role"] == "Recaudo" 
	|| $_SESSION["role"] == "Entrenador")){
		$security = 1;
	} else {
		$response = new Response();
		$response->status = false;
		array_push($response->message,array('id' => "message-error", 'message' => "No tienes los permisos suficientes para ejecutar estar tarea"));
	}
	
	if(isset($_POST["tag"])){
		$tag = $_POST['tag'];
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
	$idTiquetera ="";
	$role = "";
	$file = "";
	$descriptionTiqueteras = "";
	$daysTiqueteras = "";
	$formDiligenciado = "";

	$descriptionTiqueterasUpdate = "";
	$daysTiqueterasUpdate = "";

	$nit_err = "";
	$password_err = "";
	$confirm_password_err = "";
	$username_err = "";
	$lastname_err = "";
	$email_err = "";
	$phonenumber_err = "";
	$role_err = "";
	$file_err = "";
	$descriptionTiqueteras_err = "";
	$daysTiqueteras_err = "";
	$descriptionTiqueterasUpdate_err = "";
	$daysTiqueterasUpdate_err = "";
	$formDiligenciado_err = "";

	// Define variables and initialize with empty values
	$mondayPicoCedula = "";
	$tuesdayPicoCedula = "";
	$webnesdayPicoCedula = "";
	$thursdayPicoCedula = "";
	$fridayPicoCedula = "";
	$saturdayPicoCedula = "";
	$sundayPicoCedula = "";

	$mondayPicoCedula_err = "";
	$tuesdayPicoCedula_err = "";
	$webnesdayPicoCedula_err = "";
	$thursdayPicoCedula_err  = "";
	$fridayPicoCedula_err = "";
	$saturdayPicoCedula_err = "";
	$sundayPicoCedula_err = "";

	// Define variables and initialize with empty values
   $tomonday = "";
   $frommonday = "";
   $totuesday = "";
   $fromtuesday = "";
   $towebnesday = "";
   $fromwebnesday = "";
   $tothursday = "";
   $fromthursday = "";
   $tofriday = "";
   $fromfriday = "";
   $tosaturday = "";
   $fromsaturday = "";
   $tosunday = "";
   $fromsunday = "";

   $tomondayafter = "";
   $frommondayafter = "";
   $totuesdayafter = "";
   $fromtuesdayafter = "";
   $towebnesdayafter = "";
   $fromwebnesdayafter = "";
   $tothursdayafter = "";
   $fromthursdayafter = "";
   $tofridayafter = "";
   $fromfridayafter = "";
   $tosaturdayafter = "";
   $fromsaturdayafter = "";
   $tosundayafter = "";
   $fromsundayafter = "";

   $monday_err = "";
   $tuesday_err = "";
   $webnesday_err = "";
   $thursday_err = "";
   $friday_err = "";
   $saturday_err = "";
   $sunday_err = "";

   $mondayafter_err = "";
   $tuesdayafter_err = "";
   $webnesdayafter_err = "";
   $thursdayafter_err = "";
   $fridayafter_err = "";
   $saturdayafter_err = "";
   $sundayafter_err = "";

	// Define variables and initialize with empty values
   $userLimit = "";
   $userLimit_err = "";

	class Response {
		public $status = false;
		public $message = array();
	}

	if(isset($tag) && $tag !== '' && $security === 1){
		
		switch ($tag) {
			case 'destroySession':{
				destroySession();
				break;
			}
			case 'register':{
				registerUser();
				break;
			}
			case 'update':{
				updateUser();
				break;
			}
			case 'delete':{
				deleteUser();
				break;
			}
			case 'getUsers':{
				getUsers();
				break;
			}
			case 'getPicoCedula':{
				getPicoCedula();
				break;
			}
			case 'getConfiguration':{
				getConfiguration();
				break;
			}
			case 'updatePicoCedula':{
				updatePicoCedula();
				break;
			}
			case 'updateScheduler':{
				updateScheduler();
				break;
			}
			case 'updateUserLimit':{
				updateUserLimit();
				break;
			}
			case 'getFile':{
				getFile();
				break;
			}
			case 'getDateScheduler':{
				getDateScheduler();
				break;
			}
			case 'getTiqueteras':{
				getTiqueteras();
				break;
			}
			case 'registerTiquetera':{
				registerTiquetera();
				break;
			}
			case 'updateTiquetera':{
				updateTiquetera();
				break;
			}
			case 'deleteTiquetera':{
				deleteTiquetera();
				break;
			}
			case 'getBooking':{
				getBooking();
				break;
			}
			case 'validateFormularioAsistencia':{
				validateFormularioAsistencia();
				break;
			}
			default:
				break;
		}
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

	$datos = strip_tags($datos);
	return $datos;
}


#Función para registarar usuarios
function registerUser(){
	$pdo = getConnection();
	$response = new Response();
	$response->status = false;
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

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
	
	//validate role
	if(empty(limpiarDatos($_POST["role"]))){
		$role_err = "Por favor selecciona un rol";    
		array_push($response->message,array('id' => "role-error", 'message' => $role_err ));
    }else {
        $role = limpiarDatos($_POST["role"]);
    }

    // Validate password
	if(!isset($_POST['origin'])){	
		if(empty(limpiarDatos($_POST["password"]))){
			$password_err = "Por favor ingresa la contraseña.";  
			array_push($response->message,array('id' => "password-error", 'message' => $password_err ));

		} else if(strlen(limpiarDatos($_POST["password"])) < 6){
			$password_err = "La contraseña debe tener minimo 6 caracteres.";
			array_push($response->message,array('id' => "password-error", 'message' => $password_err ));
		} else{
			$password = limpiarDatos($_POST["password"]);
		}
		
		// Validate confirm password
		if(empty(limpiarDatos($_POST["confirm_password"]))){
			$confirm_password_err = "Por favor confirme la contraseña"; 
			array_push($response->message,array('id' => "confirm-password-error", 'message' => $confirm_password_err ));
		} else{
			$confirm_password = limpiarDatos($_POST["confirm_password"]);
			if(empty($password_err) && ($password != $confirm_password)){
				$confirm_password_err = "Las contraseñas no coinciden.";
				array_push($response->message,array('id' => "confirm-password-error", 'message' => $confirm_password_err ));
			}
		}
	}
	// Check input errors before inserting in database

	if(empty($nit_err) && empty($password_err) && empty($confirm_password_err)
	&& empty($email_err) && empty($phonenumber_err) && empty($lastname_err)){
        
		// Prepare an insert statement
		if(!isset($_POST['origin']))	
        	$sql = "INSERT INTO users (nit, username, lastname, email, phonenumber, role, token) VALUES (:nit, :password, :username, :lastname, :email, :phonenumber, :role, :token);";
		else 
			$sql = "INSERT INTO users (nit, username, lastname, email, phonenumber, role, token, id_tiquetera) VALUES (:nit, :username, :lastname, :email, :phonenumber, :role, :token, :id_tiquetera);";
			$token = openssl_random_pseudo_bytes(24);

			//Convert the binary data into hexadecimal representation.
			$token = bin2hex($token);
		
			if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$param_username = $_POST["username"];
			$param_lastname = $_POST["lastname"];
			$param_email = $_POST["email"];
			$param_phonenumber = $_POST["phonenumber"];
			$param_nit = $nit;
			$id_tiquetera = $_POST["id_tiquetera"];
			//TODO-> CAMBIAR PARA QUE CARGU EL ROLE DESDE EL FORMULARIO
			$param_role = $role;

			$stmt->bindParam(":nit", $param_nit, PDO::PARAM_STR);
			$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":lastname", $param_lastname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":phonenumber", $param_phonenumber, PDO::PARAM_STR);
            $stmt->bindParam(":role", $param_role, PDO::PARAM_STR);
			$stmt->bindParam(":token", $token, PDO::PARAM_STR);
			$stmt->bindParam(":id_tiquetera", $id_tiquetera, PDO::PARAM_STR);
			
			
			if(!isset($_POST['origin'])){	
				$stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
				$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
			}
			// Attempt to execute the prepared statement
            if($stmt->execute()){
				
				// Prepare a select statement
				$sql = "SELECT u.id, u.nit, u.username, u.lastname, u.phonenumber, u.email, u.password, r.name as role, r.id as id_role, u.consent, u.id_tiquetera
                FROM users u, roles r
                WHERE u.role = r.id and nit = :nit";
		
				$id_role;
				$consent;
				if($stmtSelect = $pdo->prepare($sql)){
					// Bind variables to the prepared statement as parameters
					$stmtSelect->bindParam(":nit", $param_nit, PDO::PARAM_STR);
		
					// Set parameters
					$param_nit = limpiarDatos($_POST["nit"]);
		
					// Attempt to execute the prepared statement
					if($stmtSelect->execute()){
						$stmtSelect  = $stmtSelect ->fetch();
						$id = $stmtSelect['id'];
						$id_role = $stmtSelect['id_role'];
						$consent= $stmtSelect['consent'];  
					} else{
						echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
					}
				}
				$response->status = true;
				$data = array(  "id" => $id,
					            "nit" => $param_nit,
								"username" => $param_username,
								"lastname" => $param_lastname,
								"email" => $param_email,
								"phonenumber" => $param_phonenumber,
								"role" => $param_role,
								"id_role" => $id_role,
								"consent" => $consent,
								"id_tiquetera" => $id_tiquetera,
								"estado" => 'Inactivo',
							);
							
				insertPurchase($id, $id_tiquetera);
				sendEmail($param_username,$param_email,'Por favor haz la asignación de tu contraseña', $token);
				array_push($response->message,array('id' => "response-message", 'message' => "El usuario se registró correctamente!", "data" => $data  ));
				echo json_encode($response);
            } else{
                echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
            }
            // Close statement
            unset($stmt);
             unset($stmtSelect);
        }
    } else {
		echo json_encode($response);
    }
    // Close connection
	unset($pdo);
	}
}

function insertPurchase($id, $id_tiquetera){
	$pdo = getConnection();
	// Prepare a select statement
	$sql = "SELECT days FROM tiqueteras WHERE id = :id_tiquetera";
try{
	if($stmtSelect = $pdo->prepare($sql)){
		// Bind variables to the prepared statement as parameters
		$stmtSelect->bindParam(":id_tiquetera", $id_tiquetera, PDO::PARAM_STR);
		// Attempt to execute the prepared statement
		if($stmtSelect->execute()){
			$stmtSelect  = $stmtSelect ->fetch();
			$sql = "INSERT INTO purchases (`ID_USER`, `AVIABLE_DAYS`, `AVIABLE`) VALUES (:idUser, :aviableDays, :aviable);";

			if($stmt = $pdo->prepare($sql)){
				// Bind variables to the prepared statement as parameters
				$param_aviable = 0;
		
				$stmt->bindParam(":idUser", $id, PDO::PARAM_STR);
				$stmt->bindParam(":aviableDays", $stmtSelect['days'], PDO::PARAM_STR);
				$stmt->bindParam(":aviable", $param_aviable, PDO::PARAM_STR);
				
				// Attempt to execute the prepared statement
				if($stmt->execute()){
					return true;
				} else {
					return false;
				}
			}
			unset($stmt);
		} else {
		    echo $stmtSelect;
		}
			unset($stmtSelect);
	}
} catch (Exception $e) {
    echo $e;
}
	 unset($pdo);
}

#Función para regitsar tiqueteras
function registerTiquetera(){
	$pdo = getConnection();
	$response = new Response();
	$response->status = false;
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

	
	if(empty(limpiarDatos($_POST["descriptionTiqueteras"]))){
		$descriptionTiqueteras_err = "Por favor ingrese la descrión de la tiquetera.";    
		array_push($response->message,array('id' => "descriptionTiqueteras-error", 'message' => $descriptionTiqueteras_err ));
    } else {
        $descriptionTiqueteras = limpiarDatos($_POST["descriptionTiqueteras"]);
	}
	
	if(empty(limpiarDatos($_POST["daysTiqueteras"]))){
		$daysTiqueteras_err = "Por favor ingrese el apellido.";    
		array_push($response->message,array('id' => "daysTiqueteras-error", 'message' => $daysTiqueteras_err ));
    } else {
        $daysTiqueteras = limpiarDatos($_POST["daysTiqueteras"]);
	}

	if(empty($descriptionTiqueteras_err) && empty($daysTiqueteras_err)){
        
		// Prepare an insert statement
			$sql = "INSERT INTO tiqueteras (DESCRIPTION_TIQUETERA, DAYS) VALUES (:descriptionTiqueteras, :daysTiqueteras);";
		
			if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$param_descriptionTiqueteras = $_POST["descriptionTiqueteras"];
			$param_daysTiqueteras = $_POST["daysTiqueteras"];

            $stmt->bindParam(":descriptionTiqueteras", $param_descriptionTiqueteras, PDO::PARAM_STR);
            $stmt->bindParam(":daysTiqueteras", $param_daysTiqueteras, PDO::PARAM_STR);
			
			// Attempt to execute the prepared statement
            if($stmt->execute()){
				
				// Prepare a select statement
				$sql = "SELECT id FROM tiqueteras where DESCRIPTION_TIQUETERA = :descriptionTiqueteras";
		
				if($stmtSelect = $pdo->prepare($sql)){
					// Bind variables to the prepared statement as parameters
					$stmtSelect->bindParam(":descriptionTiqueteras", $param_descriptionTiqueteras, PDO::PARAM_STR);
		
					// Attempt to execute the prepared statement
					if($stmtSelect->execute()){
						$stmtSelect  = $stmtSelect ->fetch();
						$idTiquetera = $stmtSelect['id'];
					} else{
						echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
					}
				}
				$response->status = true;
				$data = array(  "id" => $idTiquetera,
					            "description" => $param_descriptionTiqueteras,
								"days" => $param_daysTiqueteras,
							);
				
				array_push($response->message,array('id' => "response-message", 'message' => "El usuario se registró correctamente!", "data" => $data  ));
				echo json_encode($response);
            } else{
                echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
            }
            // Close statement
            unset($stmt);
             unset($stmtSelect);
        }
    } else {
		echo json_encode($response);
    }
    // Close connection
	unset($pdo);
	}
}

#Función para actualizar el usuario
function updateUser(){
	
	$pdo = getConnection();
	$response = new Response();
	$response->status = false;
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

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
        
		// Prepare an insert statement
		if(isset($_POST["idRole"])){
			$param_role = $_POST["idRole"];
			$sql = "UPDATE users set  username = :username, lastname = :lastname, email = :email, phonenumber = :phonenumber, role = :roleId, estado = :estado where id = :id";
		} else {
			$sql = "UPDATE users set  username = :username, lastname = :lastname, email = :email, phonenumber = :phonenumber where id = :id";
		} 
         
        if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$param_username = $_POST["username"];
			$param_lastname = $_POST["lastname"];
			$param_email = $_POST["email"];
			
			
			$param_phonenumber = $_POST["phonenumber"];
			$param_id = $_POST["id"];
			$stmt->bindParam(":id", $param_id, PDO::PARAM_STR);
			$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":lastname", $param_lastname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
			$stmt->bindParam(":phonenumber", $param_phonenumber, PDO::PARAM_STR);
			
			
			if(isset($_POST["idRole"])){
				$param_estado = $_POST["estado"];
				$stmt->bindParam(":estado", $param_estado, PDO::PARAM_STR);
				$stmt->bindParam(":roleId", intval($param_role), PDO::PARAM_INT);
			}

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
									"id_role" => $param_role,
								);
					
				} else {
					$data = array(	"id" => $param_id,
									"nit" => $param_nit,
									"username" => $param_username,
									"lastname" => $param_lastname,
									"email" => $param_email,
									"phonenumber" => $param_phonenumber,
								);
				}
				updateSession();
				//uploadFile();
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
}

function uploadFile(){
	if (isset($_POST['file'])) {
		echo $_POST['file']['name'];
		$fileTmpPath = $_POST['file']['tmp_name'];
        $fileName = $_POST['file']['name'];
        $fileSize = $_POST['file']['size'];
        $fileType = $_POST['file']['type'];
		$fileNameCmps = explode(".", $fileName);
		echo $fileType;
	/*if (isset($_POST['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK ) {
        // get details of the uploaded file
        $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
        $fileName = $_FILES['uploadedFile']['name'];
        $fileSize = $_FILES['uploadedFile']['size'];
        $fileType = $_FILES['uploadedFile']['type'];
        $fileNameCmps = explode(".", $fileName);*/
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = array('jpg', 'png', 'pdf');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // directory in which the uploaded file will be moved
            $uploadFileDir = '../uploaded_files/';
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
    }
}

function updateSession(){
	$user = $_SESSION['user'];
	$user['username'] = $_POST["username"];
	$user['lastname'] = $_POST["lastname"];
	$user['email'] = $_POST["email"];
	$user['phonenumber'] = $_POST["phonenumber"];
	$_SESSION['user'] = $user;
}



#Función para elminar la usuario
function deleteUser(){
	$pdo = getConnection();
	try {
			$sql = "DELETE FROM `users` WHERE id = :id";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(":id", $_POST['id'], PDO::PARAM_STR);
			if($stmt->execute()){
				echo "El usuario fue eliminado satisfactoriamente.";
			} else {
				echo "Lo sentimos, al parecer se presento un problema eliminar el usuario, intente nuevamente o contacte al administrador";
			}
		unset($stmt);
	} catch (Exception $e) {
		echo $e->getMessage();
	  }
	 unset($pdo);
}

##Captura todos los usuarios que se encuentran registrados en la plataforma
function getUsers(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT u.id, u.username, u.nit, u.lastname, u.email, u.phonenumber, r.id as id_role , r.name as role, u.estado, u.consent, id_tiquetera from users u, roles r where u.role = r.id order by created_at desc"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
	echo json_encode($result);
	 unset($stmt);
	 unset($pdo);
}

##Captura todos los usuarios que se encuentran registrados en la plataforma
function getBooking(){
	$pdo = getConnection();
	//$date = date('Y-m-d');
	$stmt = $pdo->prepare("SELECT b.id, b.day, b.schedulerFrom, b.schedulerTo, b.userId, b.date, b.currentdate, u.username, u.lastname, u.nit FROM booking b , users u WHERE b.userId = u.id ");
	
	//$stmt->bindParam(":date", $date, PDO::PARAM_STR);
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
	echo json_encode($result);
	 unset($stmt);
	 unset($pdo);
}

##Captura todas las tiquereteas registradas en la plataforma
function getTiqueteras(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT id, DESCRIPTION_TIQUETERA as description, days FROM tiqueteras"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
	 unset($stmt);
	  unset($pdo);
	echo json_encode($result);
}

#Función para actualizar el usuario
function updateTiquetera(){
	
	$pdo = getConnection();
	$response = new Response();
	$response->status = false;
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

	
	if(empty(limpiarDatos($_POST["descriptionTiqueteras"]))){
		$descriptionTiqueteras_err = "Por favor ingrese la descripción de la tiquetera.";    
		array_push($response->message,array('id' => "descriptionTiqueteras-error", 'message' => $descriptionTiqueteras_err ));
    } else {
        $descriptionTiqueteras = limpiarDatos($_POST["descriptionTiqueteras"]);
	}
	
	if(empty(limpiarDatos($_POST["daysTiqueteras"]))){
		$daysTiqueteras_err = "Por favor ingrese los días de la tiquera.";    
		array_push($response->message,array('id' => "daysTiqueteras-error", 'message' => $daysTiqueteras_err ));
    } else {
        $daysTiqueteras = limpiarDatos($_POST["daysTiqueteras"]);
	}
	
	
    // Check input errors before inserting in database
    if(empty($descriptionTiqueteras_err) && empty($daysTiqueteras_err)){
        
		// Prepare an insert statement
		$sql = "UPDATE tiqueteras set  DESCRIPTION_TIQUETERA = :descriptionTiqueteras, days = :days where id = :id";
         
        if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$param_description = $_POST["descriptionTiqueteras"];
			$param_days = $_POST["daysTiqueteras"];
			
			
			$param_id = $_POST["id"];
			$stmt->bindParam(":id", $param_id, PDO::PARAM_STR);
			$stmt->bindParam(":descriptionTiqueteras", $param_description, PDO::PARAM_STR);
            $stmt->bindParam(":days", $param_days, PDO::PARAM_STR);
			
			
            // Attempt to execute the prepared statement
            if($stmt->execute()){
				// Redirect to login page
				$response->status = true;
				$data = array(	"id" => $param_id,
								"description" => $param_description,
								"days" => $param_days,
							);
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
}

#Función para elminar la tiquetera
function deleteTiquetera(){
	$pdo = getConnection();
	try {
			$sql = "DELETE FROM `tiqueteras` WHERE id = :id";
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(":id", $_POST['id'], PDO::PARAM_STR);
			if($stmt->execute()){
				echo "La tiquerera fue eliminada satisfactoriamente.";
			} else {
				echo "Lo sentimos, al parecer se presento un problema eliminar la tiquetera, intente nuevamente o contacte al administrador";
			}
		 unset($stmt);
	} catch (Exception $e) {
		echo $e->getMessage();
	  }
	  unset($pdo);
}

##Captura configuración de días pico y cédula
function getPicoCedula(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT id, day, restriction, schedulerTo, schedulerFrom, schedulerToAfter, schedulerFromAfter FROM pico_cedula"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
     unset($stmt);
    unset($pdo);
	echo json_encode($result);
}

##Captura configuración de días pico y cédula
function getDateScheduler(){
	$pdo = getConnection();
	$sql = "SELECT id, day, restriction, schedulerTo, schedulerFrom FROM pico_cedula WHERE day = :day";
         echo 'day' . $_POST["day"];
        if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$stmt->bindParam(":day", $_POST["day"], PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

		}
	 unset($stmt);
	  unset($pdo);
	echo json_encode($result);
}

##Captura configuración de limite de usuarios
function getConfiguration(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT id, user_limit FROM configuration"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetch(PDO::FETCH_ASSOC); 
 unset($stmt);
  unset($pdo);
	echo json_encode($result);
}

#Función para actualizar el usuario
function updatePicoCedula(){
	$response = new Response();
	$response->status = false;

	$mondayPicoCedula = $_POST['mondayPicoCedula'];
	$sundayPicoCedula = $_POST['sundayPicoCedula'];
	$tuesdayPicoCedula = $_POST['tuesdayPicoCedula'];
	$saturdayPicoCedula = $_POST['saturdayPicoCedula'];
	$thursdayPicoCedula = $_POST['thursdayPicoCedula'];
	$webnesdayPicoCedula = $_POST['webnesdayPicoCedula'];
	$fridayPicoCedula = $_POST['fridayPicoCedula'];
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		

    /* Validate id
    if(empty(limpiarDatos($mondayPicoCedula))){
		$mondayPicoCedula_err = "Por favor ingrese el pico y cédula.";
		array_push($response->message,array('id' => "nit-error", 'message' => $mondayPicoCedula_err ));
	} else if(empty(limpiarDatos($tuesdayPicoCedula))){
		$tuesdayPicoCedula_err = "Por favor ingrese el pico y cédula.";
		array_push($response->message,array('id' => "nit-error", 'message' => $tuesdayPicoCedula_err ));
    } else if(empty(limpiarDatos($webnesdayPicoCedula))){
		$webnesdayPicoCedula_err = "Por favor ingrese el pico y cédula.";
		array_push($response->message,array('id' => "nit-error", 'message' => $webnesdayPicoCedula_err ));
    } else if(empty(limpiarDatos($thursdayPicoCedula))){
		$thursdayPicoCedula_err = "Por favor ingrese el pico y cédula.";
		array_push($response->message,array('id' => "nit-error", 'message' => $thursdayPicoCedula_err ));
    } else if(empty(limpiarDatos($fridayPicoCedula))){
		$fridayPicoCedula_err = "Por favor ingrese el pico y cédula.";
		array_push($response->message,array('id' => "nit-error", 'message' => $fridayPicoCedula_err ));
    } else if(empty(limpiarDatos($saturdayPicoCedula))){
		$saturdayPicoCedula_err = "Por favor ingrese el pico y cédula.";
		array_push($response->message,array('id' => "nit-error", 'message' => $saturdayPicoCedula_err ));
    } else if(empty(limpiarDatos($sundayPicoCedula))){
		$sundayPicoCedula_err = "Por favor ingrese el pico y cédula.";
		array_push($response->message,array('id' => "nit-error", 'message' => $sundayPicoCedula_err ));
    } */
	    // Check input errors before inserting in database
	if(empty($mondayPicoCedula_err) && empty($tuesdayPicoCedula_err) && empty($webnesdayPicoCedula_err) && empty($thursdayPicoCedula_err)
	&& empty($fridayPicoCedula_err) && empty($saturdayPicoCedula_err) && empty($sundayPicoCedula_err)){
        
		excecuteUpdatePicoCedula($mondayPicoCedula,"monday");
        excecuteUpdatePicoCedula($tuesdayPicoCedula,"tuesday");
        excecuteUpdatePicoCedula($webnesdayPicoCedula,"webnesday");
        excecuteUpdatePicoCedula($thursdayPicoCedula,"thursday");
        excecuteUpdatePicoCedula($fridayPicoCedula,"friday");
        excecuteUpdatePicoCedula($saturdayPicoCedula,"saturday");
        excecuteUpdatePicoCedula($sundayPicoCedula,"sunday");
		
		echo true;
        
    } else {
		echo json_encode($response);
    }
  
}
}

#Función para actualizar el usuario
function updateScheduler(){
	$response = new Response();
	$response->status = false;

	$tomonday = $_POST['to-monday'];
	$tosunday = $_POST['to-sunday'];
	$totuesday = $_POST['to-tuesday'];
	$tosaturday = $_POST['to-saturday'];
	$tothursday = $_POST['to-thursday'];
	$towebnesday = $_POST['to-webnesday'];
	$tofriday = $_POST['to-friday'];
	
	$frommonday = $_POST['from-monday'];
	$fromsunday = $_POST['from-sunday'];
	$fromtuesday = $_POST['from-tuesday'];
	$fromsaturday = $_POST['from-saturday'];
	$fromthursday = $_POST['from-thursday'];
	$fromwebnesday = $_POST['from-webnesday'];
	$fromfriday = $_POST['from-friday'];

	$tomondayafter = $_POST['to-monday-after'];
	$tosundayafter = $_POST['to-sunday-after'];
	$totuesdayafter = $_POST['to-tuesday-after'];
	$tosaturdayafter = $_POST['to-saturday-after'];
	$tothursdayafter = $_POST['to-thursday-after'];
	$towebnesdayafter = $_POST['to-webnesday-after'];
	$tofridayafter = $_POST['to-friday-after'];
	
	$frommondayafter = $_POST['from-monday-after'];
	$fromsundayafter = $_POST['from-sunday-after'];
	$fromtuesdayafter = $_POST['from-tuesday-after'];
	$fromsaturdayafter = $_POST['from-saturday-after'];
	$fromthursdayafter = $_POST['from-thursday-after'];
	$fromwebnesdayafter = $_POST['from-webnesday-after'];
	$fromfridayafter = $_POST['from-friday-after'];

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		

		
    if(empty(limpiarDatos($tomonday) || empty(limpiarDatos($frommonday)))){
		$monday_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "monday-error", 'message' => $monday_err ));
	} else if(empty(limpiarDatos($totuesday) || empty(limpiarDatos($fromtuesday)))){
		$tuesday_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "tuesday-error", 'message' => $tuesday_err ));
	} else if(empty(limpiarDatos($towebnesday) || empty(limpiarDatos($fromwebnesday)))){
		$webnesday_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "webnesday-error", 'message' => $webnesday_err ));
	} else if(empty(limpiarDatos($tothursday) || empty(limpiarDatos($fromthursday)))){
		$thursday_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "thursday-error", 'message' => $thursday_err ));
	} else if(empty(limpiarDatos($tofriday) || empty(limpiarDatos($fromfriday)))){
		$friday_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "friday-error", 'message' => $friday_err ));
	} else if(empty(limpiarDatos($tosaturday) || empty(limpiarDatos($fromsaturday)))){
		$saturday_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "saturday-error", 'message' => $saturday_err ));
	} else if(empty(limpiarDatos($tosunday) || empty(limpiarDatos($fromsunday)))){
		$sunday_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "sunday-error", 'message' => $sunday_err ));
	} 
	

	// validación horarios de la tarde
	if(empty(limpiarDatos($tomondayafter) || empty(limpiarDatos($frommondayafter)))){
		$mondayafter_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "mondayafter-error", 'message' => $mondayafter_err ));
	} else if(empty(limpiarDatos($totuesdayafter) || empty(limpiarDatos($fromtuesdayafter)))){
		$tuesdayafter_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "tuesdayafter-error", 'message' => $tuesdayafter_err ));
	} else if(empty(limpiarDatos($towebnesdayafter) || empty(limpiarDatos($fromwebnesdayafter)))){
		$webnesdayafter_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "webnesdayafter-error", 'message' => $webnesdayafter_err ));
	} else if(empty(limpiarDatos($tothursdayafter) || empty(limpiarDatos($fromthursdayafter)))){
		$thursdayafter_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "thursdayafter-error", 'message' => $thursdayafter_err ));
	} else if(empty(limpiarDatos($tofridayafter) || empty(limpiarDatos($fromfridayafter)))){
		$fridayafter_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "fridayafter-error", 'message' => $fridayafter_err ));
	} else if(empty(limpiarDatos($tosaturdayafter) || empty(limpiarDatos($fromsaturdayafter)))){
		$saturdayafter_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "saturdayafter-error", 'message' => $saturdayafter_err ));
	} else if(empty(limpiarDatos($tosundayafter) || empty(limpiarDatos($fromsundayafter)))){
		$sundayafter_err = "Por favor ingrese el horario.";
		array_push($response->message,array('id' => "sundayafter-error", 'message' => $sundayafter_err ));
    } 
	    // Check input errors before inserting in database
	if(empty($monday_err) && empty($tuesday_err) && empty($webnesday_err) && empty($thursday_err)
	&& empty($friday_err) && empty($saturday_err) && empty($sunday_err)){
		excecuteUpdateScheduler($tomonday,$frommonday,$tomondayafter,$frommondayafter,"monday");
        excecuteUpdateScheduler($totuesday,$fromtuesday,$totuesdayafter,$fromtuesdayafter,"tuesday");
        excecuteUpdateScheduler($towebnesday,$fromwebnesday,$towebnesdayafter,$fromwebnesdayafter,"webnesday");
        excecuteUpdateScheduler($tothursday,$fromthursday,$tothursdayafter,$fromthursdayafter,"thursday");
        excecuteUpdateScheduler($tofriday,$fromfriday,$tofridayafter,$fromfridayafter,"friday");
        excecuteUpdateScheduler($tosaturday,$fromsaturday,$tosaturdayafter,$fromsaturdayafter,"saturday");
        excecuteUpdateScheduler($tosunday,$fromsunday,$tosundayafter,$fromsundayafter,"sunday");
		echo true;
    } else {
		echo json_encode($response);
    }
  
}
}

#Función para actualizar el usuario
function updateUserLimit(){
	$response = new Response();
	$response->status = false;

	$userLimit = $_POST['userLimit'];

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

	if(empty(limpiarDatos($userLimit))){
		$userLimit_err = "Por favor ingrese número máximo de usuarios.";
		array_push($response->message,array('id' => "userLimit-error", 'message' => $sunday_err ));
    } 
	    // Check input errors before inserting in database
	if(empty($userLimit_err)){
		excecuteUpdateUserLimit($userLimit);
		echo true;
    } else {
		echo json_encode($response);
    }
  
}
}



## Acutualiza los horarios
function excecuteUpdateScheduler($schedulerTo,$schedulerFrom,$schedulerToAfter,$schedulerFromAfter,$day){
	$pdo = getConnection();
	$sql = "UPDATE pico_cedula set  schedulerTo = :schedulerTo, schedulerFrom = :schedulerFrom, schedulerToAfter = :schedulerToAfter, schedulerFromAfter = :schedulerFromAfter  where day = :day";
         
        if($stmt = $pdo->prepare($sql)){
			
			$stmt->bindParam(":schedulerTo", $schedulerTo, PDO::PARAM_STR);
			$stmt->bindParam(":schedulerFrom", $schedulerFrom, PDO::PARAM_STR);			
			$stmt->bindParam(":schedulerToAfter", $schedulerToAfter, PDO::PARAM_STR);
			$stmt->bindParam(":schedulerFromAfter", $schedulerFromAfter, PDO::PARAM_STR);

			$stmt->bindParam(":day", $day, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
				return 1;
            } else{
                return 0;
            }

            // Close statement
            unset($stmt);
        }
    unset($pdo);
}

## Acutualiza los horarios
function excecuteUpdateUserLimit($userLimit){
	$pdo = getConnection();
	$sql = "UPDATE configuration set  user_limit = :userLimit";
         
        if($stmt = $pdo->prepare($sql)){
			
			$stmt->bindParam(":userLimit", $userLimit, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
				return 1;
            } else{
                return 0;
            }

            // Close statement
            unset($stmt);
        }
 unset($pdo);
}

function excecuteUpdatePicoCedula($restriction,$day){
	$pdo = getConnection();
	$sql = "UPDATE pico_cedula set  restriction = :restriction where day = :day";
         
        if($stmt = $pdo->prepare($sql)){
			
			$stmt->bindParam(":restriction", $restriction, PDO::PARAM_STR);
			$stmt->bindParam(":day", $day, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
				return 1;
            } else{
                return 0;
            }

            // Close statement
            unset($stmt);
        }
     unset($pdo);
}

function getFile(){
	//$attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/indesa/uploaded_files/0f7cbb837cdae51a64d020ec95343411.png";
	$attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/indesa/img/Formulario_Indesa.pdf";
	//echo $attachment_location;
        if (file_exists($attachment_location)) {

            header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
            header("Cache-Control: public"); // needed for internet explorer
			//header("Content-Type: image/png");
			header("Content-Type: application/pdf; charset=utf-8");
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length:".filesize($attachment_location));
            header("Content-Disposition: attachment; filename=Formulario_Indesa.pdf");
			$file = base64_encode(bin2hex(readfile($attachment_location)));
			echo $file;
			
			//echo 'asdasd';
            die();        
        } else {
            die("Error: File not found.");
        } 
}

## E encarga de validar que el usuario haya llenado el formulario el día actual
function validateFormularioAsistencia(){
    $pdo = getConnection();
    // Prepare a select statement
    $sql = "SELECT id FROM `attendance_registration` WHERE user_id = :id and currentDate >  CURDATE()";
	
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":id", $_POST['id'], PDO::PARAM_STR);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Check if nit exists, if yes then verify password
            if($stmt->rowCount() > 0){
                echo 1;
			} else {
				echo 0;
			}
        }
    }
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

function sendEmail($name, $email, $message, $token){


	// Check for empty fields
	if(empty($name)      ||
	empty($email)     ||
	!filter_var($email,FILTER_VALIDATE_EMAIL))
	{
	echo "No arguments Provided!";
	return false;
	}
    
	$name = limpiarDatos($name);
	$email = limpiarDatos($email);
	$message = limpiarDatos($message);

	$myfile = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="width:100%;font-family:arial, helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">
	 <head> 
	  <meta charset="UTF-8"> 
	  <meta content="width=device-width, initial-scale=1" name="viewport"> 
	  <meta name="x-apple-disable-message-reformatting"> 
	  <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
	  <meta content="telephone=no" name="format-detection"> 
	  <title>Nuevo correo electrónico</title> 
	  <!--[if (mso 16)]>
		<style type="text/css">
		a {text-decoration: none;}
		</style>
		<![endif]--> 
	  <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--> 
	  <!--[if gte mso 9]>
	<xml>
		<o:OfficeDocumentSettings>
		<o:AllowPNG></o:AllowPNG>
		<o:PixelsPerInch>96</o:PixelsPerInch>
		</o:OfficeDocumentSettings>
	</xml>
	<![endif]--> 
	  <style type="text/css">
	#outlook a {
		padding:0;
	}
	.ExternalClass {
		width:100%;
	}
	.ExternalClass,
	.ExternalClass p,
	.ExternalClass span,
	.ExternalClass font,
	.ExternalClass td,
	.ExternalClass div {
		line-height:100%;
	}
	.es-button {
		mso-style-priority:100!important;
		text-decoration:none!important;
	}
	a[x-apple-data-detectors] {
		color:inherit!important;
		text-decoration:none!important;
		font-size:inherit!important;
		font-family:inherit!important;
		font-weight:inherit!important;
		line-height:inherit!important;
	}
	.es-desk-hidden {
		display:none;
		float:left;
		overflow:hidden;
		width:0;
		max-height:0;
		line-height:0;
		mso-hide:all;
	}
	@media only screen and (max-width:600px) {p, ul li, ol li, a { font-size:16px!important; line-height:150%!important } h1 { font-size:30px!important; text-align:center; line-height:120% } h2 { font-size:26px!important; text-align:center; line-height:120% } h3 { font-size:20px!important; text-align:center; line-height:120% } h1 a { font-size:30px!important } h2 a { font-size:26px!important } h3 a { font-size:20px!important } .es-menu td a { font-size:16px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:16px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:16px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class="gmail-fix"] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:block!important } a.es-button { font-size:20px!important; display:block!important; border-width:10px 0px 10px 0px!important } .es-btn-fw { border-width:10px 0px!important; text-align:center!important } .es-adaptive table, .es-btn-fw, .es-btn-fw-brdr, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0px!important } .es-m-p0r { padding-right:0px!important } .es-m-p0l { padding-left:0px!important } .es-m-p0t { padding-top:0px!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } tr.es-desk-hidden, td.es-desk-hidden, table.es-desk-hidden { width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } tr.es-desk-hidden { display:table-row!important } table.es-desk-hidden { display:table!important } td.es-desk-menu-hidden { display:table-cell!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } }
	</style> 
	 </head> 
	 <body style="width:100%;font-family:arial, "helvetica neue", helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0"> 
	  <div class="es-wrapper-color" style="background-color:#F6F6F6"> 
	   <!--[if gte mso 9]>
				<v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
					<v:fill type="tile" color="#f6f6f6"></v:fill>
				</v:background>
			<![endif]--> 
	   <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top"> 
		 <tr style="border-collapse:collapse"> 
		  <td valign="top" style="padding:0;Margin:0"> 
		   <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%"> 
			 <tr style="border-collapse:collapse"> 
			  <td align="center" style="padding:0;Margin:0"> 
			   <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center"> 
				 <tr style="border-collapse:collapse"> 
				  <td align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px"> 
				   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
					 <tr style="border-collapse:collapse"> 
					  <td class="es-m-p0r es-m-p20b" valign="top" align="center" style="padding:0;Margin:0;width:560px"> 
					   <table width="100%" cellspacing="0" cellpadding="0" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
						 <tr style="border-collapse:collapse"> 
						  <td align="center" style="padding:0;Margin:0;font-size:0px"><img class="adapt-img" src="https://ahfet.stripocdn.email/content/guids/CABINET_b1ebefbd9cd69fe74046e26760648f55/images/96951600572401695.jpg" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></td> 
						 </tr> 
					   </table></td> 
					 </tr> 
				   </table></td> 
				 </tr> 
				 <tr style="border-collapse:collapse"> 
				  <td align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px"> 
				   <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
					 <tr style="border-collapse:collapse"> 
					  <td align="center" valign="top" style="padding:0;Margin:0;width:560px"> 
					   <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
						 <tr style="border-collapse:collapse"> 
						  <td align="center" style="padding:0;Margin:0"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, "helvetica neue", helvetica, sans-serif;line-height:21px;color:#333333">Por favor pulsa click sobre el siguiente botón para terminar tu registro.<br></p></td> 
						 </tr> 
					   </table></td> 
					 </tr> 
				   </table></td> 
				 </tr> 
				 <tr style="border-collapse:collapse"> 
				  <td align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px"> 
				   <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
					 <tr style="border-collapse:collapse"> 
					  <td align="center" valign="top" style="padding:0;Margin:0;width:560px"> 
					   <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
						 <tr style="border-collapse:collapse"> 
						  <td align="center" style="padding:0;Margin:0"><span class="es-button-border" style="border-style:solid;border-color:#2CB543;background:#2CB543;border-width:0px 0px 2px 0px;display:inline-block;border-radius:30px;width:auto"><a href="http://registro.indesa.gov.co/views/user/register-password.php?token='. $token . '" class="es-button" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, "helvetica neue", helvetica, sans-serif;font-size:18px;color:#FFFFFF;border-style:solid;border-color:#31CB4B;border-width:10px 20px 10px 20px;display:inline-block;background:#31CB4B;border-radius:30px;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center; padding:20px">Registrar</a></span></td> 
						 </tr> 
					   </table></td> 
					 </tr> 
				   </table></td> 
				 </tr> 
				 <tr style="border-collapse:collapse"> 
				  <td align="left" style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px"> 
				   <!--[if mso]><table style="width:560px" cellpadding="0" cellspacing="0"><tr><td style="width:270px" valign="top"><![endif]--> 
				   <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left"> 
					 <tr style="border-collapse:collapse"> 
					  <td class="es-m-p20b" align="left" style="padding:0;Margin:0;width:270px"> 
					   <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
						 <tr style="border-collapse:collapse"> 
						  <td align="left" style="padding:0;Margin:0"><h3 style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:arial, "helvetica neue", helvetica, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333"><br>HORARIOS Y DIRECCIÓN</h3><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:12px;font-family:arial, "helvetica neue", helvetica, sans-serif;line-height:18px;color:#333333"><strong>Teléfono(s):&nbsp;</strong>314 685 80 09 – 321 255 80 29.<br><strong>Correo electrónico:&nbsp;</strong><a href="mailto:usuario@indesa.gov.co" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, "helvetica neue", helvetica, sans-serif;font-size:12px;text-decoration:underline;color:#2CB543">usuario@indesa.gov.co</a><br><strong>Dirección física:&nbsp;</strong>Calle 76 E Sur #46 B 82<br><strong>Horario de atención en oficinas:</strong><br>Lunes a jueves de 8:00 a.m. a 12:00 m. y de 2:00 p.m. a 6:00 p.m. Viernes de 8:00 a.m. a 12:00 m. y de 2:00 p.m. a 5:00 p.m.<br><strong>Correo Electrónico para Notificaciones Judiciales:</strong><br><a href="mailto:notificacionesjudiciales@indesa.gov.co" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, "helvetica neue", helvetica, sans-serif;font-size:12px;text-decoration:underline;color:#2CB543">notificacionesjudiciales@indesa.gov.co</a></p></td> 
						 </tr> 
					   </table></td> 
					 </tr> 
				   </table> 
				   <!--[if mso]></td><td style="width:20px"></td><td style="width:270px" valign="top"><![endif]--> 
				   <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right"> 
					 <tr style="border-collapse:collapse"> 
					  <td align="left" style="padding:0;Margin:0;width:270px"> 
					   <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px"> 
						 <tr style="border-collapse:collapse"> 
						  <td align="center" style="padding:0;Margin:0;font-size:0px"><img class="adapt-img" src="https://ahfet.stripocdn.email/content/guids/CABINET_b1ebefbd9cd69fe74046e26760648f55/images/43011600572857904.png" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic" width="270" height="39"></td> 
						 </tr> 
						 <tr style="border-collapse:collapse"> 
						  <td align="center" style="padding:0;Margin:0;font-size:0px"><img class="adapt-img" src="https://ahfet.stripocdn.email/content/guids/CABINET_b1ebefbd9cd69fe74046e26760648f55/images/75521600572914678.png" alt style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"></td> 
						 </tr> 
					   </table></td> 
					 </tr> 
				   </table> 
				   <!--[if mso]></td></tr></table><![endif]--></td> 
				 </tr> 
			   </table></td> 
			 </tr> 
		   </table></td> 
		 </tr> 
	   </table> 
	  </div>  
	 </body>
	</html>';

	// Creatfile_get_contents("email_template.html")e the email and send the message
	$to = $email; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
	$email_subject = "Asignación de contraseña";
	$email_body = $myfile;
	$headers = "From: actividadfisica@indesa.gov.co\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
	$headers .= "Reply-To:" . $email ."\r\n";   
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	mail($to,$email_subject,$email_body,$headers);
	fclose($myfile);
}



 ?>






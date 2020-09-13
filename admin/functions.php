<?php 
	require 'config.php';
	session_start();
	$security = 0;
	if(isset($_SESSION["role"]) && $_SESSION["role"] == "Administrador"){
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
	$role = "";

	$nit_err = "";
	$password_err = "";
	$confirm_password_err = "";
	$username_err = "";
	$lastname_err = "";
	$email_err = "";
	$phonenumber_err = "";
	$role_err = "";

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
	return $datos;
}


#Función para cargar las categorias disponibles
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
    
	// Check input errors before inserting in database

	if(empty($nit_err) && empty($password_err) && empty($confirm_password_err)
	&& empty($email_err) && empty($phonenumber_err) && empty($lastname_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (nit, password, username, lastname, email, phonenumber, role) VALUES (:nit, :password, :username, :lastname, :email, :phonenumber, :role);";
         
        if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
			$param_username = $_POST["username"];
			$param_lastname = $_POST["lastname"];
			$param_email = $_POST["email"];
			$param_phonenumber = $_POST["phonenumber"];
			$param_nit = $nit;
			//TODO-> CAMBIAR PARA QUE CARGU EL ROLE DESDE EL FORMULARIO
			$param_role = $role;

            $stmt->bindParam(":nit", $param_nit, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
			$stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":lastname", $param_lastname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":phonenumber", $param_phonenumber, PDO::PARAM_STR);
            $stmt->bindParam(":role", $param_role, PDO::PARAM_STR);

            // Set parameters
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
			// Attempt to execute the prepared statement
            if($stmt->execute()){
				
				// Prepare a select statement
				$sql = "SELECT id FROM users WHERE nit = :nit";
        
				if($stmtSelect = $pdo->prepare($sql)){
					// Bind variables to the prepared statement as parameters
					$stmtSelect->bindParam(":nit", $param_nit, PDO::PARAM_STR);
		
					// Set parameters
					$param_nit = limpiarDatos($_POST["nit"]);
		
					// Attempt to execute the prepared statement
					if($stmtSelect->execute()){
						$stmtSelect  = $stmtSelect ->fetch();
						$id = $stmtSelect['id'];
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
							);

				array_push($response->message,array('id' => "response-message", 'message' => "El usuario se registró correctamente!", "data" => $data  ));
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
			$sql = "UPDATE users set  username = :username, lastname = :lastname, email = :email, phonenumber = :phonenumber, role = :roleId where id = :id";
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
	} catch (Exception $e) {
		echo $e->getMessage();
	  }
}

##Captura todos los usuarios que se encuentran registrados en la plataforma
function getUsers(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT u.id, u.username, u.nit, u.lastname, u.email, u.phonenumber, r.id as id_role , r.name as role from users u, roles r where u.role = r.id order by created_at desc"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
	echo json_encode($result);
}



##Captura configuración de días pico y cédula
function getPicoCedula(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT id, day, restriction, schedulerTo, schedulerFrom, schedulerToAfter, schedulerFromAfter FROM pico_cedula"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

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
	
	echo json_encode($result);
}

##Captura configuración de limite de usuarios
function getConfiguration(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT id, user_limit FROM configuration"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetch(PDO::FETCH_ASSOC); 

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

 ?>






<?php 
	require 'config.php';

	if(isset($_POST["tag"])){
		$tag = $_POST['tag'];
	}
	

	// Define variables and initialize with empty values
   $userLimit = "";
   $userLimit_err = "";
   $file_err = "";
   
	if(isset($tag) && $tag !== ''){
		switch ($tag) {
			case 'getDateScheduler':{
				getDateScheduler();
				break;
			}
			case 'getCounterUser':{
				getCounterUser();
				break;
			}
			case 'insertScheduler':{
				insertScheduler();
				break;
			}
			case 'getConfiguration':{
				getConfiguration();
				break;
			}
			default:
				break;
		}
	}

	class Responses {
		public $status = false;
		public $message = array();
	}
	 

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
    
    $scheduler = getSchedulerD();


function validateLenghtR($data, $lenght){
	if(strlen($data) > intval($lenght))
		return 0;
	return 1;
}

function insertScheduler(){
	// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $loader = true;
    $pdo = getConnection();
	$response = new Responses();
	$response->status = false;
	$schedulerFrom = $_POST['schedulerFromUpdate'];
	$schedulerTo = $_POST['schedulerToUpdate'];

    // Validate nit
    if(empty(limpiarDatosR($_POST["nit"]))){
		$nit_err = "Por favor ingrese el número de documento.";
		array_push($response->message,array('id' => "nit-error-update", 'message' => $nit_err ));
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE nit = :nit";
       
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":nit", $param_nit, PDO::PARAM_STR);

            // Set parameters
            $param_nit = limpiarDatosR($_POST["nit"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $nit = limpiarDatosR($_POST["nit"]);
                } else {
                    $nit_err = "El número de documento no se encuentra registrado.";
					array_push($response->message,array('id' => "nit-error-update", 'message' => $nit_err ));
                }
            } else{
                $message = "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
            }

            // Close statement
            unset($stmt);
        }
	}
	
    //validate date
	if(empty(limpiarDatosR($_POST["dateQuoteUpdate"]))){
		$date_err = "Por favor selecciona la fecha de la reserva.";    
		array_push($response->message,array('id' => "date-error-update", 'message' => $date_err ));
    } else if (validateLenghtR(limpiarDatosR($_POST["dayupdate"]),10) < 1){
		$date_err = "El número no es valido";    
		array_push($response->message,array('id' => "date-error-update", 'message' => $date_err ));
	} else {
		//validate date
		if(empty(limpiarDatosR($_POST["schedulerToUpdate"])) || empty(limpiarDatosR($_POST["schedulerFromUpdate"]))){
			$scheduler_err = "Por favor selecciona la hora de la reserva.";    
			array_push($response->message,array('id' => "scheduler-error-update", 'message' => $scheduler_err ));
		}
	}


    
	
	// Check input errors before inserting in database

    if(empty($nit_err) && empty($date_err) && empty($scheduler_err)){
        
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE nit = :nit";

        if($stmtSelect = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmtSelect->bindParam(":nit", $param_nit, PDO::PARAM_STR);

            // Set parameters
            $param_nit = limpiarDatosR($_POST["nit"]);

            // Attempt to execute the prepared statement
            if($stmtSelect->execute()){
                $stmtSelect  = $stmtSelect ->fetch();
                $id = $stmtSelect['id'];
            } else{
                echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
            }
        }
              
        // Close statement
        unset($stmt);
            // Prepare an insert statement
            $sql = "INSERT INTO booking (day, schedulerFrom, schedulerTo, userId, date) VALUES (:day, :schedulerFrom, :schedulerTo, :userId, :date);";
            
            if($stmtBooking = $pdo->prepare($sql)){
                // Bind variables to the prepared statement as parameters
				$param_role = 2;
				$day = "";
				if($_POST['dayupdate'] == 'wednesday')
					$day = 'webnesday';	
				else 
					$day = $_POST['dayupdate'];
                $stmtBooking->bindParam(":day", $day, PDO::PARAM_STR);
                $stmtBooking->bindParam(":schedulerFrom", $_POST["schedulerFromUpdate"], PDO::PARAM_STR);
                $stmtBooking->bindParam(":schedulerTo", $_POST["schedulerToUpdate"], PDO::PARAM_STR);
                $stmtBooking->bindParam(":userId", $id, PDO::PARAM_STR);
                $stmtBooking->bindParam(":date", $_POST["dateupdate"], PDO::PARAM_STR);

				// Attempt to execute the prepared statement
                if($stmtBooking->execute()){
                    $day = "";
                    $schedulerFrom = "";
                    $schedulerTo = "";
                    $date = "";
                    $nit = "";
                    $id ="";
                    $success = "Preinscripción satisfactoria";
					$loader = false;
					$response->status = true;
					array_push($response->message,array('id' => "successupdate", 'message' => 'Inscripción satisfactoria'));
					echo json_encode($response);
                } else{
                    echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
                }
                // Close statement
                unset($stmtBooking);
            }
    } else {
		echo json_encode($response);
    }
    // Close connection
	unset($pdo);
}

}

function getConfiguration(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT booking_type FROM configuration"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetch(PDO::FETCH_ASSOC); 

	echo json_encode($result);
}

function limpiarDatosR($datos){
	// Eliminamos los espacios en blanco al inicio y final de la cadena
	$datos = trim($datos);

	// Quitamos las barras / escapandolas con comillas
	$datos = stripslashes($datos);

	// Convertimos caracteres especiales en entidades HTML (&, "", '', <, >)
	$datos = htmlspecialchars($datos);
	return $datos;
}

##Captura horarios scheduler
function getSchedulerD(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT id, day, restriction, schedulerTo, schedulerFrom FROM pico_cedula"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

	return $result;
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


##Captura configuración de limite de usuarios
function getUserMax(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT user_limit FROM configuration"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetch(PDO::FETCH_ASSOC); 
	return intval($result['user_limit']);
}

function getCounterUser($schedulerFrom,$day){
    $pdo = getConnection();
     // Prepare a select statement
     $sql = "SELECT count(date) as counte FROM booking WHERE date = :date AND schedulerFrom = :schedulerFrom and day =:day";

     if($stmtSelect = $pdo->prepare($sql)){
		 // Bind variables to the prepared statement as parameters
         $stmtSelect->bindParam(":date", $_POST['date'], PDO::PARAM_STR);
         $stmtSelect->bindParam(":schedulerFrom", $schedulerFrom, PDO::PARAM_STR);
         $stmtSelect->bindParam(":day", $day, PDO::PARAM_STR);
         // Attempt to execute the prepared statement
         if($stmtSelect->execute()){
			 $stmtSelect  = $stmtSelect ->fetch();
			 $userMax = getUserMax();
			return $userMax - intval($stmtSelect['counte']);
         } else{
             return "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
         }
     }
     unset($stmtSelect);
     
}



##Captura configuración de días pico y cédula
function getPicoCedula(){
	$pdo = getConnection();
	$stmt = $pdo->prepare("SELECT id, day, restriction, schedulerTo, schedulerFrom FROM pico_cedula"); 
	$stmt->execute();

	// set the resulting array to associative
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

	echo json_encode($result);
}

##Captura configuración de días pico y cédula
function getDateScheduler(){
	$pdo = getConnection();
	#Función para cargar cotizaciones perdientes
	$stmt  = $pdo->prepare('SELECT id, day, restriction, schedulerTo, schedulerFrom, schedulerToAfter, schedulerFromAfter FROM pico_cedula WHERE day = :day');
	$stmt->bindParam(":day", $_POST["day"], PDO::PARAM_STR);
	$stmt->execute(); 
	$stmt = $stmt->fetch(PDO::FETCH_ASSOC);
	if($stmt){
		$schedulerTo = $stmt['schedulerTo'];
		$to = intval(explode(":",$schedulerTo)[0]);
		$tomm = explode(":",$schedulerTo)[1];
		$schedulerFrom = $stmt['schedulerFrom'];
		$from = intval(explode(":",$schedulerFrom)[0]);
		$frommm = explode(":",$schedulerFrom)[1];

		//variables horario tarde
		$schedulerToAfter = $stmt['schedulerToAfter'];
		$toAfter = intval(explode(":",$schedulerToAfter)[0]);
		$tommAfter = explode(":",$schedulerToAfter)[1];
		$schedulerFromAfter = $stmt['schedulerFromAfter'];
		$fromAfter = intval(explode(":",$schedulerFromAfter)[0]);
		$frommmAfter = explode(":",$schedulerFromAfter)[1];

		$response = [];
		for($index = $from; $index < $to; $index++){
			$fromFormatted = formatHour($index) . ":" . $tomm;
			array_push($response,array(
							"schedulerFrom" => $fromFormatted,
							"schedulerTo" => (formatHour($index+1) . ":" . $frommm),
							"counter" => getCounterUser($fromFormatted,$stmt['day'])
							));
			
		}

		for($index = $fromAfter; $index < $toAfter; $index++){
			$fromFormatted = formatHour($index) . ":" . $tommAfter;
			array_push($response,array(
							"schedulerFrom" => $fromFormatted,
							"schedulerTo" => (formatHour($index+1) . ":" . $frommmAfter),
							"counter" => getCounterUser($fromFormatted,$stmt['day'])
							));
			
		}
		echo json_encode($response);
	} else {
		echo json_encode(array()) ;
	}
}

function formatHour($hour){
	if($hour < 10)
		return '0' . strval($hour);
	else 
		return strval($hour);
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



 ?>






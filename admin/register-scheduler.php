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
    $success = "";
    $loader = false;
    $dest_path = "";
    
    $file_err = "";
    $date_err = "";
    $scheduler_err = "";
    $nit_err = "";


    getSchedulerDoed();

    //
function getSchedulerDoed(){
    $pdo = getConnection();
    // Prepare a select statement
    $sql = "SELECT schedulerFrom, schedulerTo FROM booking WHERE userId = :id and date = :date";
    //$nextDay = date('Y-m-d', strtotime(' +1 day'));
    $nextDay = date("Y/m/d");
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $id = $_SESSION['id'];
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
    
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
      }
    $loader = true;
    $pdo = getConnection();
	$response = new Response();
    $response->status = false;
    $id = $_SESSION['user']['id'];
    

    if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK ) {
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
            $uploadFileDir = '../../uploaded_files/';
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

    $param_nit = limpiarDatos($_POST["nit"]);
	
    //validate date
	if(empty(limpiarDatos($_POST["dateQuoteUpdate"]))){
		$date_err = "Por favor selecciona la fecha de la reserva.";    
		array_push($response->message,array('id' => "date-error", 'message' => $date_err ));
    } else if (validateLenght(limpiarDatos($_POST["dateQuoteUpdate"]),10) < 1){
		$date_err = "El número no es valido";    
		array_push($response->message,array('id' => "date-error", 'message' => $date_err ));
	} else {
        //validate date
        if(empty(limpiarDatos($_POST["schedulerToUpdate"])) || empty(limpiarDatos($_POST["schedulerFromUpdate"]))){
            $scheduler_err = "Por favor selecciona la hora de la reserva.";    
            array_push($response->message,array('id' => "scheduler-error", 'message' => $scheduler_err ));
        }
    }


	// Check input errors before inserting in database

    if(empty($scheduler_err)){
        
        // Prepare an insert statement
        $aviableDays = intval($_SESSION['user']['aviable_days'])-1;
        $aviable = 1;
        if($aviableDays == 0)
            $aviable = 0;

        if($aviable == 0){
            $sql = "UPDATE users set consent = :consent, estado = :estado  WHERE id = :id";
        } else {
            $sql = "UPDATE users set consent = :consent  WHERE id = :id";
        }    
        if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
          
            $stmt->bindParam(":consent", $dest_path, PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);

            if($aviable == 0){
                $estado = 'Inactivo';
                $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
            }

			// Attempt to execute the prepared statement
            $stmt->execute();

            $sqlPurchases = "UPDATE purchases set aviable_days = :aviableDays, aviable = :aviable  WHERE id_user = :id";
         
            if($stmtPurchases = $pdo->prepare($sqlPurchases)){
                // Bind variables to the prepared statement as parameters
                
                $stmtPurchases->bindParam(":aviableDays", $aviableDays, PDO::PARAM_STR);
                $stmtPurchases->bindParam(":aviable", $aviable, PDO::PARAM_STR);
                $stmtPurchases->bindParam(":id", $id, PDO::PARAM_STR);
    
                // Attempt to execute the prepared statement
                $stmtPurchases->execute();
                unset($stmtBooking);
                // Prepare an insert statement
                $sqlInsert = "INSERT INTO booking (day, schedulerFrom, schedulerTo, userId, date) VALUES (:day, :schedulerFrom, :schedulerTo, :userId, :date);";
                
                if($stmtBooking = $pdo->prepare($sqlInsert)){
                    // Bind variables to the prepared statement as parameters
                    $param_role = 2;
    
                    $stmtBooking->bindParam(":day", $_POST["day"], PDO::PARAM_STR);
                    $stmtBooking->bindParam(":schedulerFrom", $_POST["schedulerFromUpdate"], PDO::PARAM_STR);
                    $stmtBooking->bindParam(":schedulerTo", $_POST["schedulerToUpdate"], PDO::PARAM_STR);
                    $stmtBooking->bindParam(":userId", $id, PDO::PARAM_STR);
                    $stmtBooking->bindParam(":date", $_POST["dateQuoteUpdate"], PDO::PARAM_STR);
    
                    // Attempt to execute the prepared statement
                    if($stmtBooking->execute()){
                        $_FILES['uploadedFile']['name']  = "";
                        $day = "";
                        $schedulerFrom = "";
                        $schedulerTo = "";
                        $date = "";
                        $_SESSION['success'] = 'Tu hora de reserva es de '. $_POST["schedulerFromUpdate"] . ' a ' . $_POST["schedulerToUpdate"];
                        $loader = false;
                        return false;
                    } else{
                        echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
                    }
                    // Close statement
                    unset($stmtBooking);
            }

            }
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
 
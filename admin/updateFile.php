<?php 

	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	  }
	$security = 0;
	class ResponseF {
		public $status = false;
		public $message = array();
	}
	if(isset($_SESSION["role"]) && $_SESSION["role"] == "Administrador"){
		$security = 1;
	} else {
		$response = new ResponseF();
		$response->status = false;
		array_push($response->message,array('id' => "message-error", 'message' => "No tienes los permisos suficientes para ejecutar estar tarea"));
	}
	
	// Define variables and initialize with empty values
	$nit = "";
	$consent_path = "";
	$file ="";
	$nit_err = "";
	$file_err = "";

	
	$response = new ResponseF();
	$response->status = false;
	
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$uploadFile = uploadFiles();
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
        $allowedfileExtensions = array('jpg', 'png', 'pdf', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // directory in which the uploaded file will be moved
            $uploadFileDir = '../uploaded_files/';
            $consent_path = $uploadFileDir . $newFileName;
            if(move_uploaded_file($fileTmpPath, $consent_path)){
            	updateConsent($consent_path);
            } else {
            	return "";
            }
        } else {
            $file_err = 'Solo se admiten archivo en formato jpg, png y pdf';
        }
	}
	return "";
}



function updateConsent($consent_path){
	require 'config.php';
	$pdo = getConnection();
    $sql = "UPDATE users SET `consent` = :consent where `nit` = :nit;";

	if($stmt = $pdo->prepare($sql)){
		// Bind variables to the prepared statement as parameters
		$param_aviable = 1;
		$nitConsent = $_SESSION['user']['nit'];
		$stmt->bindParam(":nit", $nitConsent, PDO::PARAM_STR);
		$stmt->bindParam(":consent", $consent_path, PDO::PARAM_STR);
		
		// Attempt to execute the prepared statement
		if($stmt->execute()){
			$response = new ResponseF();
			$response->status = true;
			array_push($response->message,array('id' => "message-ok", 'message' => "Consentimiento cargado correctamente"));
			echo json_encode($response);
		} else {
			$response = new ResponseF();
			$response->status = true;
			array_push($response->message,array('id' => "message-ok", 'message' => "Lo sentimos se presentó un error. Vuelve a intentarlo"));
			echo json_encode($response);
		}
	}
	unset($stmt);
		
	 unset($pdo);
}


 ?>
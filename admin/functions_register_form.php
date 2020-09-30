<?php

    if(isset($_POST["tag"])){
		$tag = $_POST['tag'];
	}
	

    // Define variables and initialize with empty values
   $userId = "";
   $fechaNacimiento = "";
   $sexo = "";
   $nacionalidad = "";
   $telefono = "";
   $direccion = "";
   $departamento = "";
   $municipio = "";
   $contactoCovid = "";
   $contactoCovidSospechoso = "";
   $enfermedades = "";
   $embarazada = "";
   $semanasGestacion = "";
   $observaciones  = "";
   $tomaMedicamentos  = "";
   $sintomas  = "";
   $temperatura = "";
   $otroDescripcion = "";
   $otroSintoma = "";


   $userLimit_err = "";
   $file_err = "";
   $sexo_err = "";
   $fechaNacimiento_err = "";
   $nacionalidad_err = "";
   $telefono_err = "";
   $direccion_err = "";
   $departamento_err = "";
   $municipio_err = "";
   $contactoCovid_err = "";
   $contactoCovidSospechoso_err = "";
   $enfermedades_err = "";
   $embarazada_err  = "";
   $semanasGestacion_err  = "";
   $tomaMedicamentos_err = "";
   $observaciones_err = "";
   $sintomas_err  = "";
   $temperatura_err = "";
   $otroDescripcion_err = "";
   $otroSintoma_err = "";
    
	if(isset($tag) && $tag !== ''){
		switch ($tag) {
			case 'saveForm':{
				saveForm();
				break;
            }
        }
    }

    class ResponseForm {
		public $status = false;
		public $message = array();
	}


#Función para regitsar tiqueteras
function saveForm(){
    require 'config.php';
	$pdo = getConnection();
	$response = new ResponseForm();
	$response->status = false;
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){

	if(empty(limpiarDatosForm($_POST["sexo"])) || ($_POST["sexo"] == 'undefined')){
		$sexo_err = "Por favor ingrese el género.";    
		array_push($response->message,array('id' => "sexo-error", 'message' => $sexo_err ));
    } else {
        $sexo = limpiarDatosForm($_POST["sexo"]);
    }
    
    if(empty(limpiarDatosForm($_POST["fechaNacimiento"]))){
		$fechaNacimiento_err = "Por favor ingrese la fecha de nacimiento.";    
		array_push($response->message,array('id' => "fechaNacimiento-error", 'message' => $fechaNacimiento_err ));
    } else {
        $fechaNacimiento = limpiarDatosForm($_POST["fechaNacimiento"]);
    }
    
    if(empty(limpiarDatosForm($_POST["nacionalidad"]))){
		$nacionalidad_err = "Por favor ingrese la nacionalidad.";    
		array_push($response->message,array('id' => "nacionalidad-error", 'message' => $nacionalidad_err ));
    } else {
        $nacionalidad = limpiarDatosForm($_POST["nacionalidad"]);
    }
    
    if(empty(limpiarDatosForm($_POST["telefono"]))){
		$telefono_err = "Por favor ingrese el número de teléfono.";    
		array_push($response->message,array('id' => "telefono-error", 'message' => $telefono_err ));
    } else {
        $telefono = limpiarDatosForm($_POST["telefono"]);
    }
   
    if(empty(limpiarDatosForm($_POST["direccion"]))){
		$direccion_err = "Por favor ingrese la dirección.";    
		array_push($response->message,array('id' => "direccion-error", 'message' => $direccion_err ));
    } else {
        $direccion = limpiarDatosForm($_POST["direccion"]);
    }

    if(empty(limpiarDatosForm($_POST["temperatura"]))){
		$temperatura_err = "Por favor ingrese la temperatura.";    
		array_push($response->message,array('id' => "temperatura-error", 'message' => $temperatura_err ));
    } else {
        $temperatura = limpiarDatosForm($_POST["temperatura"]);
    }
    
    if(empty(limpiarDatosForm($_POST["departamento"]))){
		$departamento_err = "Por favor ingrese el departamento.";    
		array_push($response->message,array('id' => "departamento-error", 'message' => $departamento_err ));
    } else {
        $departamento = limpiarDatosForm($_POST["departamento"]);
    }
    
    if(empty(limpiarDatosForm($_POST["municipio"]))){
		$municipio_err = "Por favor ingrese el municipio.";    
		array_push($response->message,array('id' => "municipio-error", 'message' => $municipio_err ));
    } else {
        $municipio = limpiarDatosForm($_POST["municipio"]);
    }
   
    if(empty(limpiarDatosForm($_POST["contactoCovid"]))  || ($_POST["contactoCovid"] == 'undefined')){
		$contactoCovid_err = "Por favor indique si tuvo contacto con personas con Covid 19.";    
		array_push($response->message,array('id' => "contactoCovid-error", 'message' => $contactoCovid_err ));
    } else {
        $contactoCovid = limpiarDatosForm($_POST["contactoCovid"]);
    }
    
    if(empty(limpiarDatosForm($_POST["contactoCovidSospechoso"])) || ($_POST["contactoCovidSospechoso"] == 'undefined')){
		$contactoCovidSospechoso_err = "Por favor ingrese si tuvo contacto con personas sospechosas con Covid 19.";    
		array_push($response->message,array('id' => "contactoCovidSospechoso-error", 'message' => $contactoCovidSospechoso_err ));
    } else {
        $contactoCovidSospechoso = limpiarDatosForm($_POST["contactoCovidSospechoso"]);
    }
    
    if(empty(limpiarDatosForm($_POST["enfermedades"])) || ($_POST["enfermedades"] == 'undefined')){
		$enfermedades_err = "Por favor ingrese si padese de alguna enfermedad.";    
		array_push($response->message,array('id' => "enfermedades-error", 'message' => $enfermedades_err ));
    } else {
        $enfermedades = limpiarDatosForm($_POST["enfermedades"]);
        if ($enfermedades === 'Otro'){
            if(empty(limpiarDatosForm($_POST["otroDescripcion"])) || ($_POST["otroDescripcion"] == 'undefined')){
                $otroDescripcion_err = "Por favor ingresa la descripción de la otra enfermedad.";    
                array_push($response->message,array('id' => "otroDescripcion-error", 'message' => $otroDescripcion_err ));
            } else {
                $otroDescripcion = limpiarDatosForm($_POST["otroDescripcion"]);
            }
        }
    }
    
    if(empty(limpiarDatosForm($_POST["embarazada"])) || ($_POST["embarazada"] == 'undefined')){
		$embarazada_err = "Por favor indique si está embarazada.";    
		array_push($response->message,array('id' => "embarazada-error", 'message' => $embarazada_err ));
    } else {
        $embarazada = limpiarDatosForm($_POST["embarazada"]);
    }
   
    
    if(empty(limpiarDatosForm($_POST["tomaMedicamentos"]))){
		$tomaMedicamentos_err = "Por favor indique si consume algún medicamento.";    
		array_push($response->message,array('id' => "tomaMedicamentos-error", 'message' => $tomaMedicamentos_err ));
    } else {
        $tomaMedicamentos = limpiarDatosForm($_POST["tomaMedicamentos"]);
    }

    if(empty(limpiarDatosForm($_POST["sintomas"]))){
		$sintomas_err = "Por favor indique si tiene algun sintoma.";    
		array_push($response->message,array('id' => "sintomas-error", 'message' => $sintomas_err ));
    } else {
        $sintomas = limpiarDatosForm($_POST["sintomas"]);

        $array =  explode(",", $sintomas);

        for($ind = 0; $ind < count($array); $ind ++){
            if ($array[$ind] === "Otro Sintoma"){
                if(empty(limpiarDatosForm($_POST["otroSintoma"])) || ($_POST["otroSintoma"] == 'undefined')){
                    $otroSintoma_err = "Por favor ingresa la descripción del otro sintoma.";    
                    array_push($response->message,array('id' => "otroSintoma-error", 'message' => $otroSintoma_err ));
                } else {
                    $otroSintoma = limpiarDatosForm($_POST["otroSintoma"]);
                }
            }
        }
    }

	if( empty($sexo_err) &&
        empty($fechaNacimiento_err) &&
        empty($nacionalidad_err) &&
        empty($telefono_err) &&
        empty($direccion_err) &&
        empty($departamento_err) &&
        empty($municipio_err) &&
        empty($contactoCovid_err) &&
        empty($contactoCovidSospechoso_err) &&
        empty($enfermedades_err) &&
        empty($otroDescripcion_err) &&
        empty($otroSintoma_err) &&
        empty($embarazada_err) &&
        empty($semanasGestacion_err) &&
        empty($tomaMedicamentos_err) &&
        empty($sintomas_err) &&
        empty($temperatura_err)
        ){
    
		// Prepare an insert statement
			$sql = "INSERT INTO attendance_registration(sexo, fecha_nacimiento, nacionalidad, telefono, direccion, departamento, municipio, contacto_covid, enfermedades, embarazada, semanas_gestacion, toma_medicamentos, sintomas, observaciones, user_id, temperatura, OtraEnfermedad, OtroSintoma)
                     VALUES (:sexo, :fecha_nacimiento, :nacionalidad, :telefono, :direccion, :departamento, :municipio, :contacto_covid, :enfermedades, :embarazada, :semanas_gestacion, :toma_medicamentos, :sintomas, :observaciones, :userId, :temperatura, :OtraEnfermedad, :otroSintoma);";
		try{
			if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters

            $stmt->bindParam(":sexo", $sexo, PDO::PARAM_STR);
            $stmt->bindParam(":fecha_nacimiento", $fechaNacimiento, PDO::PARAM_STR);
            $stmt->bindParam(":nacionalidad", $nacionalidad, PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $telefono, PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $direccion, PDO::PARAM_STR);
            $stmt->bindParam(":departamento", $departamento, PDO::PARAM_STR);
            $stmt->bindParam(":municipio", $municipio, PDO::PARAM_STR);
            $stmt->bindParam(":contacto_covid", $contactoCovid, PDO::PARAM_STR);
            $stmt->bindParam(":enfermedades", $enfermedades, PDO::PARAM_STR);
            $stmt->bindParam(":OtraEnfermedad", $otroDescripcion, PDO::PARAM_STR);
            $stmt->bindParam(":embarazada", $embarazada, PDO::PARAM_STR);
            $stmt->bindParam(":semanas_gestacion", $semanasGestacion, PDO::PARAM_STR);
            $stmt->bindParam(":toma_medicamentos", $tomaMedicamentos, PDO::PARAM_STR);
            $stmt->bindParam(":sintomas", $sintomas, PDO::PARAM_STR);
            $stmt->bindParam(":otroSintoma", $otroSintoma, PDO::PARAM_STR);
            $stmt->bindParam(":temperatura", $temperatura, PDO::PARAM_STR);
            $stmt->bindParam(":observaciones", $_POST['observaciones'], PDO::PARAM_STR);
            
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
              }
            $stmt->bindParam(":userId", $_SESSION["id"], PDO::PARAM_STR);
			// Attempt to execute the prepared statement

            if($stmt->execute()){
                $response->status = true;
                $_SESSION["form-ok"] = true;
				array_push($response->message,array('id' => "response-message", 'message' => "Formulario de asistencia registrado correctamente!" ));
				echo json_encode($response);
            } else{
                echo "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
            }
            // Close statement
            unset($stmt);
            
        }
    } catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

    } else {
		echo json_encode($response);
    }
    // Close connection
	unset($pdo);
	}
}


function limpiarDatosForm($datos){
	// Eliminamos los espacios en blanco al inicio y final de la cadena
	$datos = trim($datos);

	// Quitamos las barras / escapandolas con comillas
	$datos = stripslashes($datos);

	// Convertimos caracteres especiales en entidades HTML (&, "", '', <, >)
	$datos = htmlspecialchars($datos);
	return $datos;
}

?>
            

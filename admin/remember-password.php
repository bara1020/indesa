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
	$email = "";
	$id ="";
    $message ="";
    $success ="";
    $name ="";
    
	$nit_err = "";
	$email_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $loader = true;
    $pdo = getConnection();
	$response = new Response();
    $response->status = false;
    

    // Validate nit
    if(empty(limpiarDatos($_POST["nit"]))){
		$nit_err = "Por favor ingrese el número de documento.";
		array_push($response->message,array('id' => "nit-error", 'message' => $nit_err ));
    } else{
        // Prepare a select statement
        $sql = "SELECT id, email, username FROM users WHERE nit = :nit";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":nit", $param_nit, PDO::PARAM_STR);

            // Set parameters
            $param_nit = limpiarDatos($_POST["nit"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $row = $stmt->fetch();
                    $email = $row['email'];
                    $name = $row['username'];
                    $id = $row['id'];
                } else if (validateLenght(limpiarDatos($_POST["nit"]),13) < 1){
					$nit_err = "El número de documento no es valido";    
					array_push($response->message,array('id' => "nit-error", 'message' => $nit_err ));
				}
            } else{
                $message = "Ocurrió un error inesperado. Por favor intentalo de nuevo.";
            }

            // Close statement
            unset($stmt);
        }
	}
	

    if(empty($nit_err)){
        $token = openssl_random_pseudo_bytes(24);
        
        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($token);
        
        // Prepare an insert statement
        $sql = "UPDATE users SET token = :token where id = :id";
         
        if($stmt = $pdo->prepare($sql)){
			// Bind variables to the prepared statement as parameters
            $stmt->bindParam(":id", $id, PDO::PARAM_STR);
            $stmt->bindParam(":token", $token, PDO::PARAM_STR);

			// Attempt to execute the prepared statement
            if($stmt->execute()){
				
                sendEmail($name, $email, $message, $token);
                header('Location: remember-password?success=true');
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
 
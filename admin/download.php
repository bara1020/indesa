<?php
 
 $attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/indesa/img/" . $_GET['file'];
 //$attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/indesa/img/Protocolo_gimnasio_Indesa.pdf";
 //echo $attachment_location;
       if (file_exists($attachment_location)) {

           header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
           header("Cache-Control: public"); // needed for internet explorer
     //header("Content-Type: image/png");
     header("Content-Type: application/pdf; charset=utf-8");
           header("Content-Transfer-Encoding: Binary");
           header("Content-Length:".filesize($attachment_location));
           header("Content-Disposition: attachment; filename=" . $_GET['file']);
     $file = base64_encode(bin2hex(readfile($attachment_location)));
     echo $file;
     
     //echo 'asdasd';
           die();        
       } else {
           die("Error: File not found.");
       } 
?>
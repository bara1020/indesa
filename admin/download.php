<?php
 
 $attachment_location ="";
 $fileName = $_GET['file'];
 if(isset($_GET['process'])){
      //$attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/indesa/uploaded_files/" . $_GET['file'];
      $attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/uploaded_files/" .  $fileName;
 } else {
      //$attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/indesa/img/" .  $fileName;
      $attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/img/" .  $fileName;
 }

 
echo $attachment_location;
       if (file_exists($attachment_location)) {
           header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
           header("Cache-Control: public"); // needed for internet explorer
           header("Content-Transfer-Encoding: Binary");
           header("Content-Length:".filesize($attachment_location));
           header("Content-Disposition: attachment; filename=" . $fileName);
           
            if(strpos($fileName, 'png'))
                header("Content-Type: image/png; charset=utf-8");
            else if(strpos($fileName, 'jpg'))
                header("Content-Type: image/jpg; charset=utf-8");
            else if(strpos($fileName, 'jpeg'))
                header("Content-Type: image/jpeg; charset=utf-8");
            else
             header("Content-Type: application/pdf; charset=utf-8");

           $file = base64_encode(bin2hex(readfile($attachment_location)));
           echo $file;
     
     //echo 'asdasd';
           die();        
       } else {
           die("Error: File not found.");
       } 
?>
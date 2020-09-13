<?php 


  if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
    /*if($user['userType'] == "2") {// indica que es un artista
      header('Location: artist.php');
    } else if($user['userType'] == "3") {// indica que es un usuario
      header('Location: principal.php');
    }*/
    
  } else {
    $link = $_SERVER['REQUEST_URI'];
    $link_array = explode('/',$link);
    $page = end($link_array);
    if(strpos($page, 'login')){
      require 'views/login.php';
    } else{
      require 'views/register.php';
    }
    //
  }
?>


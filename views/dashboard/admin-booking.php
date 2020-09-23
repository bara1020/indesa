<?php 
 require '../../admin/functions.php'; 
 if (session_status() == PHP_SESSION_NONE) {
   session_start();
 }

 if(!$_SESSION['loggedin']){
   header('Location: ../login');
 }
 $user = $_SESSION['user'];

 $role = $user['role'];


include('../../includes/header.php');
include('../../includes/navbar.php');
?>


<div class="container">
<div class="row">
    <div class="col-md-12 text-center">
      <!-- Button trigger modal -->
      <button id="btn-export" type="button" class="btn btn-primary" >
      Exportar
      </button>
    </div>
  </div>
    <input type="text"
      class="form-control" name="roleuser" id="roleuser" aria-describedby="helpId" placeholder="" value="<?php echo $role?>" style="display:none">
  <div class="row">
    <div class="col-md-12 dvjson">
    <table id="booking" class="table table-striped table-bordered" width="100%"></table>
    </div>
  </div>
</div>



<?php 

include('../../includes/scripts.php');
echo '  <script src="../../js/dashboard.js"></script>';
echo '  <script src="../../vendor/excelexport/excelexport.js"></script>';
include('../../includes/footer.php');
?>
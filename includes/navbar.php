    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../dashboard/admin-user.php">
          <img width="80%" src="../../img/logo.png" alt="Logo">
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

     


     <?php
     
     if($_SESSION["role"] == 'Administrador' || $_SESSION["role"] == 'Recaudo'){
      echo '<!-- Nav Item - Users -->
      <li class="nav-item">
        <a class="nav-link" href="../dashboard/admin-user.php">
          <i class="fas fa-fw fa-users"></i>
          <span>Usuarios</span></a>
      </li>';
     }

        if($_SESSION["role"] == 'Administrador'){
            echo ' <!-- Nav Item - Users -->
      <li class="nav-item">
        <a class="nav-link" href="../dashboard/admin-config.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Configuraciones</span></a>
      </li>

      <!-- Nav Item - Tiqueteras -->
      <li class="nav-item">
        <a class="nav-link" href="../dashboard/admin-tiqueteras.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Tiqueteras</span></a>
      </li>';
            
        }
     ?>
     
      <li class="nav-item">
        <a class="nav-link" href="../dashboard/admin-booking.php">
          <i class="fas fa-fw fa-table"></i>
          <span>Reservas</span></a>
      </li>
     

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

<!-- Main Content -->
<div id="content">

  <!-- Topbar -->
  <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
      <i class="fa fa-bars"></i>
    </button>


    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
      <!-- Nav Item - User Information -->
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user['username']?></span>
          <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
        </a>
        <!-- Dropdown - User Information -->
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
            
            
            
         
          <?php
          
          if($_SESSION["role"] == 'Administrador' || $_SESSION["role"] == 'Recaudo'){
           echo ' <a class="dropdown-item" href="../dashboard/admin-user.php">
            <i class="fas fa-users fa-sm fa-fw mr-2 text-gray-400"></i>
            Usuarios
          </a>';   
          }
          
            if($_SESSION["role"] == 'Administrador'){
             echo '<a class="dropdown-item" href="../dashboard/admin-config.php">
                <i class="fas fa-table fa-sm fa-fw mr-2 text-gray-400"></i>
                Configuraciones
              </a>
              <a class="dropdown-item" href="../dashboard/admin-tiqueteras.php">
                <i class="fas fa-table fa-sm fa-fw mr-2 text-gray-400"></i>
                Tiqueteras
              </a>
              ';
                }   
              ?>
              <a class="dropdown-item" href="../dashboard/admin-booking.php">
                <i class="fas fa-table fa-sm fa-fw mr-2 text-gray-400"></i>
                Reservas
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="../../admin/logout.php" >
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Salir
              </a>
        </div>
      </li>

    </ul>

  </nav>
  <!-- End of Topbar -->


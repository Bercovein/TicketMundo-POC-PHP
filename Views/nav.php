<nav class="navbar navbar-expand-lg  navbar-dark bg-dark" style="position: fixed; top: 0px; width: 100%;">
     <span class="navbar-text">
          <strong>

               <a href="<?php echo FRONT_ROOT ?>HomeController/index">
                    <img style="width: 100px; height: 40px;" src="<?php echo FRONT_ROOT.VIEWS_PATH ?>img/logo.png">
               </a>
          </strong>
     </span>
     <ul class="navbar-nav ml-auto">


          <?php 
               if(!empty($_SESSION["Userlogged"])){
                    if($_SESSION["Userlogged"]->getRol()=="A"){

                    $clientTitle = "Clientes";
                    $cardTitle = "Tarjetas";?>

                    <li class="nav-item">
                         <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>HomeController/index">Inicio</a>
                    </li>
              
                    <li class="nav-item">
                          <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>ArtistController/ShowAddView">Artistas</a>
                    </li>
                    
                    <li class="nav-item">
                         <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>CategoryController/ShowAddView">Categorias</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>EventPlaceController/ShowAddView">Lugares</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>TypeOfSeatController/ShowAddView">Plazas</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>EventController/ShowAddView">Eventos</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>CalendarController/ShowAddView">Calendarios</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>EventSeatsController/ShowAddView">Asientos</a>
                    </li>

          <?php } else if($_SESSION["Userlogged"]->getRol()=="C"){ 

                    $clientTitle = "Mis Datos";
                    $cardTitle = "Mis Tarjetas"; ?>

                    <form method="POST" action="<?=FRONT_ROOT?>PurchaseLineController/searchBy">

                         <div>

                              <input class="form-control form-control-lg"  style="width: 250px; height: 30px; float: left; margin-left: -280px;" type="text" name="search" placeholder="Buscar...">

                              <div style="color:gray; margin-top: 5px; margin-right: 10px; margin-left: -10px;">
                                   <label style="color:gray;">Buscar por: </label>
                                   <input  class="nav-item" type="radio" name="typeOfSearch" value="artist" checked>Artista
                                   <input  class="nav-item" type="radio" name="typeOfSearch" value="event">Evento
                              </div>
                         </div>

                    </form>


                    <li class="nav-item">
                         <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>HomeController/index">Inicio</a>
                    </li>

                    <li class="nav-item">
                         <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>CartController/ShowAddView">Comprar</a>
                    </li>

                    <li class="nav-item">
                         <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>CartController/ShowCartView">Mi Carrito</a>
                    </li>

          <?php } ?>

               <li class="nav-item">
                    <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>PurchaseController/showPurchaseListView">Historial</a>
               </li>
               <li class="nav-item">
                    <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>ClientController/ShowListView"><?php echo $clientTitle;?></a>
               </li>
               <li class="nav-item">
                         <a class="nav-link" style="color:orange;" href="<?php echo FRONT_ROOT ?>CardController/ShowView"><?php echo $cardTitle;?></a>
               </li>

          <?php }?>
          
               <?php 
               if(empty($_SESSION["Userlogged"])){ ?>

               <li class="nav-item">
                    <a class="nav-link" style="color:white; text-shadow: 1px 1px 1px orange" href="<?php echo FRONT_ROOT ?>UserController/ShowLoginView">Iniciar Sesión</a>
               </li>

               <li class="nav-item">
                    <a class="nav-link" style="color:white; text-shadow: 1px 1px 1px blue" href="<?php echo FRONT_ROOT ?>UserController/showRegisterView">¡Registrarme!</a>
               </li>

               <?php }else{ ?>

               <li class="nav-item">
                    <a class="nav-link" style="color:white; text-shadow: 1px 1px 1px orange" href="<?php echo FRONT_ROOT ?>UserController/UserLogout">Cerrar Sesión</a>
               </li>
               <?php } ?>
          
     </ul>
</nav>
<br><br><br>
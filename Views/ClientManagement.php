<?php 
namespace Views;

include('nav.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Gestion de Clientes</title>
	</head>
	<body>
		<main class="d-flex align-items-center justify-content-center">
          <div class="content">
               <header class="text-center">
               	<br>
               

               </header>
               <form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>ClientController/newClient">

                <h2 align="center">Ingrese sus datos</h2>

                    <div class="form-group">
                        <label for="ClientFirstName">Nombre:</label> 
						            <input type="text" name="ClientFirstName" placeholder="Ej: Juan" required class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                        <label for="ClientLastName">Apellido:</label> 
						            <input type="text" name="ClientLastName" placeholder="Ej: Perez" required class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                        <label for="ClientDni">Dni:</label> 
						            <input type="text" name="ClientDni"  placeholder="Ej: 12312312" required maxlength="16" pattern="[0-9]+" class="form-control form-control-lg">
                    </div>

                    <input hidden type="text" name="userId" value="<?php echo $_SESSION["Userlogged"]->getId(); ?>">

                    <button class="btn btn-dark btn-block btn-lg" type="submit" name="buttonSubmitClient">Cargar Datos</button>

               <br>
               <div align="left">
                    <a href="<?php echo FRONT_ROOT ?>HomeController/index">
                    <input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
                    </a>
               </div>
               </form>

          </div>
          
     	</main>

	</body>
</html>
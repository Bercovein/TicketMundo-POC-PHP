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

               <form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>ClientController/updateClient">

                <h2 align="center">Modifique sus datos</h2>
                    
                    <input hidden type="number" name="ClientId" value="<?php echo $client->getId(); ?>" required> 

                    <div class="form-group">
                        <label for="ClientFirstName">Nombre:</label> 
						            <input type="text" name="ClientFirstName" value="<?php echo $client->getFirstName(); ?>" required class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                        <label for="ClientLastName">Apellido:</label> 
						            <input type="text" name="ClientLastName" value="<?php echo $client->getLastName(); ?>" required class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                        <label for="ClientDni">Dni:</label> 
						            <input type="text" name="ClientDni" value="<?php echo $client->getDni(); ?>" required maxlength="16" pattern="[0-9]+" class="form-control form-control-lg">
                    </div>

                    <input hidden type="text" name="userId" value="<?php echo $client->getUser()->getId(); ?>">
                    
                    <button class="btn btn-dark btn-block btn-lg" type="submit" name="buttonSubmitClientUpdate">Actualizar</button>

               <br>
               <div style="width: 100px; float: left;">
                    <a href="<?php echo FRONT_ROOT ?>clientController/showListView">
                    <input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
                    </a>
               </div>

               <br>
               
               </form>

          </div>
          
     	</main>

	</body>
</html>
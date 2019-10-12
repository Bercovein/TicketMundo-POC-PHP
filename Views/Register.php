<?php

namespace Views;

?>

<html>

	<body>

		<main class="d-flex align-items-center justify-content-center height-100">
              
               <form class="login-form bg-dark-alpha p-5 text-white" method="post" action="<?=FRONT_ROOT?>UserController/newUser">

                    <h2 align="center" style="text-shadow: 2px 2px 2px gray;">Registro</h2>

                    <h6 align="center" style="text-shadow: 2px 2px 2px gray;">Al registrarte podes ser parte de Ticket Mundo!
                    Solo debes seguir estos simples pasos: </h6>

                    <br>

                    <div class="form-group">
                         <input type="email" name="emailUser" class="form-control form-control-lg" placeholder="Ingresar email" required>
                    </div>
                    <div class="form-group">
                         <input type="password" name="passUser" class="form-control form-control-lg" placeholder="Ingresar contraseña">
                    </div>
                    <div class="form-group">
                         <input type="password" name="repeatPassUser" class="form-control form-control-lg" placeholder="Repetir contraseña">
                    </div>
                    <button class="btn btn-dark btn-block btn-lg" type="submit">
                         Registrarme
                    </button>
               <br>

               <h6 align="right" style="text-shadow: 2px 2px 2px gray;"> *Antes de continuar, recordá que todos los campos son obligatorios y las contraseñas deben coincidir para poder continuar*</h6>

               <div style="width: 100px; float: left;">
                    <a href="<?php echo FRONT_ROOT ?>UserController/ShowLoginView">
                    <input align="left" type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
                    </a>
               </div>
               <br>

               </form>
     	</main>
	</body>
</html>
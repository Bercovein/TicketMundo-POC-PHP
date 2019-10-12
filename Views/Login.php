<?php

namespace Views;

?>

<html>

	<body>

		<main class="d-flex align-items-center justify-content-center height-100">
          <div class="content">
               
               
               <form class="login-form bg-dark-alpha p-5 text-white" method="post" action="<?=FRONT_ROOT?>UserController/UserLogin">

                    <h2 align="center" style="text-shadow: 2px 2px 2px gray;">Login</h2>

                    <div class="form-group">
                         <label for="nameUser" style="text-shadow: 2px 2px 2px gray;">Email </label>
                         <input type="email" name="emailUser" class="form-control form-control-lg" placeholder="Ingresar Email" required>
                    </div>
                    <div class="form-group">
                         <label for="passUser" style="text-shadow: 2px 2px 2px gray;">Contraseña</label>
                         <input type="password" name="passUser" class="form-control form-control-lg" placeholder="Ingresar contraseña">
                    </div>
                    <button class="btn btn-dark btn-block btn-lg" type="submit">
                         Iniciar Sesión
                    </button>
               <br>
               
               <a href="<?php echo FRONT_ROOT ?>UserController/showRegisterView">
               <b><p align="right" style="font-size: 14px; color: black">¿No tenés usuario? Registrate!</p></b>

               <div style="width: 100px; float: left;">
                    <a href="<?php echo FRONT_ROOT ?>HomeController/index">
                    <input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
                    </a>
               </div>
               <br>
               </form>

          </div>
          
     	</main>
	</body>
</html>
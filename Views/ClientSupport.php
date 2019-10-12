<?php 

namespace Views;

include('nav.php');

?>
<!DOCTYPE html>
<html>
<head>

	</script>
</head>
<body>
	<main class="d-flex align-items-center justify-content-center">
          
          <div class="content"> 
               
               <form class="login-form bg-dark-alpha p-5 text-white" method="post" action="<?=FRONT_ROOT?>ClientController/SupportEmail" style="margin: 30px;">

                    <h2 align="center" style="text-shadow: 2px 2px 2px gray;">Atención al Cliente</h2>

                    <div class="form-group" style="text-align: center">
                         <label for="text" style="text-shadow: 2px 2px 2px gray;">¡Puede enviarnos su consulta aqui!</label>
                         
                         <textarea required name="texto" onKeyDown="valida_longitud()" onKeyUp="valida_longitud()" id="comment" class="form-control form-control-lg" name="message" rows="10" cols="35" maxlength="300" onchange="init_contadorTa('textCount','maxCount', '300')"></textarea>
                         <p style="float: right;">Max.Caracteres: 300</p>

                    </div>
                   
                    <div class="form-group" style="text-align: center">
                         <label for="nameUser" style="text-shadow: 2px 2px 2px gray;">No olvide ingresar su nombre e email para que le respondamos a la brevedad. </label>
                         <input type="name" name="name" class="form-control form-control-lg" placeholder="Ingresar Nombre" required>
                         <br>
                         <input type="email" name="emailUser" class="form-control form-control-lg" placeholder="Ingresar Email" required>
                    </div>
                    
                    <button class="btn btn-dark btn-block btn-lg" type="submit">
                         Enviar Consulta
                    </button>
               <br>

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

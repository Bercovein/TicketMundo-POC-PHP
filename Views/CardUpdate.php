<?php 
namespace Views;

include('nav.php');
?>

<!DOCTYPE html>
<html>
	<head>
	
	</head>
	<body>
		<div align="center" border="2" class="table" style="margin-top: 2%">
			
			<form style="text-align: left; width: 700px" class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>CardController/updateCard">

				<h2 align="center">Modificar Tarjeta</h2>
				
				<input hidden type="number" name="CardId" value="<?php echo $card->getId(); ?>">

				<div style="width: 275px; float: left;">
					<label for="CardNumber">Nro. de Tarjeta:</label> 
					<input class="form-control form-control-lg" type="text" name="CardNumber" value="<?php echo $card->getNumber(); ?>" required maxlength="20" pattern="[0-9]+">
					<br>
				</div>

				<div style="width: 250px; float: right;">

					<label  for="CardsegurityCode">Código Seguridad:</label> 
					<input class="form-control form-control-lg" type="text" name="CardsegurityCode" value="<?php echo $card->getSecurityCode(); ?>" maxlength="3" pattern="[0-9]+" required>
					<br>

				</div>

				<div style="width: 275px; float: left;">
					<label hidden for="cardDni">Dni:</label> 
					<input hidden class="form-control form-control-lg" type="number" name="cardDni" value="<?php echo $card->getClient()->getDni(); ?>" required>
				</div>

				<div style="width: 250px; float: left; margin-left: 180px">
					<label for="CardExpirationDate">Fecha Expiración:</label> 
					<input class="form-control form-control-lg" type="date" name="CardExpirationDate" min="<?php echo date('Y-m-d',strtotime(date("Y-m-d", time()))); ?>" value="<?php echo $card->getExpirationDate(); ?>" required>
					<br>
				</div>

				<button class="btn btn-dark btn-block btn-lg" type="submit" name="buttonSubmitCardUpdate"> Actualizar </button>
				
				<br>
        		<div style="width: 100px; float: left;">
            		<a href="<?php echo FRONT_ROOT ?>CardController/showView">
                	<input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
            	</a>
        		</div>
        		<br>

			</form>

		</div>
	</body>
</html>

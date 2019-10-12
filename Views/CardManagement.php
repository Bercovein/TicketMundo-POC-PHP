<?php 
namespace Views;

include('nav.php');
?>

<!DOCTYPE html>
<html>
	<head>
	
	</head>
	<body>
		<div align="center"  class="table" style="margin-top: 2.7%">
			
			<form style="text-align: left; width: 700px" class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>CardController/newCard">

				<h2 align="center">Agregar Tarjeta</h2>

				<div style="width: 275px; float: left;">
					<label for="CardNumber">Nro. de Tarjeta:</label> 
					<input class="form-control form-control-lg" type="text" name="CardNumber" placeholder="5555 5555 5555 5555" required maxlength="20" pattern="[0-9]+">
					<br>
				</div>

				<div style="width: 250px; float: right;">

					<label  for="CardsegurityCode">Código Seguridad:</label> 
					<input class="form-control form-control-lg" type="text" name="CardsegurityCode" placeholder="123" maxlength="3" pattern="[0-9]+" required>
					<br>

				</div>

				<div style="width: 275px; float: left;">
					<label for="cardDni">Dni asociado:</label> 
					<input class="form-control form-control-lg" type="text" name="cardDni" value="<?php echo $client->getDni()?>" readonly >
					<br>
				</div>

				<div style="width: 250px; float: right;">
					<label for="CardExpirationDate">Fecha Expiración:</label> 
					<input class="form-control form-control-lg" type="date" name="CardExpirationDate" required min="<?php echo date('Y-m-d',strtotime(date("Y-m-d", time()))); ?>">
					<br>
				</div>

				<button class="btn btn-dark btn-block btn-lg" type="submit" name="buttonSubmitCard"> Cargar </button>

			</form>

		</div>
	</body>
</html>

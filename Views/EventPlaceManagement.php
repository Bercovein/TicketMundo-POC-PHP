<?php 
namespace Views;

include('nav.php');
?>

<!DOCTYPE html>
<html>
	<head>

	</head>
	<body>
		<div align="center" border="2" style="margin-top: 2%">

			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>EventPlaceController/newEventPlace" style="width: 700px">
				<h2>Agregar Lugar</h2>
				<br>
					<div style="width: 300px; float: left;">

						<label for="eventPlaceName"><h4>Nombre</h4></label> 
						<br>
						<input type="text" name="eventPlaceName" class="form-control form-control-lg" style="width: 300px" placeholder="Ej: Teatro Gran Rex" required>
						<br><br>

					</div>

					<div style="width: 200px; float: right;">

						<label for="eventPlaceCapacity"><h4>Capacidad</h4></label> 
						<input type="number" name="eventPlaceCapacity" class="form-control form-control-lg" style="width: 200px" placeholder="Ej: 15000" required min = "1">
						<br><br>
						
					</div>

				<button class="btn btn-dark btn-block btn-lg" style="width: 300px" type="submit" name="buttonSubmitEventPlace"> Cargar </button>

			</form>
		</div>
		<br><br>
		<?php
		$this->showListView();
		?>
	</body>
</html>
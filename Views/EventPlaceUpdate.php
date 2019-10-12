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

			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>EventPlaceController/updateEventPlace" style="width: 700px">

				<h2>Modificar Lugar</h2>
				<br>

					<input hidden type="number" name="eventPlaceId" value="<?php echo $eventPlace->getId(); ?>" required>

					<div style="width: 300px; float: left;">

						<label for="eventPlaceName"><h4>Nombre</h4></label> 
						<br>
						<input type="text" name="eventPlaceName" class="form-control form-control-lg" style="width: 300px" value="<?php echo $eventPlace->getName(); ?>" required>
						<br><br>

					</div>

					<div style="width: 200px; float: right;">

						<label for="eventPlaceCapacity"><h4>Capacidad</h4></label> 
						<input type="text" readonly name="eventPlaceCapacity" class="form-control form-control-lg" style="width: 200px" value="<?php echo $eventPlace->getCapacity(); ?>" required>
						<br><br>
						
					</div>

				<button class="btn btn-dark btn-block btn-lg" style="width: 300px" type="submit" name="buttonSubmitEventPlace"> Actualizar </button>
				
				<br>
        		<div style="width: 100px; float: left;">
            		<a href="<?php echo FRONT_ROOT ?>EventPlaceController/showAddView">
                	<input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
            	</a>
        		</div>
        		<br>
			</form>
		</div>

	</body>
</html>
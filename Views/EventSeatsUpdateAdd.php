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
			
			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>EventSeatsController/<?php echo $path; ?>" style="width: 700px">

				<h2>Modificar Asientos</h2>
				<br>

				<label for="calendarId"><h4><?php echo $calendar->getDate()." - ".$calendar->getEvent()->getName(). ' - Capacidad: ('.$capacity."/".$total.")"; ?></h4></label>	
			
				<input hidden type="text" name="calendarId" value="<?php echo $calendar->getId();?>">

				<table border="1" class="table bg-light-alpha" style="width: 500px; text-align: center;" align="center"  >
					
					<tr>
						<th>Tipo</th>
						<th>Precio</th>
						<th>Cantidad</th>
					</tr>

			  <?php for($i = 0; $i < count($seatList); $i ++) { ?>

						<tr>
							<td>
								<input hidden type="text" name="TypeOfSeat[]" id="<?php echo 'TypeOfSeat'.$i ?>" value="<?php echo $seatList[$i]->getTypeOfSeat()->getId() ?>" class="form-control form-control-lg">
								<?php echo $seatList[$i]->getTypeOfSeat()->getName() ?>
							</td>

							<td>
								<input type="number" name="price[]" id="<?php echo 'price'.$i ?>" value="<?php echo $seatList[$i]->getPrice(); ?>" min="0" class="form-control form-control-lg">
							</td>

							<td>
								<input type="number" name="quantity[]" id="<?php echo 'quantity'.$i ?>" value="<?php echo $seatList[$i]->getQuantity(); ?>" min="0" class="form-control form-control-lg">
							</td>
						<tr>			
			  <?php } ?> 

				</table>
				<br>

				<button class="btn btn-dark btn-block btn-lg" style="width: 500px" type="submit" name="buttonSubmitEventSeatsUpdate"> 
					Cargar 
				</button>
				
				<br>
        		<div style="width: 100px; float: left;">
            		<a href="<?php echo FRONT_ROOT ?>EventSeatsController/showAddView">
                	<input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
            	</a>
        		</div>
        		<br>
			</form>
		</div>

	</body>
</html>
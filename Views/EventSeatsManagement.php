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
			
			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>EventSeatsController/<?php echo $path?>" style="width: 700px">

				<h2><?php echo $title ?></h2>
				<br>
				<label for="calendarId"><h4>Calendario</h4></label>	
				<select class="form-control form-control-lg" style="width: 600px" name="calendarId">
					<?php

					foreach($listCalendar as $calendar) { ?>
						<?php 
							$total = $calendar->getEventPlace()->getCapacity();
							$capacity = 0;

							foreach ($listEventSeats as $seat) {
								if($seat->getCalendar()->getId()==$calendar->getId())
									$capacity += $seat->getQuantity();
							}
									
							if($capacity == 0) { ?>
								<option value="<?php echo $calendar->getId() ?>">
									<?php echo $calendar->getDate().' - '.$calendar->getEvent()->getName(). ' - Capacidad: ('.$capacity."/".$total.")"; ?>
								</option>
			<?php 	}       } ?>
				</select>

				<?php if($path == 'newEventSeats') { ?>
				<div>
					<a class="text-white" href="<?php echo FRONT_ROOT ?>EventSeatsController/showSeatsFestivalView"><u style="margin-top: 4px;float: right; color: black; text-shadow: 5px 5px 5px #A4A4A4;">Cargar Asientos Festival</u></a>
				</div>
				<?php } ?>

				<br><br>

				<table border="1" class="table bg-light-alpha" style="width: 500px; text-align: center;" align="center"  >
					
					<tr>
						<th>Tipo</th>
						<th>Precio</th>
						<th>Cantidad</th>
					</tr>

					<?php for($i = 0; $i < count($listTypeOfSeat) ; $i++) { ?>

						<tr>

							<td>
								<input hidden type="text" name="TypeOfSeat[]" id="<?php echo 'TypeOfSeat'.$i ?>" value="<?php echo $listTypeOfSeat[$i]->getId() ?>" class="form-control form-control-lg">
								<?php echo $listTypeOfSeat[$i]->getName() ?>
							</td>

							<td>
								<input type="number" name="price[]" id="<?php echo 'price'.$i ?>" value="0" min="0" class="form-control form-control-lg">
							</td>

							<td>
								<input type="number" name="quantity[]" id="<?php echo 'quantity'.$i ?>" value="0" min="0" class="form-control form-control-lg">
							</td>

						</tr>
					
					<?php } ?>

				</table>
				<br>

				<button class="btn btn-dark btn-block btn-lg" style="width: 500px" type="submit" name="buttonSubmitEventSeats"> 
					Cargar 
				</button>
				
				<?php if($path == 'newEventSeatsFestival') { ?>
					<br>
	        		<div style="width: 100px; float: left;">
	            		<a href="<?php echo FRONT_ROOT ?>EventSeatsController/showAddView">
	                	<input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
	            	</a>
	        		</div>
				<?php } ?>
				<br>
			</form>
		</div>
		<br><br>
		<?php
		$this->showListView();
		?>
	</body>
</html>
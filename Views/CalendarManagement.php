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
			
			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>CalendarController/newCalendar" style="width: 700px">

				<h2>Agregar Calendario</h2>
				<br>	

				<div style="width: 300px; float: left; height: 130px;">

					<label for="calendarDate"><h4>Fecha</h4></label> 
					<br>
					<input type="date" name="calendarDate" min="<?php echo date('Y-m-d',strtotime(date("Y-m-d", time()))); ?>" class="form-control form-control-lg" style="width: 195px" required>
					<br>
				</div>

				<div style="width: 300px; float: right; height: 130px;">

					<label for="calendarEvent"><h4>Evento</h4></label> 	

					<select class="form-control form-control-lg" style="width: 270px;" name="calendarEvent">
					<?php 
						foreach($listEvent as $event) { ?>
							<option value="<?php echo $event->getId() ?>">
								<?php echo $event->getName() ?>
							</option>
				  <?php } ?>
					</select>
					<a class="text-white" href="<?php echo FRONT_ROOT ?>CalendarController/showFestivalView"><u style="margin-top: 4px;float: right; color: black; text-shadow: 5px 5px 5px #A4A4A4;">Cargar Festival</u></a>
					<br>	
				</div>

				<div style="width: 300px; float: left; height: 130px;">
					
					<label for="calendarTime"><h4>Hora</h4></label> 
					<input type="time" name="calendarTime" class="form-control form-control-lg" style="width: 195px" required>
					<br>
				</div>

				<div style="width: 300px; float: right; height: 130px;">

					<label for="calendarEventPlace"><h4>Lugar Evento</h4></label>	
					<select class="form-control form-control-lg" style="width: 270px" name="calendarEventPlace">
						<?php 
						foreach($listEventPlace as $eventPlace) { ?>
							<option value="<?php echo $eventPlace->getId() ?>">
								<?php echo $eventPlace->getName() ?>
							</option>
				  <?php } ?>
					</select>
					<br>

				</div>

				<div style="">
	
					<label for="calendarArtists[]"><h4>Artista/s</h4></label>
					<br>
				    <select class="form-control form-control-lg" style="width: 300px" required multiple name="calendarArtists[]"> 
				        <?php 
						foreach($listArtist as $artist) { ?>
							<option value="<?php echo $artist->getId() ?>">
								<?php echo $artist->getName() ?>
							</option>
						<?php } ?>
				    </select>
				    <br>
				</div>
				<br>
				<button class="btn btn-dark btn-block btn-lg" style="width: 400px" type="submit" name="buttonSubmitCalendar"> Cargar </button>

			</form>

		</div>
		<br>
		
		<?php
		$this->showListView();
		?>

	</body>
</html>
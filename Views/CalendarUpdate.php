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
			
			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>CalendarController/updateCalendar" style="width: 700px">

				<h2>Modificar Calendario</h2>
				<br>
					
				<input hidden type="number" name="calendarId" value="<?php echo $calendar->getId(); ?>" required>

				<div style="width: 300px; float: left;">

					<label for="calendarDate"><h4>Fecha</h4></label> 
					<br>
					<input type="date" name="calendarDate" value="<?php echo $calendar->getDate(); ?>"  class="form-control form-control-lg" style="width: 195px" required>
					<br><br>

				</div>

				<div style="width: 300px; float: right;">

					<label for="calendarEvent"><h4>Evento</h4></label> 

					<select class="form-control form-control-lg" style="width: 300px" name="calendarEvent">
					<?php 
						foreach($listEvent as $event) { ?>
							<option value="<?php echo $event->getId() ?>"
								<?php if($event->getId() == $calendar->getEvent()->getId()) echo 'selected' ?> >
								<?php echo $event->getName() ?>
							</option>
				  <?php } ?>
					</select>
					<br><br>	
				</div>

				<div style="width: 300px; float: left;">

					<label for="calendarEventPlace"><h4>Lugar Evento</h4></label>	
					<select class="form-control form-control-lg" style="width: 200px" name="calendarEventPlace">
						<?php 
						foreach($listEventPlace as $eventPlace) { ?>
							<option value="<?php echo $eventPlace->getId() ?>"
								<?php if($eventPlace->getId() == $calendar->getEventPlace()->getId()) echo 'selected' ?> >
								<?php echo $eventPlace->getName() ?>
							</option>
				  <?php } ?>
					</select>
					<br><br><br>

				</div>

				<?php
					$artList = array();

					foreach ($listArtist as $artist){
	                    if(!in_array($artist, $calendar->getArtistList())){
							array_push($artList, $artist);
	                    }
	                }
                ?>

				<div style="width: 300px; float: right;">
	
					<label for="calendarArtists[]"><h4>Agregar Artista/s</h4></label>
					<br>
				    <select class="form-control form-control-lg" style="width: 300px" multiple name="calendarArtists[]"> 
				    	<option hidden value="0" selected><----></option>
				    	<option disabled><-------------></option>
				        <?php 
					        foreach($artList as $artist) { ?>
								<option value="<?php echo $artist->getId() ?>">
										<?php echo $artist->getName() ?>
								</option>
				      <?php } ?>			
				    </select>
				    <br><br>
				</div>

				<button class="btn btn-dark btn-block btn-lg" style="width: 400px" type="submit" name="buttonSubmitCalendar"> Actualizar </button>
				
				<br>
        		<div style="width: 100px; float: left;">
            		<a href="<?php echo FRONT_ROOT ?>CalendarController/showAddView">
                	<input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
            	</a>
        		</div>
        		<br>
			</form>

		</div>

	</body>
</html>
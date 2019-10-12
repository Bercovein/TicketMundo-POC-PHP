<?php 
namespace Views;

include('nav.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1"></meta>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></meta>
		<script src="<?php echo JS_PATH?>jquery.min.js"></script>
		<script src="<?php echo JS_PATH?>jquery-ui.js"></script>
		
	<script type="text/javascript">
		
		function chargeDate(){

			var date = new Date($('#festivalDate').val());
			var days = document.getElementById("dayQuantity").value;

			date.setDate(date.getDate() + parseInt(days) - 1);

			var date2 = date.toISOString().slice(0,10);

			document.getElementById("festivalDate2").innerHTML="";
			document.getElementById("festivalDate2").value = date2;
		}
			
	</script>	
	
	<script type="text/javascript">
	
		$(document).ready(function(){
			$("#dayQuantity").change(function(){ 

				if ($(this).val() >= $(this).attr('max')*1) { 
					$(this).val($(this).attr('max'));
					alert('se alcanzo la cantidad maxima de dias para realizar el Festival.');
				}	
			});
		});
	</script>

	</head>

	<body>
		<div align="center" border="2" style="margin-top: 2%">
			
			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>CalendarController/newFestival" style="width: 700px;">

				<h2>Agregar Festival</h2>
				<br>
				
				<div style="width: 310px; float: left; height: 130px;">

					<label for="festivalEvent"><h4>Evento</h4></label> 

					<select required class="form-control form-control-lg" style="width: 310px" name="festivalEvent">
					<?php 
						foreach($listEvent as $event) { ?>
							<option value="<?php echo $event->getId() ?>">
								<?php echo $event->getName() ?>
							</option>
				  <?php } ?>
					</select>
					<br>	
				</div>
				
				<div style="width: 250px; float: right; height: 260px;">
	
					<label for="festivalArtists[]"><h4>Artista/s</h4></label>
					<br>
				    <select class="form-control form-control-lg" style="width: 250px;height: 176px" required multiple name="festivalArtists[]"> 
				        <?php 
						foreach($listArtist as $artist) { ?>
							<option value="<?php echo $artist->getId() ?>">
								<?php echo $artist->getName() ?>
							</option>
						<?php } ?>
				    </select>
				    <br><br>
				</div>
				
				<div style="width: 200px; float: left; height: 130px;">

					<label for="festivalEventPlace"><h4>Lugar Evento</h4></label>	
					<select required class="form-control form-control-lg" style="width: 200px" name="festivalEventPlace">
						<?php 
						foreach($listEventPlace as $eventPlace) { ?>
							<option value="<?php echo $eventPlace->getId() ?>">
								<?php echo $eventPlace->getName() ?>
							</option>
				  <?php } ?>
					</select>
					<br>
				</div>

				<div style="width: 80px; float: left; margin-left: 30px; height: 130px;">
					<label for="dayQuantity"><h4>Días</h4></label> 
					<input class="form-control form-control-lg" type="number" name="dayQuantity" id="dayQuantity" value="2" min="2" max="7" onkeydown="return false" onChange="chargeDate()" required">
					<br>
				</div>	 
				
				<div style="width: 200px;float: left; height: 130px;">

					<label for="festivalDate"><h4>Desde</h4></label>	
					<input type="date" name="festivalDate" id="festivalDate" min="<?php echo date('Y-m-d',strtotime(date("Y-m-d", time()))); ?>" class="form-control form-control-lg" style="width: 200px" onChange="chargeDate()" required>
					<br>
				</div>

				<div style="width: 200px; height: 130px; margin-left: 30px; float: left;">

					<label for="festivalDate2"><h4>Hasta</h4></label>	
					<input disabled type="date" name="festivalDate2" id="festivalDate2" min="<?php echo date('Y-m-d',strtotime(date("Y-m-d", time()))); ?>" class="form-control form-control-lg" style="width: 200px" required>
					<br>
				</div>

				<div style="width: 140px; float: right; height: 130px;">
					
					<label for="festivalTime"><h4>Hora</h4></label>	
					<input type="time" name="festivalTime" class="form-control form-control-lg" style="width: 140px;" required>
					<br>
				</div>

        		<div style="width: 100px; float: left;">
            		<a href="<?php echo FRONT_ROOT ?>CalendarController/showAddView">
                	<input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
            	</a>
        		</div>

        		<button class="btn btn-dark btn-block btn-lg" style="width: 400px;" type="submit" name="buttonSubmitFestival"> Cargar </button>
        		
			</form>

		</div>
		<br>

	</body>
</html>
<?php
namespace Views;

?>

<html>
	<head>
		
	</head>
	<body>
		<main class="py-5">
			<div align="center">
				<section id="listado" class="mb-5">
					<div class="login-form bg-dark-alpha p-5 text-white" style="text-align: center; width: 600px; margin-top: -3%">

						<label class="titulo"><h2>Listado</h2></label>
						<?php	
						foreach ($listCalendar as $calendar) { 

							$seatList= array();

							foreach ($listEventSeats as $seat) {
								if($seat->getCalendar()->getId()==$calendar->getId())
									array_push($seatList, $seat);
							}

							if(!empty($seatList)){ ?>

								<table class="table bg-light-alpha" style="width: 250px" align="center" border="2"  style="margin-top: 1%">								
									<tr>
										<th width="200px" colspan="5"><b><p style="float: left; font-size: 20px;"><?php echo $calendar->getDate()." - ".$calendar->getEvent()->getName();?></p></b>
										
				
										<a class="text-white" href="<?php echo FRONT_ROOT ?>EventSeatsController/showUpdateAddView/<?php echo $calendar->getId();?>/newEventSeats"><u style="margin-top: 4px;float: right; color: black; text-shadow: 5px 5px 5px #A4A4A4;">Agregar</u></a>

										<a class="text-white" href="<?php echo FRONT_ROOT ?>EventSeatsController/showUpdateAddView/<?php echo $calendar->getId();?>/updateEventSeats"><u style="margin-top: 4px;float: right; color: black; text-shadow: 5px 5px 5px #A4A4A4;">Editar&nbsp;&nbsp;</u></a>

										</th>
									</tr>
									<tr>
										<th width="100px">Tipo de Plaza</th>
										<th width="100px">Precio</th>
										<th width="100px">Cantidad disponible</th>
										<th width="100px">Remanente</th>
										<th>x</th>
									</tr>
								<?php

									$seatList= array();

									foreach ($listEventSeats as $seat) {
										if($seat->getCalendar()->getId()==$calendar->getId())
											array_push($seatList, $seat);
									}

									foreach($seatList as $eventSeats) {

										$idSeat = "c".$calendar->getId()."t".$eventSeats->getTypeOfSeat()->getId();
										?>
										<tr>
											<form id="<?php echo $idSeat;?>" action="<?php echo FRONT_ROOT; ?>EventSeatsController/deleteEventSeats" method="POST">
												<td><?php echo $eventSeats->getTypeOfSeat()->getName(); ?></td>
												<td><?php echo $eventSeats->getPrice(); ?></td>
												<td><?php echo $eventSeats->getQuantity(); ?></td>
												<td><?php echo $eventSeats->getRemanents(); ?></td>
												
												<td>
													<input type="hidden" name="eventSeatsId" value="<?php echo $eventSeats->getId() ?>">

													<input type="submit" class="btn-danger" value="x" onClick = "prevent(<?php echo '\'#'.$idSeat.'\''?>)">
												</td>	
											</form>
										</tr>
							<?php   } ?>
								</table>  
							<?php }	
						} ?> 			
					</div>
				</section>
			</div>
		</main>
	</body>
</html>

<?php

include("Views/footer.php");
?>

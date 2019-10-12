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
					<div class="login-form bg-dark-alpha p-5 text-white" style="text-align: center; margin-top: -3%; width: 1300px; font-size: 14px;">

						<label class="titulo"><h2>Listado</h2></label>

						<table class="table bg-light-alpha" style="width: 250px" align="center" border="2" class="table" style="margin-top: 1%; margin-left: -20px;">
							<tr>
								<th width="100px">Fecha/Hora</th>
								<th width="300px">Evento</th>
								<th width="100px">Lugar</th>
								<th width="100px">Artista/s</th>
								<th width="100px">Plazas</th>
								<th width="50px">Estado</th>
								<th width="50px">Modificar</th>
							</tr>

							<?php 
							if(empty($listCalendar)){ ?>
								<tr>
									<?php for($i=0;$i<5;$i++){ ?>
									<td align="center"><?php echo '-'?></td>
									<?php } ?>						
								</tr>
							<?php 
							}else{

								foreach ($listCalendar as $calendar) { ?>
									<tr>
										<td><?php echo $calendar->getDate(); ?></td>
										<td><?php echo $calendar->getEvent()->getName(); ?></td>
										<td><?php echo $calendar->getEventPlace()->getName(); ?></td>
										<td>

											<table>
														
												<?php 

												if($calendar->getArtistList()!=NULL){

													foreach($calendar->getArtistList() as $artist){ ?>
														<tr>
															<td><?php echo $artist->getName()."<br>";?></td>
															<td>

																<?php $idArtist = "c".$calendar->getId()."a".$artist->getId();?>

																<form id="<?php echo $idArtist;?>" action="<?php echo FRONT_ROOT; ?>CalendarController/deleteArtistFromCalendar" method="POST">

																<input type="hidden" name="calendarId" value="<?php echo $calendar->getId();?>">
																<input type="hidden" name="artistId" value="<?php echo $artist->getId();?>">
																<i class="far fa-trash-alt">
																<input type="submit" class="btn" value="x" style="background-color: #FF0000; color: white;" onClick = "prevent(<?php echo '\'#'.$idArtist.'\''?>)">
															</i>
															</td>
														</tr>
											<?php  } 
												}else echo "Sin Artistas Vinculados";?>
														
													</table>
												</form>
											</td>
											<td>
												<table border="7%" align="center">

											<?php   $seatList= array();

													foreach ($listEventSeats as $seat) {
														if($seat->getCalendar()->getId()==$calendar->getId())
															array_push($seatList, $seat);
													}

													if(!empty($seatList)) { ?>
														<tr>
															<th>Tipo Plaza</th>
															<th>Precio</th>
															<th>Cantidad</th>
															<th>Remanente</th>
															<th width="70px">Total Vendido</th>
														</tr>

												  <?php foreach($seatList as $seats) { ?>

															<tr>
																<td><?php echo $seats->getTypeOfSeat()->getName(); ?></td>
																<td><?php echo $seats->getPrice(); ?></td>
																<td><?php echo $seats->getQuantity(); ?></td>
																<td><?php echo $seats->getRemanents(); ?></td>
																<td>
																	<?php echo "$".$seats->getSells();?>
																</td>
															</tr>

												  <?php } ?>
											  <?php } else { echo "Sin Plaza Evento"; } ?>
		
												</table>
											</td>

										<?php $idCalendar = "calendarNro".$calendar->getId();?>

										<form id="<?php echo $idCalendar;?>" action="<?php echo FRONT_ROOT; ?>CalendarController/deleteCalendar" method="POST">
											
											<td>
												<input type="hidden" name="calendarId" value="<?php echo $calendar->getId();?>">
										<?php if($calendar->getState()==0){
												$value= 'Activo';
												$color= 'background-color: #00FF00; color: black;';
											}else{
												$value= 'Inactivo';
												$color= 'background-color: #FF0000; color: white;';
												
											}?>
												<input type="submit" class="btn" style="<?php echo $color;?>" value="<?php echo $value;?>"
												<?php if($calendar->getDate() < date("Y-"."m-"."d "."H:"."i")) { echo 'disabled'; } ?> onClick = "prevent(<?php echo '\'#'.$idCalendar.'\''?>)">
											</td>
										</form>

										<form action="<?php echo FRONT_ROOT; ?>CalendarController/showUpdateView" method="POST">
											<td>
												<input type="hidden" name="calendarId" value="<?php echo $calendar->getId();?>">
												<input type="submit" class="btn" style="background-color: #FF8000; color: white;" value="Editar" <?php if($calendar->getDate() < date("Y-"."m-"."d "."H:"."i")) { echo 'disabled'; } ?>>
											</td>
										</form>
									</tr>
							<?php }
							} ?> 
						</table>
					</div>
				</section>
			</div>
		</main>
	</body>
</html>

<?php

include("Views/footer.php");
?>

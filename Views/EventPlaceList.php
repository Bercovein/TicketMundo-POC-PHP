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
					<div class="login-form bg-dark-alpha p-5 text-white" style="text-align: center; margin-top: -5%">

						<label class="titulo"><h2>Listado</h2></label>
								
								  
					<table class="table bg-light-alpha" style="width: 250px" align="center" border="2" class="table" style="margin-top: 1%">						
						<tr>
							<th width="300px">Nombre</th>
							<th width="300px">Capacidad</th>
							<th width="50px">Estado</th>
							<th width="50px">Modificar</th>
						</tr>

						<?php 
						if(empty($listEventPlace)){ ?>
							<tr>
								<?php for($i=0;$i<2;$i++){ ?>
									<td align="center"><?php echo '-'?></td>
								<?php } ?>
								</tr>
								<?php 
						}else{

							foreach ($listEventPlace as $eventPlace) { 

								$id = "form".$eventPlace->getId();	?>
								
								<tr>
									<form id="<?php echo $id;?>" action="<?php echo FRONT_ROOT; ?>EventPlaceController/deleteEventPlace" method="POST">
										
										<td><?php echo $eventPlace->getName(); ?></td>
										<td><?php echo $eventPlace->getCapacity(); ?></td>
										<td>
											<input type="hidden" name="eventPlaceId" value="<?php echo $eventPlace->getId();?>">

								<?php if($eventPlace->getState()==0){
											$value= 'Activo';
												$color= 'background-color: #00FF00; color: black;';
											}else{
												$value= 'Inactivo';
												$color= 'background-color: #FF0000; color: white;';
										}?>
											<input type="submit" class="btn" style="<?php echo $color;?>" value="<?php echo $value;?>" onClick = "prevent(<?php echo '\'#'.$id.'\''?>)">
										</td>					
									</form>

									<form action="<?php echo FRONT_ROOT; ?>EventPlaceController/showUpdateView" method="POST">				
										<td>
											<input type="hidden" name="eventPlaceId" value="<?php echo $eventPlace->getId();?>">
											<input type="submit" class="btn" style="background-color: #FF8000; color: white;" value="Editar">
										</td>
									</form>
								</tr>
							<?php }		} ?> 
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
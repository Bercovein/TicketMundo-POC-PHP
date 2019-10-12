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
					<div class="login-form bg-dark-alpha p-5 text-white" style="text-align: center; margin-top: -3%">

						<label class="titulo"><h2>Listado</h2></label>

						<table class="table bg-light-alpha" style="width: 250px" align="center" border="2" class="table" style="margin-top: 1%">
							<tr>
								<th width="100px">Nombre</th>
								<th width="50px">Estado</th>
								<th width="50px">Modificar</th>
							</tr>

								<?php 
								if(empty($listTypeOfSeat)){ ?>
									<tr>
										<?php for($i=0;$i<1;$i++){ ?>
										<td align="center"><?php echo '-'?></td>
										<?php } ?>						
									</tr>
								<?php 
								}else{

									foreach ($listTypeOfSeat as $typeOfSeat) { 

										$id = "form".$typeOfSeat->getId();?>
										<tr>
											<form id="<?php echo $id;?>" action="<?php echo FRONT_ROOT; ?>TypeOfSeatController/deleteTypeOfSeat" method="POST">
												<td><?php echo $typeOfSeat->getName(); ?></td>

												<td>
													<input type="hidden" name="typeOfSeatId" value="<?php echo $typeOfSeat->getId();?>">

											  <?php if($typeOfSeat->getState()==0){
													
													$value= 'Activo';
													$color= 'background-color: #00FF00; color: black;';
													}else{
														$value= 'Inactivo';
														$color= 'background-color: #FF0000; color: white;';
													}?>
													<input type="submit" class="btn" style="<?php echo $color;?>" value="<?php echo $value;?>" onClick = "prevent(<?php echo '\'#'.$id.'\''?>)">

												</td>
											</form>

											<form action="<?php echo FRONT_ROOT; ?>TypeOfSeatController/showUpdateView" method="POST">
												<td>
													<input type="hidden" name="typeOfSeatId" value="<?php echo $typeOfSeat->getId();?>">
													<input type="submit" class="btn" style="background-color: #FF8000; color: white;" value="Editar">
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

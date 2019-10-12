<?php
namespace Views;

include('nav.php');

?>

<html>
	<head>

	</head>
	<body>
		<main class="py-5">
			<div align="center">
				<section id="listado" class="mb-5">
					<div class="login-form bg-dark-alpha p-5 text-white" border="3" style="text-align: center; width: 800px; margin-top: 1%">

						<label class="titulo"><h2><?php echo $title; ?></h2></label>

					<table align="center" border="2" class="table" style="margin-top: 1%">
						<tr>
							<th width="200px">Cliente</th>
							<th width="200px">Nro. Tarjeta</th>
							<th width="200px">Cod. Seguridad</th>
							<th width="200px">Fecha Expiración</th>
							<th width="200px">Eliminar</th>
							<th width="50px">Modificar</th>
						</tr>

							<?php 
							if(empty($listCard)){ ?>
								<tr>
									<?php for($i=0;$i<5;$i++){ ?>
									<td align="center"><?php echo '-'?></td>
									<?php } ?>						
								</tr>
							<?php 
							}else{

								foreach ($listCard as $card) { 

									$id = "form".$card->getId();

									?>
									<tr>
										<form id="<?php echo $id;?>" action="<?php echo FRONT_ROOT; ?>CardController/deleteCard" method="POST">
										
											<td><?php echo $card->getClient()->getLastName().", ".$card->getClient()->getFirstName(); ?></td>
											<td><?php echo $card->getNumber(); ?></td>
											<td><?php echo $card->getSecurityCode(); ?></td>
											<td><?php echo $card->getExpirationDate(); ?>
											</td>
			

											<td>
												<input type="hidden" name="cardId" value="<?php echo $card->getId();?>">
												<input type="submit" class="btn" value="X" style="background-color: #FF0000; color: white;" onClick = "prevent(<?php echo '\'#'.$id.'\''?>)">
											</td>
										</form>

										<form action="<?php echo FRONT_ROOT; ?>CardController/showUpdateView" method="POST">
											<td>
												<input type="hidden" name="cardId" value="<?php echo $card->getId();?>">
												<input type="submit" class="btn" value="Editar" style="background-color: #FF8000; color: white;">
											</td>
										</form>
									</tr>
							<?php }
							} ?> 
					</table>
					<?php if($title == "Mis Tarjetas"){ ?>
						<br>
		  			<div style="width: 150px; float: right;">
                    	<a href="<?php echo FRONT_ROOT ?>CardController/showAddView">
                    		<input  type="button" name="addCard" class="btn  btn-block btn-lg" style="height: 40px; width: 150px; font-size: 14px; background-color: #01DF01;" value="Agregar Tarjeta">
                    	</a>
					</div>
					<br>

			  <?php }?>
					</div>

				</section>
			</div>
		</main>
	</body>
</html>


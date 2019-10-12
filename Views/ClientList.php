<?php
namespace Views;

include('nav.php');

$rol = $_SESSION["Userlogged"]->getRol();

if($rol=="A")
	$title="Listado de Clientes";
else
	$title="Mis Datos";

?>

<html>
	<head>
		
	</head>
	<body>
		<main class="py-5">
			<div align="center">
				<section id="listado" class="mb-5">
					<div class="login-form bg-dark-alpha p-5 text-white" style="text-align: center; margin-top: -1%; width: 900px;">

						<label class="titulo"><h2><?php echo $title; ?></h2></label>

					<br><br>

					<table align="center" border="2" class="table" style="margin-top: 1%">
						<tr>
							<th width="200px">Cliente</th>
							<th width="200px">Email</th>
							<th width="200px">Dni</th>
							<th width="200px">Tarjeta/s</th>
							<th width="50px">Eliminar</th>
							<th width="50px">Modificar</th>
						</tr>

							<?php 
							if(empty($listClient)){ ?>
								<tr>
									<?php for($i=0;$i<3;$i++){ ?>
									<td align="center"><?php echo '-'?></td>
									<?php } ?>						
								</tr>
							<?php 
							}else{

								foreach ($listClient as $client) { 

									$id = "form".$client->getId(); ?>
									
									<tr>
										<form id="<?php echo $id;?>" action="<?php echo FRONT_ROOT; ?>ClientController/deleteClient" method="POST">
										
											<td><?php echo $client->getLastName().", ".$client->getFirstName(); ?></td>
											<td><?php echo $client->getUser()->getEmail(); ?></td>
											<td><?php echo $client->getDni(); ?></td>
											<td>
												<?php 

												$cardList= array();

												foreach ($listCards as $card) {
													if($card->getClient()->getId()==$client->getId())
														array_push($cardList, $card);
												}

												if(!empty($cardList)){
													foreach($cardList as $card){
														echo $card->getNumber()."<br>";
													} 
												}else
													if($rol =="A"){
														echo 'Sin tarjeta/s';
													}
													else{ ?>

														<div style="width: 150px; float: right;">
									                    	<a href="<?php echo FRONT_ROOT ?>CardController/showAddView">
									                    		<input  type="button" name="addCard" class="btn  btn-block btn-lg" style="height: 40px; width: 150px; font-size: 14px; background-color: #01DF01;" value="Agregar Tarjeta">
									                    	</a>
														</div>
											<?php   }
												?>
											</td>

											<td>
												<input type="hidden" name="clientDni" value="<?php echo $client->getDni();?>">
												<input type="submit" class="btn" value="X" style="background-color: #FF0000; color: white;" onClick = "prevent(<?php echo '\'#'.$id.'\''?>)">
											</td>
									
										</form>

										<form action="<?php echo FRONT_ROOT; ?>ClientController/showUpdateView" method="POST">
											<td>
												<input type="hidden" name="clientId" value="<?php echo $client->getId();?>">
												<input type="submit" class="btn" value="Editar" style="background-color: #FF8000; color: white;">
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

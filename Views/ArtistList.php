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

					<table class="table bg-light-alpha" align="center" border="2" style="margin-top: 1%; width: 250px;">
						<tr>
							<th width="100px">Nombre</th>
							<th width="50px">Estado</th>
							<th width="50px">Modificar</th>
						</tr>

							<?php 
							if(empty($listArtist)){ ?>
								<tr>
									<?php for($i=0;$i<1;$i++){ ?>
									<td align="center"><?php echo '-'?></td>
									<?php } ?>						
								</tr>
							<?php 
							}else{
								foreach ($listArtist as $artist) { 

									$id = "form".$artist->getId();
									?>
									
									<tr>

										<form id="<?php echo $id;?>" action="<?php echo FRONT_ROOT; ?>ArtistController/deleteArtist" method="POST">
											
											<td><?php echo $artist->getName(); ?></td>

										<td>
											<input type="hidden" name="artistId" value="<?php echo $artist->getId();?>">

									<?php if($artist->getState()==0){
												$value= 'Activo';
												$color= 'background-color: #00FF00; color: black;';
											}else{
												$value= 'Inactivo';
												$color= 'background-color: #FF0000; color: white;';
												
											}?>
												<input type="submit" class="btn" style="<?php echo $color;?>" value="<?php echo $value;?>" onClick = "prevent(<?php echo '\'#'.$id.'\''?>)">
											</td>
										</form>
										<?php ?>
										<form action="<?php echo FRONT_ROOT; ?>ArtistController/showUpdateView" method="POST">
											<td>
												<input type="hidden" name="artistId" value="<?php echo $artist->getId();?>">
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

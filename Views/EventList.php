<?php
namespace Views;
?>

<html>
	<head>

	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH?>imgStyle.css">
	<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH?>lightbox.min.css">
	<script type="text/javascript" src="<?php echo JS_PATH?>lightbox-plus-jquery.min.js"></script>

	</head>
	<body>
		<main class="py-5">
			<div align="center">
				<section id="listado" class="mb-5">
					<div class="login-form bg-dark-alpha p-5 text-white" style="text-align: center; margin-top: -5%; width: 1000px;">

						<label class="titulo"><h2>Listado</h2></label>

					<table class="table bg-light-alpha" align="center" border="2" class="table" style="margin-top: 1%">
						<tr>
							<th width="300px">Banner</th>
							<th width="300px">Nomber</th>
							<th width="300px">Categoria</th>
							<th width="50px">Estado</th>
							<th width="50px">Modificar</th>
						</tr>

							<?php 
							if(empty($listEvent)){ ?>
								<tr>
									<?php for($i=0;$i<2;$i++){ ?>
									<td align="center"><?php echo '-'?></td>
									<?php } ?>						
								</tr>
							<?php 
							}else{

								foreach ($listEvent as $event) { 

									$id = "form".$event->getId();
									$val= 'X';
									$col= 'background-color: #FF0000;';
									?>
									<tr>
											<td>
										<?php 	$source = '';
												if($event->getBanner()!=null){ 
													$source = FRONT_ROOT.BANNER_PATH.$event->getBanner(); 
												?>
												<div style="float: right; margin-top: -10px; margin-right: -10px;">
													<form id="<?php echo 'b'.$id;?>" action="<?php echo FRONT_ROOT; ?>EventController/deleteBannerFromEvent" method="POST">
														<input type="hidden" name="eventId" value="<?php echo $event->getId();?>">
														<input type="submit" style="<?php echo $col;?>" class="btn-danger" value="<?php echo $val;?>" onClick = "prevent(<?php echo '\'#'.'b'.$id.'\''?>)">
													</form>
												</div>
												<div class ="gallery">
													<a href="<?php echo $source;?>" data-lightbox="mygallery" data-title="<?php echo $event->getName()?>"><img style="height: 100px;" src="<?php echo $source;?>" alt="(Sin Banner)"></a>
												</div>
												
										  <?php }else{ 

										  			echo '(Sin Banner)';
										  		}?>
												
											</td>
											<td><?php echo $event->getName(); ?></td>
											<td><?php echo $event->getCategory()->getName(); ?></td>

											<td>
												<form id="<?php echo $id;?>" action="<?php echo FRONT_ROOT; ?>EventController/deleteEvent" method="POST">
												<input type="hidden" name="eventId" value="<?php echo $event->getId();?>">
												
										  <?php if($event->getState()==0){
													$value= 'Activo';
												$color= 'background-color: #00FF00; color: black;';
											}else{
												$value= 'Inactivo';
												$color= 'background-color: #FF0000; color: white;';
											}?>
												<input type="submit" class="btn" style="<?php echo $color;?>" value="<?php echo $value;?>" onClick = "prevent(<?php echo '\'#'.$id.'\''?>)">

											</form>
											</td>
										

										<td>		
											<form action="<?php echo FRONT_ROOT; ?>EventController/showUpdateView" method="POST">
												
												<input type="hidden" name="eventId" value="<?php echo $event->getId();?>">
												<input type="submit" class="btn" style="background-color: #FF8000; color: white;" value="Editar">
											</form>
										</td>
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

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
					<div class="login-form bg-dark-alpha p-5 text-white" border="3" style="text-align: center; margin-top: -1%; width: 900px;">

						<label class="titulo"><h2>Mi Carrito</h2></label>
					  
					<table class="table bg-light-alpha" align="center" border="2" style="margin-top: 1%;width: 800px;">
						<tr>
						<th width="200px">Evento</th>
						<th width="200px">Plaza</th>
						<th width="200px">Lugar</th>
						<th width="200px">Fecha</th>
						<th width="100px">Cantidad</th>
						<th width="100px">Precio</th>
						<th width="100px">Subtotal</th>
						<th width="100px">Eliminar</th>
						</tr>

							<?php 
							
							$total=0;

							if(empty($listPurchaseLine)){ ?>
								<tr>
									<?php for($i=0;$i<8;$i++){ ?>
									<td align="center"><?php echo '-'?></td>
									<?php } ?>						
								</tr>
							<?php 
							}else{
								
								foreach ($listPurchaseLine as $line) { 

									$total+=$line->getTotal();
									?>

									<tr>
										<td><?php echo  $line->getEventSeat()->getCalendar()->getEvent()->getName(); ?></td>
										<td><?php echo $line->getEventSeat()->getTypeOfSeat()->getName(); ?></td>
										<td><?php echo  $line->getEventSeat()->getCalendar()->getEventPlace()->getName(); ?></td>
										<td><?php echo $line->getEventSeat()->getCalendar()->getDate(); ?></td>
										<td>
											<form action="<?php echo FRONT_ROOT; ?>CartController/updateQuantity" method="POST">
												<input type="hidden" name="lineId" value="<?php echo $line->getId();?>">
												<input type="hidden" name="modify" value="+">
												<input style="margin: 1px;" type="submit" value="+">
											</form>
											
											<input type="text" name="quantity" readonly style="text-align: center; width: 40px;" value="<?php echo $line->getQuantity();?>">

											<form action="<?php echo FRONT_ROOT; ?>CartController/updateQuantity" method="POST">
												<input type="hidden" name="lineId" value="<?php echo $line->getId();?>">
												<input type="hidden" name="modify" value="-">
												<input style="margin: 1px;" type="submit" value="-">
											</form>
										</td>
										<td><?php echo $line->getPrice(); ?></td>
										<td><?php echo $line->getTotal(); ?></td>

										<td>
											<?php $id = "line".$line->getId();?>
											<form id="<?php echo $id;?>" action="<?php echo FRONT_ROOT; ?>CartController/deleteCartLine" method="POST">
												<input type="hidden" name="lineId" value="<?php echo $line->getId();?>">
												<input type="submit" class="btn" value="X" style="background-color: #FF0000; color: white;" onClick = "prevent(<?php echo '\'#'.$id.'\''?>)">
											</form>
										</td>
									</tr>
							<?php }
							} ?> 
					</table>
						
						<form id="removeAll" action="<?php echo FRONT_ROOT; ?>CartController/deleteCart" method="POST">

						<input type="submit" class="btn" value="Remover Todo" style="background: #0080FF;  float: left; box-shadow: 0px -1px 15px 4px #00aaff;" onClick = "prevent(<?php echo '\'#removeAll\''?>)">

						<div style="float: right; width: 150px; text-align: center">
							<h4>Total</h4>
							<input  type="text" readonly name="total" value="<?php echo '$'.$total?>" class="form-control form-control-lg" style="text-align: center"> 
						</div>
						<br><br><br><br>

						<div style="width: 150px; float: right;">
							
	                    	<a href="<?php echo FRONT_ROOT ?>CartController/showConfirmPurchaseView">
	                         	<input type="button" name="btn-buy" class="btn btn-block btn-lg" style="height: 40px; width: 150px; font-size: 14px; background-color: #01DF01" value="¡Comprar Ahora!">
	                   		</a>
                   		</div>
                   		<br>
					</div>
				</section>
			</div>
		</main>
	</body>
</html>

<?php

include("Views/footer.php");
?>


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
					<div class="login-form bg-dark-alpha p-5 text-white" style="text-align: center; margin-top: -1%; width: 900px;">

						<form id="form" action="<?php echo FRONT_ROOT; ?>PurchaseController/ConfirmPurchase" method="POST"
							>

							<label class="titulo" style="margin-right: -90px;">
								<h2>¡Comprar ahora!</h2>
							</label>

							<input name="fecha" type="text" id="fecha" value="<?php echo date("Y/m/d"); ?>" size="10" readonly class="form-control form-control-lg" style="width: 150px; float: right; background-color: lightgray;"/>
					  
							<table class="table bg-light-alpha" align="center" border="2" style="margin-top: 1%;width: 800px;">
								<tr>
								<th width="200px">EVENTO</th>
								<th width="200px">PLAZA</th>
								<th width="200px">LUGAR</th>
								<th width="200px">FECHA</th>
								<th width="100px">CANTIDAD</th>
								<th width="100px">PRECIO</th>
								<th width="100px">SUBTOTAL</th>
								</tr>

								<?php 
							
								$total=0;

								if(empty($listPurchaseLine)){ ?>
									<tr>
										<?php for($i=0;$i<6;$i++){ ?>
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
											<td><?php echo $line->getQuantity(); ?></td>
											<td><?php echo $line->getPrice(); ?></td>
											<td><?php echo $line->getTotal(); ?></td>

										</tr>
								<?php }
								} ?> 
							</table>

							<br>
						
							<div style="float: right; width: 170px; text-align: center; margin-top: -10px; ">
								<h4>Total</h4>
								<input type="text" readonly name="total" value="<?php echo $total?>" class="form-control form-control-lg" style="text-align: center"> 
							</div>

							<div class="login-form bg-dark-alpha p-5 text-white" style="height: 200px;">
								<div style="float: left;">
									<label align="center">Tarjeta</label>
									<select class="form-control form-control-lg" name="cardId" style="width: 200px; text-align: center;" required>
										<?php
											foreach($listCards as $card) { ?>
						
												<option value="<?php echo $card->getId() ?>">
													<?php echo $card->getNumber(); ?>
												</option>
									<?php 	} ?>

									</select>
								</div>
								<div style="float: right;">
									<label align="center">Cod. Seguridad</label>
									<input required class="form-control form-control-lg" type="text" name="cardCode" maxlength="3" style="width: 150px; text-align: center;">
								</div>	
							</div>

							<div style="width: 170px; float: right; margin-top: -100px;">
		                         		
		                        <input  type="submit" name="btn-buy" class="btn btn-block btn-lg" style="height: 40px; width: 170px; font-size: 14px; background-color: #01DF01; box-shadow: 0px -1px 15px 4px #00aaff;" value="Confirmar Compra" onClick = "prevent(<?php echo '\'#form\''?>)">
		  
		                    </div>
	                	</form>

                   		<div style="float: right; margin-top: -40px;">
            				<a href="<?php echo FRONT_ROOT ?>CartController/showCartView">
                				<input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 170px; font-size: 14px; float: center" value="Regresar">
            				</a>
        				</div>
					</div>
			</div>
		</main>
	</body>
</html>

<?php

include("Views/footer.php");
?>


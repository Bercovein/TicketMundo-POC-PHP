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
		<div class="login-form bg-dark-alpha p-5 text-white" border="3" style="text-align: center; margin-top: -1%; width: 1200px">

			<label class="titulo"><h2><?php echo $title; ?></h2></label>
					  
			<table class="table bg-light-alpha" align="center" border="2" style="margin-top: 1%;width: 800px;">

			<tr>
				<th width="200px">Fecha</th>
				<th width="200px">Cliente</th>
				<th width="300px">Lineas de Compra</th>
				<th width="300px">Total</th>
			</tr>

	<?php 
			if(empty($listPurchase)){ ?>
				
				<tr>
			<?php for($i=0;$i<4;$i++){ ?>
					
					<td align="center"><?php echo '-'?></td>
			<?php } ?>						
				</tr>
			<?php 
				}else{

					foreach ($listPurchase as $purchase) { ?>

						<tr>
							<td><?php echo $purchase->getDate();?></td>
							<td><?php echo $purchase->getClient()->getLastName().", ".$purchase->getClient()->getFirstName();?></td>
							<td>
							<table>
								<tr>
									<th width="200px">Evento</th>
									<th width="200px">Plaza</th>
									<th width="200px">Lugar</th>
									<th width="200px">Fecha</th>
									<th width="100px">Cantidad</th>
									<th width="100px">Precio</th>
									<th width="100px">Subtotal</th>
								</tr>
						<?php 
								if(!empty($purchase->getPurchaseLine())){
													
									$purchaseLines = $purchase->getPurchaseLine();
									
									foreach($purchaseLines as $line){ ?>
										<tr>
										
										<td><?php echo  $line->getEventSeat()->getCalendar()->getEvent()->getName(); ?></td>
										<td><?php echo $line->getEventSeat()->getTypeOfSeat()->getName(); ?></td>
										<td><?php echo  $line->getEventSeat()->getCalendar()->getEventPlace()->getName(); ?></td>
										<td><?php echo $line->getEventSeat()->getCalendar()->getDate(); ?></td>
										<td><?php echo $line->getQuantity(); ?></td>
										<td><?php echo $line->getPrice(); ?></td>
										<td><?php echo $line->getTotal(); ?></td>
										<td align="center">
																
										<form action="../<?php FRONT_ROOT?>TicketController/QRgenerator" method="POST">

										<input hidden type="text" name="lineId" value="<?php echo $line->getId();?>">
																	
										<button class="btn btn-dark btn-block btn-lg" style="width: 100px; font-size: 12px; box-shadow: 0px -1px 15px 4px #ff9d00;" type="submit" name="btn-QRgen">Ver Tickets</button>

										</form>
										<br>
										<?php $id = "p".$purchase->getId()."l".$line->getId(); ?>
										<form id="<?php echo $id;?>" action="../<?php FRONT_ROOT?>PurchaseController/reSendEmail" method="POST">

										<input hidden type="text" name="lineId" value="<?php echo $line->getId();?>">
																	
										<button class="btn" style="width: 130px; font-size: 12px;box-shadow: 0px -1px 15px 4px #00aaff;" type="submit" name="btn-sendmail" onClick = "prevent(<?php echo '\'#'.$id.'\''?>)">Reenviar Tickets</button>

										</form>

										</td>
										</tr>

								<?php } 
								}else
									for($i=0; $i<7; $i++){ ?>
										<td> - </td>
							<?php }	?>
							</table>
							</td>
										
							<td><?php echo $purchase->getTotal(); ?></td>
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

<?php
namespace Views;

include('nav.php');

?>

<html>
	<head>
		
	</head>
	<body>
		
		<div align="center" style="margin: 15px;">
			<h2 style="color: orange; text-shadow: 3px 3px 3px black">Mis Tickets para:</h2>
			<h1 style="color: orange; text-shadow: 3px 3px 3px black"><?php echo $event;?></h1>
					
		<div style="width: 100px;">
            <a href="<?php echo FRONT_ROOT ?>PurchaseController/showPurchaseListView">
            <input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
            </a>
        </div>

	<?php   foreach($ticketList as $ticket){ ?>

				<div class="text-white p-5" align="center" border="2" style="background-image: url(../Views/img/ticketbg2.png);width: 500px; height: 252px;margin: 15px;">
					<br>
					<div style="width: 360px;height: 162px;">
							
						<div  style="text-align: left;float: left;width: 230px;margin-top: 10px">

							<h5><?php echo $date."hs";?><br>
							<?php echo $place;?><br>
							<?php echo $name;?><br>
							<?php echo $dni;?><br>
							<?php echo $type;?><br>
							<?php echo "Nro: ".$ticket->getNumber();?></h5>

						</div>

						<div style="float: right;width: 120px;">
								
							<img style="width: 120px; margin-top: 20px; margin-bottom: 20px;" src= "<?php echo FRONT_ROOT.TEMP_PATH.$ticket->getId().$fileType;?>"/>
						</div>
	               	</div>
				</div>
		<?php   } ?>
		</div>		
	</body>
</html>


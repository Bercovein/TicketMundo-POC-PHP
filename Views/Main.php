<?php

namespace Views;

use Controller\HomeController as HomeController;

include('nav.php');

if(isset($_SESSION["Userlogged"])){

	$path = FRONT_ROOT."PurchaseLineController/ShowAddView";
	
	if($_SESSION["Userlogged"]->getRol()=="A")
		$title = "Hola administrador!";
	else
		$title = "Hola usuario! Te estabamos esperando!";
}
else{
	$path = FRONT_ROOT."UserController/ShowLoginView";
	$title = "Bienvenido a TicketMundo HiperMegaRed!";
}

?>

<!DOCTYPE html>
<html>

	<body>
		
		<div align="center" style="margin-top: 5px;">
			<h1 style="color: orange; text-shadow: 3px 3px 3px black"><?php echo $title;?></h1>
		</div>
		<div class="container" align="center" border="2" style="margin-top: 1%">
		
  <?php if(!isset($listEvent))
			$listEvent = HomeController::index();

		foreach ($listEvent as $event){ 

  			if($event->getBanner()!=NULL){ ?>
				
				<a href="<?php echo $path.'/'.$event->getId(); ?>">
					<img style="width: 540px; height: 200px; margin: 5px;" src="<?php echo FRONT_ROOT.BANNER_PATH.$event->getBanner()?>">
				</a>
  <?php 	}
  		} ?>	
		</div>

	</body>
</html>
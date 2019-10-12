<?php 
	namespace Controller;

	use \Exception as Exception;


	use Model\PurchaseLine as PurchaseLine;
	use Model\Purchase as Purchase;


	use DAO\PDOEventSeats as PDOEventSeats;
	use DAO\PDOCalendar as PDOCalendar;
	use DAO\PDOClient as PDOClient;
	use DAO\PDOPurchase as PDOPurchase;
	use DAO\PDOPurchaseLine as PDOPurchaseLine;
	use DAO\PDOCard as PDOCard;


	class CartController{

		private $DAOEventSeats;
		private $DAOCalendar;
		private $DAOClient;
		private $DAOPurchase;
		private $DAOPurchaseLine;
		private $DAOCard;

		public function __construct()
	    {

	        $this->DAOEventSeats = new PDOEventSeats();
	        $this->DAOCalendar = new PDOCalendar();
	        $this->DAOClient = new PDOClient();
	       	$this->DAOPurchase = new PDOPurchase();
	       	$this->DAOPurchaseLine = new PDOPurchaseLine();
	       	$this->DAOCard = new PDOCard();
	    }

		public function showAddView($message = '',$mType ='')
	    {   
	    	try
	    	{
		    	$listEventSeats = $this->DAOEventSeats->getAll();
		    	$listCalendar = $this->DAOEventSeats->getCalendarsFromEventSeats();
		    	$title = "Agregar Compra";
		    	
		    	if(!empty($message))
			    	echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 	

		        include_once(VIEWS_PATH.'PurchaseLineManagement.php');
	        }
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Gestión de Carritos.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>';
	            require_once(VIEWS_PATH."Main.php");
	        }
	    }

	    public function showAddFestivalView($message = '',$mType ='')
	    {   
	    	try
	    	{
		    	$listEventSeats = $this->DAOEventSeats->getAll();
		    	$listCalendar = $this->DAOEventSeats->getFestivalCalendarsFromEventSeats(); 
		    	
		    	if(!empty($message))
			    	echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 	

		        include_once(VIEWS_PATH.'PurchaseFestivalManagement.php');
	        }
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Gestión de Carritos.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>';
	            require_once(VIEWS_PATH."Main.php");
	        }
	    }
	    
	    public function showConfirmPurchaseView(){

	    	try
	    	{
	    		$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

		    	$listPurchaseLine = $_SESSION[$client->getLastName().$client->getDni()];

		    	if(!empty($listPurchaseLine)){
		    		
		    		$listCards = $this->DAOCard->getByClient($client->getId());
		    		
		    		if(count($listCards)!=0){

		    			include_once(VIEWS_PATH.'ConfirmPurchase.php');
		    		
		    		}else{
		    			$message = 'Primero debe cargar una o mas tarjetas';
		    			$mType ='warning';
		    			echo '<script>swal("","' . $message . '","' . $mType . '");</script>';
		    			include_once(VIEWS_PATH."CardManagement.php");
		    		}
		    		
		    	}else{
		    		$message = 'Primero debe cargar el carrito con al menos un producto.';
		    		$mType ='warning';
		    		$this->showAddView($message,$mType);
		    	} 
	        }
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la pagina Confirmacion de la compra.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>';
	            $this->showCartView();
	        }
	    }


	    public function showCartView($message = '',$mType ='')
	    {   
	    	try
	    	{	
	    		if(!empty($message))
			    	echo '<script>swal("","' . $message . '","' . $mType . '");</script>';

		    	$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

		    	if(isset($client)){

		    		$listPurchaseLine = array();

			    	if(!empty($_SESSION[$client->getLastName().$client->getDni()]))
						$listPurchaseLine = $_SESSION[$client->getLastName().$client->getDni()];
					else{
						$_SESSION[$client->getLastName().$client->getDni()] = array();
					}
					
			        include_once(VIEWS_PATH.'Cart.php');
		    	}else{
		    		$message = "Para acceder a su carrito debe cargar sus datos primero.";
		    		$mType ='warning';
		    		echo '<script>swal("","' . $message . '","' . $mType . '");</script>';
		    		include_once(VIEWS_PATH."ClientManagement.php");
		    	}
	        }
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de lista del Carrito.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>';
	            require_once(VIEWS_PATH."Main.php");
	        }
	    }

	    public function newCartLine($idCalendar, $idEventSeat, $price, $quantity)
	    {
	    	try
	    	{
		    	$eventSeat = $this->DAOEventSeats->getById($idEventSeat);

		    	$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

		    	if(!empty($client)){

		    		if(!isset($_SESSION[$client->getLastName().$client->getDni()])){

						$_SESSION[$client->getLastName().$client->getDni()] = array();
						$id=0;
					}else{
						$id = count($_SESSION[$client->getLastName().$client->getDni()]);
					}

					if($eventSeat != NULL){

						$cartLines = $_SESSION[$client->getLastName().$client->getDni()];

						$lineId = $this->compareLines($cartLines, $eventSeat,$price);

						if(isset($lineId)){

							$oldQuantity = $cartLines[$lineId]->getQuantity();

							$cartLines[$lineId]->setQuantity($oldQuantity + $quantity);

							$message='Linea de compra actualizada con exito!\nRevise su carrito.';
							

						}else{

							$purchaseLine = new PurchaseLine();
							$purchaseLine->setId($id);
							$purchaseLine->setQuantity($quantity);
							$purchaseLine->setPrice($price);
							$purchaseLine->setEventSeat($eventSeat);

							array_push($cartLines, $purchaseLine);

							$message='Linea de compra agregada con exito!\nRevise su carrito.';
						}
						$mType ='success';
						$_SESSION[$client->getLastName().$client->getDni()] = $cartLines;
						
					}else{
						$message='No existe el asiento';
						$mType ='warning';
					}

					$this->showAddView($message,$mType);

				}else{
					$message = 'Debe cargar los datos de cliente primero.';
					$mType ='warning';
					echo '<script>swal("","' . $message . '","' . $mType . '");</script>';
					include_once(VIEWS_PATH."ClientManagement.php");
				}
					 
			}
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar agregar una linea de Carrito.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>';
	            $this->showCartView();
	        }
		}

		public function newFestivalCartLine($eventName, $idEventSeat, $price, $quantity)
		{
			try
	    	{
		    	$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

		    	if(!empty($client))
		    	{
		    		$seat = $this->DAOEventSeats->getById($idEventSeat);
		    		$seatType = $seat->getTypeOfSeat();

		    		if($seat != NULL)
					{	
			    		$listEventSeats = $this->DAOEventSeats->getEventSeatsByEventByTypeOfSeat($eventName,$seatType->getName());

			    		$price *= 0.8;

						foreach($listEventSeats as $eventSeat)
						{	
							if(!isset($_SESSION[$client->getLastName().$client->getDni()]))
				    		{
								$_SESSION[$client->getLastName().$client->getDni()] = array();
								$id = 0;
							}else
								$id = count($_SESSION[$client->getLastName().$client->getDni()]);

							$cartLines = $_SESSION[$client->getLastName().$client->getDni()];

							$lineId = $this->compareLines($cartLines, $eventSeat,$price);

							if(isset($lineId)){

								$oldQuantity = $cartLines[$lineId]->getQuantity();

								$cartLines[$lineId]->setQuantity($oldQuantity + $quantity);

								$message='Linea de compra actualizada con exito!\nRevise su carrito.';
								
							}else{

								$purchaseLine = new PurchaseLine();
								$purchaseLine->setId($id);
								$purchaseLine->setQuantity($quantity);
								$purchaseLine->setPrice($price);
								$purchaseLine->setEventSeat($eventSeat);

								array_push($cartLines, $purchaseLine);

								$message='Linea de compra agregada con exito!\nRevise su carrito.';
							}
								$_SESSION[$client->getLastName().$client->getDni()] = $cartLines;
						}
						$mType ='success';
					}else{
						$message='No existe el asiento';
						$mType ='warning';
					}

					$this->showAddView($message,$mType);

				}else{
					$message = 'Debe cargar los datos de cliente primero.';
					$mType ='warning';
					echo '<script>swal("","' . $message . '","' . $mType . '");</script>';
					include_once(VIEWS_PATH."ClientManagement.php");
				}	 
			}
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar agregar lineas de Carrito de Festival.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>';
	            $this->showCartView();
	        }
		}

		public function updateQuantity($lineId,$sign){

			try{
				$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

				$purchaseLine = $_SESSION[$client->getLastName().$client->getDni()];

				$quantity = $purchaseLine[$lineId]->getQuantity();

				$remanents = $purchaseLine[$lineId]->getEventSeat()->getRemanents();
				
				if($sign=='+')
					$quantity++;
				else
					$quantity--;

				if($quantity == 0){
					
					$message = "Imposible comprar 0 (cero) cantidad de entradas.";
					$mType ='warning';
					echo '<script>swal("","' . $message . '","' . $mType . '");</script>';

				}else if($quantity > $remanents){
					
					$message = "La cantidad supera el limite de entradas.";
					$mType ='warning';
					echo '<script>swal("","' . $message . '","' . $mType . '");</script>';
				}else{
					$purchaseLine[$lineId]->setQuantity($quantity);

					$_SESSION[$client->getLastName().$client->getDni()] = $purchaseLine;
				}

				$this->showCartView();
			}catch(Exception $ex){
	            $message = 'Oops ! \n\n Hubo un problema.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>';
	            $this->showCartView();
	        }
		}

		public function compareLines($cartLines,$eventSeat,$price){

			try{
				$answer = NULL;

				$seatType = $eventSeat->getTypeOfSeat();
				$seatCalendar = $eventSeat->getCalendar();

				for($i=0; $i<count($cartLines); $i++){

					$lineSeat = $cartLines[$i]->getEventSeat();
					$lineType = $lineSeat->getTypeOfSeat();
					$lineCalendar = $lineSeat->getCalendar();
					$linePrice = $cartLines[$i]->getPrice();

					if($seatType->getId() == $lineType->getId()){
						if($seatCalendar->getId() == $lineCalendar->getId()){
							if($price == $linePrice)
							{	
								$answer = $i;
								$i=count($cartLines);
							}
						}
					}
				}
				return $answer;
			}catch(Exception $ex){
	            $message = 'Oops ! \n\n Hubo un problema.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>';
	            $this->showCartView();
	        }
		}

		public function deleteCartLine($id){ 

			try
			{
				$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

				$purchaseLine = $_SESSION[$client->getLastName().$client->getDni()];

				for($i=0; $i<count($purchaseLine); $i++){

					if($purchaseLine[$i]->getId()==$id){
						
						unset($purchaseLine[$i]);
						$purchaseLine=array_values($purchaseLine);
					}
				}

				for($i=0; $i<count($purchaseLine); $i++){

					if($purchaseLine[$i]->getId()!=$i)
						$purchaseLine[$i]->setId($i);
				}

				$_SESSION[$client->getLastName().$client->getDni()] = $purchaseLine;

				$this->showCartView();
			}
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar eliminar una linea de Carrito.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>';
	            $this->showCartView();
	        }
		}

		public function deleteCart(){

			try
			{
				$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

				$session = $_SESSION[$client->getLastName().$client->getDni()];

				if($session){
					unset($_SESSION[$client->getLastName().$client->getDni()]);
					$message = "Carrito vaciado con exito!";
					$mType ='success';
				}else{
					$message = "Debe agregar al menos un item al carrito para vaciarlo, ya que este se encuentra vacío.";
					$mType ='warning';
				}
				$this->showCartView($message,$mType);
			}
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar eliminar el Carrito.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>';
	            $this->showCartView();
	        }
		}	
	}

?>

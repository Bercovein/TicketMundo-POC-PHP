<?php 
	namespace Controller;

	use \Exception as Exception;
	use Model\Purchase as Purchase;
	use Model\Ticket as Ticket;
	use Model\PurchaseLine as PurchaseLine;
	use Model\Client as Client;

	use DAO\PDOPurchase as PDOPurchase; 
	use DAO\PDOCard as PDOCard;
	use DAO\PDOClient as PDOClient;
	use DAO\PDOPurchaseLine as PDOPurchaseLine;
	use DAO\PDOEventSeats as PDOEventSeats;
	use DAO\PDOTicket as PDOTicket;

	use QRcodelib\QRcode as QRcode;

	class PurchaseController
	{
		private $DAOPurchase;
		private $DAOCard;
		private $DAOClient;
		private $DAOPurchaseLine;
		private $DAOEventSeats;
		private $DAOTicket;

		public function __construct ()
	    {
	        $this->DAOPurchase = new PDOPurchase();
	        $this->DAOCard = new PDOCard();
	        $this->DAOClient = new PDOClient();
	        $this->DAOPurchaseLine = new PDOPurchaseLine();
	        $this->DAOEventSeats = new PDOEventSeats();
	        $this->DAOTicket = new PDOTicket();
	    }

		public function showCartView($message = '',$mType = '')
	    {   
	    	try
	    	{
	    		if(!empty($message)){
		    		echo '<script>swal("","' . $message . '","' . $mType . '");</script>';     	
			    }	

		    	$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

		    	$listPurchaseLine = array();

		    	if(!empty($_SESSION[$client->getLastName().$client->getDni()]))
					$listPurchaseLine = $_SESSION[$client->getLastName().$client->getDni()];
				else
					$_SESSION[$client->getLastName().$client->getDni()] = array();
				
		        include_once(VIEWS_PATH.'Cart.php');
	        }
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de lista del Carrito.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>'; 
	            require_once(VIEWS_PATH."Main.php");
	        }
	    }

	    public function showPurchaseListView($message = '', $mType=''){

	    	try
	    	{
	    		if(!empty($message)){
		    		echo '<script>swal("","' . $message . '","' . $mType . '");</script>';     	
			    }
	    		
	    		if($_SESSION["Userlogged"]->getRol()=="A"){

					$listPurchase = $this->DAOPurchase->getAll();
					$title = 'Historial de compras';
	    		}else{
	    			$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

	    			if(isset($client)){
		    			$listPurchase = $this->DAOPurchase->getByClient($client->getId()); 
		    		
		    			$title = 'Mi historial de compras';
		    		}else{
		    			$message = "Para ver su historial de compra debe cargar sus datos primero.";
		    			$mType ='warning';
		    			echo '<script>swal("","' . $message . '","' . $mType . '");</script>';
		    			include_once(VIEWS_PATH."ClientManagement.php");
		    		}
	    		}
	    		include_once(VIEWS_PATH.'PurchaseList.php');
	    	}
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la pagina de historial de compras.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>'; 
	            require_once(VIEWS_PATH."Main.php");
	        }
	    }

	    public function ConfirmPurchase($date, $total, $idCard, $securityCode){

	    	try
	    	{	
	    		$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

	    		$card = $this->DAOCard->getById($idCard);

				if($card->getSecurityCode()==$securityCode){

					if(isset($_SESSION[$client->getLastName().$client->getDni()])){
		    			
		    			$listPurchaseLine = $_SESSION[$client->getLastName().$client->getDni()];

		    			$purchaseVerify = $this->remanentVerify($listPurchaseLine);

		    			if(empty($purchaseVerify)){

		    				$this->newPurchase($listPurchaseLine, $date, $client, $total);

		    				$_SESSION[$client->getLastName().$client->getDni()] = NULL;

		    				$message = 'Compra confirmada con exito! \nLa información de la compra ha sido enviada a su casilla de correo.';
		    				$mType ='success';

		    				$this->showPurchaseListView($message,$mType);

		    			}else{
		    				$message = 'No hay suficientes entradas para '.$purchaseVerify->getEventSeat()->getCalendar()->getEvent()->getName().'\nElija otra cantidad y/u otra plaza.';
		    				$mType ='warning';
		    				$this->showCartView($message,$mType);
		    			}	
		    		}else{
		    			$message = 'Debe cargar el carrito primero.';
		    			$mType ='warning';
		    			$this->showCartView($message,$mType);
		    		}

				}else{
					$message='Oops, parece que el codigo de seguridad no es valido.\nIntentalo de nuevo.';
					$mType ='warning';
					$this->showCartView($message,$mType);
				}
	        }
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar confirmar la compra.\n Consulte a su Administrador o vuelva a intentarlo.';
	            echo '<script>swal("","' . $message . '","error");</script>'; 
	            require_once(VIEWS_PATH."Cart.php");
	        }
	    }

	    public function reSendEmail($lineId){

	    	try
	    	{

		    	$ticketList = $this->DAOTicket->getByLineId($lineId);

		    	if($ticketList){

			    	$eventName = $ticketList[0]->getPurchaseLine()->getEventSeat()->getCalendar()->getEvent()->getName();

			    	$clientEmail = $ticketList[0]->getClient()->getUser()->getEmail();

			    	$this->sendMail($clientEmail,$eventName,$ticketList);
			    	$message = "Ticket/s reenviado/s con exito!";
			    	$mType = 'success';

			    }else{
			    	$message = "No se ha podido reenviar el mail.";
			    	$mType = 'warning';
			    }
			    $this->showPurchaseListView($message,$mType);

			}catch(Exception $ex){
				$message = 'Oops ! \n\n Hubo un problema al intentar reenviar el email.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."PurchaseList.php");
			}
	    }

	    public function sendMail($email,$eventName,$ticketList)
        {	
        	try
        	{
	            $attached = "";

				$headers = "From: TicketMundo <ticketmundohipermegared@gmail.com>\r";

				$affair = "Tus tickets para: ".$eventName;

				$headers .= "\nMIME-version: 1.0\n";
				$headers .= "Content-type: multipart/mixed;";
				$headers .= "boundary=\"--_Separator_--\"\n";

				$headerText = "----_Separator_--\n";
				$headerText .= "Content-type: text/plain;charset=iso-8859-1\n";
				$headerText .= "Content-transfer-encoding: 7BIT\n";
				$text = "\n\n\n"."Estos son tus codigos QR para ".$eventName."!";
				$text .= "\n\n"."Esperamos que tu experiencia en TicketMundo haya sido la mejor!";
				$text .= "\n\n"."(Cualquier inconveniente podes visualizar y/o reenviar tus tickets desde la pagina.)";
				$text .= "\n\n\n"."Gracias por confiar siempre en nuestro equipo!";


				$toSend = $headerText.$text;

				foreach ($ticketList as $ticket) {

					$ticketPath = $ticket->getId().".png";
					
					$file = fopen(TEMP_PATH.$ticketPath, 'r');
					$content = fread($file, filesize(TEMP_PATH.$ticketPath));

					$attached .= "\n\n----_Separator_--\n";
					$attached .= "Content-type: .png;name=\" ".$ticketPath." \"\n";
					$attached .= "Content-Transfer-Encoding: BASE64\n";
					$attached .= "Content-disposition: attachment;dataname=\" ".$ticketPath." \"\n\n";

					$attached .= chunk_split(base64_encode($content));
					fclose($file);
				}

				$toSend .= $attached."\n\n----_Separator_----\n";

				mail($email, $affair, $toSend, $headers);

			}catch(Exception $ex){
				$message = 'Oops ! \n\n Hubo un problema al intentar enviar el email.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."PurchaseList.php");
			}

        }

        public function QRgenerator($ticket){

	    	try
	    	{
				if(!file_exists(TEMP_PATH))
					mkdir(TEMP_PATH);

				$size = 5; /*tamaño de cada cuadro en px*/
				$level = "M"; /*L,M,Q,H nivel de encriptacion*/ 
				$frameSize = 1; /*marco blanco alrededor*/

				$dni = $ticket->getClient()->getDni();
				$name = $ticket->getClient()->getFirstName()." ".$ticket->getClient()->getLastName();
				
				$calendar = $ticket->getPurchaseLine()->getEventSeat()->getCalendar();
				
				$type = $ticket->getPurchaseLine()->getEventSeat()->getTypeOfSeat()->getName();
				
				$event = $calendar->getEvent()->getName();
				$place = $calendar->getEventPlace()->getName();
				$date = $calendar->getDate();
				
				$fileName = TEMP_PATH.$ticket->getId();
				$fileType= ".png";

				if(file_exists($fileName.$fileType))
					unlink($fileName.$fileType);

				if(!file_exists(TEMP_PATH.$fileName.$fileType)){
						
					$nroTicket = $ticket->getNumber();					

					$content = 	$name."\n".
							"Dni: ".$dni."\n".
							$date."\n".
							$event."\n".
							$place."\n".
							$type." ".$nroTicket."\n";

					QRcode::png($content, $fileName.$fileType, $level, $size, $frameSize);
				}

				return $fileName.$fileType;

			}catch(Exception $ex){
				$message = 'Oops ! \n\n Hubo un problema al intentar generar el codigo qr.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."Main.php");
			}
	    }

	    public function newTicket(PurchaseLine $purchaseLine, Client $client)
	    {
	    	try
            {
				$ticket = new Ticket();
				$ticket->setClient($client);
				$ticket->setPurchaseLine($purchaseLine);

				$quantity = $purchaseLine->getEventSeat()->getQuantity();
				$remanents = $purchaseLine->getEventSeat()->getRemanents();

				for($i=1; $i<=$purchaseLine->getQuantity(); $i++){
					
					$number = $quantity - $remanents + $i;
					$ticket->setNumber($number);
					$this->DAOTicket->add($ticket);
					
				}

				$ticketList = $this->DAOTicket->getByLineId($purchaseLine->getId());

				$eventName = $ticketList[0]->getPurchaseLine()->getEventSeat()->getCalendar()->getEvent()->getName();

                $clientEmail = $client->getUser()->getEmail();

				foreach ($ticketList as $qr) {
						
					$this->QRgenerator($qr);	
				}
				$this->sendMail($clientEmail,$eventName,$ticketList);
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar un Ticket.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."Main.php");
            }
		}


	    public function remanentVerify(Array $listPurchaseLine){

	    	try
	    	{	
	    		$purchaseLine = NULL;

	    		foreach ($listPurchaseLine as $line) {

			    	$seat = $line->getEventSeat();
			    		
			    	$dbSeat = $this->DAOEventSeats->getById($seat->getId());
			    		
			    	if($dbSeat->getRemanents()<$line->getQuantity()){
						
						$purchaseLine = $line;
						break;
			    	}
	    		}
		    	
		    	return $purchaseLine;
		    }
	        catch(Exception $ex)
	        {
	            $message = 'Oops ! \n\n Hubo un problema al intentar verificar las remanentes.';
	            echo '<script>swal("","' . $message . '","error");</script>'; 
	            require_once(VIEWS_PATH."Cart.php");
	        }
	    }

	    public function newPurchase(Array $purchaseLine, $date, $client, $total)
	    {
	    	try
            {	
				$purchase = new Purchase();
		    	$purchase->setTotal($total);
		    	$purchase->setDate($date);
		    	$purchase->setClient($client);

				$this->DAOPurchase->add($purchase);

				$purchaseId = $this->DAOPurchase->getLastPurchaseId($client->getId());

		    	foreach ($purchaseLine as $line){

		    		$this->DAOPurchaseLine->add($line, $purchaseId);

		    		$line->setId($this->DAOPurchaseLine->getLastPurchaseLineId($purchaseId));

		    		$seat =  $this->DAOEventSeats->getById($line->getEventSeat()->getId());

		    		$this->DAOEventSeats->editRemanents($seat->getId(), $seat->getRemanents() - $line->getQuantity());

		    		$this->newTicket($line, $client);
		    	} 
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar una Compra.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."Main.php");
            }
		}

		public function deletePurchase($id)
		{
			try
            {
				$this->DAOPurchase->delete($id);
				$this->showPurchaseListView(); 
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar eliminar una Compra.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."Main.php");
            }
		}

		public function getNextId()
		{
			try
            {
				$purchases=$this->DAOPurchase->getAll();
				$count=count($purchases);
				$id = 0;

				if($count>0){
					$count--;
					$id = $purchases[$count]->getId()+1;
				}
				return $id;
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema de tipo Exception.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."Main.php");
            }
		}

	}

?>

<?php 
	namespace Controller;

	use \Exception as Exception;
	use Model\Ticket as Ticket;

	use DAO\PDOTicket as PDOTicket; 
	use QRcodelib\QRcode as QRcode;

	class TicketController
	{
		private $DAOTicket;

		public function __construct ()
	    {
	        $this->DAOTicket = new PDOTicket(); 
	    }

	    public function showTicketView($purchaseLineId)
	    {   
	    	try
            {  
		    	$ticket = $this->DAOTicket->getByLineId($purchaseLineId);

		        include_once(VIEWS_PATH.'TicketView.php');
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de listas de Tickets.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    }

	    public function QRgenerator($purchaseLineId){

	    	try
	    	{
				if(!file_exists(TEMP_PATH))
					mkdir(TEMP_PATH);

				$ticketList = $this->DAOTicket->getByLineId($purchaseLineId);

				$size = 5; /*tamaño de cada cuadro en px*/
				$level = "M"; /*L,M,Q,H nivel de encriptacion*/ 
				$frameSize = 1; /*marco blanco alrededor*/

				$dni = $ticketList[0]->getClient()->getDni();
				$name = $ticketList[0]->getClient()->getFirstName()." ".$ticketList[0]->getClient()->getLastName();
				
				$calendar = $ticketList[0]->getPurchaseLine()->getEventSeat()->getCalendar();
				
				$type = $ticketList[0]->getPurchaseLine()->getEventSeat()->getTypeOfSeat()->getName();
				
				$event = $calendar->getEvent()->getName();
				$place = $calendar->getEventPlace()->getName();
				$date = $calendar->getDate();

				foreach ($ticketList as $ticket) {
				
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
				}
				include_once(VIEWS_PATH.'TicketView.php');

			}catch(Exception $ex){
				$message = 'Oops ! \n\n Hubo un problema al intentar generar el codigo qr.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
			}
	    }

		public function deleteTicket($id)
		{
			try
            {
				$this->DAOTicket->delete($id);
				$this->showAddView();
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar eliminar un Ticket.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
		}
	}

?>

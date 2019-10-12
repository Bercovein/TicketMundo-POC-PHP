<?php 
	namespace Controller;

	use \Exception as Exception;
	use Model\PurchaseLine as PurchaseLine;
	use Model\Ticket as Ticket;

	use DAO\PDOPurchaseLine as PDOPurchaseLine;
	use DAO\PDOEventSeats as PDOEventSeats;
	use DAO\PDOTicket as PDOTicket;
	use DAO\PDOCalendar as PDOCalendar;
	use DAO\PDOEvent as PDOEvent;

	class PurchaseLineController
	{
		private $DAOPurchaseLine;
		private $DAOEventSeats;
		private $DAOTicket;
		private $DAOCalendar;
		private $DAOEvent;

		public function __construct()
	    {        
	        $this->DAOPurchaseLine = new PDOPurchaseLine();
	        $this->DAOEventSeats = new PDOEventSeats();
	        $this->DAOTicket = new PDOTicket();
	        $this->DAOCalendar = new PDOCalendar();
	        $this->DAOEvent = new PDOEvent();
	    }

		public function showAddView($eventId = '')
	    {   
	    	try
            {	
            	if($_SESSION["Userlogged"]->getRol()=="C"){
            	
	            	$listPurchaseLine = $this->DAOPurchaseLine->getAll();
			    	$listEventSeats = $this->DAOEventSeats->getAll();

	            	if(!empty($eventId)){
	            		$event = $this->DAOEvent->getById($eventId);

	            		if($event != null)
	            		{
		            		$listCalendar = $this->DAOEventSeats->getByEvent($event->getName());
		            		if(!empty($listCalendar))
		            			$title = "Agregar Compra de <br>".$listCalendar[0]->getEvent()->getName();
		            		else
		            			require_once(VIEWS_PATH."Main.php");
	            		}else{
	            			$message = "Evento no encontrado";
	            			$mType ='warning';
	            			echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 
	            			require_once(VIEWS_PATH."Main.php");
	            		}

	            	}else{
	            		$listCalendar = $this->DAOEventSeats->getCalendarsFromEventSeats();
	            		$title = "Agregar Compra";
	            	}
			        include_once(VIEWS_PATH.'PurchaseLineManagement.php');
			    }else
			    	require_once(VIEWS_PATH."Main.php");
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión de Linea de Compra.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    }

	    public function searchBy($name, $option){

	    	try
            {
            	$name = ucwords($name);

		    	if($option == "event")
		    		$listCalendar = $this->DAOCalendar->getByEvent($name);

		    	else if($option == "artist")

		    		$listCalendar = $this->DAOCalendar->getByArtist($name);

		    	if(!empty($listCalendar)){

		    		$listPurchaseLine = $this->DAOPurchaseLine->getAll();
					$listEventSeats = $this->DAOEventSeats->getAll();
					$title = "Agregar Compra para <br>".$name;

					include_once(VIEWS_PATH.'PurchaseLineManagement.php');

		    	}else{
		    		$message = "No hay eventos asignados a la busqueda de ".$name.". Asegurese de haber escrito bien su busqueda.";
		    		echo '<script>swal("","' . $message . '","warning");</script>';
		    		require_once(VIEWS_PATH."Main.php");
		    	}
	    	}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión de Linea de Compra.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    }

	    public function newPurchaseLine($idCalendar, $idEventSeat, $price, $quantity)
	    {
	    	try
            {
		    	$eventSeat = $this->DAOEventSeats->getById($idEventSeat);

				if($eventSeat != NULL){

					$purchaseLine = new PurchaseLine();
					$purchaseLine->setQuantity($quantity);
					$purchaseLine->setPrice($price);
					$purchaseLine->setEventSeat($eventSeat);

					$this->DAOPurchaseLine->add($purchaseLine);

					$message='Linea de compra agregada con exito!';
					$mType ='success';

				}else{
					$message='La Linea de compra ya existe.';
					$mType ='warning';
				}
				echo '<script>swal("","'.$message.'","'.$mType.'");</script>';
				$this->showAddView(); 
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar una Linea de Compra.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
		}

		public function getNextId()
		{
			try
            {
				$lines=$this->DAOPurchaseLine->getAll();
				$count=count($lines);
				$id = 0;

				if($count>0){
					$count--;
					$id = $lines[$count]->getId()+1;
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

		// Funciones para Ajax

		public function chargeSelect()
		{
			try
            {
		        if(isset($_POST["idCalendar"]))
		        {
			        $opciones = '<option value="0"> Elige una plaza</option>';

			        $EventseatsList = $this->DAOEventSeats->getByCalendarId($_POST["idCalendar"]);

			        foreach ($EventseatsList as $eventSeats)
			        {
			           $opciones.='<option value="'.$eventSeats->getId().'">$'.$eventSeats->getPrice()." - ".$eventSeats->getTypeOfSeat()->getName(). " (Quedan: ".$eventSeats->getRemanents().')</option>';
			        }
			        echo $opciones;
		        }
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar las Plazas Eventos disponibles.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    }

	    public function chargePrice()
	    {
	    	try
            {
		        if(isset($_POST["idEventSeats"]))
		        {
			        $eventSeats = $this->DAOEventSeats->getById($_POST["idEventSeats"]);

			        $opciones.='<option value="'.$eventSeats->getPrice().'">$'.$eventSeats->getPrice().'</option>';

			        echo $opciones;
		        }
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar cargar el precio.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    } 

	    public function chargeMaxQuantity(){
	    	try
            {
		        if(isset($_POST["idEventSeats"]))
		        {
			        $eventSeats = $this->DAOEventSeats->getById($_POST["idEventSeats"]);

			        $opciones.='<option value="'.$eventSeats->getQuantity().'">$'.$eventSeats->getQuantity().'</option>';

			        echo $opciones;
		        }
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar cargar el precio.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    }
	    
	    //ESTO ES PARA PURCHASEFESTIVALMANAGEMENT.PHP 

	    public function chargeDateCalendar()
		{
			try
            {
		        if(isset($_POST["eventName"]))
		        {
			        $calendarList = $this->DAOCalendar->getByEvent($_POST["eventName"]);

			        foreach ($calendarList as $calendar)
			           $opciones.='<option value="'.$calendar->getId().'">'.$calendar->getDate().'</option>';

			        echo $opciones;
		        }
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar las Fechas disponibles del festival.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    }

	    public function chargeEventSeats()
	    {
	    	try
            {
		        if(isset($_POST["eventName"]))
		        {
		        	$opciones = '<option value="0"> Elige una plaza</option>';

		        	$calendar = $this->DAOCalendar->getByEvent($_POST["eventName"]);
			        $eventSeatsList = $this->DAOEventSeats->getByCalendarId($calendar[0]->getId());

			        foreach ($eventSeatsList as $eventSeats) {
			        	$opciones.= '<option value="'.$eventSeats->getId().'">$'.$eventSeats->getPrice()." - ".$eventSeats->getTypeOfSeat()->getName().'</option>';
			        } 

			        echo $opciones;
		        }
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar las Plazas Eventos disponibles.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    }

	    //ESTO ES PARA PURCHASEFESTIVALMANAGEMENT2.PHP 

	    public function chargeDateCalendar2()
		{
			try
            {
		        if(isset($_POST["eventName"]))
		        {
			        $opciones = '<option value="0"> Elige una Fecha</option>';

			        $calendarList = $this->DAOCalendar->getByEvent($_POST["eventName"]);

			        foreach ($calendarList as $calendar)
			        {
			           $opciones.='<option value="'.$calendar->getId().'">'.$calendar->getDate().'</option>';
			        }
			        echo $opciones;
		        }
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar las Fechas disponibles del festival.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    }
	 	
	 	public function chargeSeatsMultiple()
		{
			try
            {
		        if(isset($_POST["idCalendarArray"]))
		        {	
		        	$opciones = '';
		        	$idCalendarArray = json_decode($_POST['idCalendarArray']);

		        	for($i = 0; $i < count($idCalendarArray); $i++) {

				       	$EventseatsList = $this->DAOEventSeats->getByCalendarId($idCalendarArray[$i]);	

				       	$opciones.='<option value="0"> Elige una plaza</option>';

					    foreach ($EventseatsList as $eventSeats)
					    {
					        $opciones.='<option value="'.$eventSeats->getId().'">$'.$eventSeats->getPrice()." - ".$eventSeats->getTypeOfSeat()->getName()." (Quedan: ".$eventSeats->getRemanents().')</option>'; 
					    }
					    $opciones.='*';
				  	} 
				  	//$arr = array("resultado"=>$opciones);
				  	//echo json_encode($opciones);
				  	echo $opciones;
		        }
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar las Plazas Eventos disponibles.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    }   


	    public function chargeDateFestival()
		{
			try
            {
		        if(isset($_POST["idCalendarArray"]))
		        {	
		        	$opciones = '';
		        	$idCalendarArray = json_decode($_POST['idCalendarArray']);
		        	$calendarList = array();

		        	for($i = 0; $i < count($idCalendarArray); $i++) {

				       	$calendar = $this->DAOCalendar->getById($idCalendarArray[$i]);	
				       	array_push($calendarList, $calendar);
				    }	
					foreach ($calendarList as $c)
					{
					    $opciones.='*';
					    $opciones.='<option value="'.$c->getDate().'">'.$c->getDate().'</option>';
					}
				  	echo $opciones;
		        }
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar las Plazas Eventos disponibles.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    } 
	}

?>

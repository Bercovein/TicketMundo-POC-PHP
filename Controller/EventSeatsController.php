<?php 
    namespace Controller;

    use \Exception as Exception;
    use Model\EventSeats as EventSeats;
    use Model\TypeOfSeat as TypeOfSeat;

    use DAO\PDOEventSeats as PDOEventSeats;
    use DAO\PDOTypeOfSeat as PDOTypeOfSeat;
    use DAO\PDOCalendar as PDOCalendar;
    use DAO\PDOPurchaseLine as PDOPurchaseLine;


    class EventSeatsController
    {
    	private $DAOEventSeats;
    	private $DAOTypeOfSeat;
    	private $DAOCalendar;
        private $DAOPurchaseLine;

    	public function __construct ()
        {
            $this->DAOEventSeats = new PDOEventSeats();
           	$this->DAOTypeOfSeat = new PDOTypeOfSeat();
            $this->DAOCalendar = new PDOCalendar();
            $this->DAOPurchaseLine = new PDOPurchaseLine();
            
        }

    	public function showAddView($message = '',$mType ='')
        {   
            try
            {
            	$listEventSeats = $this->DAOEventSeats->getAll();
            	$listTypeOfSeat = $this->DAOTypeOfSeat->getAll();
            	$listCalendar = $this->DAOCalendar->getAllWithoutFestival();
                $path = 'newEventSeats';
                $title = "Asientos";

            	if(!empty($message))
        	    	echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 	

                include_once(VIEWS_PATH.'EventSeatsManagement.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión de Plaza Eventos.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showListView()
        {     
            try
            {
            	$listCalendar = $this->DAOCalendar->getAll();
                $listEventSeats = $this->DAOEventSeats->getAll();

                include_once(VIEWS_PATH.'EventSeatsList.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de listas de Plaza Eventos.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showSeatsFestivalView(){
             try
            {
                $listEventSeats = $this->DAOEventSeats->getAll();
                $listTypeOfSeat = $this->DAOTypeOfSeat->getAll();
                $listCalendar = $this->DAOCalendar->getCalendarFestival();
                $path = 'newEventSeatsFestival';
                $title = "Asientos Festival";

                if(!empty($message))
                    echo '<script>swal("","' . $message . '","' . $mType . '");</script>';  

                include_once(VIEWS_PATH.'EventSeatsManagement.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión de Plaza Eventos.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showUpdateAddView($id,$path)
        {   
            try
            {   
                $auxListSeat = $this->DAOEventSeats->getByCalendarId($id);

                if($auxListSeat != NULL)
                {
                    foreach($auxListSeat as $seat){
                        if($this->DAOPurchaseLine->getByEventSeats($seat->getId()) != NULL){
                            $message = 'No es posible agregar o editar asiento/s ya que esta relacionado con al menos una Linea de compra.'; 
                            $mType ='warning';
                            $this->showAddView($message,$mType);
                        }
                    }

                        $listEventSeats = $this->DAOEventSeats->getAll();
                        $calendar = $this->DAOCalendar->getById($id);
                        
                        $capacity = 0;
                        $total = $calendar->getEventPlace()->getCapacity();
                        $seatList = array();
                        $typeList = array();

                        foreach ($listEventSeats as $seat) {

                            if($seat->getCalendar()->getId() == $calendar->getId()){

                                array_push($seatList, $seat);
                                array_push($typeList, $seat->getTypeOfSeat());
                                $capacity += $seat->getQuantity();
                            }
                        }

                        if($path == "newEventSeats"){

                            $listTypeOfSeat = $this->DAOTypeOfSeat->getAll(); 
                            $seatList = array();

                            foreach ($listTypeOfSeat as $type){
                                if(!in_array($type, $typeList)){

                                    $seat = new Eventseats();
                                    $seat->setQuantity(0);
                                    $seat->setPrice(0);
                                    $seat->setTypeOfSeat($type);

                                    array_push($seatList, $seat);
                                }
                            }
                        }
                        require_once(VIEWS_PATH."EventSeatsUpdateAdd.php");
                }else{
                    $message = 'Calendario sin Asientos';
                    $mType ='warning';
                    $this->showAddView($message,$mType);
                }
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Modificación de Plaza Evento.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function newEventSeats($idCalendar, Array $idTypeOfSeats, Array $prices, Array $quantities)
        {	
            try
            {
                $message = "";
                $in = "";
                $out = "";

                $calendar = $this->DAOCalendar->getById($idCalendar);

            	if($calendar != NULL && $idTypeOfSeats != NULL){

                    $capacity = $calendar->getEventPlace()->getCapacity();
                    $seatList = $this->DAOEventSeats->getByCalendarId($idCalendar);

                    if($this->sumCapacity($seatList,$quantities) <= $capacity){

                		for($i = 0; $i < count($idTypeOfSeats); $i++){

                            $type = $this->DAOTypeOfSeat->getById($idTypeOfSeats[$i]);

                            if($prices[$i]>0 && $quantities[$i]>0){

                                if($this->existTypeOfSeat($idCalendar, $type) != $idTypeOfSeats[$i]){
                                    
                        		    $eventSeats = new EventSeats();

                        			$eventSeats->setQuantity($quantities[$i]);
                        			$eventSeats->setPrice($prices[$i]);
                        			$eventSeats->setRemanents($quantities[$i]);	
                        			$eventSeats->setTypeOfSeat($type);
                                    $eventSeats->setCalendar($calendar);
                                    
                        			$this->DAOEventSeats->add($eventSeats);

                                    $in = $in ." ". $type->getName(); 
                        	    }else
                                   $out = $out ." ". $type->getName();   
                            }
                        }
                    }else{
                        $message = "La cantidad de entradas disponible supera la capacidad del lugar.";
                        $mType ='warning';
                    }

                    if(!empty($in)){
        			    $message = "La/s plaza/s".$in." agregada/s con exito.\\n";
                        $mType ='success';
                    }
                    if(!empty($out)){
                        $message = $message . $out." ya existen.";
                        $mType = 'warning';
                    }
        		}else{
        			$message = "El calendario no existe.";
                    $mType ='warning';
                }

        		$this->showAddView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar un Plaza Evento.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
    	}

        public function newEventSeatsFestival($idCalendar, Array $idTypeOfSeats, Array $prices, Array $quantities)
        {   
            try
            {
                $message = "";
                $out = "";
                $mType = "";

                $eventName = $this->DAOCalendar->getById($idCalendar)->getEvent()->getName();
                $listCalendar = $this->DAOCalendar->getByEvent($eventName);

                $listEventSeats = array();

                foreach($listCalendar as $calendar)
                {
                    if($calendar != NULL && $idTypeOfSeats != NULL){

                        $capacity = $calendar->getEventPlace()->getCapacity();
                        $seatList = $this->DAOEventSeats->getByCalendarId($idCalendar);

                        if($this->sumCapacity($seatList,$quantities) <= $capacity){

                            for($i = 0; $i < count($idTypeOfSeats); $i++){

                                $type = $this->DAOTypeOfSeat->getById($idTypeOfSeats[$i]);

                                if($prices[$i]>0 && $quantities[$i]>0){

                                    if($this->existTypeOfSeat($idCalendar, $type) != $idTypeOfSeats[$i]){
                                        
                                        $eventSeats = new EventSeats();

                                        $eventSeats->setQuantity($quantities[$i]);
                                        $eventSeats->setPrice($prices[$i]);
                                        $eventSeats->setRemanents($quantities[$i]); 
                                        $eventSeats->setTypeOfSeat($type);
                                        $eventSeats->setCalendar($calendar);
                                        
                                        array_push($listEventSeats, $eventSeats);

                                    }else
                                       $out = $out ." ". $type->getName();   
                                }
                            }
                        }else{
                            $message = "La cantidad de entradas disponible supera la capacidad del lugar.";
                            $mType ='warning';
                        }

                        if(!empty($out)){
                            $message = $message . $out." ya existen.";
                            $mType = 'warning';
                        }
                    }else{
                        $message = "El calendario no existe.";
                        $mType ='warning';
                    }

                    if($mType == 'warning')
                        $this->showSeatsFestivalView($message,$mType);
                }

                foreach($listEventSeats as $seats)
                    $this->DAOEventSeats->add($seats);

                $message = "La/s plaza/s fueron agregada/s con exito.";
                $mType ='success';

                $this->showSeatsFestivalView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar un Plaza Evento en el Festival.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function existTypeOfSeat($idCalendar, TypeOfSeat $type)
        {
            try
            {
                $seatList = $this->DAOEventSeats->getByCalendarId($idCalendar);

                $verify = NULL;

                foreach($seatList as $seat){

                    $seatType=$seat->getTypeOfSeat();

                    if($seatType->getId() == $type->getId() && $seatType->getName() == $type->getName()){

                        $verify = $seatType->getId();
                        break;
                    }
                }
                return $verify;
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar eliminar un Plaza Evento.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function sumCapacity(Array $seatList,Array $quantities)
        {
            try
            {    
                $sum = 0;

                foreach ($quantities as $quantity)
                    $sum += $quantity;

                foreach ($seatList as $seat)
                    $sum += $seat->getQuantity();

                return $sum;
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema de tipo Exception.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function updateEventSeats($idCalendar, Array $idTypeOfSeats, Array $prices, Array $quantities)
        {   
            try
            {
                $message = "";
                $in = "";

                $calendar = $this->DAOCalendar->getById($idCalendar);

                if($calendar != NULL && $idTypeOfSeats != NULL){

                    $sum = 0;

                    foreach ($quantities as $quantity)
                        $sum += $quantity;

                    if($sum <= $calendar->getEventPlace()->getCapacity()){

                        for($i = 0; $i < count($idTypeOfSeats); $i++){

                            $type = $this->DAOTypeOfSeat->getById($idTypeOfSeats[$i]);

                            if($prices[$i] > 0 && $quantities[$i] > 0){

                                $eventSeats = new EventSeats();

                                $eventSeats->setQuantity($quantities[$i]);
                                $eventSeats->setPrice($prices[$i]);
                                $eventSeats->setRemanents($quantities[$i]); 
                                $eventSeats->setTypeOfSeat($type);
                                $eventSeats->setCalendar($calendar);
                                    
                                $this->DAOEventSeats->update($eventSeats);

                                $in = $in ." ". $type->getName(); 
                            }
                        }
                    }else{
                        $message = "La cantidad de entradas disponible supera la capacidad del lugar.";
                        $mType ='warning';
                    }

                    if(!empty($in)){
                        $message = "La/s plaza/s".$in." actualizada/s con exito.\\n";
                        $mType ='success';
                    }
                }else{
                    $message = "El calendario no existe.";
                    $mType = 'warning';
                }

                $this->showAddView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar actualizar la Plaza Evento.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

    	public function deleteEventSeats($id)
        {
            try
            {
                if($this->DAOPurchaseLine->getByEventSeats($id) != NULL)
                {
                    $message = 'No se es posible Eliminar este asiento ya que esta relacionado con al menos una Linea de compra.';
                    $mType ='warning';
                }
                else
                {   
                    $this->DAOEventSeats->delete($id);
                    $message = "Asiento eliminado con exito!";
                    $mType = 'success';
                }
        		$this->showAddView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar eliminar un Plaza Evento.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            } 
    	}

    }

?>

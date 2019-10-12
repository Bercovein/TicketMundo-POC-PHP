<?php 
    namespace Controller;

    use \Exception as Exception;
    use Model\Calendar as Calendar;

    use DAO\PDOCalendar as PDOCalendar;
    use DAO\PDOEvent as PDOEvent;
    use DAO\PDOEventPlace as PDOEventPlace;
    use DAO\PDOArtist as PDOArtist;
    use DAO\PDOEventSeats as PDOEventSeats;
    use DAO\PDOCategory as PDOCategory;

    class CalendarController
    {
        private $DAOCalendar;
        private $DAOEvent;
        private $DAOEventPlace;
        private $DAOArtist;
        private $DAOEventSeats;
        private $DAOCategory;

        public function __construct ()
        {
        
            $this->DAOCalendar = new PDOCalendar();
            $this->DAOEvent = new PDOEvent();
            $this->DAOEventPlace = new PDOEventPlace();
            $this->DAOArtist = new PDOArtist();
            $this->DAOEventSeats = new PDOEventSeats(); 
            $this->DAOCategory = new PDOCategory(); 
        }

        public function showMain(){

            $listEvent = $this->DAOEvent->getAllActives();

            include_once VIEWS_PATH.'Main.php';
        }

        public function showAddView($message = '',$mType = ''){   
           
            try
            {
                $listEvent = $this->DAOEvent->getAllActivesWithoutFestivals();

                if(count($listEvent)!=0){
                    
                    $listEventPlace = $this->DAOEventPlace->getAllActives();

                    if(count($listEventPlace)!=0){

                        $listArtist = $this->DAOArtist->getAllActives();

                        if(count($listArtist)!=0){

                            $listEventSeats =  $this->DAOEventSeats->getAll();
                            $listCalendar = $this->DAOCalendar->getAll();

                            if(!empty($message))
                                echo '<script>swal("","' . $message . '","' . $mType . '");</script>';   

                            include_once(VIEWS_PATH.'CalendarManagement.php');
                        
                        }else{
                            $message = "Primero debe cargar Artistas.";
                            $mType = 'warning';
                            echo '<script>swal("","' . $message . '","' . $mType . '");</script>';   
                            include_once(VIEWS_PATH.'ArtistManagement.php');
                        }
                    
                    
                    }else{
                        $message = "Primero debe cargar Lugares.";
                        $mType = 'warning';
                        echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 
                        include_once(VIEWS_PATH.'EventPlaceManagement.php');   
                    }
                    
                }else{
                    $listCategory = $this->DAOCategory->getAllActives();

                    if(count($listCategory)!=0){
                        $message = "Primero debe cargar Eventos.";
                        $mType = 'warning';
                            echo '<script>swal("","' . $message . '","' . $mType . '");</script>';    
                        include_once(VIEWS_PATH.'EventManagement.php');
                    }else {
                        $message = "Primero debe cargar Categorias.";
                        $mType = 'warning';
                        echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 
                        include_once(VIEWS_PATH.'CategoryManagement.php'); 
                    }
                }
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión de Calendarios.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                $this->showMain();
            }
        }

        public function showFestivalView($message = '', $mType = '')
        { 
            try
            {
                $auxList = $this->DAOEvent->getFestivalsActives();
                $listEvent = array();

                foreach ($auxList as $event) {
                    if(count($this->DAOCalendar->getByEvent($event->getName())) == 0)
                        array_push($listEvent, $event);
                }

                if(count($listEvent)!=0){
                    
                    $listEventPlace = $this->DAOEventPlace->getAllActives();

                    if(count($listEventPlace)!=0){

                        $listArtist = $this->DAOArtist->getAllActives();

                        if(count($listArtist)!=0){

                            $listEventSeats =  $this->DAOEventSeats->getAll();
                            $listCalendar = $this->DAOCalendar->getAll();

                            if(!empty($message))
                                echo '<script>swal("","' . $message . '","' . $mType . '");</script>';   

                            include_once(VIEWS_PATH.'FestivalManagement.php');
                        
                        }else{
                            $message = "Primero debe cargar Artistas.";
                            $mType = 'warning';
                            echo '<script>swal("","' . $message . '","' . $mType . '");</script>';   
                            include_once(VIEWS_PATH.'ArtistManagement.php');
                        }
                      
                    }else{
                        $message = "Primero debe cargar Lugares.";
                        $mType = 'warning';
                        echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 
                        include_once(VIEWS_PATH.'EventPlaceManagement.php');   
                    }
                    
                }else{
                    $listCategory = $this->DAOCategory->getAllActives();

                    if(count($listCategory)!=0){
                        $message = "Primero debe cargar Eventos.";
                        $mType = 'warning';
                            echo '<script>swal("","' . $message . '","' . $mType . '");</script>';    
                        include_once(VIEWS_PATH.'EventManagement.php');
                    }else {
                        $message = "Primero debe cargar Categorias.";
                        $mType = 'warning';
                        echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 
                        include_once(VIEWS_PATH.'CategoryManagement.php'); 
                    }
                }
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión de festivales.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                $this->showMain();
            }
        } 


        public function showListView()
        {   
            try
            {
                $listCalendar = $this->DAOCalendar->getAll();
                $listEventSeats =  $this->DAOEventSeats->getAll();

                include_once(VIEWS_PATH.'CalendarList.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de listas de Calendarios.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                $this->showMain();
            }
        }

        public function showUpdateView($id)
        {      
            try
            {
                $calendar = $this->DAOCalendar->getById($id);
                $listEvent = $this->DAOEvent->getAllActives();
                $listEventPlace = $this->DAOEventPlace->getAllActives();
                $listArtist = $this->DAOArtist->getAllActives();

                include_once(VIEWS_PATH.'CalendarUpdate.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Modificación de Calendarios.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                $this->showListView();
            }
        }

        public function newCalendar($date, $idEvent, $time, $idEventPlace, Array $idArtistList)
        {   
            try
            {   
                $nextId = $this->getNextId();
                $event = $this->DAOEvent->getById($idEvent);
                $eventPlace = $this->DAOEventPlace->getById($idEventPlace);
                $artistList = array();

                foreach($idArtistList as $id){
                    $artist = $this->DAOArtist->getById($id);
                    array_push($artistList, $artist);
                }
                
                if($this->DAOCalendar->getById($nextId) == NULL){

                    $calendar = new Calendar();
                    $calendar->setId($nextId);
                    $calendar->setDate($date."\\".$time);
                    $calendar->setEvent($event);
                    $calendar->setEventPlace($eventPlace);
                    $calendar->setArtistList($artistList);
                   
                    $this->DAOCalendar->add($calendar);

                    $message = "Calendario agregado con exito!";
                    $mType = 'success';
                }else{
                    $message = "El Calendario ya existe.";
                    $mType = 'warning';
                }

                $this->showAddView($message,$mType); 
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar el Calendario.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                $this->showMain();
            }
        }

        public function newFestival($idEvent, Array $idArtistList, $idEventPlace, $days, $date, $time)
        {   
            try
            {   
                $event = $this->DAOEvent->getById($idEvent);
                $eventPlace = $this->DAOEventPlace->getById($idEventPlace);
                $artistList = array();

                foreach($idArtistList as $id){
                    $artist = $this->DAOArtist->getById($id);
                    array_push($artistList, $artist);
                }
                
                for($i = 0; $i < $days; $i++)
                {   
                    $newDate = strtotime ( '+'.$i.' day' , strtotime ( $date ) ) ;
                    $newDate = date ( 'Y-m-j' , $newDate );

                    $calendar = new Calendar();
                    $calendar->setDate($newDate."\\".$time);
                    $calendar->setEvent($event);
                    $calendar->setEventPlace($eventPlace);
                    $calendar->setArtistList($artistList);
 
                    $this->DAOCalendar->add($calendar);
                }

                $message = "Calendario agregado con exito!";
                $mType = 'success';

                $this->showAddView($message,$mType); 
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar el Calendario.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                $this->showMain();
            }
        }

        public function deleteCalendar($id)
        {
            try
            {
                if($this->DAOEventSeats->getByCalendarId($id) != NULL)
                {
                    $message = 'No se es posible modificar el estado de este calendario ya que esta relacionado con al menos un Asiento.';
                    $mType = 'warning';
                }
                else
                {   
                    $this->DAOCalendar->delete($id);
                    $message = "Estado del Calendario modificado con exito!";
                    $mType = 'success';
                }
                $this->showAddView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar eliminar el Calendario.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                $this->showListView();
            }
        }
        public function deleteArtistFromCalendar($calendarId, $artistId)
        {
            try
            { 
                $this->DAOCalendar->deleteArtistFromCalendar($calendarId, $artistId);

                $message = "Artista desvinculado del calendario con exito!";
                echo '<script>swal("","' . $message . '","success");</script>';
    
                $this->showAddView();
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar eliminar al artista del Calendario.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                $this->showListView();
            }
        }

        public function updateCalendar($id, $date, $idEvent, $idEventPlace, Array $idArtistList)
        {   
            try
            {
                $event = $this->DAOEvent->getById($idEvent);
                $eventPlace = $this->DAOEventPlace->getById($idEventPlace);
                $artistList = array();

                
                foreach($idArtistList as $idArt){
                    if($idArt != 0){
                        $artist = $this->DAOArtist->getById($idArt);
                        array_push($artistList, $artist);
                    }
                }
               
                if($this->DAOCalendar->getById($id) != NULL){

                    $calendar = new Calendar();
                    $calendar->setId($id);
                    $calendar->setDate($date);
                    $calendar->setEvent($event);
                    $calendar->setEventPlace($eventPlace);
                    $calendar->setArtistList($artistList);
                   
                    $this->DAOCalendar->update($calendar);

                    $message = "Calendario actualizado con exito!";
                    $mType = 'success';
                }else{
                    $message = "El Calendario no existe.";
                    $mType = 'warning';
                }

                $this->DAOCalendar->updateAll();
                $this->DAOEvent->updateAll();
                $this->showAddView($message,$mType); 
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar actualizar el Calendario.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                $this->showListView();
            }
        }

        public function getNextId()
        {
            try
            {
                $calendars=$this->DAOCalendar->getAll();
                $id = 0;

                foreach($calendars as $calendar){
                    if($id<$calendar->getId())
                        $id=$calendar->getId();
                }
                return $id+1;
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema de tipo Exception.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                $this->showMain();
            }
        }

        public function chargeDate()
        {
            try
            {
                if(isset($_POST["cantDate"]))
                {
                    $opciones = '<option value="0"> Elige una plaza</option>';

                    $EventseatsList = $this->DAOEventSeats->getByCalendarId($_POST["cantDate"]);

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

    }

?>

<?php
namespace Controller;

use DAO\PDOEvent as PDOEvent;
use DAO\PDOCalendar as PDOCalendar;
use DAO\PDOEventSeats as PDOEventSeats;

class HomeController{	
	
	public static function index(){

		try{
			$daoEvent = new PDOEvent();
			$daoCalendar = new PDOCalendar();
			$daoEventSeats = new PDOEventSeats();

			$daoCalendar->updateAll();
			$daoEvent->updateAll();

			$listEvent = $daoEventSeats->getEventsFromCalendarsFromEventSeats();

			include_once VIEWS_PATH.'Main.php';

			return $listEvent;
		}catch(Exception $ex){

            $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Gestión de Artistas.\n Consulte a su Administrador o vuelva a intentarlo.';
            echo '<script>swal("","' . $message . '","error");</script>';                
            require_once(VIEWS_PATH."Main.php");
        }
	}
}
?>
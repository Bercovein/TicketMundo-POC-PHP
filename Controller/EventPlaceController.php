<?php 
    namespace Controller;

    use \Exception as Exception;
    use Model\EventPlace as EventPlace;

    use DAO\PDOEventPlace as PDOEventPlace;
    use DAO\PDOCalendar as PDOCalendar;

    class EventPlaceController
    {
        private $DAOEventPlace;
        private $DAOCalendar;

        public function __construct ()
        { 
            $this->DAOEventPlace = new PDOEventPlace();
            $this->DAOCalendar = new PDOCalendar();
        }

        public function showAddView($message = '',$mType = '')
        {   
            try
            {
                $listEventPlace = $this->DAOEventPlace->getAll();

                if(!empty($message))
                    echo '<script>swal("","' . $message . '","' . $mType . '");</script>';   

                include_once(VIEWS_PATH.'EventPlaceManagement.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestón de Lugar Evento.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showListView()
        {   
            try
            {  
                $listEventPlace = $this->DAOEventPlace->getAll();

                include_once(VIEWS_PATH.'EventPlaceList.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de listas de Lugar Evento.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showUpdateView($id)
        {   
            try
            {
                $eventPlace = $this->DAOEventPlace->getById($id);

                include_once(VIEWS_PATH.'eventPlaceUpdate.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Modificación de Lugar Evento.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function newEventPlace($name,$capacity)
        {
            try
            {
                $name=ucwords($name);
                $id = $this->getNextId();

                if($this->DAOEventPlace->getById($id) == NULL && $this->DAOEventPlace->getByName($name)==NULL){

                    $eventPlace = new EventPlace();
                    $eventPlace->setId($id);
                    $eventPlace->setName($name);
                    $eventPlace->setcapacity($capacity);

                    $this->DAOEventPlace->add($eventPlace);
                    $message='Lugar de evento Agregado con exito!';
                    $mType = 'success';
                }else{
                    $message='El lugar de evento ya existe.';
                    $mType = 'warning';
                }
                $this->showAddView($message,$mType); 
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar un Lugar Evento.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function deleteEventPlace($id)
        {
            try
            {
                if($this->DAOCalendar->getByPlace($id) != NULL)
                {
                    $message = 'No se es posible modificar el estado del lugar ya que esta relacionado con al menos un Calendario.';
                    $mType = 'warning';
                }
                else
                {   
                    $message = "Estado del lugar modificado con exito!";
                    $mType = 'success';
                    $this->DAOEventPlace->delete($id);
                }
                $this->showAddView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar modificar el estado de un Lugar Evento.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function updateEventPlace($id, $name,$capacity)
        {
            try
            {
                $name=ucwords($name);

                if($this->DAOEventPlace->getById($id) != NULL && $this->DAOEventPlace->getByName($name)==NULL){

                    $eventPlace = new EventPlace();
                    $eventPlace->setId($id);
                    $eventPlace->setName($name);
                    $eventPlace->setcapacity($capacity);

                    $this->DAOEventPlace->update($eventPlace);
                    $message='Lugar de evento Actualizado con exito!';
                    $mType = 'success';
                }else{
                    $message='El lugar de evento ya existe.';
                    $mType = 'warning';
                }
                $this->showAddView($message,$mType); 
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar actualizar un Lugar Evento.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function getNextId()
        {
            try
            {
                $eventplaces=$this->DAOEventPlace->getAll();
                $id = 0;

                foreach($eventplaces as $eventplace){
                    if($id<$eventplace->getId())
                        $id=$eventplace->getId();
                }
                return $id+1;
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

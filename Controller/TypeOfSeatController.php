<?php 
    namespace Controller;

    use \Exception as Exception;
    use Model\TypeOfSeat as TypeOfSeat;

    use DAO\PDOTypeOfSeat as PDOTypeOfSeat; 
    use DAO\PDOEventSeats as PDOEventSeats;


    class TypeOfSeatController
    {
        private $DAOTypeOfSeat;
        private $DAOEventseats;

        public function __construct ()
        {
            $this->DAOTypeOfSeat = new PDOTypeOfSeat(); 
            $this->DAOEventseats = new PDOEventSeats();        
        }

        public function showAddView($message = '',$type = '')
        {   
            try
            {
                $listTypeOfSeat = $this->DAOTypeOfSeat->getAll();

                if(!empty($message))
                    echo '<script>swal("","' . $message . '","' . $type . '");</script>';   

                include_once(VIEWS_PATH.'TypeOfSeatManagement.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión Tipo de Plazas.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showListView()
        {    
            try
            { 
                $listTypeOfSeat = $this->DAOTypeOfSeat->getAll();

                include_once(VIEWS_PATH.'TypeOfSeatList.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de listas Tipo de Plazas.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showUpdateView($id)
        {   
            try
            {
                $typeOfSeat = $this->DAOTypeOfSeat->getById($id);

                include_once(VIEWS_PATH.'TypeOfSeatUpdate.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Modificación de Tipo Plaza.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function newTypeOfSeat($name)
        {
            try
            {
                $name = ucwords($name);
                $id = $this->getNextId();
                

                if($this->DAOTypeOfSeat->getById($id) == NULL && $this->DAOTypeOfSeat->getByName($name) == NULL){

                    $typeOfSeat = new TypeOfSeat();
                    $typeOfSeat->setId($id);
                    $typeOfSeat->setName($name);

                    $this->DAOTypeOfSeat->add($typeOfSeat);
                    $message='Tipo de plaza agregada con exito!';
                    $type = 'success';
                }else{
                    $message='El tipo de plaza ya existe.';
                    $type = 'warning';
                }
                $this->showAddView($message, $type); 
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar un Tipo de Plaza.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function deleteTypeOfSeat($id)
        {
            try
            {
                if($this->DAOEventseats->getByType($id) != NULL)
                {
                    $message = 'No se es posible modificar el estado de esta plaza ya que esta relacionada con al menos un Asiento.';
                    $type = 'warning';
                }
                else
                {   
                    $this->DAOTypeOfSeat->delete($id);
                    $message = "Estado del tipo de plaza modificado con exito!";
                    $type = 'success';
                }
                $this->showAddView($message,$type);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar modificar el estado de un Tipo de Plaza.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function updateTypeOfSeat($id, $name)
        {
            try
            {
                $name = ucwords($name);  

                if($this->DAOTypeOfSeat->getById($id) != NULL && $this->DAOTypeOfSeat->getByName($name) == NULL){

                    $typeOfSeat = new TypeOfSeat();
                    $typeOfSeat->setId($id);
                    $typeOfSeat->setName($name);

                    $this->DAOTypeOfSeat->update($typeOfSeat);
                    $message='Tipo de plaza Actualizada con exito!';
                    $type = 'success';
                }else{
                    $message='El tipo de plaza ya existe.';
                    $type = 'warning';

                }
                $this->showAddView($message,$type); 
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar actualizar un Tipo de Plaza.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function getNextId()
        {
            try
            {
                $typeOfSeats=$this->DAOTypeOfSeat->getAll();
                $id = 0;

                foreach($typeOfSeats as $typeOfSeat){
                    if($id<$typeOfSeat->getId())
                        $id=$typeOfSeat->getId();
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

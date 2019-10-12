<?php 
	namespace Controller;

	use \Exception as Exception;
	use Model\Event as Event;

	use DAO\PDOEvent as PDOEvent;
	use DAO\PDOCategory as PDOCategory;
	use DAO\PDOCalendar as PDOCalendar;


	class EventController
	{
		private $DAOEvent;
		private $DAOCategory;
		private $DAOCalendar;

		public function __construct ()
	    {
	        
	        $this->DAOEvent = new PDOEvent();
	        $this->DAOCategory = new PDOCategory();
	        $this->DAOCalendar = new PDOCalendar();
	        
	    }

		public function showAddView($message = '',$mType = '')
	    {   
	    	try
            {
            	if(!empty($message))
			    	echo '<script>swal("","' . $message . '","' . $mType . '");</script>';  

            	$listCategory = $this->DAOCategory->getAllActives();
		    	
		    	if(count($listCategory)!=0){

		    		$listEvent = $this->DAOEvent->getAll();

		    		include_once(VIEWS_PATH.'EventManagement.php');
		    	}else{
		    		$message = "Debe cargar categorías primero.";
		    		$mType = 'warning';
		    		echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 
		    		include_once(VIEWS_PATH.'CategoryManagement.php');
		    	}   
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión de eventos.\n Consulte a su Administrador.';
                echo '<script>swal("","' . $message . '","error");</script>';  
                require_once(VIEWS_PATH."Main.php");
            }
	    }

	    public function showListView()
	    {    
	    	try
            { 
		    	$listEvent = $this->DAOEvent->getAll();

		        include_once(VIEWS_PATH.'EventList.php');
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de listas de eventos.\n Consulte a su Administrador.';
                echo '<script>swal("","' . $message . '","error");</script>';  
                require_once(VIEWS_PATH."Main.php");
            }
	    }

	    public function showUpdateView($id)
        {   
            try
            {
                $event = $this->DAOEvent->getById($id);
                $listCategory = $this->DAOCategory->getAllActives();
                
                include_once(VIEWS_PATH.'EventUpdate.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Modificación de Eventos.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';  
                require_once(VIEWS_PATH."Main.php");
            }
        }

	    public function newEvent($name,$idCategory,$file)
	    {
	    	try
            {
		    	$name=ucwords($name);
		    	$id = $this->getNextId();
		    	$category = $this->DAOCategory->getbyId($idCategory);

				if($this->DAOEvent->getById($id) == NULL && $this->DAOEvent->getByName($name) == NULL){

					$event = new Event();
					$event->setId($id);
					$event->setName($name);
					$event->setCategory($category);

					$event->setBanner(null);
		
					if($file!=null){
						if(!empty($file["name"])){
							$event->setBanner($file["name"]);
							$this->Upload($file);
						}
					}

					$this->DAOEvent->add($event);

					$message = "Evento Agregado con exito!";
					$mType = 'success';
				}else{
					$message = "El Evento ya existe.";
					$mType = 'warning';
				}

				$this->showAddView($message,$mType);
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar un evento.\n Consulte a su Administrador.';
                echo '<script>swal("","' . $message . '","error");</script>';  
                require_once(VIEWS_PATH."Main.php");
            } 
		}

		public function Upload($file)
        {
            try
            {
                $fileName = $file["name"];
                $tempFileName = $file["tmp_name"];
                $type = $file["type"];
                
                $filePath = BANNER_PATH.basename($fileName);            

                $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                $imageSize = getimagesize($tempFileName);

                if($imageSize !== false)
                {
                    move_uploaded_file($tempFileName, $filePath);

                }
                else{
                    $message = "El archivo no corresponde a una imagen";
                    echo '<script>swal("","' . $message . '","warning");</script>';
                }
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar una imagen al evento.\n Consulte a su Administrador.';
                echo '<script>swal("","' . $message . '","error");</script>';
            }
        } 

        public function deleteBannerFromEvent($id)
		{
			try
            {	
            	$eventName = $this->DAOEvent->getById($id)->getName();

            	if($this->DAOCalendar->getByEvent($eventName) != NULL)
                {
                    $message = 'No es posible eliminar el banner de este evento ya que esta relacionado con al menos un Calendario. Pruebe con modificarlo.';
                    $mType = 'warning';
                }
                else
                {
					$this->DAOEvent->deleteBanner($id);
					$message = "Banner del evento eliminado con exito!";
					$mType ='success';
				}
				$this->showAddView($message,$mType);
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar eliminar el banner de un evento.\n Consulte a su Administrador.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."Main.php");
            }
		}   

		public function deleteEvent($id)
		{
			try
            {	
            	$eventName = $this->DAOEvent->getById($id)->getName();

            	if($this->DAOCalendar->getByEvent($eventName) != NULL)
                {
                    $message = 'No es posible modificar el estado de este evento ya que esta relacionado con al menos un Calendario.';
                    $mType = 'warning';
                }
                else
                {
					$this->DAOEvent->delete($id);
					$message = "Estado del evento modificado con exito!";
					$mType ='success';
				}
				$this->showAddView($message,$mType);
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar modificar el estado de un evento.\n Consulte a su Administrador.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."Main.php");
            }
		}

		public function updateEvent($id, $name,$idCategory, $file)
	    {
	    	try
            {
		    	$name = ucwords($name);
		    	$category = $this->DAOCategory->getbyId($idCategory);

		    	$event = $this->DAOEvent->getById($id);

		    	$flag = 0;

				if($event != NULL){

					if($event->getName()==$name){
		    			if($event->getCategory()->getName()==$category->getName()){
		    				if(empty($file["name"])){
		    					$flag = 1;
		    				}
		    			}
					}

					if($flag == 0){
						
						$event = new Event();
						$event->setId($id);
						$event->setName($name);
						$event->setCategory($category);

						$event->setBanner(NULL);

						if(!empty($file["name"])){	
							$event->setBanner($file["name"]);
							$this->Upload($file);	
						}

						$this->DAOEvent->update($event);

						$message = "Evento actualizado con exito!";
						$mType = 'success';
					}else{
						$message = "La información del evento no sufrió cambios.";
						$mType = 'warning';
					}
				}else{
					$message = "El evento que intenta modificar no existe.";
					$mType = 'warning';
				}

				$this->showAddView($message,$mType);
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar actualizar un evento.\n Consulte a su Administrador.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
		}

		public function getNextId()
		{
			try
            {
				$events=$this->DAOEvent->getAll();
				$id = 0;

				foreach($events as $event){
					if($id<$event->getId())
						$id=$event->getId();
				}
				return $id+1;
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema de tipo Exception.\n Consulte a su Administrador.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."Main.php");
            }
		}

	}

?>

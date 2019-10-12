<?php 
	namespace Controller;

	use \Exception as Exception;
	use Model\Artist as Artist;

	//use DAO\DAOArtist as DAOArtist; /*DAO*/
	use DAO\PDOArtist as PDOArtist; /*PDO*/
	use DAO\PDOCalendar as PDOCalendar;

	class ArtistController{

		private $DAOArtist;
		private $DAOCalendar;

		public function __construct ()
	    {
	        //$this->DAOArtist = new DAOArtist(); /*DAO*/
	        $this->DAOArtist = new PDOArtist(); /*PDO*/
	        $this->DAOCalendar = new PDOCalendar();
	    }

		public function showAddView($message = '',$mType = '')
	    {   
	    	try
            {
		    	$listArtist = $this->DAOArtist->getAll();

		    	if(!empty($message)){
		    		echo '<script>swal("","' . $message . '","' . $mType . '");</script>';     	
			    }	

		        include_once(VIEWS_PATH.'ArtistManagement.php');
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Gestión de Artistas.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';                
                require_once(VIEWS_PATH."Main.php");
            }
	    }

	    public function showListView()
	    {
	    	try
            {
		    	$listArtist = $this->DAOArtist->getAll();

		        include_once(VIEWS_PATH.'ArtistList.php');
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Listas de Artistas.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                $this->showAddView();
            }
	    }

	    public function showUpdateView($idArtist)
	    {   
	    	try
            {
		    	$artist = $this->DAOArtist->getById($idArtist);

		        include_once(VIEWS_PATH.'ArtistUpdate.php');
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Modificación de Artistas.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
				$this->showAddView();            
			}
	    }

	    public function newArtist($name)
	    {
	    	try
            {
		    	$name=ucwords($name);
		    	$id = $this->getNextId();

				if($this->DAOArtist->getById($id) == NULL && $this->DAOArtist->getByName($name) == NULL){

					$artist = new Artist();
					$artist->setName($name);
					$artist->setId($id);

					$this->DAOArtist->add($artist);
					$message = "Artista Agregado con exito!";
					$mType = 'success';
				}else{
					$message = "El artista ya existe.";
					$mType = 'warning';
				}
				$this->showAddView($message,$mType);
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar al Artista.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
		}

		public function deleteArtist($id)
		{
			try
            {
            	$artistName = $this->DAOArtist->getById($id)->getName();
	            if($this->DAOCalendar->getByArtist($artistName) != NULL)
	            {
	                $message = 'No se es posible modificar el estado de este artista ya que esta relacionado con al menos un Calendario.';
	                $mType = 'warning';
	            }
	            else
	            {	
					$this->DAOArtist->delete($id);
					$message = "Estado del artista modificado con exito!";
					$mType = 'success';
				}
				$this->showAddView($message,$mType); 
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar modificar el estado de al Artista.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                $this->showAddView();
            }
		}

		public function updateArtist($id, $newName)
		{
			try
			{
				$newName = ucwords($newName);

				if($this->DAOArtist->getById($id) != NULL && $this->DAOArtist->getByName($newName) == NULL){

					$artist = new Artist();
					$artist->setName($newName);
					$artist->setId($id);

					$this->DAOArtist->update($artist);
					$message = "Artista actualizado con exito!";
					$mType = 'success';
				}else{
					$message = "El artista ya existe.";
					$mType = 'warning';
				}

				$this->showAddView($message,$mType); 
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar actualizar al Artista.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                $this->showAddView();
            }
		}

		public function getNextId()
		{
			try
            {
				$artists=$this->DAOArtist->getAll();
				$id = 0;

				foreach($artists as $artist){
					if($id<$artist->getId())
						$id=$artist->getId();
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

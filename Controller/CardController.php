<?php 
	namespace Controller;

	use \Exception as Exception;
	use Model\Card as Card;

	use DAO\PDOCard as PDOCard;
	use DAO\PDOClient as PDOClient;

	class CardController
	{
		private $DAOCard;
		private $DAOClient;

		public function __construct ()
	    {
	    	$this->DAOCard = new PDOCard();
	        $this->DAOClient = new PDOClient();   
	    }

	    public function showAddView($message ='',$mType = '')
	    {   
	    	try
            {
            	$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

		        include_once(VIEWS_PATH.'CardManagement.php');
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de carga de tarjetas.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    }

	    public function showListView()
	    {   
	    	try
            {
		    	$listCard = $this->DAOCard->getAll();

		    	$title = "Listado de Tarjetas";

		        include_once(VIEWS_PATH.'CardList.php');
		    }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión de Tarjetas.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
	    }

	    public function showClientView($message = '',$mType = '')
	    {   
	    	try
            {
		    	$client = $this->DAOClient->getByUser($_SESSION["Userlogged"]->getId());

		    	if(!empty($client)){

		    		$listCard = $this->DAOCard->getByClient($client->getId());
		    		
		    		if(!empty($listCard)){

		    			$title = "Mis Tarjetas";
		    			include_once(VIEWS_PATH.'CardList.php');
		    		}else{

		    			$this->showAddView();
		    		}
		        }
		    	else{
		    		$message = "Para cargar una tarjeta primero debe cargar sus datos.";
		    		$mType ='warning';
		    		echo '<script>swal("","' . $message . '","' . $mType . '");</script>';
		    		include_once(VIEWS_PATH."ClientManagement.php");
		    	}	

		    	if(!empty($message))
			    	echo '<script>swal("","' . $message . '","' . $mType . '");</script>';
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de listas de Tarjetas.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }	
	    }

	    public function showView($message = '',$mType = '')
		{
			try
            {	
            	if(!empty($message))
			    	echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 

				if($_SESSION["Userlogged"]->getRol()=="A")
					$this->showListView();
				else
					$this->showClientView();
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            } 
		}

		public function showUpdateView($id)
        {   
            try
            {
                $card = $this->DAOCard->getById($id);

                include_once(VIEWS_PATH.'CardUpdate.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Modificación de Tarjetas.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                $this->showListView();
            }
        }

	    public function newCard($number, $securityCode, $dni, $expirationDate)
	    {	
	    	try
            {
		    	$id = $this->getNextId();

		    	$client = $this->DAOClient->getByDni($dni);

				if($this->DAOCard->getById($id) == NULL && $client != NULL){

					$card = new Card();
					$card->setNumber($number);
					$card->setsecurityCode($securityCode);
					$card->setExpirationDate($expirationDate);
					$card->setId($id);
					$card->setClient($client);

					$this->DAOCard->add($card);

					$message = "Tarjeta Agregada con exito!";
					$mType = 'success';
				}else{
					$message = "El cliente con ese Dni no esta registrado.";
					$mType = 'success';
				}

				$this->showView($message,$mType);

			}catch(Exception $ex){

                $message = 'Oops ! \n\n Hubo un problema al intentar agregar la Tarjeta.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                $this->showView();
            }
		}

		public function deleteCard($id)
		{
			try
            {
				$this->DAOCard->delete($id);
				$message = "Tarjeta eliminada con exito!";
				$mType = 'success';
				$this->showView($message,$mType);
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar eliminar la Tarjeta.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                $this->showView();
            } 
		}

		public function updateCard($id, $number, $securityCode, $dni, $expirationDate)
	    {	
	    	try
            {
		    	$client = $this->DAOClient->getByDni($dni);

				if($this->DAOCard->getById($id) != NULL && $client != NULL){

					$card = new Card();
					$card->setNumber($number);
					$card->setsecurityCode($securityCode);
					$card->setExpirationDate($expirationDate);
					$card->setId($id);
					$card->setClient($client);

					$this->DAOCard->update($card);

					$message = "Tarjeta Actualizada con exito!";
					$mType = 'success';
				}else{
					$message = "El cliente con ese Dni no esta registrado.";
					$mType = 'success';
				}

				$this->showView($message,$mType);
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar actualizar la Tarjeta.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                $this->showView();
            }
		}

		public function getNextId()
		{
			try
            {
				$cards=$this->DAOCard->getAll();
				$id = 0;

				foreach($cards as $card){
					if($id<$card->getId())
						$id=$card->getId();
				}
				return $id+1;
			}
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema de tipo Exception.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                $this->showView();
            }
		}
	}

?>

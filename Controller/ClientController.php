<?php 
    namespace Controller;

    use \Exception as Exception;
    use Model\Client as Client;

    use DAO\PDOClient as PDOClient;
    use DAO\PDOUser as PDOUser;
    use DAO\PDOCard as PDOCard;

    class ClientController
    {
    	private $daoClient;
    	private $DAOUser;
    	private $DAOCard;

    	public function __construct ()
        {
        	
            $this->daoClient = new PDOClient();
            $this->DAOUser = new PDOUser();
            $this->DAOCard = new PDOCard();
        }

    	public function showAddView($message = '',$mType = '')
        {   
            try
            {
            	$listClient = $this->daoClient->getAll();
            	$listCards = $this->DAOCard->getAll();

            	$Userlogged = null;
            	if($_SESSION["Userlogged"]->getRol()=="C")
            	$Userlogged = $_SESSION["Userlogged"]->getId();

            	if(!empty($message))
        	    	echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 	

                include_once(VIEWS_PATH.'ClientManagement.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión de clientes.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showListView($message = '',$mType = '')
        {   
            try
            {
                if(!empty($message))
                    echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 

                if($_SESSION["Userlogged"]->getRol()=="A"){
                    
                    $title="Listado de Clientes";
                    
                    $listClient= $this->daoClient->getAll();
                    $listCards = $this->DAOCard->getAll();

                    include_once(VIEWS_PATH.'ClientList.php');
                }
                else{
                    
                    $title="Mis Datos";

                    $listClient = array();
                    $client = $this->daoClient->getByUser($_SESSION["Userlogged"]->getId());

                    array_push($listClient, $client);

                    if(empty($client))
                        include_once(VIEWS_PATH.'ClientManagement.php');
                    else{
                        $listCards = $this->DAOCard->getByClient($client->getId());
                        include_once(VIEWS_PATH.'ClientList.php');
                    }
                }	
        
            }catch(Exception $ex){
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de listas de clientes.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showMainView($message = '',$mType ='')
        {   
            try
            {
            	if(!empty($message))
        	    	echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 	
          
                include_once(VIEWS_PATH.'Main.php'); 
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina Principal.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showUpdateView($id)
        {   
            try
            {
                $client = $this->daoClient->getById($id);

                include_once(VIEWS_PATH.'ClientUpdate.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Modificación de Clientes.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function newClient($firstName,$lastName,$dni, $idUser)
        {
            try
            {
            	$user = $this->DAOUser->getById($idUser);

            	if($this->daoClient->getByDni($dni) == NULL && $user != NULL){

        	    	$id = $this->getNextId();
        			
        			$client = new Client();
        			$client->setFirstName(ucwords($firstName));
        			$client->setLastName(ucwords($lastName));
        			$client->setDni($dni);
        			$client->setUser($user);

        			$this->daoClient->add($client);

                    $email = $user->getEmail();
                    $password = $user->getPassword();

                    $this->sendMail($email,$password,$client);

        			$message='Información agregada con exito! Se ha enviado un correo electronico a su casilla con la información registrada. No olvide cargar una tarjeta para comenzar a comprar!';
                    $mType ='success';
        		}else{
        			$message='La información de cliente ya existe.';
                    $mType ='warning';
                }
        		
        		$this->showListView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar el Cliente.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
    	}

        public function sendMail($email,$password,Client $client)
        {   
            try
            {
                $attached = "";

                $headers = "From: TicketMundo <ticketmundohipermegared@gmail.com>\r";

                $affair = "Bienvenido a TicketMundo HiperMegaRed!";

                $headers .= "MIME-version: 1.0\n";
                $headers .= "Content-type: multipart/mixed;";
                $headers .= "boundary=\"--_Separator_--\"\n";

                $headerText = "----_Separator_--\n";
                $headerText .= "Content-type: text/plain;charset=iso-8859-1\n";
                $headerText .= "Content-transfer-encoding: 7BIT\n";

                $text = "\n\n\n"."Te damos la bienvenida a TicketMundo!";
                $text .="\n\n"."Los datos de tu cuenta son: ";
                $text .="\n\n"."Email: ".$email;
                $text .="\n"."Password: ".$password;

                $firstName = $client->getFirstName();
                $lastName = $client->getLastName();
                $dni = $client->getDni();

                $text .="\n\n"."Nombre: ".$firstName;
                $text .="\n"."Apellido: ".$lastName;
                $text .="\n"."Dni: ".$dni;
                $text .="\n\n"."Gracias por elegirnos!";

                $toSend = $headerText.$text;

                mail($email, $affair, $toSend, $headers);

            }catch(Exception $ex){
                $message = 'Oops ! \n\n Hubo un problema al intentar enviar el email.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."PurchaseList.php");
            }
        }

    	public function deleteClient($dni)
        {
            try
            {
                if(count($this->DAOCard->getByClientDni($dni)) > 0)
                    $this->daoClient->deleteClientCards($dni);
                else
        		    $this->daoClient->deleteClient($dni);

                $message = "Cliente eliminado con exito!";
                $mType = 'success';

        		$this->showListView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar eliminar el cliente.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
    	}

        public function updateClient($id, $firstName,$lastName,$dni, $idUser)
        {
            try
            {
                $user = $this->DAOUser->getById($idUser);

                if($this->daoClient->getById($id) != NULL && $user != NULL){

                    if( $this->daoClient->getByDni($dni) == NULL){
                    
                        $client = new Client();
                        $client->setId($id);
                        $client->setFirstName(ucwords($firstName));
                        $client->setLastName(ucwords($lastName));
                        $client->setDni($dni);
                        $client->setUser($user);

                        $this->daoClient->update($client);

                        $message='Cliente Actualizado con exito!';
                        $mType ='success';
                    }else{
                        $message = "El Dni ingresado ya existe"; 
                        $mType ='warning'; 
                    }
                }else{
                    $message='El Cliente ya existe.';
                    $mType ='warning';
                }
                
                $this->showListView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar actualizar el Cliente.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            } 
        }

    	public function getNextId()
        {
            try
            {
        		$client=$this->daoClient->getAll();
        		$count=count($client);
        		$id = 0;

        		if($count>0){
        			$count--;
        			$id = $client[$count]->getId()+1;
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

        public function clientSupport(){

            try{
                require_once(VIEWS_PATH."ClientSupport.php");
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al mostrar la pagina de Atención al Cliente.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }
        public function supportEmail($query,$name,$email)
        {   
            try
            {   
                if(!empty($query)&&!empty($email)){
                    $attached = "Aguantelapija";

                    $headers = "From: ".$name."<ticketmundohipermegared@gmail.com>\r";

                    $affair = "Atención al Cliente";

                    $headers .= "\nMIME-version: 1.0\n";
                    $headers .= "Content-type: multipart/mixed;";
                    $headers .= "boundary=\"--_Separator_--\"\n";

                    $headerText = "----_Separator_--\n";
                    $headerText .= "Content-type: text/plain;charset=iso-8859-1\n";
                    $headerText .= "Content-transfer-encoding: 7BIT\n";

                    $text = "\n\n\n"."Ha llegado una nueva consulta de: ";
                    $text .="\n\n".$email;
                    $text .="\n\n"."- Consulta -";
                    $text .= "\n\n".$query;
                    $text .= "\n\n"."- Fin de la consulta -";

                    $toSend = $headerText.$text;

                    mail("ticketmundohipermegared@gmail.com", $affair, $toSend, $headers);
                    $message = 'Su consulta ha sido enviada con exito. Pronto responderemos a su casilla de correo. Gracias por confiar en nosotros!';
                    $mType = 'success';
                }else{
                    $message = 'Su consulta no pudo ser procesada. El email y/o la consulta realizada no son validos. Intentelo de nuevo.';
                    $mType = 'error';
                }
                $this->showMainView($message,$mType);

            }catch(Exception $ex){
                $message = 'Oops ! \n\n Hubo un problema al intentar generar la consulta.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>'; 
                require_once(VIEWS_PATH."Main.php");
            }
        }
    }

?>

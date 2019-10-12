<?php 
    namespace Controller;

    use \Exception as Exception;
    use Model\User as User;

    use DAO\PDOUser as PDOUser; 

    class UserController
    {
    	private $DAOUser;

    	public function __construct ()
        {
           
            $this->DAOUser = new PDOUser(); 
        }

    	public function showAddView($message = '',$mType ='')
        {   
            try
            {
            	$listUser = $this->DAOUser->getAll();

            	if(!empty($message))
        	    	echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 	

                include_once(VIEWS_PATH.'UserManagement.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión de Usuarios.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showListView()
        {   
            try
            {  
            	$listUser = $this->DAOUser->getAll();

                include_once(VIEWS_PATH.'UserList.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de listas de Usuarios.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showLoginView($message = '',$mType ='')
        {    
            try
            {            	
                if(is_numeric($message)){
                    $message = 'Debe iniciar sesión o registrarse para continuar.';
                    $mType = 'warning';
                }
                
                if(!empty($message))
                	echo '<script>swal("","' . $message . '","' . $mType . '");</script>';

                include_once(VIEWS_PATH.'Login.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Login.\n Consulte a su Administrador o vuelva a intentarlo.';
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

        public function showRegisterView($message= '',$mType='')
        {
        	try
            {
            	if(!empty($message))
                	echo '<script>swal("","' . $message . '","' . $mType . '");</script>'; 

            	include_once(VIEWS_PATH.'Register.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Registro de Usuarios.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showAddClientView($message= '',$mType='')
        {
        	try
            {
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

        public function newUser($email,$password, $rePassword)
        {	
            try
            {
            	if($password==$rePassword){
            		if($this->DAOUser->getByEmail($email) == NULL){

        			$user = new User();
        			$user->setEmail($email);
        			$user->setPassword($password);
        			$user->setRol("C");

        			$this->DAOUser->add($user);
        			$message = "Usuario agregado con exito!";
                    $mType = 'success';

        			$_SESSION["Userlogged"]=$this->DAOUser->getByEmail($email);



        			$this->showAddClientView($message,$mType);
        			}else{
        				$message = "El Usuario ya existe.";
                        $mType = 'warning';
        				$this->showRegisterView($message,$mType); 
            		}
            	}else{
            		$message = "Las contraseñas no coinciden";
            		$mType = 'warning';
                    $this->showRegisterView($message,$mType);
            	}
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar un Usuario.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
    	}

    	public function deleteUser($email)
        {
            try
            {
        		$this->DAOUser->delete($email);
        		$this->showAddView(); 
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar eliminar un Usuario.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
    	}

    	public function UserLogin($email,$password)
        {
            try
            {
        		$user = $this->DAOUser->getByEmail($email);

        		if($user!=NULL){

        			if($email == $user->getEmail() && $password == $user->getPassword()){
        				
        				$_SESSION["Userlogged"]=$user;

        				$message = 'Bienvenido!';
                        $mType = 'success';
        				$this->showMainView($message,$mType);
        			}
        			else{
        				$message = "La contraseña es incorrecta. \\n Intentelo de nuevo.";
                        echo '<script>swal("","' . $message . '","warning");</script>';
        				$this->showLoginView();
        			}
        		}
        		else{
        			$message = "El usuario no existe.";
                    echo '<script>swal("","' . $message . '","warning");</script>';
        			$this->showLoginView();
        		}
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar iniciar sesión.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
    	}

    	public function UserLogout()
        {
            try
            {
        		if(!empty($_SESSION["Userlogged"])){

        			unset($_SESSION["Userlogged"]);
        		}
        		session_destroy();
                $message = 'Gracias por visitarnos! Esperamos verte de nuevo!';
                $mType = 'success';
               	$this->showMainView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar cerrar sesión.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                $this->showMainView();
            }
    	}
    }

?>

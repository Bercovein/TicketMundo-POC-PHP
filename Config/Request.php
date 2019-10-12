<?php
namespace Config; 
   
class Request
{
	/*atributos para almacenar todos los valores que vengan por url*/
	private $controller;
	private $method;
	private $parameters=array();

	 function __construct()
	{
		 /*  En el archivo htaccess se define una regla de reescritura para poder tomar la url tanto para todo method de petición.*/
        $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);

            /*
              Convierto la url en un array tomando como separador la "/".
             */
        $urlToArray = explode("/", $url);
            /*
  				Filtro el arreglo para eliminar datos vacios en caso de haberlos.
             */
        $ArregloUrl = array_filter($urlToArray);
             /*
              Defino un controlador por defecto en el caso de que el arreglo llegue vacío
            	 Si el arreglo tiene datos, tomo como controlador el primer elemento.
             */

        if(empty($ArregloUrl)) 
            $this->controller = 'HomeController';
        else 
            $this->controller = ucwords(array_shift($ArregloUrl));
            

            /*
             Defino un método por defecto en el caso de que el arreglo llegue vacío
             Si el arreglo tiene datos, tomo como método el primero elemento.
             */
        if(empty($ArregloUrl))
            $this->method = 'index';
        else 
            $this->method = array_shift($ArregloUrl);
    
            /**
             * Capturo el method de petición y lo guardo en una variable
             */
        $methodRequest = $this->getmethodRequest();
           /**
             * Si el método es GET, en caso de que el arreglo llegue con datos, 
             * lo guardo entero en el campo "parameters" de la  clase. 
             *
             * Si el método es POST, guardo todos los datos que llegaron por POST
             * en el campo "parameters"
             */

        if($methodRequest == 'GET') {

            unset($_GET['url']);

            if(!empty($_GET)){

                foreach ($_GET as $key => $value) {
                    array_push($this->parameters, $value);
                }

            }else 
                $this->parameters = $ArregloUrl;    
        } else if ($_POST)
            $this->parameters = $_POST;

        if($_FILES)
        {
            unset($this->parameters["button"]);

          foreach($_FILES as $file)
          {
              array_push($this->parameters, $file);
          }
        }
    }

        /**
        * Devuelve el método HTTP
        * con el que se hizo el
        * Request
        * @return String
        */
        public static function getMethodRequest()
        {
            return $_SERVER['REQUEST_METHOD'];
        }
        /**
        * Devuelve el controlador
        * @return String
        */
        public function getController() {
            return $this->controller;
        }
        /**
        * Devuelve el método 
        * @return String
        */
        public function getMethod() {
            return $this->method;
        }
        /**
        * Devuelve los atributos
        * @return Array
        */
        public function getParameters() {
            return $this->parameters;
        }
    
	}
 ?>
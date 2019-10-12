<?php 
namespace Config;

use Config\Request as Request;

    class Router {

        /**
         * Se encarga de direccionar a la pagina solicitada
         * @param Request
         */
        public function __construct() {}

        public static function reDirect(Request $request) 
        {      
            $controller = $request->getcontroller();
            $method = $request->getMethod();

            $parameters = $request->getparameters();
          
            $controllerClassName = "Controller\\".$controller;
            $controller = new $controllerClassName();

            if(!isset($parameters)) 
                call_user_func(array($controller, $method));
            else 
                call_user_func_array(array($controller, $method),$parameters);  
        }
    }

 ?>
 
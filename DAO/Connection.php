<?php
    namespace DAO;

    use \PDO as PDO;
    use \Exception as Exception;

/*abstrae el manejo de la db del dao*/
/*todo lo de adentro de los metodos deben ir en try catch para que no se rompa el programa
*/

    class Connection
    {
        private $pdo = null; //
        private $pdoStatement = null; //
        private static $instance = null; //

        private function __construct() //es privado para que nadie haga un new
        {
            try
            {
                $this->pdo = new PDO("mysql:host=".DB_HOST."; dbname=".DB_NAME, DB_USER, DB_PASS); //estancia un nuevo objeto PDO, clase propia de php
                //tipo de motor, nombre de la db, usuario de la db, contraseña de la db
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public static function GetInstance() //singleton
        {
            if(self::$instance == null)
                self::$instance = new Connection();

            return self::$instance;
        }

        public function Execute($query, $parameters = array()) //se utiliza siempre que consultemos a la db que devuelve resultados
        {
            try
            {
                $this->Prepare($query); //prepara la query para ser ejecutada
                //y si hay parametros va a hacer el foreach
                foreach($parameters as $parameterName => $value)
                    $this->pdoStatement->bindParam(":".$parameterName, $parameters[$parameterName]); //bindparam: nombre de comodin(siempre con : primero), valor del parametro..
                
                $this->pdoStatement->execute(); //ejecuta la sentencia sql
                //pdoStatement guarda los resultados de la db
                return $this->pdoStatement->fetchAll(); //devuelve los datos como una matriz
            }
            catch(Exception $ex){
                throw $ex;
            }
        }
        
        public function ExecuteNonQuery($query, $parameters = array()) //sirve para modificar la db
        {
            try
            {
                $this->Prepare($query);
                
                foreach($parameters as $parameterName => $value)
                {   
                    $this->pdoStatement->bindParam(":".$parameterName, $parameters[$parameterName]);
                }
        
                $this->pdoStatement->execute();

                return $this->pdoStatement->rowCount(); //retorna la cantidad de filas afectadas, para comprobar si se modifico la db
            }
            catch(Exception $ex)
            {
                throw $ex;
            }                   
        }
    
        public function ExecuteNonQuery2($query, $parameters) 
        {
            try
            {
                $this->Prepare($query);
                
                foreach($parameters as $parameterName)
                {
                    foreach($parameterName as $name => $value)
                        $this->pdoStatement->bindParam(":".$name, $value);
                }
        
                $this->pdoStatement->execute();

                return $this->pdoStatement->rowCount(); 
            }
            catch(Exception $ex)
            {
                throw $ex;
            }                   
        }
    
        
        private function Prepare($query)
        {
            try{
                $this->pdoStatement = $this->pdo->prepare($query);
            }

            catch(Exception $ex){
                throw $ex;
            }

        }
    }
?>
<?php
    namespace DAO;

    use DAO\Connection as Connection;
    use DAO\IDAOClient as IDAOClient;
    
    use Model\Client as Client;
    use Model\User as User;
    use Model\Card as Card;
    
    
    class PDOClient implements IDAOClient
    {
        private $connection;
        private $tableName = "Clients"; 
        private $tableUser = "Users";
        private $tableCard = "Cards";

        public function add(Client $client)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (firstName, lastName, dni, fk_user) VALUES (:firstName, :lastName , :dni, :fk_user);";

                $parameters["firstName"] = $client->getFirstName();
                $parameters["lastName"] = $client->getLastName();
                $parameters["dni"] = $client->getDni();
                $parameters["fk_user"] = $client->getUser()->getId();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getAll() 
        {
            try
            {
                $clientList = array(); 

                $query = "SELECT client_id, firstName, lastName, dni, fk_user, user_email, password, rol
                    FROM ".$this->tableName.
                    " INNER JOIN ".$this->tableUser." on fk_user = user_id 
                    ORDER BY client_id;";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query); 

                foreach ($resultSet as $row) 
    			{   

                    $client = new Client();
                    $client->setId($row["client_id"]);
                    $client->setFirstName($row["firstName"]);
                    $client->setLastName($row["lastName"]);
                    $client->setDni($row["dni"]);

                    $User = new User();
                    $User->setId($row["fk_user"]);
                    $User->setEmail($row["user_email"]);
                    $User->setPassword($row["password"]);
                    $User->setRol($row["rol"]);

                    $client->setUser($User);

                    array_push($clientList, $client);
    			}
                return $clientList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getById($id)
        {
            try
            {
                $client = null;

                $query = "SELECT client_id, firstName, lastName, dni, fk_user, user_email, password, rol
                    FROM ".$this->tableName.
                    " INNER JOIN ".$this->tableUser." on fk_user = user_id 
                    WHERE client_id = :client_id;";

                $parameters["client_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row) 
    			{
                    $client = new Client();
                    $client->setId($row["client_id"]);
                    $client->setFirstName($row["firstName"]);
                    $client->setLastName($row["lastName"]);
                    $client->setDni($row["dni"]);

                    $User = new User();
                    $User->setId($row["fk_user"]);
                    $User->setEmail($row["user_email"]);
                    $User->setPassword($row["password"]);
                    $User->setRol($row["rol"]);

                    $client->setUser($User);
                }
                return $client;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByDni($dni)
        {
            try
            {
                $client = null;

                $query = "SELECT client_id, firstName, lastName, dni, fk_user, user_email, password, rol
                    FROM ".$this->tableName.
                    " INNER JOIN ".$this->tableUser." on user_id = fk_user 
                    WHERE dni = :dni;";

                $parameters["dni"] = $dni;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
                {
                    $client = new Client();
                    $client->setId($row["client_id"]);
                    $client->setFirstName($row["firstName"]);
                    $client->setLastName($row["lastName"]);
                    $client->setDni($dni);

                    $User = new User();
                    $User->setId($row["fk_user"]);
                    $User->setEmail($row["user_email"]);
                    $User->setPassword($row["password"]);
                    $User->setRol($row["rol"]);

                    $client->setUser($User);

                }        
                return $client;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function addCardByDni($dni, Card $card)
        {
            try
            {
                $query = "UPDATE ".$this->tableCard." SET fk_client = (SELECT client_id FROM 
                        ".$this->tableName." WHERE dni = :dni) WHERE card_id = :card_id"; 

                $parameters["card_id"]=$card->getId();
                $parameters["dni"] = $dni;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByUser($idUser)
        {
            try
            {
                $client = null;

                $query = "SELECT client_id, firstName, lastName, dni, fk_user, user_email, password, rol
                    FROM ".$this->tableName.
                    " INNER JOIN ".$this->tableUser." on fk_user = user_id 
                    WHERE fk_user = :fk_user;";

                $parameters["fk_user"] = $idUser;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
                {
                    $client = new Client();
                    $client->setId($row["client_id"]);
                    $client->setFirstName($row["firstName"]);
                    $client->setLastName($row["lastName"]);
                    $client->setDni($row["dni"]);

                    $User = new User();
                    $User->setId($row["fk_user"]);
                    $User->setEmail($row["user_email"]);
                    $User->setPassword($row["password"]);
                    $User->setRol($row["rol"]);

                    $client->setUser($User);
                }           
                return $client;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        } 

        public function deleteClientCards($dni)
        {
            try
            {   $query = "DELETE a2 FROM ".$this->tableName." AS a1 INNER JOIN ".$this->tableCard." AS a2 
                WHERE client_id = fk_client AND a1.dni LIKE :dni;";

                $query .= "DELETE FROM ".$this->tableName." WHERE dni = :dni;";
                
                $parameters["dni"] = $dni;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function deleteClient($dni)
        {
            try
            {
                $query = "DELETE FROM ".$this->tableName." WHERE dni = :dni;";
                
                $parameters["dni"] = $dni;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function update(Client $client)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." 
                    SET firstName = :firstName, lastName = :lastName, dni = :dni, fk_user = :fk_user 
                    WHERE client_id = :client_id;";

                $parameters["firstName"] = $client->getFirstName();
                $parameters["lastName"] = $client->getLastName();
                $parameters["dni"] = $client->getDni();
                $parameters["fk_user"] = $client->getUser()->getId();
                $parameters["client_id"] = $client->getId();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }  
    }
?>
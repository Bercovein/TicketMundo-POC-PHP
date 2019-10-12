<?php
    namespace DAO;

    use DAO\Connection as Connection;
    use DAO\IDAOCard as IDAOCard;
    
    use Model\Card as Card;
    use Model\Client as Client;
    use Model\User as User;
    
    class PDOCard implements IDAOCard
    {
        private $connection; 
        private $tableName = "Cards"; 
        private $tableClient = "Clients";
        private $tableUser = "Users";

        public function add(Card $card)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (card_number, securityCode, expirationDate, fk_client) VALUES (:card_number, :securityCode, :expirationDate, :fk_client);";

                $parameters["card_number"] = $card->getNumber();
                $parameters["securityCode"] = $card->getsecurityCode();
                $parameters["expirationDate"] = $card->getExpirationDate();
                $parameters["fk_client"] = $card->getClient()->getId();

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
                $cardList = array(); 

                $query = "SELECT card_id, card_number, securityCode, expirationDate, fk_client, firstName, lastName, dni, fk_user, user_email, password, rol
                    FROM ".$this->tableName."
                      inner join ".$this->tableClient." on client_id= fk_client
                      inner join ".$this->tableUser." on user_id = fk_user
                      ORDER BY fk_client"; 

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row) 
    			{                
                    $card = new Card();
                    $card->setId($row["card_id"]);
                    $card->setNumber($row["card_number"]);
                    $card->setSecurityCode($row["securityCode"]);
                    $card->setExpirationDate($row["expirationDate"]);

                    $client = new Client();
                    $client->setId($row["fk_client"]);
                    $client->setFirstName($row["firstName"]);
                    $client->setLastName($row["lastName"]);
                    $client->setDni($row["dni"]);

                    $User = new User();
                    $User->setId($row["fk_user"]);
                    $User->setEmail($row["user_email"]);
                    $User->setpassword($row["password"]);
                    $User->setRol($row["rol"]);

                    $client->setUser($User);

                    $card->setClient($client);

    				array_push($cardList, $card);
    			}
                return $cardList;
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
                $card = null;

                $query = "SELECT card_id, card_number, securityCode, expirationDate, fk_client, firstName, lastName, dni, fk_user, user_email, password, rol
                    FROM ".$this->tableName."
                      inner join ".$this->tableClient." on client_id= fk_client
                      inner join ".$this->tableUser." on user_id = fk_user 
                      WHERE card_id = :card_id";

                $parameters["card_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row) 
    			{
                    $card = new Card();
                    $card->setId($row["card_id"]);
                    $card->setNumber($row["card_number"]);
                    $card->setsecurityCode($row["securityCode"]);
                    $card->setExpirationDate($row["expirationDate"]);

                    $client = new Client();
                    $client->setId($row["fk_client"]);
                    $client->setFirstName($row["firstName"]);
                    $client->setLastName($row["lastName"]);
                    $client->setDni($row["dni"]);

                    $User = new User();
                    $User->setId($row["fk_user"]);
                    $User->setEmail($row["user_email"]);
                    $User->setpassword($row["password"]);
                    $User->setRol($row["rol"]);

                    $client->setUser($User);

                    $card->setClient($client);
                }            
                return $card;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByClient($clientId) 
        {
            try
            {
                $cardList = array(); 

                $query = "SELECT card_id, card_number, securityCode, expirationDate, fk_client, firstName, lastName, dni, fk_user, user_email, password, rol
                    FROM ".$this->tableName."
                      inner join ".$this->tableClient." on client_id= fk_client
                      inner join ".$this->tableUser." on user_id = fk_user 
                      WHERE fk_client = :fk_client"; 

                $parameters["fk_client"] = $clientId;

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query,$parameters);
                
                foreach ($resultSet as $row) 
                {                
                    $card = new Card();
                    $card->setId($row["card_id"]);
                    $card->setNumber($row["card_number"]);
                    $card->setsecurityCode($row["securityCode"]);
                    $card->setExpirationDate($row["expirationDate"]);

                    $client = new Client();
                    $client->setId($row["fk_client"]);
                    $client->setFirstName($row["firstName"]);
                    $client->setLastName($row["lastName"]);
                    $client->setDni($row["dni"]);

                    $User = new User();
                    $User->setId($row["fk_user"]);
                    $User->setEmail($row["user_email"]);
                    $User->setpassword($row["password"]);
                    $User->setRol($row["rol"]);

                    $client->setUser($User);

                    $card->setClient($client);

                    array_push($cardList, $card);
                }
                return $cardList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }  

        public function getByClientDni($dni) 
        {
            try
            {
                $cardList = array(); 

                $query = "SELECT card_id, card_number, securityCode, expirationDate, fk_client, firstName, lastName, dni, fk_user, user_email, password, rol
                    FROM ".$this->tableName."
                      inner join ".$this->tableClient." on client_id= fk_client
                      inner join ".$this->tableUser." on user_id = fk_user 
                      WHERE dni = :dni"; 

                $parameters["dni"] = $dni;

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query,$parameters);
                
                foreach ($resultSet as $row) 
                {                
                    $card = new Card();
                    $card->setId($row["card_id"]);
                    $card->setNumber($row["card_number"]);
                    $card->setsecurityCode($row["securityCode"]);
                    $card->setExpirationDate($row["expirationDate"]);

                    $client = new Client();
                    $client->setId($row["fk_client"]);
                    $client->setFirstName($row["firstName"]);
                    $client->setLastName($row["lastName"]);
                    $client->setDni($row["dni"]);

                    $User = new User();
                    $User->setId($row["fk_user"]);
                    $User->setEmail($row["user_email"]);
                    $User->setpassword($row["password"]);
                    $User->setRol($row["rol"]);

                    $client->setUser($User);

                    $card->setClient($client);

                    array_push($cardList, $card);
                }
                return $cardList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        } 

        public function delete($id)
        {
            try
            {
                $query = "DELETE FROM ".$this->tableName." WHERE card_id = :card_id";
                
                $parameters["card_id"] = $id;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function deleteByClient($clientId)
        {
            try
            {
                $query = "DELETE FROM ".$this->tableName." WHERE fk_client = :fk_client";
                
                $parameters["fk_client"] = $clientId;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function update(Card $card)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." 
                    SET card_number = :card_number, securityCode = :securityCode, expirationDate = :expirationDate, fk_client = :fk_client   
                    WHERE card_id = :card_id;";

                $parameters["card_number"] = $card->getNumber();
                $parameters["securityCode"] = $card->getsecurityCode();
                $parameters["expirationDate"] = $card->getExpirationDate();
                $parameters["fk_client"] = $card->getClient()->getId();
                $parameters["card_id"] = $card->getId();

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
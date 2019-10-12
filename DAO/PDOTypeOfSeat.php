<?php
    namespace DAO;

    use Model\TypeOfSeat as TypeOfSeat;
    use DAO\Connection as Connection;
    use DAO\IDAOTypeOfSeat as IDAOTypeOfSeat;

    class PDOTypeOfSeat implements IDAOTypeOfSeat
    {
        private $connection;
        private $tableName = "TypeOfSeats"; 

        public function add(TypeOfSeat $TypeOfSeat)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (type_name) VALUES (:type_name);";

                $parameters["type_name"] = $TypeOfSeat->getName();

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
                $TypeOfSeatList = array(); 

                $query = "SELECT type_id, type_name, type_state FROM ".$this->tableName." ORDER BY type_name"; 

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query); 
                
                foreach ($resultSet as $row) 
    			{                
                    $TypeOfSeat = new TypeOfSeat();
                    $TypeOfSeat->setId($row["type_id"]);
                    $TypeOfSeat->setName($row["type_name"]);
                    $TypeOfSeat->setState($row["type_state"]);

    				array_push($TypeOfSeatList, $TypeOfSeat);
    			}
                return $TypeOfSeatList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getAllActives() 
        {
            try
            {
                $TypeOfSeatList = array(); 

                $query = "SELECT type_id, type_name, type_state FROM ".$this->tableName." WHERE type_state = 0 ORDER BY type_name"; 

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query); 
                
                foreach ($resultSet as $row) 
                {                
                    $TypeOfSeat = new TypeOfSeat();
                    $TypeOfSeat->setId($row["type_id"]);
                    $TypeOfSeat->setName($row["type_name"]);
                    $TypeOfSeat->setState($row["type_state"]);

                    array_push($TypeOfSeatList, $TypeOfSeat);
                }
                return $TypeOfSeatList;
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
                $TypeOfSeat = null;

                $query = "SELECT type_id, type_name, type_state FROM ".$this->tableName." WHERE type_id = :type_id";

                $parameters["type_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row) 
    			{
                    $TypeOfSeat = new TypeOfSeat();
                    $TypeOfSeat->setId($row["type_id"]);
                    $TypeOfSeat->setName($row["type_name"]);
                    $TypeOfSeat->setState($row["type_state"]);
                }           
                return $TypeOfSeat;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByName($name)
        {
            try
            {
                $TypeOfSeat = null;

                $name=ucwords($name);

                $query = "SELECT type_id, type_name, type_state FROM ".$this->tableName." WHERE type_name = :type_name";

                $parameters["type_name"] = $name;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row) 
                {
                    $TypeOfSeat = new TypeOfSeat();
                    $TypeOfSeat->setId($row["type_id"]);
                    $TypeOfSeat->setName($row["type_name"]);
                    $TypeOfSeat->setState($row["type_state"]);
                }          
                return $TypeOfSeat;
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
                $query = "UPDATE ".$this->tableName." SET type_state = if(type_state=0, 1,0) where type_id = :type_id";
                
                $parameters["type_id"] = $id;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function update(TypeOfSeat $typeOfSeat)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." 
                    SET type_name = :type_name  
                    WHERE type_id = :type_id;";

                $parameters["type_name"] = $typeOfSeat->getName();
                $parameters["type_id"] = $typeOfSeat->getId();

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
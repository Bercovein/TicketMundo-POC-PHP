<?php
    namespace DAO;

    use Model\EventPlace as EventPlace;
    use DAO\Connection as Connection;
    use DAO\IDAOEventPlace as IDAOEventPlace;

    class PDOEventPlace implements IDAOEventPlace
    {
        private $connection; 
        private $tableName = "EventPlaces";

        public function add(EventPlace $EventPlace)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (place_name, capacity) VALUES (:place_name, :capacity);";

                $parameters["place_name"] = $EventPlace->getName();
                $parameters["capacity"] = $EventPlace->getCapacity();

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
                $EventPlaceList = array(); 

                $query = "SELECT place_id, place_name, capacity, place_state FROM ".$this->tableName." ORDER BY place_name"; 

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query); 
                
                foreach ($resultSet as $row)
    			{                
                    $EventPlace = new EventPlace();
                    $EventPlace->setId($row["place_id"]);
                    $EventPlace->setName($row["place_name"]);
                    $EventPlace->setCapacity($row["capacity"]);
                    $EventPlace->setState($row["place_state"]);

    				array_push($EventPlaceList, $EventPlace);
    			}
                return $EventPlaceList;
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
                $EventPlaceList = array(); 

                $query = "SELECT place_id, place_name, capacity, place_state FROM ".$this->tableName." where place_state = 0 ORDER BY place_name"; 

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query); 
                
                foreach ($resultSet as $row)
                {                
                    $EventPlace = new EventPlace();
                    $EventPlace->setId($row["place_id"]);
                    $EventPlace->setName($row["place_name"]);
                    $EventPlace->setCapacity($row["capacity"]);
                    $EventPlace->setState($row["place_state"]);

                    array_push($EventPlaceList, $EventPlace);
                }
                return $EventPlaceList;
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
                $EventPlace = null;

                $query = "SELECT place_id, place_name, capacity, place_state FROM ".$this->tableName." WHERE place_id = :place_id";

                $parameters["place_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
    			{
                    $EventPlace = new EventPlace();
                    $EventPlace->setId($row["place_id"]);
                    $EventPlace->setName($row["place_name"]);
                    $EventPlace->setCapacity($row["capacity"]);
                    $EventPlace->setState($row["place_state"]);
                }         
                return $EventPlace;
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
                $EventPlace = null;

                $name=ucwords($name);

                $query = "SELECT place_id, place_name, capacity, place_state FROM ".$this->tableName." WHERE place_name = :place_name";

                $parameters["place_name"] = $name;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
                {
                    $EventPlace = new EventPlace();
                    $EventPlace->setId($row["place_id"]);
                    $EventPlace->setName($row["place_name"]);
                    $EventPlace->setCapacity($row["capacity"]);
                    $EventPlace->setState($row["place_state"]);
                }           
                return $EventPlace;
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
                $query = "UPDATE ".$this->tableName." SET place_state = if(place_state=0, 1,0) where place_id = :place_id";
                
                $parameters["place_id"] = $id;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function update(EventPlace $eventPlace)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." 
                    SET capacity = :capacity, place_name = :place_name  
                    WHERE place_id = :place_id;";

                $parameters["capacity"] = $eventPlace->getCapacity();
                $parameters["place_name"] = $eventPlace->getName();
                $parameters["place_id"] = $eventPlace->getId();

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


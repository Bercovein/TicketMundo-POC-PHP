<?php
    namespace DAO;

    use Model\Artist as Artist;
    use DAO\Connection as Connection;
    use DAO\IDAOArtist as IDAOArtist;
    
    class PDOArtist implements IDAOArtist
    {
        private $connection;
        private $tableName = "Artists";

        public function add(Artist $artist)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (artist_name) VALUES (:artist_name);";

                $parameters["artist_name"] = $artist->getName();

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
                $artistList = array(); 

                $query = "SELECT artist_id, artist_name, artist_state FROM ".$this->tableName." ORDER BY artist_name;";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query); 
                
                foreach ($resultSet as $row) 
    			{                
                    $artist = new Artist();
                    $artist->setId($row["artist_id"]);
                    $artist->setName($row["artist_name"]);
                    $artist->setState($row["artist_state"]);

    				array_push($artistList, $artist);
    			}
                return $artistList;
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
                $artistList = array(); 

                $query = "SELECT artist_id, artist_name, artist_state FROM ".$this->tableName." WHERE artist_state = 0 ORDER BY artist_name;";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query); 
                
                foreach ($resultSet as $row) 
                {                
                    $artist = new Artist();
                    $artist->setId($row["artist_id"]);
                    $artist->setName($row["artist_name"]);
                    $artist->setState($row["artist_state"]);

                    array_push($artistList, $artist);
                }
                return $artistList;
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
                $artist = null;

                $query = "SELECT artist_id, artist_name, artist_state FROM ".$this->tableName." WHERE artist_id = :artist_id;";

                $parameters["artist_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row) 
    			{
                    $artist = new Artist();
                    $artist->setId($row["artist_id"]);
                    $artist->setName($row["artist_name"]);
                    $artist->setState($row["artist_state"]);
                }             
                return $artist;
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
                $artist = null;

                $name=ucwords($name);

                $query = "SELECT artist_id, artist_name, artist_state FROM ".$this->tableName." WHERE artist_name = :artist_name;";

                $parameters["artist_name"] = $name;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
                {
                    $artist = new Artist();
                    $artist->setId($row["artist_id"]);
                    $artist->setName($row["artist_name"]);
                    $artist->setState($row["artist_state"]);
                }             
                return $artist;
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
                $query = "UPDATE ".$this->tableName." SET artist_state = if(artist_state=0, 1,0) where artist_id = :artist_id";
                
                $parameters["artist_id"] = $id;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function update(Artist $artist)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." 
                    SET artist_name = :artist_name  
                    WHERE artist_id = :artist_id;";

                $parameters["artist_name"] = $artist->getName();
                $parameters["artist_id"] = $artist->getId();

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
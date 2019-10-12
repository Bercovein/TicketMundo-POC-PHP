<?php
    namespace DAO;

    use Model\Event as Event;
    use Model\Category as Category;
    use Model\Calendar as Calendar;
    use DAO\Connection as Connection;
    use DAO\IDAOEvent as IDAOEvent;
    
    class PDOEvent implements IDAOEvent
    {
        private $connection; 
        private $tableName = "Events"; 
        private $tableCalendar="Calendars";

        public function add(Event $Event)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (title, fk_category, banner) VALUES (:title, :fk_category, :banner);";

                $parameters["title"] = $Event->getName();
                $parameters["fk_category"] = $Event->getCategory()->getId();
                $parameters["banner"] = $Event->getBanner();

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
                $EventList = array(); 

                $query = "SELECT event_id, title, event_state, fk_category, category_name, banner FROM ".$this->tableName.
                " inner join Categories on fk_category=category_id ORDER BY title;"; 

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query); 
                
                foreach ($resultSet as $row) 
    			{                
                    $Event = new Event();
                    $Event->setId($row["event_id"]);
                    $Event->setName($row["title"]);
                    $Event->setBanner($row["banner"]);
                    $Event->setState($row["event_state"]);

                    $category= new Category();
                    $category->setId($row["fk_category"]);
                    $category->setName($row["category_name"]);

                    $Event->setCategory($category);

    				array_push($EventList, $Event);
    			}
                return $EventList;
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
                $EventList = array(); 

                $query = "SELECT event_id, title, event_state, fk_category, category_name, banner FROM ".$this->tableName.
                " inner join Categories on fk_category=category_id
                  WHERE event_state = 0
                  ORDER BY title;"; 

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query); 
                
                foreach ($resultSet as $row) 
                {                
                    $Event = new Event();
                    $Event->setId($row["event_id"]);
                    $Event->setName($row["title"]);
                    $Event->setBanner($row["banner"]);
                    $Event->setState($row["event_state"]);

                    $category= new Category();
                    $category->setId($row["fk_category"]);
                    $category->setName($row["category_name"]);

                    $Event->setCategory($category);

                    array_push($EventList, $Event);
                }
                return $EventList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getAllActivesWithoutFestivals() 
        {
            try
            {
                $EventList = array(); 

                $query = "SELECT event_id, title, event_state, fk_category, category_name, banner FROM ".$this->tableName.
                " inner join Categories on fk_category=category_id
                  WHERE event_state = 0 and category_name != 'Festival' 
                  ORDER BY title;"; 

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query); 
                
                foreach ($resultSet as $row) 
                {                
                    $Event = new Event();
                    $Event->setId($row["event_id"]);
                    $Event->setName($row["title"]);
                    $Event->setBanner($row["banner"]);
                    $Event->setState($row["event_state"]);

                    $category= new Category();
                    $category->setId($row["fk_category"]);
                    $category->setName($row["category_name"]);

                    $Event->setCategory($category);

                    array_push($EventList, $Event);
                }
                return $EventList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getFestivalsActives() 
        {
            try
            {
                $EventList = array(); 

                $query = "SELECT event_id, title, event_state, fk_category, category_name, banner FROM ".$this->tableName.
                " inner join Categories on fk_category=category_id
                  WHERE event_state = 0 and category_name = 'Festival' 
                  ORDER BY title;"; 

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query); 
                
                foreach ($resultSet as $row) 
                {                
                    $Event = new Event();
                    $Event->setId($row["event_id"]);
                    $Event->setName($row["title"]);
                    $Event->setBanner($row["banner"]);
                    $Event->setState($row["event_state"]);

                    $category= new Category();
                    $category->setId($row["fk_category"]);
                    $category->setName($row["category_name"]);

                    $Event->setCategory($category);

                    array_push($EventList, $Event);
                }
                return $EventList;
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
                $Event = null;

                $query = "SELECT event_id, title, event_state, fk_category, category_name, banner FROM ".$this->tableName.
                " inner join Categories on fk_category=category_id WHERE event_id = :event_id;";

                $parameters["event_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
    			{
                    $Event = new Event();
                    $Event->setId($row["event_id"]);
                    $Event->setName($row["title"]);
                    $Event->setBanner($row["banner"]);
                    $Event->setState($row["event_state"]);

                    $category= new Category();
                    $category->setId($row["fk_category"]);
                    $category->setName($row["category_name"]);

                    $Event->setCategory($category);
                }          
                return $Event;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByBanner($banner)
        {
            try
            {
                $Event = null;

                $query = "SELECT event_id, title, event_state, fk_category, category_name, banner FROM ".$this->tableName.
                " inner join Categories on fk_category=category_id WHERE banner = :banner;";

                $parameters["banner"] = $banner;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
                {
                    $Event = new Event();
                    $Event->setId($row["event_id"]);
                    $Event->setName($row["title"]);
                    $Event->setBanner($row["banner"]);
                    $Event->setState($row["event_state"]);

                    $category= new Category();
                    $category->setId($row["fk_category"]);
                    $category->setName($row["category_name"]);

                    $Event->setCategory($category);
                }          
                return $Event;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }


        public function getByCategoryId($id)
        {
            try
            {
                $Event = null;

                $query = "SELECT event_id, title, event_state, fk_category, category_name, banner FROM ".$this->tableName.
                " inner join Categories on fk_category=category_id WHERE fk_category = :fk_category;";

                $parameters["fk_category"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
                {
                    $Event = new Event();
                    $Event->setId($row["event_id"]);
                    $Event->setName($row["title"]);
                    $Event->setBanner($row["banner"]);
                    $Event->setState($row["event_state"]);

                    $category= new Category();
                    $category->setId($row["fk_category"]);
                    $category->setName($row["category_name"]);

                    $Event->setCategory($category);
                }          
                return $Event;
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
                $Event = null;

                $name=ucwords($name);

                $query = "SELECT event_id, title, event_state, fk_category, category_name FROM ".$this->tableName.
                " inner join Categories on fk_category=category_id  WHERE title = :title;";

                $parameters["title"] = $name;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
                {
                    $Event = new Event();
                    $Event->setId($row["event_id"]);
                    $Event->setName($row["title"]);
                    $Event->setState($row["event_state"]);

                    $category= new Category();
                    $category->setId($row["fk_category"]);
                    $category->setName($row["category_name"]);

                    $Event->setCategory($category);
                }          
                return $Event;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function deleteBanner($id)
        {
            try
            {
                 $query = "UPDATE ".$this->tableName." 
                    SET banner = :banner
                    WHERE event_id = :event_id;";
                
                $parameters["event_id"] = $id;
                $parameters["banner"] = NULL;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
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
                $query = "UPDATE ".$this->tableName." SET event_state = if(event_state=0, 1,0) where event_id = :event_id";
                
                $parameters["event_id"] = $id;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function update(Event $event)
        {
            try
            {   
                if($event->getBanner()!=NULL){

                    $query = "UPDATE ".$this->tableName." 
                    SET title = :title, fk_category = :fk_category, banner = :banner
                    WHERE event_id = :event_id;";

                    $parameters["banner"] = $event->getBanner();

                }else{
                    $query = "UPDATE ".$this->tableName." 
                    SET title = :title, fk_category = :fk_category
                    WHERE event_id = :event_id;";
                }

                $parameters["title"] = $event->getName();
                $parameters["fk_category"] = $event->getCategory()->getId();
                $parameters["event_id"] = $event->getId();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex){
                throw $ex;
            }
        }

        public function updateAll()
        {
            try
            {   
                $query = "UPDATE ".$this->tableName." 
                set event_state = 1 where now() > all (select day from ".$this->tableCalendar." where event_id = fk_event) and event_state = 0;"; 

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

    }
?>
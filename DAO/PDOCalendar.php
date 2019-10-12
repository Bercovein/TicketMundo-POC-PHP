<?php
    namespace DAO;

    use DAO\Connection as Connection;
    use DAO\IDAOCalendar as IDAOCalendar;
    
    use Model\Calendar as Calendar;
    use Model\Event as Event;
    use Model\Category as Category;
    use Model\EventPlace as EventPlace;
    use Model\Artist as Artist;
    use Model\TypeOfSeat as TypeOfSeat;

    class PDOCalendar implements IDAOCalendar
    {
        private $connection; 
        private $tableName = "Calendars"; 
        private $CalendarsXArtists = "CalendarsXArtists";
        private $tableEvent = "Events";
        private $tablePlace = "EventPlaces";
        private $tableCategory = "Categories";
        private $tableArtist = "Artists";
        private $tableType = "TypeOfSeats";
        private $tableSeats = "EventSeats";


        public function add(Calendar $Calendar)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (day, fk_event, fk_eventplace) VALUES (:day, :fk_event, :fk_eventplace);";

                $parameters["day"] = $Calendar->getDate();
                $parameters["fk_event"] = $Calendar->getEvent()->getId();
                $parameters["fk_eventplace"] = $Calendar->getEventPlace()->getId();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);

                $Calendar->setId($this->getLastId());

                $this->addArtists($Calendar);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function addArtists(Calendar $Calendar)
        {
            try
            {
                $artistList=$Calendar->getArtistList();

                $query = "INSERT INTO ".$this->CalendarsXArtists." (pfk_artist, pfk_ca_calendar) VALUES ";
                foreach ($artistList as $artist){ 
                    $query .= "(".$artist->getId().",".$Calendar->getId()."),";
                }
                $query = rtrim($query, ',');

                $parameters = array();
    
                foreach ($artistList as $artist) {
                    $parameter1["pfk_ca_calendar"] = $Calendar->getId();
                    array_push($parameters, $parameter1);
                    $parameter2["pfk_artist"] = $artist->getId();
                    array_push($parameters, $parameter2);
                }

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery2($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            } 
        }
        

        public function getLastId(){

            try
            {
                $query = " SELECT calendar_id FROM ".$this->tableName." ORDER BY calendar_id desc limit 1;";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query); 

                $id = 1;

                foreach ($resultSet as $row){
                    $id = $row["calendar_id"];
                }
                return $id;
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
                $CalendarList = array(); 

                $query = "(SELECT calendar_id, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, calendar_state, fk_event, title, 
                    fk_category, category_name, fk_eventplace, place_name, 
                    capacity, pfk_artist AS idArtist, artist_name AS artistName 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    inner join ".$this->CalendarsXArtists." on calendar_id = pfk_ca_calendar
                    inner join ".$this->tableArtist." on artist_id = pfk_artist
                    order by calendar_id)
                union
                    (SELECT calendar_id, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, calendar_state, fk_event, title, fk_category, 
                    category_name, fk_eventplace, place_name, capacity, 
                    '0' AS idArtist, ' ' AS artistName
                    FROM ".$this->tableName." 
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    where calendar_id <> ALL(SELECT pfk_ca_calendar 
                        FROM ".$this->CalendarsXArtists.")
                    order by calendar_id)
                order by DATE_FORMAT(day,'%Y-%m-%d %H:%i');";

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query); 

                $date = null;

                foreach ($resultSet as $row) 
    			{   
                    if($row["day"] != $date)
                    {
                        $Calendar = new Calendar();
                        $Calendar->setId($row["calendar_id"]);
                        $Calendar->setDate($row["day"]);
                        $Calendar->setState($row["calendar_state"]);

                        $Event = new Event();
                        $Event->setId($row["fk_event"]);
                        $Event->setName($row["title"]);

                        $Category = new Category();
                        $Category->setId($row["fk_category"]);
                        $Category->setName($row["category_name"]);

                        $Event->setCategory($Category);

                        $EventPlace = new EventPlace();
                        $EventPlace->setId($row["fk_eventplace"]);
                        $EventPlace->setName($row["place_name"]);
                        $EventPlace->setCapacity($row["capacity"]);

                        $Calendar->setEventPlace($EventPlace);
                        $Calendar->setEvent($Event);

                        $artistList = $Calendar->getArtistList();

                        $date = $row["day"];

                        array_push($CalendarList, $Calendar);
                    }

                    if($row["idArtist"] != '0'){

                        $artist = new Artist();
                        $artist->setId($row["idArtist"]);
                        $artist->setName($row["artistName"]);

                        array_push($artistList, $artist);
                        $Calendar->setArtistList($artistList);
                    }
                }
                
                return $CalendarList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getAllWithoutFestival()
        {
            try
            {
                $CalendarList = array(); 

                $query = "(SELECT calendar_id, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, calendar_state, fk_event, title, 
                    fk_category, category_name, fk_eventplace, place_name, 
                    capacity, pfk_artist AS idArtist, artist_name AS artistName 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    inner join ".$this->CalendarsXArtists." on calendar_id = pfk_ca_calendar
                    inner join ".$this->tableArtist." on artist_id = pfk_artist
                    where category_name != 'Festival'
                    order by calendar_id)
                union
                    (SELECT calendar_id, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, calendar_state, fk_event, title, fk_category, 
                    category_name, fk_eventplace, place_name, capacity, 
                    '0' AS idArtist, ' ' AS artistName
                    FROM ".$this->tableName." 
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    where category_name != 'Festival' and calendar_id <> ALL(SELECT pfk_ca_calendar 
                        FROM ".$this->CalendarsXArtists.")
                    order by calendar_id)
                order by DATE_FORMAT(day,'%Y-%m-%d %H:%i');";

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query); 

                $date = null;

                foreach ($resultSet as $row) 
                {   
                    if($row["day"] != $date)
                    {
                        $Calendar = new Calendar();
                        $Calendar->setId($row["calendar_id"]);
                        $Calendar->setDate($row["day"]);
                        $Calendar->setState($row["calendar_state"]);

                        $Event = new Event();
                        $Event->setId($row["fk_event"]);
                        $Event->setName($row["title"]);

                        $Category = new Category();
                        $Category->setId($row["fk_category"]);
                        $Category->setName($row["category_name"]);

                        $Event->setCategory($Category);

                        $EventPlace = new EventPlace();
                        $EventPlace->setId($row["fk_eventplace"]);
                        $EventPlace->setName($row["place_name"]);
                        $EventPlace->setCapacity($row["capacity"]);

                        $Calendar->setEventPlace($EventPlace);
                        $Calendar->setEvent($Event);

                        $artistList = $Calendar->getArtistList();

                        $date = $row["day"];

                        array_push($CalendarList, $Calendar);
                    }

                    if($row["idArtist"] != '0'){

                        $artist = new Artist();
                        $artist->setId($row["idArtist"]);
                        $artist->setName($row["artistName"]);

                        array_push($artistList, $artist);
                        $Calendar->setArtistList($artistList);
                    }
                }
                return $CalendarList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getCalendarFestival()
        {
            try
            {
                $calendarList = array(); 

                $query = "SELECT calendar_id, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, calendar_state, fk_event, title, 
                    fk_category, category_name, fk_eventplace, place_name, capacity 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    where category_name = 'Festival'
                    group by title;";

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query); 

                foreach ($resultSet as $row)
                {
                    $Calendar = new Calendar();
                    $Calendar->setId($row["calendar_id"]);
                    $Calendar->setDate($row["day"]);
                    $Calendar->setState($row["calendar_state"]);

                    $Event = new Event();
                    $Event->setId($row["fk_event"]);
                    $Event->setName($row["title"]);

                    $Category = new Category();
                    $Category->setId($row["fk_category"]);
                    $Category->setName($row["category_name"]);

                    $Event->setCategory($Category);

                    $EventPlace = new EventPlace();
                    $EventPlace->setId($row["fk_eventplace"]);
                    $EventPlace->setName($row["place_name"]);
                    $EventPlace->setCapacity($row["capacity"]);

                    $Calendar->setEventPlace($EventPlace);
                    $Calendar->setEvent($Event);

                    array_push($calendarList, $Calendar); 
                }     
                return $calendarList;
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
                $Calendar = null;

                $query = "SELECT calendar_id, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, calendar_state, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity, pfk_artist, artist_name
                    FROM ".$this->tableName." 
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    inner join ".$this->CalendarsXArtists." on calendar_id = pfk_ca_calendar
                    inner join ".$this->tableArtist." on artist_id = pfk_artist
                    WHERE calendar_id = :calendar_id
                    order by DATE_FORMAT(day,'%Y-%m-%d %H:%i')";

                $parameters["calendar_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                $id = 0;

                foreach ($resultSet as $row)
    			{
                    if($row["calendar_id"] != $id)
                    {
                        $Calendar = new Calendar();
                        $Calendar->setId($row["calendar_id"]);
                        $Calendar->setDate($row["day"]);
                        $Calendar->setState($row["calendar_state"]);

                        $Event = new Event();
                        $Event->setId($row["fk_event"]);
                        $Event->setName($row["title"]);

                        $Category = new Category();
                        $Category->setId($row["fk_category"]);
                        $Category->setName($row["category_name"]);

                        $Event->setCategory($Category);

                        $EventPlace = new EventPlace();
                        $EventPlace->setId($row["fk_eventplace"]);
                        $EventPlace->setName($row["place_name"]);
                        $EventPlace->setCapacity($row["capacity"]);

                        $Calendar->setEventPlace($EventPlace);
                        $Calendar->setEvent($Event);

                        $artistList = $Calendar->getArtistList();

                        $id = $row["calendar_id"];
                    }

                    if($row["pfk_artist"] != NULL){

                        $artist = new Artist();
                        $artist->setId($row["pfk_artist"]);
                        $artist->setName($row["artist_name"]);

                        array_push($artistList, $artist);
                        $Calendar->setArtistList($artistList);
                    }
                }          
                return $Calendar;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByPlace($id)
        {
            try
            {
                $Calendar = null;

                $query = "SELECT calendar_id, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, calendar_state, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity, pfk_artist, artist_name
                    FROM ".$this->tableName." 
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    inner join ".$this->CalendarsXArtists." on calendar_id = pfk_ca_calendar
                    inner join ".$this->tableArtist." on artist_id = pfk_artist
                    WHERE fk_eventplace = :fk_eventplace
                    order by DATE_FORMAT(day,'%Y-%m-%d %H:%i');";

                $parameters["fk_eventplace"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                $id = 0;

                foreach ($resultSet as $row)
                {
                    if($row["calendar_id"] != $id)
                    {
                        $Calendar = new Calendar();
                        $Calendar->setId($row["calendar_id"]);
                        $Calendar->setDate($row["day"]);
                        $Calendar->setState($row["calendar_state"]);

                        $Event = new Event();
                        $Event->setId($row["fk_event"]);
                        $Event->setName($row["title"]);

                        $Category = new Category();
                        $Category->setId($row["fk_category"]);
                        $Category->setName($row["category_name"]);

                        $Event->setCategory($Category);

                        $EventPlace = new EventPlace();
                        $EventPlace->setId($row["fk_eventplace"]);
                        $EventPlace->setName($row["place_name"]);
                        $EventPlace->setCapacity($row["capacity"]);

                        $Calendar->setEventPlace($EventPlace);
                        $Calendar->setEvent($Event);

                        $artistList = $Calendar->getArtistList();

                        $id = $row["calendar_id"];
                    }

                    if($row["pfk_artist"] != NULL){

                        $artist = new Artist();
                        $artist->setId($row["pfk_artist"]);
                        $artist->setName($row["artist_name"]);

                        array_push($artistList, $artist);
                        $Calendar->setArtistList($artistList);
                    }
                }          
                return $Calendar;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByEvent($title)
        {
            try
            {
                $calendarList=array();

                $query = "SELECT calendar_id, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, calendar_state, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity
                    FROM ".$this->tableName." 
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    WHERE title = :title
                    and calendar_state = 0
                    order by DATE_FORMAT(day,'%Y-%m-%d %H:%i');";

                $parameters["title"] = $title;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
            
                foreach ($resultSet as $row)
                {
                        $Calendar = new Calendar();
                        $Calendar->setId($row["calendar_id"]);
                        $Calendar->setDate($row["day"]);
                        $Calendar->setState($row["calendar_state"]);

                        $Event = new Event();
                        $Event->setId($row["fk_event"]);
                        $Event->setName($row["title"]);

                        $Category = new Category();
                        $Category->setId($row["fk_category"]);
                        $Category->setName($row["category_name"]);

                        $Event->setCategory($Category);

                        $EventPlace = new EventPlace();
                        $EventPlace->setId($row["fk_eventplace"]);
                        $EventPlace->setName($row["place_name"]);
                        $EventPlace->setCapacity($row["capacity"]);

                        $Calendar->setEventPlace($EventPlace);
                        $Calendar->setEvent($Event);

                        array_push($calendarList, $Calendar);    
                }
               return $calendarList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByArtist($name)
        {
            try
            {
                $calendarList=array();

                $query = "SELECT calendar_id, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, calendar_state, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity
                    FROM ".$this->CalendarsXArtists." 
                    inner join ".$this->tableArtist." on artist_id = pfk_artist
                    inner join ".$this->tableName." on  pfk_ca_calendar = calendar_id
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    WHERE artist_name = :artist_name
                    and calendar_state = 0
                    order by DATE_FORMAT(day,'%Y-%m-%d %H:%i');";

                $parameters["artist_name"] = $name;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);

                foreach ($resultSet as $row)
                {
                    $Calendar = new Calendar();
                    $Calendar->setId($row["calendar_id"]);
                    $Calendar->setDate($row["day"]);
                    $Calendar->setState($row["calendar_state"]);

                    $Event = new Event();
                    $Event->setId($row["fk_event"]);
                    $Event->setName($row["title"]);

                    $Category = new Category();
                    $Category->setId($row["fk_category"]);
                    $Category->setName($row["category_name"]);

                    $Event->setCategory($Category);

                    $EventPlace = new EventPlace();
                    $EventPlace->setId($row["fk_eventplace"]);
                    $EventPlace->setName($row["place_name"]);
                    $EventPlace->setCapacity($row["capacity"]);

                    $Calendar->setEventPlace($EventPlace);
                    $Calendar->setEvent($Event);

                    array_push($calendarList, $Calendar); 
                }          
                return $calendarList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function delete($id)
        {
            try
            {   /*
                $query = "DELETE FROM ".$this->CalendarsXArtists." WHERE pfk_ca_calendar = :pfk_ca_calendar;";
                
                $parameters["pfk_ca_calendar"] = $id;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
                */
                
                //$query2 = "DELETE FROM ".$this->tableName." WHERE calendar_id = :calendar_id;";
                $query2 = "UPDATE ".$this->tableName." SET calendar_state = if(calendar_state = 0, 1,0) where calendar_id = :calendar_id";

                $parameters2["calendar_id"] = $id;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query2, $parameters2);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function deleteArtistFromCalendar($idCalendar,$idArtist)
        {
            try
            {
                $query = "DELETE FROM ".$this->CalendarsXArtists." WHERE pfk_ca_calendar = :pfk_ca_calendar and pfk_artist = :pfk_artist;";
                
                $parameters["pfk_ca_calendar"] = $idCalendar;
                $parameters["pfk_artist"] = $idArtist;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function update(Calendar $calendar)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." 
                    SET day = :day, fk_event = :fk_event, fk_eventplace = :fk_eventplace   
                    WHERE calendar_id = :calendar_id;";

                $parameters["day"] = $calendar->getDate();
                $parameters["fk_event"] = $calendar->getEvent()->getId();
                $parameters["fk_eventplace"] = $calendar->getEventPlace()->getId();
                $parameters["calendar_id"] = $calendar->getId();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);

                if($calendar->getArtistList()!=NULL)
                    $this->addArtists($calendar);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function updateAll()
        {
            try
            { 
                $query = "update ".$this->tableName." set calendar_state = 1 where day < now() and calendar_state = 0;";

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
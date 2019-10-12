<?php
    namespace DAO;

    use DAO\Connection as Connection;
    use DAO\IDAOEventSeats as IDAOEventSeats;

    use Model\EventSeats as EventSeats;
    use Model\TypeOfSeat as TypeOfSeat;
    use Model\Calendar as Calendar;
    use Model\Event as Event;
    use Model\Category as Category;
    use Model\EventPlace as EventPlace;
    use Model\Artist as Artist;


    class PDOEventSeats implements IDAOEventSeats
    {
        private $connection; 
        private $tableName = "EventSeats"; 
        private $tableCalendar = "Calendars";
        private $tableSeats = "TypeOfSeats";
        private $tableEvent = "Events";
        private $tableCategory = "Categories";
        private $tablePlace = "EventPlaces";
        private $CalendarsXArtists = "CalendarsXArtists";
        private $tableArtist = "Artists";

        public function add(EventSeats $seats)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (pfk_typeofseats, pfk_calendar, quantity, price, remanents) VALUES (:pfk_typeofseats, :pfk_calendar, :quantity, :price, :remanents);";

                    $parameters["pfk_calendar"] = $seats->getCalendar()->getId();
                    $parameters["pfk_typeofseats"] = $seats->getTypeOfSeat()->getId();
                    $parameters["quantity"] = $seats->getQuantity();
                    $parameters["price"] = $seats->getPrice();
                    $parameters["remanents"] = $seats->getRemanents();

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
                $EventSeatList = array();             

                $query = "SELECT seats_id, quantity, price, remanents, pfk_typeofseats, type_name, type_state, pfk_calendar, day, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableSeats." on type_id=pfk_typeofseats 
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    ORDER BY seats_id";

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query); 
                
                $id = 0;

                foreach ($resultSet as $row) 
    			{      
                    if($row["seats_id"] != $id)
                    {          
                        $EventSeat = new EventSeats();
                        $EventSeat->setId($row["seats_id"]);
                        $EventSeat->setQuantity($row["quantity"]);
                        $EventSeat->setPrice($row["price"]);
                        $EventSeat->setRemanents($row["remanents"]);

                        $typeOfSeat= new TypeOfSeat();
                        $typeOfSeat->setId($row["pfk_typeofseats"]);
                        $typeOfSeat->setName($row["type_name"]);
                        $typeOfSeat->setState($row["type_state"]);

                        $EventSeat->setTypeOfSeat($typeOfSeat);

                        $Calendar = new Calendar();
                        $Calendar->setId($row["pfk_calendar"]);
                        $Calendar->setDate($row["day"]);

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

                        $EventSeat->setCalendar($Calendar);

        				array_push($EventSeatList, $EventSeat);

                        $id = $row["seats_id"];
                    }
    			}
                return $EventSeatList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByCalendarId($idCalendar)
        {
            try
            {
                $eventSeatsList = array(); 

                $query = "SELECT seats_id, quantity, price, remanents, pfk_typeofseats, type_name, type_state, pfk_calendar, day, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableSeats." on type_id=pfk_typeofseats 
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    WHERE pfk_calendar = ".$idCalendar."
                    ORDER BY seats_id";

                $connection = Connection::GetInstance();

                $resultSet = $connection->Execute($query); 
                
                $id = 0;

                foreach ($resultSet as $row) 
                {   
                    if($row["seats_id"] != $id)
                    {             
                        $EventSeat = new EventSeats();
                        $EventSeat->setId($row["seats_id"]);
                        $EventSeat->setQuantity($row["quantity"]);
                        $EventSeat->setPrice($row["price"]);
                        $EventSeat->setRemanents($row["remanents"]);

                        $typeOfSeat= new TypeOfSeat();
                        $typeOfSeat->setId($row["pfk_typeofseats"]);
                        $typeOfSeat->setName($row["type_name"]);
                        $typeOfSeat->setState($row["type_state"]);

                        $EventSeat->setTypeOfSeat($typeOfSeat);

                        $Calendar = new Calendar();
                        $Calendar->setId($idCalendar);
                        $Calendar->setDate($row["day"]);

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

                        $EventSeat->setCalendar($Calendar);

                        array_push($eventSeatsList, $EventSeat);

                        $id = $row["seats_id"];
                    }
                }
                return $eventSeatsList;     
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
                $EventSeat = null;

                $query = "SELECT seats_id, quantity, price, remanents, pfk_typeofseats, type_name, type_state, pfk_calendar, day, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableSeats." on type_id=pfk_typeofseats 
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    WHERE seats_id = :seats_id
                    ORDER BY seats_id";

                $parameters["seats_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                $id = 0;

                foreach ($resultSet as $row) 
    			{
                    if($row["seats_id"] != $id)
                    { 
                        $EventSeat = new EventSeats();
                        $EventSeat->setId($row["seats_id"]);
                        $EventSeat->setQuantity($row["quantity"]);
                        $EventSeat->setPrice($row["price"]);
                        $EventSeat->setRemanents($row["remanents"]);

                        $typeOfSeat= new TypeOfSeat();
                        $typeOfSeat->setId($row["pfk_typeofseats"]);
                        $typeOfSeat->setName($row["type_name"]);
                        $typeOfSeat->setState($row["type_state"]);

                        $EventSeat->setTypeOfSeat($typeOfSeat);

                        $Calendar = new Calendar();
                        $Calendar->setId($row["pfk_calendar"]);
                        $Calendar->setDate($row["day"]);

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

                        $EventSeat->setCalendar($Calendar);

                        $id = $row["seats_id"];
                    }
                }         
                return $EventSeat;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByType($id)
        {
            try
            {
                $EventSeat = null;

                $query = "SELECT seats_id, quantity, price, remanents, pfk_typeofseats, type_name, type_state, pfk_calendar, day, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableSeats." on type_id=pfk_typeofseats 
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    WHERE pfk_typeofseats = :pfk_typeofseats
                    ORDER BY seats_id";

                $parameters["pfk_typeofseats"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                $id = 0;

                foreach ($resultSet as $row) 
                {
                    if($row["seats_id"] != $id)
                    { 
                        $EventSeat = new EventSeats();
                        $EventSeat->setId($row["seats_id"]);
                        $EventSeat->setQuantity($row["quantity"]);
                        $EventSeat->setPrice($row["price"]);
                        $EventSeat->setRemanents($row["remanents"]);

                        $typeOfSeat= new TypeOfSeat();
                        $typeOfSeat->setId($row["pfk_typeofseats"]);
                        $typeOfSeat->setName($row["type_name"]);
                        $typeOfSeat->setState($row["type_state"]);

                        $EventSeat->setTypeOfSeat($typeOfSeat);

                        $Calendar = new Calendar();
                        $Calendar->setId($row["pfk_calendar"]);
                        $Calendar->setDate($row["day"]);

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

                        $EventSeat->setCalendar($Calendar);

                        $id = $row["seats_id"];
                    }

                }         
                return $EventSeat;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getCalendarsFromEventSeats()
        {
            try
            {
                $CalendarList = array();

                $query = "SELECT pfk_calendar, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, calendar_state, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity, pfk_artist, artist_name 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id 
                    inner join ".$this->CalendarsXArtists." on calendar_id = pfk_ca_calendar
                    inner join ".$this->tableArtist." on artist_id = pfk_artist
                    where calendar_state = 0 
                    ORDER BY DATE_FORMAT(day,'%Y-%m-%d %H:%i');";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                $date = 0;

                foreach ($resultSet as $row) 
                {   
                    if($row["day"] != $date)
                    {
                        $Calendar = new Calendar();
                        $Calendar->setId($row["pfk_calendar"]);
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

                    if($row["pfk_artist"] != NULL){

                        $artist = new Artist();
                        $artist->setId($row["pfk_artist"]);
                        $artist->setName($row["artist_name"]);

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
        
        public function getFestivalCalendarsFromEventSeats()
        {
            try
            {
                $CalendarList = array();

                $query = "SELECT pfk_calendar, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, calendar_state, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity
                    FROM ".$this->tableName." 
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id 
                    where calendar_state = 0 and category_name = 'Festival'
                    group by title;";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);

                foreach ($resultSet as $row) 
                {   
                    $Calendar = new Calendar();
                    $Calendar->setId($row["pfk_calendar"]);
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

                    array_push($CalendarList, $Calendar);
                }
                return $CalendarList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }
        
        public function getEventsFromCalendarsFromEventSeats()
        {
            try
            {
                $eventList = array();

                $query = "SELECT event_id, title, event_state, fk_category, category_name, banner
                    FROM ".$this->tableName." 
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category=category_id
                    where calendar_state = 0 and event_state = 0
                    GROUP BY event_id
                    ORDER BY title;";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);

                foreach ($resultSet as $row) 
                {   
                    $Event = new Event();
                    $Event->setId($row["event_id"]);
                    $Event->setName($row["title"]);
                    $Event->setState($row["event_state"]);
                    $Event->setBanner($row["banner"]);

                    $Category = new Category();
                    $Category->setId($row["fk_category"]);
                    $Category->setName($row["category_name"]);

                    $Event->setCategory($Category);

                    array_push($eventList, $Event);
                }
                return $eventList;
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
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar 
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    WHERE title = :title
                    and calendar_state = 0
                    group by calendar_id 
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

        public function getEventSeatsByEventByTypeOfSeat($title,$type)
        {
            try
            {
                $eventSeatsList = array();

                $query = "SELECT seats_id, quantity, price, remanents, pfk_typeofseats, type_name, type_state, pfk_calendar, day, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableSeats." on type_id=pfk_typeofseats 
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    WHERE title = :title and type_name = :type_name 
                    ORDER BY seats_id";

                $parameters["title"] = $title;
                $parameters["type_name"] = $type;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
            
                $id = 0;

                foreach ($resultSet as $row) 
                {      
                    if($row["seats_id"] != $id)
                    {          
                        $EventSeat = new EventSeats();
                        $EventSeat->setId($row["seats_id"]);
                        $EventSeat->setQuantity($row["quantity"]);
                        $EventSeat->setPrice($row["price"]);
                        $EventSeat->setRemanents($row["remanents"]);

                        $typeOfSeat= new TypeOfSeat();
                        $typeOfSeat->setId($row["pfk_typeofseats"]);
                        $typeOfSeat->setName($row["type_name"]);
                        $typeOfSeat->setState($row["type_state"]);

                        $EventSeat->setTypeOfSeat($typeOfSeat);

                        $Calendar = new Calendar();
                        $Calendar->setId($row["pfk_calendar"]);
                        $Calendar->setDate($row["day"]);

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

                        $EventSeat->setCalendar($Calendar);

                        array_push($eventSeatsList, $EventSeat);

                        $id = $row["seats_id"];
                    }
                }
                return $eventSeatsList;
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
                $query = "DELETE FROM ".$this->tableName." WHERE seats_id = :seats_id";
                
                $parameters["seats_id"] = $id;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function update(EventSeats $eventSeats)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." 
                    SET quantity = :quantity, price = :price, remanents = :remanents, pfk_typeofseats = :pfk_typeofseats
                    WHERE pfk_calendar = :pfk_calendar and pfk_typeofseats = :pfk_typeofseats";

                $parameters["quantity"] = $eventSeats->getQuantity();
                $parameters["price"] = $eventSeats->getPrice();
                $parameters["remanents"] = $eventSeats->getRemanents();
                $parameters["pfk_typeofseats"] = $eventSeats->getTypeOfSeat()->getId();
                $parameters["pfk_calendar"] = $eventSeats->getCalendar()->getId();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function editRemanents($eventSeatId, $newRemanents){

            try
            {
                $query = "UPDATE ".$this->tableName." 
                    SET remanents = :remanents  
                    WHERE seats_id = :seats_id;";
                
                $parameters["seats_id"] = $eventSeatId;
                $parameters["remanents"] = $newRemanents;

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
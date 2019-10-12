<?php
    namespace DAO;

    use DAO\Connection as Connection;
    use DAO\IDAOPurchaseLine as IDAOPurchaseLine;
    use Model\PurchaseLine as PurchaseLine;
    use Model\EventSeats as EventSeats;
    use Model\TypeOfSeat as TypeOfSeat;
    use Model\Calendar as Calendar;
    use Model\Event as Event;
    use Model\Category as Category;
    use Model\EventPlace as EventPlace;
    use Model\Artist as Artist;


    class PDOPurchaseLine implements IDAOPurchaseLine
    {
        private $connection; 
        private $tableName = "PurchaseLines";
        private $tableSeats = "EventSeats";
        private $tableType = "TypeOfSeats";
        private $tableCalendar = "Calendars";
        private $tableEvent = "Events";
        private $tableCategory = "Categories";
        private $tablePlace = "EventPlaces";
        private $CalendarsXArtists = "CalendarsXArtists";
        private $tableArtist = "Artists";
        private $tablePurchase = "Purchases";

        public function add(PurchaseLine $purchaseLine, $purchaseId)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (line_quantity, line_price, fk_eventseat, fk_purchase) VALUES (:line_quantity , :line_price , :fk_eventseat, :fk_purchase);";

                $parameters["line_quantity"] = $purchaseLine->getQuantity();
                $parameters["line_price"] = $purchaseLine->getPrice();
                $parameters["fk_eventseat"] = $purchaseLine->getEventSeat()->getId();
                $parameters["fk_purchase"] = $purchaseId;

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
                $purchaseLineList = array(); 
              
                $query = "SELECT line_id, line_quantity, line_price, fk_eventseat, quantity, price, remanents,
                    pfk_typeofseats, type_name, pfk_calendar, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity, pfk_ca_calendar, pfk_artist, artist_name 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableSeats." on fk_eventseat = seats_id 
                    inner join ".$this->tableType." on pfk_typeofseats = type_id
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    inner join ".$this->CalendarsXArtists." on calendar_id = pfk_ca_calendar
                    inner join ".$this->tableArtist." on artist_id = pfk_artist 
                    ORDER BY line_id";
                
                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query); 
                
                $id = 0;

                foreach ($resultSet as $row) 
    			{
                    if($row["line_id"] != $id)
                    {             
                        $purchaseLine = new PurchaseLine();
                        $purchaseLine->setId($row["line_id"]);
                        $purchaseLine->setQuantity($row["line_quantity"]);
                        $purchaseLine->setPrice($row["line_price"]);
                        
                        $eventSeats = new EventSeats();
                        $eventSeats->setId($row["fk_eventseat"]);
                        $eventSeats->setQuantity($row["quantity"]);
                        $eventSeats->setPrice($row["price"]);
                        $eventSeats->setRemanents($row["remanents"]);

                        $typeofseat = new TypeOfSeat();
                        $typeofseat->setId($row["pfk_typeofseats"]);
                        $typeofseat->setName($row["type_name"]);

                        $eventSeats->setTypeOfSeat($typeofseat);

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

                        $eventSeats->setCalendar($Calendar);

                        $artistList = $Calendar->getArtistList();

                        $purchaseLine->setEventSeat($eventSeats);

        				array_push($purchaseLineList, $purchaseLine);

                        $id = $row["line_id"];
                    }
                    if($row["pfk_artist"] != NULL){

                        $artist = new Artist();
                        $artist->setId($row["pfk_artist"]);
                        $artist->setName($row["artist_name"]);

                        array_push($artistList, $artist);
                        $Calendar->setArtistList($artistList);
                    }
    			}
                return $purchaseLineList;
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
                $purchaseLine = null;

                $query = "SELECT line_id, line_quantity, line_price, fk_eventseat, quantity, price, remanents,
                    pfk_typeofseats, type_name, pfk_calendar, day, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity, pfk_ca_calendar, pfk_artist, artist_name 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableSeats." on fk_eventseat = seats_id 
                    inner join ".$this->tableType." on pfk_typeofseats = type_id
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    inner join ".$this->CalendarsXArtists." on calendar_id = pfk_ca_calendar
                    inner join ".$this->tableArtist." on artist_id = pfk_artist 
                    WHERE purchaseLine_id = :line_id";
            
                $parameters["line_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                $id = 0;

                foreach ($resultSet as $row) 
                {
                    if($row["line_id"] != $id)
                    {             
                        $purchaseLine = new PurchaseLine();
                        $purchaseLine->setId($row["line_id"]);
                        $purchaseLine->setQuantity($row["line_quantity"]);
                        $purchaseLine->setPrice($row["line_price"]);
                        
                        $eventSeats = new EventSeats();
                        $eventSeats->setId($row["fk_eventseat"]);
                        $eventSeats->setQuantity($row["quantity"]);
                        $eventSeats->setPrice($row["price"]);
                        $eventSeats->setRemanents($row["remanents"]);

                        $typeofseat = new TypeOfSeat();
                        $typeofseat->setId($row["pfk_typeofseats"]);
                        $typeofseat->setName($row["type_name"]);

                        $eventSeats->setTypeOfSeat($typeofseat);

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

                        $eventSeats->setCalendar($Calendar);

                        $artistList = $Calendar->getArtistList();

                        $purchaseLine->setEventSeat($eventSeats);

                        $id = $row["line_id"];
                    }
                    if($row["pfk_artist"] != NULL){

                        $artist = new Artist();
                        $artist->setId($row["pfk_artist"]);
                        $artist->setName($row["artist_name"]);

                        array_push($artistList, $artist);
                        $Calendar->setArtistList($artistList);
                    }
                }      
                return $purchaseLine;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByEventSeats($id)
        {
            try
            {
                $purchaseLine = null;

                $query = "SELECT line_id, line_quantity, line_price, fk_eventseat, quantity, price, remanents,
                    pfk_typeofseats, type_name, pfk_calendar, day, fk_event, title, fk_category, category_name, fk_eventplace, place_name, capacity, pfk_ca_calendar, pfk_artist, artist_name 
                    FROM ".$this->tableName." 
                    inner join ".$this->tableSeats." on fk_eventseat = seats_id 
                    inner join ".$this->tableType." on pfk_typeofseats = type_id
                    inner join ".$this->tableCalendar." on calendar_id=pfk_calendar
                    inner join ".$this->tableEvent." on fk_event = event_id 
                    inner join ".$this->tableCategory." on fk_category = category_id
                    inner join ".$this->tablePlace." on fk_eventplace = place_id
                    inner join ".$this->CalendarsXArtists." on calendar_id = pfk_ca_calendar
                    inner join ".$this->tableArtist." on artist_id = pfk_artist 
                    WHERE fk_eventseat = :fk_eventseat";
            
                $parameters["fk_eventseat"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                $id = 0;

                foreach ($resultSet as $row) 
                {
                    if($row["line_id"] != $id)
                    {             
                        $purchaseLine = new PurchaseLine();
                        $purchaseLine->setId($row["line_id"]);
                        $purchaseLine->setQuantity($row["line_quantity"]);
                        $purchaseLine->setPrice($row["line_price"]);
                        
                        $eventSeats = new EventSeats();
                        $eventSeats->setId($row["fk_eventseat"]);
                        $eventSeats->setQuantity($row["quantity"]);
                        $eventSeats->setPrice($row["price"]);
                        $eventSeats->setRemanents($row["remanents"]);

                        $typeofseat = new TypeOfSeat();
                        $typeofseat->setId($row["pfk_typeofseats"]);
                        $typeofseat->setName($row["type_name"]);

                        $eventSeats->setTypeOfSeat($typeofseat);

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

                        $eventSeats->setCalendar($Calendar);

                        $artistList = $Calendar->getArtistList();

                        $purchaseLine->setEventSeat($eventSeats);

                        $id = $row["line_id"];
                    }
                    if($row["pfk_artist"] != NULL){

                        $artist = new Artist();
                        $artist->setId($row["pfk_artist"]);
                        $artist->setName($row["artist_name"]);

                        array_push($artistList, $artist);
                        $Calendar->setArtistList($artistList);
                    }
                }      
                return $purchaseLine;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getLastPurchaseLineId($purchaseId){

            try
            {
                $query = "SELECT line_id FROM ".$this->tableName." 
                inner join ".$this->tablePurchase." on fk_purchase = purchase_id 
                WHERE purchase_id = :purchase_id
                ORDER BY line_id desc limit 1;";

                $parameters["purchase_id"] = $purchaseId;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
                    $id = $row["line_id"];
                   
                return $id;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }
        
    }
?>
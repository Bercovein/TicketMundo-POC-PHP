<?php
    namespace DAO;

    use DAO\Connection as Connection;
    use DAO\IDAOPurchase as IDAOPurchase;

    use Model\Purchase as Purchase;
    use Model\Client as Client;
    use Model\User as User;
    use Model\PurchaseLine as PurchaseLine;
    use Model\EventSeats as EventSeats;
    use Model\TypeOfSeat as TypeOfSeat;
    use Model\Calendar as Calendar;
    use Model\Event as Event;
    use Model\EventPlace as EventPlace;

    class PDOPurchase implements IDAOPurchase
    {
        private $connection; 
        private $tableName = "Purchases";
        private $tableClient = "Clients";
        private $tableUser = "Users";
        private $tableLine = "PurchaseLines";
        private $tableSeats = "EventSeats";
        private $tableType = "TypeOfSeats";
        private $tableCalendar = "Calendars";
        private $tableEvent = "Events";
        private $tablePlace = "EventPlaces";


        public function add(Purchase $purchase)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (total, purchase_date, fk_client) VALUES (:total, :purchase_date, :fk_client);";

                $parameters["total"] = $purchase->getTotal();
                $parameters["purchase_date"] = $purchase->getDate();
                $parameters["fk_client"] = $purchase->getClient()->getId();

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
                $purchaseList = array(); 

                $query = "SELECT purchase_id, purchase_date, total, fk_client, dni, firstName, lastName, fk_user,
                user_email, line_id, line_quantity, line_price, fk_eventseat, pfk_typeofseats, type_name, pfk_calendar, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, fk_event, title, fk_eventplace, place_name  
                FROM ".$this->tableName." 
                inner join ".$this->tableClient." on fk_client = client_id 
                inner join ".$this->tableUser." on fk_user = user_id 
                inner join ".$this->tableLine." on fk_purchase = purchase_id 
                inner join ".$this->tableSeats." on  seats_id = fk_eventseat 
                inner join ".$this->tableType." on pfk_typeofseats = type_id 
                inner join ".$this->tableCalendar." on calendar_id=pfk_calendar 
                inner join ".$this->tableEvent." on fk_event = event_id 
                inner join ".$this->tablePlace." on fk_eventplace = place_id 
                ORDER BY purchase_id"; 

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query); 
                
                $id = 0;

                foreach ($resultSet as $row)
    			{   
                    if($row["purchase_id"] != $id)
                    {           
                        $purchase = new Purchase();
                        $purchase->setId($row["purchase_id"]);
                        $purchase->setTotal($row["total"]);
                        $purchase->setDate($row["purchase_date"]);

                        $client = new Client();
                        $client->setId($row["fk_client"]);
                        $client->setDni($row["dni"]);
                        $client->setFirstName($row["firstName"]);
                        $client->setLastName($row["lastName"]);

                        $User = new User();
                        $User->setId($row["fk_user"]);
                        $User->setEmail($row["user_email"]);

                        $client->setUser($User);

                        $purchaseLineList=$purchase->getPurchaseLine();

                        $purchase->setClient($client);

                        array_push($purchaseList, $purchase);

                        $id = $row["purchase_id"];
                    }

                    if($row["line_id"] != NULL){
                                    
                        $purchaseLine = new PurchaseLine();
                        $purchaseLine->setId($row["line_id"]);
                        $purchaseLine->setQuantity($row["line_quantity"]);
                        $purchaseLine->setPrice($row["line_price"]);
                            
                        $eventSeats = new EventSeats();
                        $eventSeats->setId($row["fk_eventseat"]);

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

                        $EventPlace = new EventPlace();
                        $EventPlace->setId($row["fk_eventplace"]);
                        $EventPlace->setName($row["place_name"]);

                        $Calendar->setEventPlace($EventPlace);
                        $Calendar->setEvent($Event);

                        $eventSeats->setCalendar($Calendar);

                        $purchaseLine->setEventSeat($eventSeats);

                        array_push($purchaseLineList, $purchaseLine); 
                        $purchase->setPurchaseLine($purchaseLineList);
                    }
    			}
                return $purchaseList;
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
                $purchase = null;

                $query = "SELECT purchase_id, purchase_date, total, fk_client, dni, fk_user,
                user_email, line_id, line_quantity, line_price, fk_eventseat, pfk_typeofseats, type_name, pfk_calendar, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, fk_event, title, fk_eventplace, place_name  
                FROM ".$this->tableName." 
                inner join ".$this->tableClient." on fk_client = client_id 
                inner join ".$this->tableUser." on fk_user = user_id 
                inner join ".$this->tableLine." on fk_purchase = purchase_id 
                inner join ".$this->tableSeats." on  seats_id = fk_eventseat 
                inner join ".$this->tableType." on pfk_typeofseats = type_id 
                inner join ".$this->tableCalendar." on calendar_id=pfk_calendar 
                inner join ".$this->tableEvent." on fk_event = event_id 
                inner join ".$this->tablePlace." on fk_eventplace = place_id
                WHERE purchase_id = :purchase_id";

                $parameters["purchase_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                $id = 0;

                foreach ($resultSet as $row)
                {   
                    if($row["purchase_id"] != $id)
                    {           
                        $purchase = new Purchase();
                        $purchase->setId($row["purchase_id"]);
                        $purchase->setTotal($row["total"]);
                        $purchase->setDate($row["purchase_date"]);

                        $client = new Client();
                        $client->setId($row["fk_client"]);
                        $client->setDni($row["dni"]);

                        $User = new User();
                        $User->setId($row["fk_user"]);
                        $User->setEmail($row["user_email"]);

                        $client->setUser($User);

                        $purchaseLineList=$purchase->getPurchaseLine();

                        $purchase->setClient($client);

                        $id = $row["purchase_id"];
                    }

                    if($row["line_id"] != NULL){
                                    
                        $purchaseLine = new PurchaseLine();
                        $purchaseLine->setId($row["line_id"]);
                        $purchaseLine->setQuantity($row["line_quantity"]);
                        $purchaseLine->setPrice($row["line_price"]);
                            
                        $eventSeats = new EventSeats();
                        $eventSeats->setId($row["fk_eventseat"]);

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

                        $EventPlace = new EventPlace();
                        $EventPlace->setId($row["fk_eventplace"]);
                        $EventPlace->setName($row["place_name"]);

                        $Calendar->setEventPlace($EventPlace);
                        $Calendar->setEvent($Event);

                        $eventSeats->setCalendar($Calendar);

                        $purchaseLine->setEventSeat($eventSeats);

                        array_push($purchaseLineList, $purchaseLine); 
                        $purchase->setPurchaseLine($purchaseLineList);
                    }
                }
                return $purchase;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getLastPurchaseId($clientId){

            try
            {
                $query = "SELECT purchase_id FROM ".$this->tableName." 
                inner join ".$this->tableClient." on fk_client = client_id 
                WHERE fk_client = :fk_client
                ORDER BY purchase_id desc limit 1;";

                $parameters["fk_client"] = $clientId;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
                    $id = $row["purchase_id"];
                   
                return $id;
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
                $purchaseList = array();

                $query = "SELECT purchase_id, purchase_date, total, fk_client, firstName, lastName, dni, fk_user,
                user_email, line_id, line_quantity, line_price, fk_eventseat, pfk_typeofseats, type_name, pfk_calendar, DATE_FORMAT(day,'%Y-%m-%d %H:%i') as day, fk_event, title, fk_eventplace, place_name  
                FROM ".$this->tableName." 
                inner join ".$this->tableClient." on fk_client = client_id 
                inner join ".$this->tableUser." on fk_user = user_id 
                inner join ".$this->tableLine." on fk_purchase = purchase_id 
                inner join ".$this->tableSeats." on  seats_id = fk_eventseat 
                inner join ".$this->tableType." on pfk_typeofseats = type_id 
                inner join ".$this->tableCalendar." on calendar_id=pfk_calendar 
                inner join ".$this->tableEvent." on fk_event = event_id 
                inner join ".$this->tablePlace." on fk_eventplace = place_id
                WHERE fk_client = :fk_client
                ORDER BY purchase_id;";

                $parameters["fk_client"] = $clientId;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                $id = 0;

                foreach ($resultSet as $row)
                {   
                    if($row["purchase_id"] != $id)
                    {           
                        $purchase = new Purchase();
                        $purchase->setId($row["purchase_id"]);
                        $purchase->setTotal($row["total"]);
                        $purchase->setDate($row["purchase_date"]);

                        $client = new Client();
                        $client->setId($row["fk_client"]);
                        $client->setDni($row["dni"]);
                        $client->setFirstName($row["firstName"]);
                        $client->setLastName($row["lastName"]);

                        $User = new User();
                        $User->setId($row["fk_user"]);
                        $User->setEmail($row["user_email"]);

                        $client->setUser($User);

                        $purchaseLineList=$purchase->getPurchaseLine();

                        $purchase->setClient($client);

                        array_push($purchaseList, $purchase);

                        $id = $row["purchase_id"];
                    }

                    if($row["line_id"] != NULL){
                                    
                        $purchaseLine = new PurchaseLine();
                        $purchaseLine->setId($row["line_id"]);
                        $purchaseLine->setQuantity($row["line_quantity"]);
                        $purchaseLine->setPrice($row["line_price"]);
                            
                        $eventSeats = new EventSeats();
                        $eventSeats->setId($row["fk_eventseat"]);

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

                        $EventPlace = new EventPlace();
                        $EventPlace->setId($row["fk_eventplace"]);
                        $EventPlace->setName($row["place_name"]);

                        $Calendar->setEventPlace($EventPlace);
                        $Calendar->setEvent($Event);

                        $eventSeats->setCalendar($Calendar);

                        $purchaseLine->setEventSeat($eventSeats);

                        array_push($purchaseLineList, $purchaseLine); 
                        $purchase->setPurchaseLine($purchaseLineList);
                    }
                }
                return $purchaseList;
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
                $query = "DELETE FROM ".$this->tableName." WHERE purchase_id = :purchase_id";
                
                $parameters["purchase_id"] = $id;

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
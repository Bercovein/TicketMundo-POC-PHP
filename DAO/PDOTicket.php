<?php
    namespace DAO;

    use DAO\Connection as Connection;
    use DAO\IDAOTicket as IDAOTicket;

    use Model\Ticket as Ticket;
    use Model\PurchaseLine as PurchaseLine;
    use Model\EventSeats as EventSeats;
    use Model\Calendar as Calendar;
    use Model\Event as Event;
    use Model\EventPlace as EventPlace;
    use Model\Client as Client;
    use Model\TypeOfSeat as TypeOfSeat;
    use Model\User as User;



    class PDOTicket implements IDAOTicket
    {
        private $connection; 
        private $tableName = "Tickets";
        private $tablePurchaseLine = "PurchaseLines";
        private $tableSeats = "EventSeats";
        private $tableCalendar = "Calendars";
        private $tableEvent = "Events";
        private $tablePlace = "EventPlaces";
        private $tableClient = "Clients";
        private $tableTypeOfSeats = "TypeOfSeats";
        private $tableUser = "Users";

        public function add(Ticket $Ticket)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (ticket_number, fk_purchaseLine, fk_client) VALUES (:ticket_number, :fk_purchaseLine, :fk_client);";

                $parameters["ticket_number"] = $Ticket->getNumber();
                $parameters["fk_purchaseLine"] = $Ticket->getPurchaseLine()->getId();
                $parameters["fk_client"] = $Ticket->getClient()->getId();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
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
                $Ticket = null;

                $query = "SELECT ticket_id, ticket_number, fk_client, dni, firstName, lastName, fk_purchaseLine, line_quantity, fk_eventseat, pfk_typeofseats, type_name, pfk_calendar, DATE_FORMAT(day,'%d-%m-%Y %H:%i') as day, fk_event, title, fk_eventplace, place_name
                FROM ".$this->tableName." 
                INNER JOIN ".$this->tableClient." on fk_client = client_id
                INNER JOIN ".$this->tablePurchaseLine." on fk_purchaseLine = line_id
                INNER JOIN ".$this->tableSeats." on pfk_typeofseats = seats_id
                INNER JOIN ".$this->tableTypeOfSeats." on pfk_typeofseats = type_id
                INNER JOIN ".$this->tableCalendar." on calendar_id=pfk_calendar
                INNER JOIN ".$this->tableEvent." on fk_event = event_id 
                INNER JOIN ".$this->tablePlace." on fk_eventplace = place_id 
                WHERE ticket_id = :ticket_id
                ORDER BY client_id;";

                $parameters["ticket_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row)
    			{
                    $Ticket = new Ticket();
                    $Ticket->setId($row["ticket_id"]);
                    $Ticket->setNumber($row["ticket_number"]);

                    $purchaseLine = new PurchaseLine();
                    $purchaseLine->setId($row["fk_purchaseLine"]);
                    $purchaseLine->setQuantity($row["line_quantity"]);

                    $eventSeat = new EventSeats();
                    $eventSeat->setId($row["fk_eventseat"]);

                    $type = new TypeOfSeat();
                    $type->seatId($row["pfk_typeofseats"]);
                    $type->setName($row["type_name"]);

                    $calendar = new Calendar();
                    $calendar->setId($row["pfk_calendar"]);
                    $calendar->setDate($row["day"]);

                    $event = new Event();
                    $event->setId($row["fk_event"]);
                    $event->setName($row["title"]);

                    $place = new EventPlace();
                    $place->setId($row["fk_eventplace"]);
                    $place->setName($row["place_name"]);

                    $client = new Client();
                    $client->setId($row["fk_client"]);
                    $client->setDni($row["dni"]);
                    $client->setFirstName($row["firstName"]);
                    $client->setLastName($row["lastName"]);

                    $calendar->setEvent($event);
                    $calendar->setEventPlace($place);
                    $eventSeat->setTypeOfSeat($type);
                    $eventSeat->setCalendar($calendar);
                    $purchaseLine->setEventSeat($eventSeat);
                    $Ticket->setPurchaseLine($purchaseLine);
                    $Ticket->setClient($client);

                }            
                return $Ticket;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByLineId($purchaseLineId)
        {
            try
            {
                $TicketList = array();

                $query = "SELECT ticket_id, ticket_number, fk_client, dni, firstName, lastName, fk_user, user_email, fk_purchaseLine, line_quantity, fk_eventseat, pfk_typeofseats, type_name, pfk_calendar, DATE_FORMAT(day,'%d-%m-%Y %H:%i') as day, fk_event, title, fk_eventplace, place_name
                FROM ".$this->tableName." 
                INNER JOIN ".$this->tableClient." on fk_client = client_id
                INNER JOIN ".$this->tableUser." on fk_user = user_id
                INNER JOIN ".$this->tablePurchaseLine." on fk_purchaseLine = line_id
                INNER JOIN ".$this->tableSeats." on fk_eventseat = seats_id
                INNER JOIN ".$this->tableTypeOfSeats." on pfk_typeofseats = type_id
                INNER JOIN ".$this->tableCalendar." on  pfk_calendar= calendar_id
                INNER JOIN ".$this->tableEvent." on fk_event = event_id 
                INNER JOIN ".$this->tablePlace." on fk_eventplace = place_id 
                WHERE fk_purchaseLine = :fk_purchaseLine";

                $parameters["fk_purchaseLine"] = $purchaseLineId;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);

                foreach ($resultSet as $row)
                {
                    $Ticket = new Ticket();
                    $Ticket->setId($row["ticket_id"]);
                    $Ticket->setNumber($row["ticket_number"]);

                    $purchaseLine = new PurchaseLine();
                    $purchaseLine->setId($row["fk_purchaseLine"]);
                    $purchaseLine->setQuantity($row["line_quantity"]);

                    $eventSeat = new EventSeats();
                    $eventSeat->setId($row["fk_eventseat"]);

                    $type = new TypeOfSeat();
                    $type->setId($row["pfk_typeofseats"]);
                    $type->setName($row["type_name"]);

                    $calendar = new Calendar();
                    $calendar->setId($row["pfk_calendar"]);
                    $calendar->setDate($row["day"]);

                    $event = new Event();
                    $event->setId($row["fk_event"]);
                    $event->setName($row["title"]);

                    $place = new EventPlace();
                    $place->setId($row["fk_eventplace"]);
                    $place->setName($row["place_name"]);

                    $client = new Client();
                    $client->setId($row["fk_client"]);
                    $client->setDni($row["dni"]);
                    $client->setFirstName($row["firstName"]);
                    $client->setLastName($row["lastName"]);

                    $user = new User();
                    $user->setId($row["fk_user"]);
                    $user->setEmail($row["user_email"]);

                    $client->setUser($user);

                    $calendar->setEvent($event);
                    $calendar->setEventPlace($place);
                    $eventSeat->setTypeOfSeat($type);
                    $eventSeat->setCalendar($calendar);
                    $purchaseLine->setEventSeat($eventSeat);
                    $Ticket->setPurchaseLine($purchaseLine);
                    $Ticket->setClient($client);

                    array_push($TicketList, $Ticket);
                }            
                return $TicketList;
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
                $query = "DELETE * FROM ".$this->tableName." WHERE ticket_id = :ticket_id;";
                
                $parameters["ticket_id"] = $id;

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
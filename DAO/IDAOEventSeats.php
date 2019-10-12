<?php 
namespace DAO;

use Model\EventSeats as EventSeats;

interface IDAOEventSeats{

	function add(EventSeats $object);
	function getAll();
	function getById($id);
	function delete($id);
	function update(EventSeats $newData);
	function getByCalendarId($idCalendar);
	function getCalendarsFromEventSeats();
	function getFestivalCalendarsFromEventSeats();
	function getEventsFromCalendarsFromEventSeats();
	function getByEvent($title);
	function getEventSeatsByEventByTypeOfSeat($title,$type);
	function editRemanents($eventSeatId, $newRemanents);
	function getByType($type_id);

}

?>
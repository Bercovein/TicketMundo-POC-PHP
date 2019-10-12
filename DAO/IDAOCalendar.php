<?php 
namespace DAO;

use Model\Calendar as Calendar;
use Model\Event as Event;

interface IDAOCalendar{

	function add(Calendar $object);
	function getAll();
	function getAllWithoutFestival();
	function getCalendarFestival();
	function getById($id);
	function delete($id);
	function update(Calendar $newData);
	function addArtists(Calendar $Calendar);
	function getLastId();
	function getByPlace($id);
	function getByEvent($eventName);
	function deleteArtistFromCalendar($idCalendar,$idArtist);
	function getByArtist($name);
	function updateAll();
}

?>
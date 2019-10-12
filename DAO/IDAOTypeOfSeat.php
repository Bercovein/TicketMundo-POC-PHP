<?php 
namespace DAO;

use Model\TypeOfSeat as TypeOfSeat;

interface IDAOTypeOfSeat{

	function add(TypeOfSeat $object);
	function getAll();
	function getAllActives();
	function getById($id);
	function getByName($name);
	function delete($id);
	function update(TypeOfSeat $newData);
}

?>
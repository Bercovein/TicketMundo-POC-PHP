<?php 
namespace DAO;

use Model\EventPlace as EventPlace;

interface IDAOEventPlace{

	function add(EventPlace $object);
	function getAll();
	function getById($id);
	function getByName($name);
	function delete($id);
	function update(EventPlace $newData);
	function getAllActives();
}

?>
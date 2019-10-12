<?php 
namespace DAO;

use Model\Card as Card;

interface IDAOCard{

	function add(Card $object);
	function getAll();
	function getById($id);
	function delete($id);
	function update(Card $newData);
	function getByClient($clientId);
	function getByClientDni($dni);

}

?>
<?php 
namespace DAO;

use Model\Client as Client;
use Model\Card as Card;

interface IDAOClient{

	function add(Client $object);
	function getAll();
	function getById($id);
	function deleteClient($dni);
	function update(Client $newData);
	function getByDni($dni);
	function getByUser($idUser);
	function addCardByDni($dni, Card $card);
	function deleteClientCards($dni);

}

?>
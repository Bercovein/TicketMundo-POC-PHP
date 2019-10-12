<?php 
namespace DAO;

use Model\Ticket as Ticket;

interface IDAOTicket{

	function add(Ticket $object);
	function getById($id);
	function delete($id);
	function getByLineId($purchaseLineId);

}

?>
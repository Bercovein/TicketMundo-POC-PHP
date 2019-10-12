<?php 
namespace DAO;

use Model\Purchase as Purchase;

interface IDAOPurchase{

	function add(Purchase $object);
	function getAll();
	function getById($id);
	function delete($id);
    function getLastPurchaseId($clientId);
    function getByClient($clientId);


}

?>
<?php 
namespace DAO;

use Model\PurchaseLine as PurchaseLine;

interface IDAOPurchaseLine{

	function add(PurchaseLine $object, $purchaseId);
	function getAll();
	function getById($id);
	function getLastPurchaseLineId($purchaseId);
	function getByEventSeats($id);

}

?>
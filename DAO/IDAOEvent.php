<?php 
namespace DAO;

use Model\Event as Event;

interface IDAOEvent{

	function add(Event $object);
	function getAll();
	function getAllActives();
	function getAllActivesWithoutFestivals();
	function getFestivalsActives();
	function getById($id);
	function getByName($name);
	function delete($id);
	function update(Event $newData);
	function getByCategoryId($categoryId);
	function getByBanner($banner);
	function updateAll();
	function deleteBanner($id);
}

?>
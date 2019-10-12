<?php 
namespace DAO;

use Model\Artist as Artist;

interface IDAOArtist{

	function add(Artist $object);
	function getAll();
	function getById($id);
	function getByName($name);
	function delete($id);
	function update(Artist $newData);
	function getAllActives();
}

?>
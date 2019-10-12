<?php 
namespace DAO;

use Model\Category as Category;

interface IDAOCategory{

	function add(Category $object);
	function getAll();
	function getById($id);
	function getByName($name);
	function delete($id);
	function update(Category $newData);
	function getAllActives();
}

?>
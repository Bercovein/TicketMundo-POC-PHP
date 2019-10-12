<?php 
namespace DAO;

use Model\User as User;

interface IDAOUser{

	function add(User $object);
	function getAll();
	function getById($id);
	function delete($id);
	function getByEmail($email);

}

?>
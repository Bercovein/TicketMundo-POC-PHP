<?php 
	namespace Model;

	use Model\User as User;

	class Client
	{
		private $id;
		private $firstName;
		private $lastName;
		private $dni;
		private $user;

		public function getUser(){
			return $this->user;
		}

		public function setUser(User $user){
			$this->user=$user;
		}

		public function getFirstName(){
			return $this->firstName;
		}

		public function setFirstName($firstName){
			$this->firstName=$firstName;
		}

		public function getLastName(){
			return $this->lastName;
		}

		public function setLastName($lastName){
			$this->lastName=$lastName;
		}

		public function getDni(){
			return $this->dni;
		}

		public function setDni($dni){
			$this->dni=$dni;
		}

		public function getId(){
	        return $this->id;
	    }

	    public function setId($id){
	        $this->id = $id;
	    }
	    
	}

?>
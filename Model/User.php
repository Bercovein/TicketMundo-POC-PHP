<?php
	namespace Model;

	class User
	{
		private $id;
		private $email;
		private $password;
		private $rol;

		public function getEmail(){
			return $this->email;
		}
		
		public function setEmail($Email){
			 $this->email=$Email;
		}

		public function getPassword(){
			return $this->password;
		}
		public function setPassword($password){
			 $this->password=$password;
		}

		public function getRol(){
			return $this->rol;
		}
		public function setRol($rol){
			 $this->rol=$rol;
		}

		public function getId(){
			return $this->id;
		}
		public function setId($id){
			 $this->id=$id;
		}

	}

?>
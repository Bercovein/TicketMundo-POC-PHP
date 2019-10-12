<?php 
	namespace Model;

	use Model\Client as Client;

	class Card
	{
		private $id;
		private $number;
		private $securityCode;
		private $expirationDate;
		private $client;

		public function getNumber(){
			return $this->number;
		}

		public function setNumber($number){
			$this->number=$number;
		}

		public function getClient(){
			return $this->client;
		}

		public function setClient(Client $client){
			$this->client=$client;
		}

		public function getSecurityCode(){
			return $this->securityCode;
		}

		public function setSecurityCode($securityCode){
			$this->securityCode=$securityCode;
		}

		public function getExpirationDate(){
			return $this->expirationDate;
		}

		public function setExpirationDate($expirationDate){
			$this->expirationDate=$expirationDate;
		}

		public function getId(){
	        return $this->id;
	    }

	    public function setId($id){
	        $this->id = $id;
	    }

	}

?>
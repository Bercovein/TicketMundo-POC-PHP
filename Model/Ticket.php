<?php 
	namespace Model;
	
	use Model\PurchaseLine as PurchaseLine;
	use Model\Client as Client;


	class Ticket
	{
		private $id;
		private $number;
		private $purchaseLine;
		private $client;

		public function getId(){
			return $this->id;
		}

		public function setId($id){
			$this->id=$id;
		}

		public function getClient(){
			return $this->client;
		}

		public function setClient(Client $client){
			$this->client=$client;
		}

		public function getPurchaseLine(){
			return $this->purchaseLine;
		}

		public function setPurchaseLine(PurchaseLine $purchaseLine){
			$this->purchaseLine=$purchaseLine;
		}

		public function getNumber(){
			return $this->number;
		}

		public function setNumber($number){
			$this->number=$number;
		}	
	}

?>
<?php 
	namespace Model;

	use Model\PurchaseLine as PurchaseLine;
	use Model\Client as Client;

	class Purchase
	{
		private $id;
		private $purchaseLine = array(); 
		private $total = 0;
		private $date;
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

		public function setPurchaseLine($purchaseLine){
			$this->purchaseLine=$purchaseLine;
		}

		public function getTotal(){
			return $this->total;
		}

		public function setTotal($total){
			$this->total=$total;
		}

		public function getDate(){
			return $this->date;
		}

		public function setDate($date){
			$this->date=$date;
		}

	}

?>
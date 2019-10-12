<?php 
	namespace Model;

	use Model\EventSeats as EventSeats;

	class PurchaseLine
	{
		private $id;
		private $quantity;
		private $price;
		private $eventSeat;

		public function getQuantity(){
			return $this->quantity;
		}

		public function setQuantity($quantity){
			$this->quantity=$quantity;
		}

		public function getPrice(){
			return $this->price;
		}

		public function setPrice($price){
			$this->price=$price;
		}

		public function getEventSeat(){
			return $this->eventSeat;
		}

		public function setEventSeat(EventSeats $eventSeat){
			$this->eventSeat=$eventSeat;
		}

		public function getId(){
	        return $this->id;
	    }

	    public function setId($id){
	        $this->id = $id;
	    }

	    public function getTotal(){
    		return ($this->price*$this->quantity);
    	}

	}

?>
<?php 
	namespace Model;

	use Model\TypeOfSeat as TypeOfSeat;
	use Model\Calendar as Calendar;

	class EventSeats
	{
		private $id;
		private $quantity;
		private $price;
		private $remanents;
		private $typeOfSeat;
		private $calendar;

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

		public function getRemanents(){
			return $this->remanents;
		}
		public function setRemanents($remanents){
			$this->remanents=$remanents;
		}

		public function setTypeOfSeat(TypeOfSeat $typeOfSeat){
			$this->typeOfSeat=$typeOfSeat;
		}
		public function getTypeOfSeat(){
			return $this->typeOfSeat;
		}

		public function setCalendar(Calendar $calendar){
			$this->calendar=$calendar;
		}
		public function getCalendar(){
			return $this->calendar;
		}

		public function getId(){
			return $this->id;
		}
		public function setId($id){
			$this->id=$id;
		}
		public function getSells(){
			return ($this->quantity - $this->remanents)*$this->price;
		}
	}

?>
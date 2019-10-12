<?php 
	namespace Model;

	use Model\EventPlace as EventPlace;
	use Model\Event as Event;
	use Model\Artist as Artist;

	class Calendar
	{
		private $id;
		private $date;
		private $event;
		private $eventPlace;
		private $artistList = array();
		private $state;

        public function getState(){
            return $this->state;
        }

        public function setState($state){
            $this->state = $state;
        }
		public function getId(){
			return $this->id;
		}

		public function setId($id){
			$this->id=$id;
		}

		public function getDate(){
			return $this->date;
		}
		public function setDate($date){
			$this->date=$date;
		}

		public function getEvent(){
			return $this->event;
		}
		public function setEvent(Event $event){
			$this->event=$event;
		}

		public function getEventPlace(){
			return $this->eventPlace;
		}
		public function setEventPlace(EventPlace $eventPlace){
			$this->eventPlace=$eventPlace;
		}

		public function getArtistList(){
			return $this->artistList;		
		}

		public function setArtistList($artistList){
			$this->artistList=$artistList;
		}

	}

?>
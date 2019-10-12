<?php 
    namespace Model;

    class TypeOfSeat
    {
    	private $id;
        private $name;
        private $state;

        public function getState(){
            return $this->state;
        }

        public function setState($state){
            $this->state = $state;
        }
        
        public function getName(){
            return $this->name;
        }

        public function setName($name){
            $this->name = $name;
        }

        public function getId(){
            return $this->id;
        }

        public function setId($id){
            $this->id = $id;
        }

    }

?>
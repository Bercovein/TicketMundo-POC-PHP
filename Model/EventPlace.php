<?php 
    namespace Model;

    class EventPlace 
    {
        private $id;
        private $name;
        private $capacity;
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
            $this->name=$name;
        }

        public function getCapacity(){
            return $this->capacity;
        }

        public function setCapacity($capacity){
            $this->capacity=$capacity;
        }

        public function getId(){
            return $this->id;
        }

        public function setId($id){
            $this->id = $id;
        }

    }

?>
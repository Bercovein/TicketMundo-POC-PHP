<?php 
    namespace Model;

    use Model\Category as Category;

    class Event 
    {
        private $id;
        private $name;
        private $category;
        private $banner;
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

        public function getName(){
            return $this->name;
        }

        public function setName($name){
            $this->name=$name;
        }

        public function getCategory(){
            return $this->category;
        }

        public function setCategory(Category $category){
            $this->category=$category;
        }

        public function getBanner(){
            return $this->banner;
        }
        public function setBanner($banner){
            $this->banner=$banner;
        }

    }

?>
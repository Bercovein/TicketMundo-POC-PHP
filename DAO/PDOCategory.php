<?php
    namespace DAO;

    use Model\Category as Category;
    use DAO\Connection as Connection;
    use DAO\IDAOCategory as IDAOCategory;
    
    class PDOCategory implements IDAOCategory
    {
        private $connection;
        private $tableName = "Categories"; 

        public function add(Category $category)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (category_name) VALUES (:category_name);";
                
                $parameters["category_name"] = $category->getName();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getAll() 
        {
            try
            {
                $categoryList = array(); 

                $query = "SELECT category_id, category_name, category_state FROM ".$this->tableName." ORDER BY category_name"; 

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)
    			{                
                    $category = new Category();
                    $category->setId($row["category_id"]);
                    $category->setName($row["category_name"]);
                    $category->setState($row["category_state"]);

    				array_push($categoryList, $category);
    			}
                return $categoryList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getAllActives() 
        {
            try
            {
                $categoryList = array(); 

                $query = "SELECT category_id, category_name, category_state FROM ".$this->tableName." where category_state = 0  ORDER BY category_name"; 

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)
                {                
                    $category = new Category();
                    $category->setId($row["category_id"]);
                    $category->setName($row["category_name"]);
                    $category->setState($row["category_state"]);

                    array_push($categoryList, $category);
                }
                return $categoryList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }


        public function getById($id)
        {
            try
            {
                $category = null;

                $query = "SELECT category_id, category_name, category_state FROM ".$this->tableName." WHERE category_id = :category_id";

                $parameters["category_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row) 
    			{
                    $category = new Category();
                    $category->setId($row["category_id"]);
                    $category->setName($row["category_name"]);
                    $category->setState($row["category_state"]);
                }               
                return $category;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByName($name)
        {
            try
            {
                $category = null;

                $query = "SELECT category_id, category_name, category_state FROM ".$this->tableName." WHERE category_name = :category_name"; 

                $parameters["category_name"] = $name;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row) 
                {
                    $category = new Category();
                    $category->setId($row["category_id"]);
                    $category->setName($row["category_name"]);
                    $category->setState($row["category_state"]);
                }        
                return $category;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function delete($id)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." SET category_state = if(category_state=0, 1,0) where category_id = :category_id";
                
                $parameters["category_id"] = $id;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function update(Category $category)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." 
                    SET category_name = :category_name  
                    WHERE category_id = :category_id;";

                $parameters["category_name"] = $category->getName();
                $parameters["category_id"] = $category->getId();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

    }
?>
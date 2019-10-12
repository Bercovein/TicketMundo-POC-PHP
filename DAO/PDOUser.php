<?php
    namespace DAO;

    use Model\User as User;
    use DAO\Connection as Connection;
    use DAO\IDAOUser as IDAOUser;
    class PDOUser implements IDAOUser
    {
        private $connection; 
        private $tableName = "Users"; 

        public function add(User $User)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (user_email, password) VALUES (:user_email, :password);";

                $parameters["user_email"] = $User->getEmail();
                $parameters["password"] = $User->getPassword();

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
                $UserList = array(); 

                $query = "SELECT user_id, user_email, password, rol FROM ".$this->tableName." ORDER BY user_id;"; 

                $this->connection = Connection::GetInstance(); 

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)
    			{                
                    $User = new User();
                    $User->setId($row["user_id"]);
                    $User->setEmail($row["user_email"]);
                    $User->setpassword($row["password"]);
                    $User->setRol($row["rol"]);

    				array_push($UserList, $User);
    			}
                return $UserList;
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
                $User = null;

                $query = "SELECT user_id, user_email, password, rol  FROM ".$this->tableName." WHERE user_id = :user_id;";
                $parameters["user_id"] = $id;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row) 
    			{
                    $User = new User();
                    $User->setId($row["user_id"]);
                    $User->setEmail($row["user_email"]);
                    $User->setpassword($row["password"]);
                    $User->setRol($row["rol"]);
                }         
                return $User;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getByEmail($email)
        {
            try
            {
                $User = null;

                $query = "SELECT user_id, user_email, password, rol FROM ".$this->tableName." WHERE user_email = :user_email;";

                $parameters["user_email"] = $email;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);
                
                foreach ($resultSet as $row){

                   	$User = new User();
                    $User->setId($row["user_id"]);
                    $User->setEmail($row["user_email"]);
                    $User->setpassword($row["password"]);
                    $User->setRol($row["rol"]);
                }
                return $User;
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
                $query = "DELETE FROM ".$this->tableName." WHERE user_id = :user_id;";
                
                $parameters["user_id"] = $id;

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
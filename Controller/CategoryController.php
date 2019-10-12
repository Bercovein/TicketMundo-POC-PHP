<?php 
    namespace Controller;

    use \Exception as Exception;
    use Model\Category as Category;

    use DAO\PDOCategory as PDOCategory;
    use DAO\PDOEvent as PDOEvent;

    class CategoryController
    {
        private $DAOCategory;
        private $DAOEvent;

        public function __construct ()
        {  
            $this->DAOCategory = new PDOCategory();
            $this->DAOEvent = new PDOEvent(); 
        }

        public function showAddView($message = '',$mType = '')
        {   
           try
            {
                $listCategory = $this->DAOCategory->getAll();

                if(!empty($message))
                    echo '<script>swal("","' . $message . '","' . $mType . '");</script>';   

                include_once(VIEWS_PATH.'CategoryManagement.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de gestión de Categorias.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showListView()
        {    
            try
            { 
                $listCategory = $this->DAOCategory->getAll();

                include_once(VIEWS_PATH.'CategoryList.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de listas de Categorias.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function showUpdateView($id)
        {   
            try
            {
                $category = $this->DAOCategory->getById($id);

                include_once(VIEWS_PATH.'CategoryUpdate.php');
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar mostrar la Pagina de Modificación de Categorias.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function newCategory($name)
        {
            try
            {
                $name=ucwords($name);
                $id = $this->getNextId();

                if($this->DAOCategory->getById($id) == NULL && $this->DAOCategory->getByName($name) == NULL){

                    $category = new Category();
                    $category->setId($id);
                    $category->setName($name);

                    $this->DAOCategory->add($category);
                    $message='Categoria Agregada con exito!';
                    $mType = 'success';
                }else{
                    $message='La categoria ya existe.';
                    $mType = 'warning';
                }
                $this->showAddView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar agregar la Categoria.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            } 
        }

        public function deleteCategory($id)
        {
            try
            {   
                if($this->DAOEvent->getByCategoryId($id) != NULL)
                {
                    $message = 'No es posible modificar el estado de esta categoria ya que esta relacionada con al menos un evento.';
                    $mType = 'warning';
                }
                else
                {   
                    $this->DAOCategory->delete($id);
                    $message = "Estado de la categoria modificado con exito!";
                    $mType ='success';
                }
                $this->showAddView($message,$mType);
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar modificar el estado de la Categoria.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function updateCategory($id, $name)
        {
            try
            {
                $name = ucwords($name);

                if($this->DAOCategory->getById($id) != NULL && $this->DAOCategory->getByName($name) == NULL){

                    $category = new Category();
                    $category->setName($name);
                    $category->setId($id);

                    $this->DAOCategory->update($category);
                    $message = "Categoría actualizada con exito!";
                    $mType = 'success';
                }else{
                    $message = "La Categoría ya existe.";
                    $mType = 'warning';
                }

                $this->showAddView($message,$mType); 
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema al intentar actualizar la Categoría.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }

        public function getNextId()
        {
            try
            {
                $categories=$this->DAOCategory->getAll();
                $id = 0;

                foreach($categories as $category){
                    if($id<$category->getId())
                        $id=$category->getId();
                }
                return $id+1;
            }
            catch(Exception $ex)
            {
                $message = 'Oops ! \n\n Hubo un problema de tipo Exception.\n Consulte a su Administrador o vuelva a intentarlo.';
                echo '<script>swal("","' . $message . '","error");</script>';
                require_once(VIEWS_PATH."Main.php");
            }
        }
        
    }

?>

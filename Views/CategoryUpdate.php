<?php 
namespace Views;

include('nav.php');
?>

<!DOCTYPE html>
<html>
	<head>
		
	</head>
	<body>
		<div align="center" border="2" style="margin-top: 2%">
			
			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>CategoryController/updateCategory">
				
				<input hidden type="text" name="categoryId" value="<?php echo $category->getId(); ?>" class="form-control form-control-lg" required>

				<label for="categoryName"><h2>Modificar Categoria</h2></label> 
				<br><br>
				<input type="text" name="categoryName" value="<?php echo $category->getName(); ?>" class="form-control form-control-lg" required>
				<br>

				<button class="btn btn-dark btn-block btn-lg" type="submit" name="buttonSubmitCategoryUpdate"> Actualizar </button>
				
				<br>
        		<div style="width: 100px; float: left;">
            		<a href="<?php echo FRONT_ROOT ?>CategoryController/showAddView">
                	<input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
            	</a>
        		</div>
        		<br>
			</form>
		</div>
		<br>

	</body>
</html>
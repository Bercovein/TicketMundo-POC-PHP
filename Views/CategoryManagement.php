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
			
			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>CategoryController/newCategory">

				<label for="categoryName"><h2>Agregar Categoria</h2></label> 
				<br><br>
				<input type="text" name="categoryName" placeholder="Ej: Obra Teatral" class="form-control form-control-lg" required>
				<br>

				<button class="btn btn-dark btn-block btn-lg" type="submit" name="buttonSubmitCategory"> Cargar </button>

			</form>
		</div>
		<br>

	<?php
		$this->showListView();
	?>

	</body>
</html>
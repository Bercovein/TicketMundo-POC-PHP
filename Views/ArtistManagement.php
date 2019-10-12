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
			
			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>ArtistController/newArtist">

				<label for="artistName"><h2>Agregar Artista</h2></label> 
				<br><br>
				<input type="text" name="artistName" class="form-control form-control-lg" placeholder="Ej: Luis Miguel" required>
				<br>

				<button class="btn btn-dark btn-block btn-lg" type="submit" name="buttonSubmitArtist"> Cargar </button>

			</form>
		</div>
		<br>
		
	<?php
		$this->showListView();
	?>

	</body>
</html>
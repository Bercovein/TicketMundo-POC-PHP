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
			
			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>ArtistController/updateArtist">
	
				<input hidden type="number" name="idArtist" class="form-control form-control-lg" value="<?php echo $artist->getId(); ?>" required>

				<label for="artistName"><h2>Modificar Artista</h2></label> 
				<br><br>
				<input type="text" name="artistName" class="form-control form-control-lg" value="<?php echo $artist->getName(); ?>" required>
				<br>

				<button class="btn btn-dark btn-block btn-lg" type="submit" name="buttonSubmitArtistUpdate"> Actualizar </button>
				
				<br>
        		<div style="width: 100px; float: left;">
            		<a href="<?php echo FRONT_ROOT ?>ArtistController/showAddView">
                	<input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
            	</a>
        		</div>
        		<br>
			</form>
		</div>
	

	</body>
</html>
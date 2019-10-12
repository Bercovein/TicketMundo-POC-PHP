<?php 
namespace Views;

include('nav.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<script type='text/javascript'>
			function preview_image(event) {
		 		var reader = new FileReader();
		 		reader.onload = function(){

		  			var output = document.getElementById('output_image');
		  			output.src = reader.result;
		 		}
		 		reader.readAsDataURL(event.target.files[0]);
			}
		</script>

	</head>
	<body>
		<div align="center" border="2" style="margin-top: 2%">
			
			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>EventController/newEvent" style="width: 700px;" enctype="multipart/form-data">

				<h2>Agregar Evento</h2>
				<br>

				<div style="width: 300px; float: left;">

					<label for="eventName"><h4>Nombre</h4></label> 
						<br>
						<input type="text" name="eventName" class="form-control form-control-lg" style="width: 300px" placeholder="Ej: Lollapalooza 20XX." required>
						<br><br>
				</div>

				<div style="width: 200px; float: right;">

						<label for="category"><h4>Categoria</h4></label> 

						<select class="form-control form-control-lg" style="width: 200px" name="category">
							<?php 
							foreach($listCategory as $category) { ?>
								<option value="<?php echo $category->getId() ?>">
									<?php echo $category->getName() ?>
								</option>
							<?php } ?>
						</select>
						<br><br>	
				</div>

				<div style="width: 350px;height: 100px; float: left;">

					<label for="banner" style="margin-left: -265px;"><h4>Banner</h4></label> <br>
					<input type="file" name="banner" style="float: left; width: 200px;" value="" accept="image/x-png,image/gif,image/jpeg" onchange="preview_image(event)">
					<br><br>

				</div>

				<div style="width: 206px; height: 80px; float: right;margin-left: -5px; border-style: dashed; border-width: 3px;">

					<img style="width: 200px; height: 74px; "  id="output_image" alt="(Preview)">
					<br><br>

				</div>
				
				<button class="btn btn-dark btn-block btn-lg" style="width: 300px" type="submit" name="button"> Cargar </button>
				
			</form>

		</div>
		<br><br>
		
		<?php
		$this->showListView();
		?>

	</body>
</html>
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
			
			<form class="login-form bg-dark-alpha p-5 text-white" method="POST" action="<?=FRONT_ROOT?>EventController/updateEvent" style="width: 700px;" enctype="multipart/form-data">

				<h2>Modificar Evento</h2>
				<br>
				
				<input hidden type="number" name="eventId" value="<?php echo $event->getId(); ?>" required>

				<div style="width: 300px; float: left;">

					<label for="eventName"><h4>Nombre</h4></label> 
					<br>
					<input type="text" name="eventName" class="form-control form-control-lg" style="width: 300px" value="<?php echo $event->getName(); ?>" required>
					<br>
				</div>
				
				<div style="width: 250px; float: right;">

						<label for="category"><h4>Categoria</h4></label> 

						<select class="form-control form-control-lg" style="width: 250px" name="category">
							<?php 
							foreach($listCategory as $category) { ?>
								<option value="<?php echo $category->getId() ?>"
									<?php if($category->getId() == $event->getCategory()->getId()) echo 'selected' ?> >
									<?php echo $category->getName() ?>
								</option>
							<?php } ?>
						</select>
						<br>	
				</div>

				<div style="width: 400px; float: center;">

					<label for="banner"><h4>Banner</h4></label> 
					<br>

			<?php   $source = '';
					if($event->getBanner()!=NULL){ 

						$source = FRONT_ROOT.BANNER_PATH.$event->getBanner();
					}?> 
					
					<img style="width: 400px; height: 148px; margin: 5px;" src="<?php echo $source;?>" id="output_image" alt="(Sin Banner)">
					<br> 

					<label style="float: left;"><h5>Cambiar</h5></label>
					<br><br> 
 					<input type="file" style="float: left;" name="banner" accept="image/x-png,image/gif,image/jpeg" onchange="preview_image(event)">

					<br><br>	

				</div>

				<div style="width: 100px; float: left;">
            		<a href="<?php echo FRONT_ROOT ?>EventController/showAddView">
                	<input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px" value="Regresar">
            	</a>
        		</div>

				<button class="btn btn-dark btn-block btn-lg" style="width: 300px;" type="submit" name="button"> Actualizar </button>

			</form>

		</div>

	</body>
</html>
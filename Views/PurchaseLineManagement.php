<?php 
namespace Views;

include('nav.php');

?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1"></meta>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></meta>
		<script src="<?php echo JS_PATH?>jquery.min.js"></script>
		<script src="<?php echo JS_PATH?>jquery-ui.js"></script>
	
	<script type="text/javascript">
	
		  $(document).ready(function(){
		    $("#lineCalendar").change(function(){
		    $.ajax({
		      url: '<?=FRONT_ROOT?>PurchaseLineController/chargeSelect',
		      type: "POST",
		      data:"idCalendar="+$("#lineCalendar").val(),
		      success: function(opciones){
		        $("#lineIdEventSeats").html(opciones);
		        $("#totalPrice").val(0);
		        $("#lineQuantity").val(1);
		        $("#linePriceEventSeats").html('<option value="0">Sin Precio</option>');
		      }
		    })
		  });
		});
	</script>

	<script type="text/javascript">
	
		  $(document).ready(function(){
		    $("#lineIdEventSeats").change(function(){
		    $.ajax({
		      url: '<?=FRONT_ROOT?>PurchaseLineController/chargePrice',
		      type: "POST",
		      data:"idEventSeats="+$("#lineIdEventSeats").val(),
		      success: function(opciones){
		        $("#linePriceEventSeats").html(opciones);
		        $("#lineQuantity").val(1);
		        $("#totalPrice").val($("#linePriceEventSeats").val());
		      }
		    })
		  });
		});
	</script>
	
	<script type="text/javascript">
	
		  $(document).ready(function(){
		    $("#lineIdEventSeats").change(function(){
		    $.ajax({
		      url: '<?=FRONT_ROOT?>PurchaseLineController/chargeMaxQuantity',
		      type: "POST",
		      data:"idEventSeats="+$("#lineIdEventSeats").val(),
		      success: function(opciones){
		      	$("#EventSeatsQuantity").html(opciones);
	   			$("#lineQuantity").attr({
	      		"max" : $("#EventSeatsQuantity").val()
				});

		      }
		    })
		  });
		});
	</script>

	<script type="text/javascript">
	
		$(document).ready(function(){
			$("#lineQuantity").change(function(){ 

				if ($(this).val() >= $(this).attr('max')*1) { 
					$(this).val($(this).attr('max'));
					alert('se alcanzo la cantidad maxima disponible del asiento seleccionado.');
				}	
			});
		});
	</script>

	<script type="text/javascript">
		
		function ChargeTotal(){

			document.getElementById("totalPrice").innerHTML="";

			var price = document.getElementById("linePriceEventSeats").value;
			var quantity = document.getElementById("lineQuantity").value;

			document.getElementById("totalPrice").value = price*quantity;
		}
			
	</script>

	<body>
		<div align="center" border="3" class="table" style="margin-top: 2%">
			
			<form class="login-form bg-dark-alpha p-5 text-white" style="width: 600px" method="POST" action="<?=FRONT_ROOT?>CartController/newCartLine">

				<h2><?php echo $title ?></h2>
				<br>

				<label for="lineCalendar" style="float: left">Calendario: </label>
				
				<a class="text-white" href="<?php echo FRONT_ROOT ?>CartController/showAddView"><u style="margin-top: 4px;margin-left: 10px;float: right; color: black; text-shadow: 5px 5px 5px #A4A4A4;">Mostrar Todos</u></a>
				
				<a class="text-white" href="<?php echo FRONT_ROOT ?>CartController/showAddFestivalView"><u style="margin-top: 4px;float: right; color: black; text-shadow: 5px 5px 5px #A4A4A4;">Comprar Festival</u></a>


			    <select class="form-control form-control-lg" name="lineCalendar" id="lineCalendar"> 
			    	<option value="0"> Elige un calendario</option>
			        <?php 
					foreach($listCalendar as $calendar) { ?>
						<option value="<?php echo $calendar->getId(); ?>">
							<?php echo $calendar->getDate()." - ".$calendar->getEvent()->getName()." - ".$calendar->getEventPlace()->getName(); ?>
						</option>
					<?php } ?>
			    </select>
			    <br>			
				
				<div style="width: 250px; float: left;">
					<label for="lineIdEventSeats" style="float: left">Plaza Evento/s: </label>
					<br>
			    	<select class="form-control form-control-lg" name="lineIdEventSeats" id="lineIdEventSeats"> 
			    		<option value="0">Sin plazas</option>
			    	</select>
			    <br>
			    </div>


				<label hidden for="linePriceEventSeats">Precio: </label>
				<select hidden class="form-control form-control-lg" name="linePriceEventSeats" id="linePriceEventSeats"> 
					<option value="0">Sin Precio</option>
				</select>
			    
			    <label hidden for="EventSeatsQuantity">Cantidad: </label>
				<select hidden disabled class="form-control form-control-lg" name="EventSeatsQuantity" id="EventSeatsQuantity"> 
					<option value="0">Sin Cantidad</option>
				</select>


			    <div style="width: 80px; float: left; margin-left: 7%">
					<label for="lineQuantity" style="float: left">Cantidad:</label> 
					<input class="form-control form-control-lg" type="number" name="lineQuantity" id="lineQuantity" value="1" min="1" max="?" onkeydown="return false" required onChange="ChargeTotal()">
					<br>
				</div>	 


				<div style="width: 100px; float: right;">
					
				    <label for="totalPrice" style="float: center">Total</label> 
					<input disabled class="form-control form-control-lg" type="text" name="totalPrice" id="totalPrice" value="0" required style="text-align: center;">
					<br>
				</div>

				<button class="btn btn-block btn-lg" style="background-color: #01DF01; width: 200px;" type="submit" name="buttonSubmitPurchaseLine">Añadir al carrito</button>
			</form>

		</div>
		<br><br>

	</body>
</html>






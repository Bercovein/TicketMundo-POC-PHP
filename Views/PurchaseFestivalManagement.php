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
		<script src="<?php echo JS_PATH?>json2.js"></script>
	
	<script type="text/javascript">
	
		$(document).ready(function(){
		    $("#lineCalendar").change(function(){
		    	$.ajax({
		        	url: '<?=FRONT_ROOT?>PurchaseLineController/chargeDateCalendar',
		      	    type: "POST",
		      	    data:"eventName="+$("#lineCalendar").val(),
		            success: function(opciones){
				        $("#festivalDate").html(opciones);
				        $("#price").val(0);
				        $("#lineQuantity").val(1);
				        $("#linePriceEventSeats").html('<option value="0">Sin Precio</option>');
						$("#subTotalPrice").val(0);
						$("#totalPrice").val(0);
		      		}
		   		})

		   		$.ajax({
		        	url: '<?=FRONT_ROOT?>PurchaseLineController/chargeEventSeats',
		      	    type: "POST",
		      	    data:"eventName="+$("#lineCalendar").val(),
		            success: function(opciones){
		            	$("#lineIdEventSeats").html(opciones);
		      		}
		   		})
		    });
		});
	</script>

	<script type="text/javascript">
		
		function charguePrice(){

			//CharguePrice
			$.ajax({
			    url: '<?=FRONT_ROOT?>PurchaseLineController/chargePrice',
			    type: "POST",
			    data:"idEventSeats="+$("#lineIdEventSeats").val(),
			    success: function(opciones){
				    $("#linePriceEventSeats").html(opciones);
				    $("#lineQuantity").val(1);
				    $("#price").val($("#linePriceEventSeats").val());

				    //REVISAR ESTO SOLO HAY 1 TOTAL
				    

				    var sum = 0;
				    for (i = 1; i <= $('#festivalDate option').length; i++) {
				    	sum += parseInt($("#price").val());
				    }
				    $("#subTotalPrice").val(sum);
				    $("#totalPrice").val($("#subTotalPrice").val()*0.8);
			    }
			})
			
			//ChargueMaxQuantity
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
		}
	</script>

	<script type="text/javascript">
		
		function ChargeTotal(){

			var price = document.getElementById("linePriceEventSeats").value;
			var quantity = document.getElementById("lineQuantity").value;

			document.getElementById("price").innerHTML="";
			document.getElementById("price").value = price*quantity;

			//REVISAR ESTO TIENE QUE SUMAR TODAS LAS FECHAS PERO SOLO HAY 1 INPUT
			var sum = 0;
			for (i = 1; i <= $('#festivalDate option').length; i++) {
				sum += parseInt(document.getElementById("price").value);
			}
			document.getElementById("subTotalPrice").value = sum;
			document.getElementById("totalPrice").value = sum*0.8;
		}
			
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

	<body>
		<div align="center" border="2" class="table" style="margin-top: 2%">
			
			<form class="login-form bg-dark-alpha p-5 text-white" style="width: 600px" method="POST" action="<?=FRONT_ROOT?>CartController/newFestivalCartLine">
				<h2>Agregar Compra Festival</h2>
				<br>
				
				<label for="lineCalendar" style="float: left">Evento: </label>

			    <select class="form-control form-control-lg" name="lineCalendar" id="lineCalendar"> 
			    	<option value="0"> Elige un festival</option>
			        <?php 
					foreach($listCalendar as $calendar) { ?>
						<option value="<?php echo $calendar->getEvent()->getName(); ?>">
							<?php echo $calendar->getEvent()->getName()." - ".$calendar->getEventPlace()->getName(); ?>
						</option>
					<?php } ?>
			    </select>
			    <br>			
				
				<label for="festivalDate" style="float: left">Fechas: </label>

			    <select disabled multiple class="form-control form-control-lg" name="festivalDate" id="festivalDate" style="width:500px"> 
			    	<option value="0">Sin fechas</option>
			    </select>
			    <br>	


				<div id="div1" style="width: 290px; float: left;">
					<label for="lineIdEventSeats" style="float: left">Plaza Evento/s: </label>
					<br>
				    <select class="form-control form-control-lg" name="lineIdEventSeats" id="lineIdEventSeats" onChange="charguePrice()"> 
				    	<option value="0">Sin plazas</option>
				    </select>
					<br>
				</div>

				<!-- Este input se esconde porque el precio ya lo muestra en el select de plaza evento -->
				<label hidden for="linePriceEventSeats">Precio: </label>
				<select hidden class="form-control form-control-lg" name="linePriceEventSeats" id="linePriceEventSeats"> 
					<option value="0">Sin Precio</option>
				</select>
				    
				<!-- este input se utiliza para calcular la cantidad maxima a comprar de un asiento -->
				<label hidden for="EventSeatsQuantity">Max Cantidad: </label>
				<select hidden disabled class="form-control form-control-lg" name="EventSeatsQuantity" id="EventSeatsQuantity"> 
					<option value="0">Sin Cantidad</option>
				</select>

				<div  id="div2" style="width: 80px; float: left; margin-left: 2%;">
					<label for="lineQuantity" style="float: left">Cantidad:</label> 
					<input class="form-control form-control-lg" type="number" name="lineQuantity" id="lineQuantity" value="1" min="1" max="?" onkeydown="return false" required onChange="ChargeTotal()">
					<br>
				</div>	 
	
				<div id="div3" style="width: 100px; float: right; margin-right: 2%">
					<label for="price" style="float: center">Precio</label> 
					<input disabled class="form-control form-control-lg" type="text" name="price" id="price" value="0" style="text-align: center;">
					<br>
				</div>
				
				<div style="float: left;width:100px;margin-left: 150px">
					<label for="subTotalPrice" ><h5>SubTotal</h5></label> 
						<input disabled class="form-control form-control-lg" type="text" name="subTotalPrice" id="subTotalPrice" value="0" style="text-align:center;width:100px">
					<br>
				</div>
				
				<div style="float: center;width:100px;margin-left: 150px">
					<label for="totalPrice" style="width:100px"><h5>Total</h5></label> 
						<input disabled class="form-control form-control-lg" type="text" name="totalPrice" id="totalPrice" value="0" style="text-align:center;width:100px">
					<br>
				</div>

				<button class="btn btn-block btn-lg" style="background-color: #01DF01; width: 200px;float:right;" type="submit" name="buttonSubmitPurchaseLine">Añadir al carrito</button>

				<br>
                <div align="left" >
                    <a href="<?php echo FRONT_ROOT ?>CartController/showAddView">
                    <input  type="button" name="return" class="btn btn-danger btn-block btn-lg" style="height: 40px; width: 100px; font-size: 14px;float: left;" value="Regresar">
                    </a>
                </div>

			</form>

		</div>
		<br><br>

	</body>
</html>






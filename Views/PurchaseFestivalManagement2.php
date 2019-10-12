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
		        	url: '<?=FRONT_ROOT?>PurchaseLineController/chargeDateCalendar2',
		      	    type: "POST",
		      	    data:"eventName="+$("#lineCalendar").val(),
		            success: function(opciones){
				        $("#festivalDate").html(opciones);
				        $("#subTotalPrice1").val(0);
				        $("#lineQuantity1").val(1);
				        $("#linePriceEventSeats1").html('<option value="0">Sin Precio</option>');
				        $("#lineIdEventSeats1").html('<option value="0">Sin plazas</option>');
						$("#date1").val('-');
						$("#totalPrice").val(0);
						$("#totalDiscount").val(0);
						

						for (i = 2; i <= 7; i++) {
							$("#lineIdEventSeats"+i).html('<option value="0">Sin plazas</option>');
							$("#subTotalPrice"+i).val(0);
							$("#lineQuantity"+i).val(1);
							$("#linePriceEventSeats"+i).html('<option value="0">Sin Precio</option>');

							$("#div0"+i).css("display", "none");
							$("#div1"+i).css("display", "none");
							$("#div2"+i).css("display", "none");
							$("#div3"+i).css("display", "none");

							$("#lineIdEventSeats"+i).prop('disabled', true);
							$("#linePriceEventSeats"+i).prop('disabled', true);
							$("#lineQuantity"+i).prop('disabled', true);
						}
		      		}
		   		})
		    });
		});
	</script>

	<script type="text/javascript">

		$(document).ready(function(){
		    $("#festivalDate").change(function(){

		    	var cantSelect = $('#festivalDate :selected').length; 

				if(cantSelect > 0){
					var selectednumbers = [];
					$('#festivalDate :selected').each(function(i, selected) {
					    selectednumbers[i] = $(selected).val();
					});

					$.ajax({
					    url: '<?=FRONT_ROOT?>PurchaseLineController/chargeSeatsMultiple',
					    type: "POST",
					    data: {'idCalendarArray':JSON.stringify(selectednumbers)},
					    success: function(opciones){  

					    	var opc = opciones.split("*");

					    	for (i = 1; i <= 7; i++) {
								$("#lineIdEventSeats"+i).html('<option value="0">Sin plazas</option>');
							    $("#subTotalPrice"+i).val(0);
							    $("#lineQuantity"+i).val(1);
							    //$("#date"+i).val('-');
							    $("#linePriceEventSeats"+i).html('<option value="0">Sin Precio</option>');
							    $("#totalPrice").val(0);
							    $("#totalDiscount").val(0);

							    $("#div0"+i).css("display", "none");
							    $("#div1"+i).css("display", "none");
							    $("#div2"+i).css("display", "none");
							    $("#div3"+i).css("display", "none");

							    $("#lineIdEventSeats"+i).prop('disabled', true);
							    $("#linePriceEventSeats"+i).prop('disabled', true);
							    $("#lineQuantity"+i).prop('disabled', true);
							}

							for (i = 0; i < cantSelect; i++) {
								$("#lineIdEventSeats"+(i+1)).html(opc[i]);
							    //$("#subTotalPrice"+(i+1)).val(0);
							    //$("#lineQuantity"+(i+1)).val(1);
							    //$("#linePriceEventSeats"+(i+1)).html('<option value="0">Sin Precio</option>');

							    $("#div0"+(i+1)).css("display", "block");
							    $("#div1"+(i+1)).css("display", "block");
							    $("#div2"+(i+1)).css("display", "block");
							    $("#div3"+(i+1)).css("display", "block");

							    $("#lineIdEventSeats"+(i+1)).prop('disabled', false);
							    $("#linePriceEventSeats"+(i+1)).prop('disabled', false);
							    $("#lineQuantity"+(i+1)).prop('disabled', false);
							}
					    }
			        })
					// Chargue Date Festival
			        $.ajax({
					    url: '<?=FRONT_ROOT?>PurchaseLineController/chargeDateFestival',
					    type: "POST",
					    data: {'idCalendarArray':JSON.stringify(selectednumbers)},
					    success: function(opciones){  
					    	var opc = opciones.split("*");
					    	var date;
					    	var aux = $("#linePriceEventSeats1");
					    	
					    	for (i = 0; i < 7; i++) {
					    		$("#linePriceEventSeats1").html(opc[i]);

					    		date = String($("#linePriceEventSeats1").val());
					    		day = date.slice(-8, -6);
					    		month = date.slice(-11, -9);
					    		$("#date"+i).val(day+"-"+month);
					    	}
					    	$("#linePriceEventSeats1") = aux;
					    }
					})
				}
		  	});
		});
	</script>

	<script type="text/javascript">
		
		function charguePrice(num){

			//CharguePrice
			$.ajax({
			    url: '<?=FRONT_ROOT?>PurchaseLineController/chargePrice',
			    type: "POST",
			    data:"idEventSeats="+$("#lineIdEventSeats"+num).val(),
			    success: function(opciones){
				    $("#linePriceEventSeats"+num).html(opciones);
				    $("#lineQuantity"+num).val(1);
				    $("#subTotalPrice"+num).val($("#linePriceEventSeats"+num).val());

				    var sum = 0;
				    for (i = 1; i <= 7; i++) {
				    	sum += parseInt($("#subTotalPrice"+i).val());
				    }
				    $("#totalPrice").val(sum);
			    }
			})
			
			//ChargueMaxQuantity
			$.ajax({
				url: '<?=FRONT_ROOT?>PurchaseLineController/chargeMaxQuantity',
				type: "POST",
				data:"idEventSeats="+$("#lineIdEventSeats"+num).val(),
				success: function(opciones){
				    $("#EventSeatsQuantity"+num).html(opciones);
			   		$("#lineQuantity"+num).attr({
			      	"max" : $("#EventSeatsQuantity"+num).val()
					});
		      	}
		    })
		}
	</script>

	<script type="text/javascript">
		
		function ChargeTotal(num){

			var price = document.getElementById("linePriceEventSeats"+num).value;
			var quantity = document.getElementById("lineQuantity"+num).value;

			document.getElementById("subTotalPrice"+num).innerHTML="";
			document.getElementById("subTotalPrice"+num).value = price*quantity;

			var sum = 0;
			for (i = 1; i <= 7; i++) {
				sum += parseInt(document.getElementById("subTotalPrice"+i).value);
			}
			document.getElementById("totalPrice").value = sum;
		}
			
	</script>
	

	<body>
		<div align="center" border="2" class="table" style="margin-top: 2%">
			
			<form class="login-form bg-dark-alpha p-5 text-white" style="width: 655px" method="POST" action="<?=FRONT_ROOT?>CartController/newFestivalCartLine">
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

			    <select multiple class="form-control form-control-lg" name="festivalDate" id="festivalDate" style="width:500px"> 
			    	<option value="0">Sin fechas</option>
			    </select>
			    <br>	

				<?php for($i = 1; $i <= 7; $i++) { ?>

					<div id="<?php echo 'div0'.$i?>" <?php if($i > 1) { ?> style="display: none;width: 85px; float: left;margin-right: 2%;" <?php } else { ?> style="width: 85px; float: left;margin-right: 2%;" <?php } ?> >
						<?php if($i == 1) { ?> 
							<label for="<?php echo 'date'.$i?>" style="float: left">Fecha: </label>
							<br>
						<?php } ?>
						<input disabled class="form-control form-control-lg" type="text" name="<?php echo 'date'.$i?>" id="<?php echo 'date'.$i?>" value="-" style="text-align: center">
					</div>

					<div id="<?php echo 'div1'.$i?>" <?php if($i > 1) { ?> style="display: none;width: 250px; float: left;" <?php } else { ?> style="width: 250px; float: left;" <?php } ?> >
						<?php if($i == 1) { ?> 
							<label for="<?php echo 'lineIdEventSeats'.$i?>" style="float: left">Plaza Evento/s: </label>
							<br>
						<?php } ?>
				    	<select class="form-control form-control-lg" name="<?php echo 'lineIdEventSeats'.$i?>" id="<?php echo 'lineIdEventSeats'.$i?>" onChange="charguePrice(<?php echo $i ?>)"> 
				    		<option value="0">Sin plazas</option>
				    	</select>
					   	<br>
				    </div>

					<label hidden for="<?php echo 'linePriceEventSeats'.$i?>">Precio: </label>
					<select hidden class="form-control form-control-lg" name="<?php echo 'linePriceEventSeats'.$i?>" id="<?php echo 'linePriceEventSeats'.$i?>"> 
						<option value="0">Sin Precio</option>
					</select>
				    
				    <label hidden for="<?php echo 'EventSeatsQuantity'.$i?>">Max Cantidad: </label>
					<select hidden disabled class="form-control form-control-lg" name="<?php echo 'EventSeatsQuantity'.$i?>" id="<?php echo 'EventSeatsQuantity'.$i?>"> 
						<option value="0">Sin Cantidad</option>
					</select>

				    <div  id="<?php echo 'div2'.$i?>" <?php if($i > 1) { ?> style="display: none;width: 80px; float: left; margin-left: 2%;" <?php } else { ?> style="width: 80px; float: left; margin-left: 2%" <?php } ?> >
				    	<?php if($i == 1) { ?> 
							<label for="<?php echo 'lineQuantity'.$i?>" style="float: left">Cantidad:</label> 
						<?php } ?>
						<input class="form-control form-control-lg" type="number" name="<?php echo 'lineQuantity'.$i?>" id="<?php echo 'lineQuantity'.$i?>" value="1" min="1" max="?" onkeydown="return false" required onChange="ChargeTotal(<?php echo $i ?>)">
					   	<br>
					</div>	 

					<div id="<?php echo 'div3'.$i?>" <?php if($i > 1) { ?> style="display: none;width: 100px; float: right; margin-right: 2%" <?php } else { ?> style="width: 100px; float: right; margin-right: 2%"  <?php } ?> >
						<?php if($i == 1) { ?> 
					   		<label for="<?php echo 'subTotalPrice'.$i?>" style="float: center">SubTotal</label> 
					   	<?php } ?>
						<input disabled class="form-control form-control-lg" type="text" name="<?php echo 'subTotalPrice'.$i?>" id="<?php echo 'subTotalPrice'.$i?>" value="0" style="text-align: center;">
					   	<br>
					</div>
				
				<?php } ?>
				
				<div style="float: left;width:100px;margin-left: 150px">
					<label for="totalPrice" ><h5>Total</h5></label> 
						<input disabled class="form-control form-control-lg" type="text" name="totalPrice" id="totalPrice" value="0" style="text-align:center;width:100px">
					<br>
				</div>
				
				<div style="float: center;width:100px;margin-left: 150px">
					<label for="totalDiscount" style="width:100px"><h5>Descuento</h5></label> 
						<input disabled class="form-control form-control-lg" type="text" name="totalDiscount" id="totalDiscount" value="0" style="text-align:center;width:100px">
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






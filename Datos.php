<?php # buy.php 
//saca la llave de conf
require 'config.inc.php';
session_start();
//recogemos valor de catalogo
?>



<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title id="titulo" >Datos</title>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="ventana1.js"></script>
<script type="text/javascript">

function oculta(id){

var F2=document.getElementById('F2');
F2.style.display='none';
}

function muestra(){
var F2=document.getElementById('F2');
F2.style.display='block';
var F1=document.getElementById('F1');
F1.style.display='none';
}


function recarga(){
/*Ruta absoluta, nos llevaría a una web externa*/
location.href = "Datos.php";
/*En cambio esta nos llevaría a una dentro de nuestro dominio*/
location.href="Datos.php";
}



window.onload=function(){oculta('F2');
}
</script>
<style>
@import 'estilos.css'screen;
</style>

</head>
<body>
<?php 
echo '<script type="text/javascript">Stripe.setPublishableKey("' . STRIPE_PUBLIC_KEY . '");</script>';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$errors = array();
	if (isset($_POST['stripeToken'])) {
		$token = $_POST['stripeToken'];
		if (isset($_SESSION['token']) && ($_SESSION['token'] == $token)) {
			$errors['token'] = 'No oprimir doble click';
		} else { 
			$_SESSION['token'] = $token;
		}		
		
	} else {
		$errors['token'] = 'No se puede procesar';
	}
	
	$amount = 2000; // cuanto le cobras

	if (empty($errors)) {
		
		try {
			require_once('Stripe.php');

			Stripe::setApiKey(STRIPE_PRIVATE_KEY);

			// Charge the order:
			$charge = Stripe_Charge::create(array(
				"amount" => $amount, // 
				"currency" => "usd",
				"card" => $token,
				"description" => $email
				)
			);

			
		} catch (Stripe_CardError $e) {
			$e_json = $e->getJsonBody();
			$err = $e_json['error'];
			$errors['stripe'] = $err['message'];
		} catch (Stripe_ApiConnectionError $e) {
		} catch (Stripe_InvalidRequestError $e) {
		} catch (Stripe_ApiError $e) {
		} catch (Stripe_CardError $e) {
		}

	} 
} 
?>

<form action="guardar.php" method="POST" id="payment-form">
<div id="F1">
<br>
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
<div class="imagen"> 
<img style="width: 215px; height: 215px;"alt="." src="producto4.jpeg">

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<img style="width: 230px; height: 265px;" alt="." src="producto2.jpeg">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<img style="width: 155px; height: 155px;" alt="." src="producto3.jpeg">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<img style="width: 242px; height: 147px;" alt="." src="producto1.jpg">
<br>
<br>
<br>
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; 
<input name="objeto[]" value="1" type="checkbox" id="cb">
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
<input name="objeto[]" value="2" type="checkbox" id="cb"> &nbsp;&nbsp; &nbsp; &nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;
<input name="objeto[]" value="3" type="checkbox" id="cb">
&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; 
<input name="objeto[]" value="4" type="checkbox" id="cb">
 &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
<br>      
<input type="button" id='boton' onclick='muestra()' value="comprar">
</div> 

</div>
















<div id="F2">
<h1>Introduce tu informacion</h1>
<div>
		<?php 
		if (isset($errors) && !empty($errors) && is_array($errors)) {
			echo '<div class="alert alert-error"><h4>Error!</h4>Ha ocurrido algun error:<ul>';
			foreach ($errors as $e) {
				echo "<li>$e</li>";
			}
			echo '</ul></div>';	
		}?>

<div id="payment-errors"></div>
<span class="help-block"></span>

<div class="alert alert-info"><h4></h4></div>
<label>Numero de tarjeta</label>
<span class="help-block">1.Introduce los numeros sin espacios.</span>
<input type="text" size="20" autocomplete="off" class="card-number input-medium">
<br>
<label>CVC</label>
<input type="text" size="4" autocomplete="off" class="card-cvc input-mini">
<br>
<label>Caducidad (MM/YYYY)</label>
<input type="text" size="2" class="card-expiry-month input-mini">
<label>&nbsp | &nbsp </label><input type="text" size="4" class="card-expiry-year input-mini">
<br>
Nombre <input type="text"class="nombre">
<br>
Correo <input type="text" name="correo">
<br>
Direccion <input type="text" name="direccion">		
</div>
<br>		
<button type="submit" class="btn" id="submitBtn">Enviar datos</button>
<input type="button" id='recargar' onclick='recarga()' value="regresar">

</form>
</div>	
</body>
</html>

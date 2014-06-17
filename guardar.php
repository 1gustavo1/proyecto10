<?
require ('./lib/Stripe.php');
require 'config.inc.php';
Stripe::setApiKey(STRIPE_PRIVATE_KEY);
$token = $_POST['stripeToken'];
$correo = $_POST['correo'];
$direccion=$_POST['direccion'];
$producto = $_POST['objeto'];
$sel=array();
$precio=0;
for($i=0; $i < count($producto); $i++){
$sel[]=$producto[$i];

if($sel[$i]==1){$precio=10+$precio;}
if($sel[$i]==2){$precio=20+$precio;}
if($sel[$i]==3){$precio=5+$precio;}
if($sel[$i]==4){$precio=20+$precio;}


}

$precio=$precio*100;
try{
//mandar correo-----------------------------------------------------------------
$uri = 'https://mandrillapp.com/api/1.0/messages/send.json';
$api=API_KEY_MANDRILL;
$postString = '{ "key": "'.$api.'",
"message": {
    "html": "<h1>GRACIAS POR COMPRAR:$</h1>",
    "text": "GRACIAS POR COMPRAR ONLINE",
    "subject": "CONFIRMACION DE COMPRA EN LINEA",
    "from_email": "gustavoggg12345@gmail.com",
    "from_name": "gustavoggg12345@gmail.com",
    "to": [
        {
            "email": "'.$correo.'",
            "name": "CLIENTE"
        }
    ],
    "headers": {

    },
    "track_opens": true,
    "track_clicks": true,
    "auto_text": true,
    "url_strip_qs": true,
    "preserve_recipients": true,

    "merge": true,
    "global_merge_vars": [

    ],
    "merge_vars": [

    ],
    "tags": [

    ],
    "google_analytics_domains": [

    ],
    "google_analytics_campaign": "...",
    "metadata": [

    ],
    "recipient_metadata": [

    ],
    "attachments": [

    ]
},
"async": false
}';

//echo $postString;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $uri);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec_follow($ch);
//curl_close($ch)

//$result = curl_exec($ch);
//echo "<br>".$result."<br>";


echo"<h1><div style='border: 10px solid black'>EXITO</div></h1>";
echo "<head><script type='text/javascript'>
function recarga(){
location.href = 'Datos.php';
/*En cambio esta nos llevarÃ­a a una dentro de nuestro dominio*/
location.href='Datos.php';
}
</script>
</head>
<body>
<input type='button' id='recargar' onclick='recarga()' value='regresar'>
</body>";

}catch(Exception $e)
{echo "correo no valido";}










//---------------------------------------------------------------------------------












/*
$miarchivo=fopen('datos.txt','a');//abrir archivo, nombre archivo, modo apertura
fwrite($miarchivo, $token . PHP_EOL);
fclose($miarchivo); //cerrar archivo
*/



try {

$token = $_POST['stripeToken'];
/*
//sirve para crear un plan en este caso anual,
Stripe_Plan::create(array(
  "amount" => 200,
  "interval" => "month",
  "name" => "Tiendita",
  "currency" => "usd",
  "id" => "tienda")
);
*/



$customer = Stripe_Customer::create(array(
  "card" => $token,
  "plan" => "tienda",
  "description" => $correo)
);

Stripe_Charge::create(array(
  "amount" => $precio, 
  "currency" => "usd",
  "customer" => $customer->id)
);
//saveStripeCustomerId($user, $customer->id);
} catch(Stripe_CardError $e) {
echo "Error";
}










//----------------------
function curl_exec_follow($ch, &$maxredirect = null) {
  
  // we emulate a browser here since some websites detect
  // us as a bot and don't let us do our job
  $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5)".
                " Gecko/20041107 Firefox/1.0";
  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent );

  $mr = $maxredirect === null ? 5 : intval($maxredirect);

  if (ini_get('open_basedir') == '' && ini_get('safe_mode') == 'Off') {

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $mr > 0);
    curl_setopt($ch, CURLOPT_MAXREDIRS, $mr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  } else {
    
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

    if ($mr > 0)
    {
      $original_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
      $newurl = $original_url;
      
      $rch = curl_copy_handle($ch);
      
      curl_setopt($rch, CURLOPT_HEADER, true);
      curl_setopt($rch, CURLOPT_NOBODY, true);
      curl_setopt($rch, CURLOPT_FORBID_REUSE, false);
      do
      {
        curl_setopt($rch, CURLOPT_URL, $newurl);
        $header = curl_exec($rch);
        if (curl_errno($rch)) {
          $code = 0;
        } else {
          $code = curl_getinfo($rch, CURLINFO_HTTP_CODE);
          if ($code == 301 || $code == 302) {
            preg_match('/Location:(.*?)\n/', $header, $matches);
            $newurl = trim(array_pop($matches));
            
            // if no scheme is present then the new url is a
            // relative path and thus needs some extra care
            if(!preg_match("/^https?:/i", $newurl)){
              $newurl = $original_url . $newurl;
            }   
          } else {
            $code = 0;
          }
        }
      } while ($code && --$mr);
      
      curl_close($rch);
      
      if (!$mr)
      {
        if ($maxredirect === null)
        trigger_error('Too many redirects.', E_USER_WARNING);
        else
        $maxredirect = 0;
        
        return false;
      }
      curl_setopt($ch, CURLOPT_URL, $newurl);
    }
  }
  return curl_exec($ch);
}


?>





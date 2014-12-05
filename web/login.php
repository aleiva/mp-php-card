<?php
session_start();

require_once('./apihelper.php');

$_SESSION['amount'] = 20;


if ($_POST) {
	//Almacenar email en sesión. Sin validación alguna
    $email             = $_POST['email'];
    $_SESSION['payer_email'] = $email;

    //Busco si el customer asociado al email existe. Esto también puede ser hecho por id de customers
    $existing_customer = get_customer($_POST['email']);
      

    if($existing_customer){
      $_SESSION['customer_id'] = $existing_customer->id;
    ?>
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="https://secure.mlstatic.com/org-img/checkout/custom/2.0/checkout.js"></script>

    <h2>Monto a pagar <?php echo $_SESSION['amount'] ?></h2>
    <form action="charge-cu3.php" method="POST" id="pay">
        <?php
        		$options[] = "<option selected disabled>Seleccione</option>";
                foreach ($existing_customer->cards as $c) {
                    $options[] = "<option value='{$c->id}' type='{$c->payment_method->payment_type_id}' pmethod='{$c->payment_method->id}' first_six_digits='{$c->first_six_digits}' binId='{$c->last_four_digits}' issuerId='{$c->issuer->id}'>{$c->payment_method->id} {$c->last_four_digits}</option>";
                }
		?>
           <select id="cardId" name="cardId" data-checkout='cardId' >
             <?php
                 echo implode("\n", $options);
             ?>
           </select> 
        <br>
        <br>
           <input data-checkout="securityCode"  placeholder="CVV" type="text"/>
        <br>
        <br>
        <button id="doPayment" title="Pay">Pagar</button>
        <br>
        <br>
        <a href="basic-form-mp.php">Ingresar otra tarjeta</a>
    </form>

      <script type="text/javascript">
         /* Replace with your public_key */
         Checkout.setPublishableKey("841d020b-1077-4742-ad55-7888a0f5aefa");
         $("#pay").submit(function( event ) {
             var $form = $(this);
             Checkout.createToken($form, mpResponseHandler);

             
             event.preventDefault();
             return false;
         });
         var mpResponseHandler = function(status, response) {
             var $form = $('#pay');
             if (response.error) {
                 alert("An error has ocurred: "+JSON.stringify(response));
             } else {
                 var card_token = response.id;
                 $form.append($('<input type="hidden" id="card_token" name="card_token"/>').val(card_token));
                 alert(card_token);
                 $form.get(0).submit();
             }   
         }
      </script>

      <?php
    }
    else{
      require_once('./basic-form-mp.php');
    }
}
?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Pay</title>
   </head>
   <body>
      <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
      <script type="text/javascript" src="https://secure.mlstatic.com/org-img/checkout/custom/2.0/checkout.js"></script>
      <h2>Monto a pagar <?php session_start(); echo $_SESSION['amount'] ?></h2>

      <form action="charge-cu3.php" method="post" id="form-pay-mp">
         <p>Card number: <input data-checkout="cardNumber" type="text"/></p>
         <p>CVV: <input data-checkout="securityCode" type="text"/></p>
         <p>MM: <input data-checkout="cardExpirationMonth" type="text"/></p>
         <p>YYYY: <input data-checkout="cardExpirationYear" type="text"/></p>
         <p>Card holder name: <input data-checkout="cardholderName" type="text"/></p>
         <p>Document number: <input data-checkout="docNumber" type="text"/></p>
         <input data-checkout="docType" type="hidden" value="DNI"/>
         <p><input type="submit" value="Make payment"></p>
      </form>
      <script type="text/javascript">
         /* Replace with your public_key */
         Checkout.setPublishableKey("841d020b-1077-4742-ad55-7888a0f5aefa");
         $("#form-pay-mp").submit(function( event ) {
             var $form = $(this);
             Checkout.createToken($form, mpResponseHandler);
             event.preventDefault();
             return false;
         });
         var mpResponseHandler = function(status, response) {
             var $form = $('#form-pay-mp');
             if (response.error) {
                 alert("An error has ocurred: "+JSON.stringify(response));
             } else {
                 var card_token = response.id;
                 $form.append($('<input type="hidden" id="card_token" name="card_token"/>').val(card_token));
                 $form.get(0).submit();
             }   
         }
      </script>
   </body>
</html>
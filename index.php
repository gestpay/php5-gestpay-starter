<!DOCTYPE HTML>
<html>
<body>
<h1>Test Payment</h1>

<?php

/***************************************************
 *              INITIAL CONFIGURATION              *
 ***************************************************
 *    ( SET UP THE ENVIRONMENT WITH YOUR CODES)    *
 ***************************************************/


/**
 * put this variable to true to pay on the test environment.
 * To retrieve your test code you can sign in at http://docs.gestpay.it/test/sign-up-for-test-environment.html
 */
$test_env = true;

/*
 * the shopLogin code
 */
$shopLogin = 'GESPAYXXXX';

/*
 * the currency code.
 * Check http://api.gestpay.it/#currency-codes for the right one
 * '242' is the code for EURO.
 */
$currency = '242';

/**
 * the amount of the transaction. How much do you want to bill your customers?
 */
$amount = '10.05';

/**
 * This is the
 */
$shopTransactionID='MY-SHOP-001';

/***************************************************
 *                  GESTPAY CODE                   *
 ***************************************************/

//used to display errors and to print the IP address of the server.
require('functions.php');
displayErrors();
printIpAddress();


//check where to connect: test or production environment?
if ($test_env) {
    $wsdl = "https://sandbox.gestpay.net/gestpay/gestpayws/WSCryptDecrypt.asmx?WSDL"; //TESTCODES
    $action_pagamento = "https://sandbox.gestpay.net/pagam/pagam.aspx";
} else {
    $wsdl = "https://ecomms2s.sella.it/gestpay/gestpayws/WSCryptDecrypt.asmx?WSDL"; //PRODUCTION
    $action_pagamento = "https://ecomm.sella.it/pagam/pagam.aspx";
}

//create the payment object array
$param = array(
    'shopLogin' => $shopLogin
,'uicCode' => $currency
,'amount' => $amount
,'shopTransactionId' => $shopTransactionID
);

//instantiate a SoapClient from Gestpay Wsdl
$client = new SoapClient($wsdl);
$objectResult = null;

//do the call to Encrypt method
try {
    $objectResult = $client->Encrypt($param);
}
//catch SOAP exceptions
catch (SoapFault $fault) {
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

//parse the XML result
$result = simplexml_load_string($objectResult->EncryptResult->any);

// if there is an error trying to contact Gestpay Server
// (e.g. your IP address is not recognized, or the shopLogin is invalid) you'll see it here.

$errCode= $result->ErrorCode;
$errDesc= $result->ErrorDescription;
if($errCode != 0){
    echo "<h2>Error: $errCode - $errDesc</h2>";
    echo '<h3>check the error in the <a href="http://api.gestpay.it/#errors">API</a></h3>';
    die();
}


//finally, we will define the variable $encString that will contain a string to pass to Gestpay upon payment.
//See the form below how it is used.
$encString= $result->CryptDecryptString;

?>

We will start a payment with this data: <br>
shopLogin: <?= $shopLogin ?><br>
amount: <?= $amount ?> &euro;
<!--hidden form, with cyphered data to start the payment process -->
<form
    name="pagamento"
    method="post"
    id="fpagam"
    action="<?= $action_pagamento ?> ">
    <input name="a" type="hidden" value="<?php echo($shopLogin) ?>" />
    <input name="b" type="hidden" value="<?php echo($encString) ?>" />
    <input style="width:90px;height:70px" type="submit" name="Pay" Value="Pay Now!" />
</form>
</body>
</html>
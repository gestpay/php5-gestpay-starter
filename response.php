<!DOCTYPE HTML>
<html>
<body>
<h1>Test Payment</h1>

<?php

//used to display errors and to print the IP address of the server.
require('functions.php');
displayErrors();

$test_env = true; 

if ($test_env) {
		$wsdl = "https://sandbox.gestpay.net/gestpay/gestpayws/WSCryptDecrypt.asmx?WSDL"; //TESTCODES 
	} else {
		$wsdl = "https://ecomms2s.sella.it/gestpay/gestpayws/WSCryptDecrypt.asmx?WSDL"; //PRODUCTION
	}

$shopLogin = $_GET["a"];

$CryptedString = $_GET["b"];

echo '<p>Objects sent by Gestpay via QueryParams:</p>';

echo '<p><strong>Shop Login:</strong> '. $shopLogin . '</p>';

echo '<p><strong>Crypted String:</strong> '. $CryptedString . '<p/>';

$params = array('shopLogin' => $shopLogin, 'CryptedString' => $CryptedString);


$client = new SoapClient($wsdl);
$objectResult = null;
try {
    $objectResult = $client->Decrypt($params);
} catch(SoapFault $fault) {
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

//parse the XML result
$result = simplexml_load_string($objectResult->DecryptResult->any);

$err = (string) $result->ErrorCode;
$errorDescription = (string) $result->ErrorDescription;

if ($err) {
	
	// 	Display the error
	echo '<h2>Error:</h2>';
    echo '<pre>' . $err . '</pre>';
    echo '<pre>' . $errorDescription . '</pre>';
	
}
else {
	
	// 	Display the result
	echo '<p>status of the transaction:</p>';
	echo '<h2>Transaction correctly done!</h2>';
    echo '<p>Down below you\'ll see the output of the \$result object.</p>';
	
	echo '<pre>';
	print_r ($result);
	echo '</pre>';
	
}


?>

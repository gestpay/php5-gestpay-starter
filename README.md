# Example payment via Gestpay Payment Page in PHP 5(+)

This repository is an example project to show how to implement a payment via Gestpay payment page.

The payment process is explained in [Gestpay Docs - Getting Started](http://docs.gestpay.it/gs/super-quick-start-guide.html) - see in particular *Using Banca Sella Payment Page*.

This repo uses the native SOAP extensions, present in PHP5+. If you are in a lower version of php, check [php4 repository](https://github.com/gestpay/php4-gestpay-starter).

## What's in this repository

| File     | Description   |
| ----------- | ------------ |
| `index.php` | is the main entry point for the application. tweaking this page you will be able to change the amount, or the currency, and so on. |
| `response.php` | when the payment is completed, Gestpay will redirect to this file to show to the user the payment status. `response.php` will decrypt the encrypted string and then it will show the SOAP message received - in the form of an array. |
| `phpinfo.php` | A simple page to check your php version and your server software. |
| `functions.php`| a file containing utility functions |
| `README.md` | this file |

## How to start the example

1. open the file `index.php` and change

 ```
 $shopLogin = 'GESPAYXXXXX';
 ```

 with your `shopLogin`.
 
2. start this webapp on a server with a public IP.
3. Connect to your [test merchant back-office](https://sandbox.gestpay.net/BackOffice/) and login
4. In *Configuration* > *IP address*, insert the public IP of your server
5. In the same page click on *Response Address* and insert:
	- *URL for positive response*: `<<your_server_address>>/response.php`
	- *URL for negative response*: `<<your_server_address>>/response.php`
	- *URL Server to Server*: `<<your_server_address>>/response.php`
6. Pay with one of the cards present in the *Notification* page.
7. Once you have payed, you'll be redirected by Gestpay on `response.php` to see the outcome of the transaction.

## Debugging

If you want to see the request you send to Gestpay:

1. Declare the SoapClient with the `trace` option set to true:
```
$client = new SoapClient($wsdl, array('trace' => 1));
```

2. write this line after the request:
```
echo "REQUEST:\n<pre>" . htmlentities($client->__getLastRequest()) . "</pre>\n";
```

## Questions, Issues, etc.

For any questions, open an issue on Github. We will gladly put off our mojito and answer.
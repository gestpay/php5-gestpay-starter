<?php

/**
 * this snippet tries to retrieve the public ip address
 * put your public ip address in gestpay merchant backoffice
 */
function printIpAddress()
{

    $externalContent = file_get_contents('https://api.ipify.org');
    if ($externalContent)
        echo "<p>IP for Gestpay configuration: $externalContent </p>";

}

function displayErrors()
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

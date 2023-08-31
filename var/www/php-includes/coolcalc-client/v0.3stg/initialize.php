<?php

// CoolCalc config and client libraries.
require_once('coolcalc-client/v0.3stg/config.php');
require_once('coolcalc-client/v0.3stg/lib/api_client.php');

// Instantiate api_client variable.
$API = new api_client($coolcalc_configs, $dbh);

?>

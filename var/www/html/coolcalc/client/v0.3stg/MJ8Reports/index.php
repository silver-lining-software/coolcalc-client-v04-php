<?php

// This is the script that downloads MJ8 reports by report id from the REST API.
// Normally this should not have any restricted access as MJ8 reports have a non-guessable id.

/* ------------------------------------------------------------------------------------------

   Put your custom code here to initialize session, log page visits, etc.

------------------------------------------------------------------------------------------ */

// CoolCalc client libraries.
// The below script provides the global variable $API which is used to connect to the CoolCalc REST API.
require_once("coolcalc-client/v0.3stg/initialize.php");

// Load report from the server.
$my_url = $coolcalc_configs["mj8_report_url"].$_GET["reportId"]."&rev=latest";
$my_headers = array(
    "Accept: application/json,application/vnd.geo+json"
);
$result = $API->GET($my_url, "", $my_headers);

// Output to client.
if ($API->response_code == 200) {
    if (!headers_sent()) {
        if ($API->response_headers["Content-Type"] == "application/json"  ||  $API->response_headers["Content-Type"] =="application/vnd.geo+json") {
            header("Content-Type: ".$API->response_headers["Content-Type"]);
            echo $result;
        } else {
            http_response_code(500);
            echo '<h3>Well this isn\'t supposed to happen now, is it?</h3>';
        }
    } else {
        http_response_code(500);
        die("<h3>Sorry, something broke.</h3>");
    }
}

?>

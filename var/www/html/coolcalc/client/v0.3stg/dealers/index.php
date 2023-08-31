<?php

// This is the main "forwarding" script that forwards requests from the client (browser)
// to the REST API for the /dealers/dealerId/... entry point.
// It is our responsability to verify that the dealerId URL segment provided corresponds to actual session data.
// Here we only send/receive JSON or GeoJSON, no other content types are accepted.


/* ------------------------------------------------------------------------------------------

   Put your custom code here to initialize session, log page visits, etc.

   For this specific script your custom code must verify that the end user 
   has access to the project list for the dealer id provided in the REST URL.

   Sample code (place after variable $my_url is created below):

    $my_dealer_id = explode('/', substr($my_path, stripos($my_path, "dealers") + 7))[1];
    if ($my_dealer_id) {
        if($my_user->account_nr != $my_dealer_id) {
            http_response_code(400);
            die("Dealer Id doesn't match.");
        }
    } else {
        http_response_code(400);
        die("Not a valid endpoint.");
    }

------------------------------------------------------------------------------------------ */

// CoolCalc client libraries.
// The below script provides the global variable $API which is used to connect to the CoolCalc REST API.
require_once("coolcalc-client/v0.3stg/initialize.php");

// Substitute the REST API's domain in the request URI so we can forward the request.
$my_path = str_replace($coolcalc_configs["path_to_client"], "", $_SERVER["REQUEST_URI"]);
$my_url = "https://".$coolcalc_configs["rest_api_server"].$my_path;

// Check that the dealer id corresponds to the PHP session (see above).
// ...

// Forward request to REST API.
switch ($_SERVER["REQUEST_METHOD"]) {
    case "OPTIONS":
        $result = $API->OPTIONS($my_url);
        break;
    case "GET":
        $my_headers = array(
            "Accept: application/json,application/vnd.geo+json"
        );
        $result = $API->GET($my_url, "", $my_headers);
        break;
    case "POST":
        $json_data = file_get_contents("php://input");
        $my_headers = array(
            "Content-Type: application/json",
            "Accept: application/json,application/vnd.geo+json"
        );
        $result = $API->POST($my_url, $json_data, $my_headers);
        break;
    case "PUT":
        $json_data = file_get_contents("php://input");
        $my_headers = array(
            "Content-Type: application/json",
            "Accept: application/json,application/vnd.geo+json"
        );
        $result = $API->PUT($my_url, $json_data, $my_headers);
        break;
    case "DELETE":
        $result = $API->DELETE($my_url);
        break;
}

// Set HTTP response code.
$my_response_code = $API->response_code;
http_response_code($my_response_code);

// For POST requests, forward HTTP "Location" header if sent by REST API to the client.
// HTTP "Location" header can be used by the client to download a newly created resource after a POST request (add to collection).
if ($_SERVER["REQUEST_METHOD"] == "POST"  &&  $API->response_headers["Location"]) {
    header("Location: ".$API->response_headers["Location"]);
}

// Forward HTTP "Allow" header to the client.
// HTTP "Allow" header is used by the client to render form controls (save, delete, etc) to a resource.
if ($API->response_headers["Allow"]) {
    header("Allow: ".$API->response_headers["Allow"]);
}

// Output to client.
// Check server response Content-Type header.
if ($my_response_code != 204) {
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

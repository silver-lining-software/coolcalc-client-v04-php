<?php

// api_client class definition.
class api_client {

    protected $dbh                   = NULL;
    protected $server_authentication = "";
    protected $client_id             = "";
    protected $client_key            = "";

    public $response_code            = NULL;
    public $response_status          = "";
    public $response_headers         = array();


    // PHP constructor.
    function __construct($my_configs = array(), $my_dbh) {

        $this->server_authentication = $my_configs["server_authentication"];
        $this->client_id             = $my_configs["client_id"];
        $this->client_key            = $my_configs["client_key"];

        // Local dbase connection (reserved).
        $this->dbh = $my_dbh;
    }


    // get_response_headers parses HTTP response headers.
    private function get_response_headers($ch, $header_line) {

        if (stripos($header_line, 'HTTP/1.1') !== FALSE) {
            list(,$response_code_str, $this->response_status ) = explode(' ', $header_line, 3);
            $this->response_code = (int) $response_code_str;
        }
        else {
            list($headername, $headervalue) = explode(":", $header_line, 2);
            $this->response_headers[trim($headername)] = trim($headervalue);
       }

       return strlen($header_line);
    }


    // call completes an HTTP request to the API server and returns its response to the client.
    protected function call($curl_url = "", $query_string = "", $HTTP_method = "", $payload = NULL, $headers = array()) {

        $API_response = NULL;

        // URL query string.
        // These are traditional GET URL parameters, if any.
        if ($query_string) {
            $curl_url .= '?'.$query_string;
        }

        // Create CURL request.
        $curl_handle = curl_init();

        // CURL options.
        curl_setopt($curl_handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl_handle, CURLOPT_URL, $curl_url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_HEADERFUNCTION, array($this, 'get_response_headers'));
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);

        // Additional CURL options depending on request type.
        switch ($HTTP_method) {

            case "OPTIONS":
                curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "OPTIONS");
                break;

            case "GET":
                curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, TRUE);
                break;

            case "POST":
                curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
                break;

            case "PUT":
                curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
                break;

            case "DELETE":
                curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }

        // HTTP basic authentication.
        if ($this->server_authentication == "HTTP_basic") {
            $API_credentials = $this->client_id.':'.$this->client_key;
            curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl_handle, CURLOPT_USERPWD, $API_credentials);
        }

        // Make CURL request and serialize response.
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        if (!empty($buffer)) {
            $API_response = $buffer;
        }

        // Return response.
        return $API_response;
    }


    // DELETE completes an HTTP DELETE request to the API server.
    public function DELETE($REST_url = "") {
        return $this->call($REST_url, "", "DELETE", NULL, array());
    }


    // GET completes an HTTP GET request to the API server and returns its response to the client.
    public function GET($REST_url = "", $query_string = "", $headers = array()) {
        return $this->call($REST_url, $query_string, "GET", NULL, $headers);
    }


    // OPTIONS completes an HTTP OPTIONS request to the API server and returns its response to the client.
    public function OPTIONS($REST_url = "") {
        return $this->call($REST_url, "", "OPTIONS", NULL, array());
    }


    // POST completes an HTTP POST request to the API server and returns its response to the client.
    public function POST($REST_url = "", $payload = NULL, $headers = array()) {
        return $this->call($REST_url, "", "POST", $payload, $headers);
    }


    // PUT completes an HTTP PUT request to the API server and returns its response to the client.
    public function PUT($REST_url = "", $payload = NULL, $headers = array()) {
        return $this->call($REST_url, "", "PUT", $payload, $headers);
    }
}

?>

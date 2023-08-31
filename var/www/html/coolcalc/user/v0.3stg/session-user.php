<?php

// This script answers ajax requests that need session/user info. 
// Currently used only for implementations with project list segregated by account nr (OEM/distributor)
// The output of this script should be a JSON object like { "dealerReference": "account-nr-123", "userReference": "nice-user-345" }.

/* ------------------------------------------------------------------------------------------

   Put your custom code here to initialize session, log page visits, etc.

   Note: 
    If an OEM or distributor allows use of the MJ8 tool by unauthenticated users,
    those users must be assigned a random "John/Jane Doe" account nr for the session,
    eg. { "dealerReference": "unknown-account-123-xyz" }

   Sample code:

    $mydata = array( "dealerReference" => $my_user->account_nr, "userReference" => $my_user->id );
    echo json_encode($mydata);

------------------------------------------------------------------------------------------ */


// Session user info.
// Replace code below with code for your application.
// ...
header("Content-Type: application/json");
echo json_encode(array( "dealerReference" => "nice-account-123", "userReference" => "nice-individual-345" ));

?>

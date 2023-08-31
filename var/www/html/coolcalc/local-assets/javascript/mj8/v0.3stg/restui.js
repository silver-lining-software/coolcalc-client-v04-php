// Page-specific code to initialize the CoolCalc REST UI when DOM content is loaded.
// The URL invoked to open the CoolCalc REST UI may include the following URL params to specify which resource to render on opening the UI:
//   1) hateoasLink
//   2) startAction
// If the URL does not include a hateoasLink param we load the dealer's MJ8 project list.
document.addEventListener("DOMContentLoaded", function(event) {

    // Check if a start action and/or initial resource HATEOAS link are specified in URL params.
    var startActionData = coolcalc.URIStartAction();

    // Load user info for the user/session, then the initial REST resource.
    var http  = new XMLHttpRequest();
    http.open("GET", myCoolCalcConfig.sessionUserURL, true);
    http.onreadystatechange = function() {
        if (http.readyState == 4) {
            if (http.status == 200) {
                var myData = JSON.parse(http.responseText);
                if (myData.dealerReference) {

                    // If no HATEOAS link was provided in by the caller (the page's URL) we load the dealer's project list.
                    if (!startActionData.hateoasLink) {
                        startActionData.hateoasLink = coolcalc.MJ8ProjectListURL(myData.dealerReference);
                    }

                    // Initialize the single page REST UI, load and render the initial REST resource.
                    coolcalc.initializeUI(myCoolCalcConfig, startActionData, null);

                } else {
                    alert("Sorry, unable to start CoolCalc session.");
                }
            } else {
                alert("Sorry, unable to start CoolCalc session.");
            }
        }
    }

    http.send();
});



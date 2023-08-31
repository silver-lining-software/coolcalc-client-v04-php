window.onload = function() {

    // Initialize a Khipu instance, for no reason other than to use its HTML rendering functions.
    var restUI = new khipu({ DOMElementId: "mj8-report", CSS: {hiddenContent: "nkn-hide"} });

    // renderReport is a callback to render the MJ8 report once the Google charts library finishes loading.
    function renderReport() {

        // URL to download the MJ8 report JSON.
        var urlParams = coolcalc.getJsonFromUrl(location.href);
        var myURL = myCoolCalcConfig.MJ8AjaxURL + "?reportId=" + encodeURIComponent(urlParams["report_id"]);

        // Load the MJ8 report and render HTML when server responds.
        var http  = new XMLHttpRequest();
        http.open("GET", myURL, true);
        http.onreadystatechange = function() {
            if (http.readyState == 4) {
                var myReport = JSON.parse(http.responseText);
                coolcalc.renderMJ8Report(document.getElementById("mj8-report"), "", myReport, "MJ8Report", {}, restUI);
            }
        }

        http.send();
    }

    // Load Google charts library.
    google.charts.load('current', {'packages': ['corechart', 'table']});
    google.charts.setOnLoadCallback(renderReport);
}



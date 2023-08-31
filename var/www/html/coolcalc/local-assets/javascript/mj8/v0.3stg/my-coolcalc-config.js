var myCoolCalcConfig = {

    // The domain names for the REST API and our local server, and the path to the local API endpoint.
    // The CoolCalc JavaScript library needs these to re-write the domain in ajax requests.
    APIDomain: "stagingapi.coolcalc.com",
    localDomain: "my-domain.com",
    APIClientEndpoint: "/coolcalc/api",

    // URL to the Khipu-JS JSON configs.
    khipuConfigURL: "https://cdn.coolcalc.com/config/mj8/v-unkuna/v0.3stg/khipu-js.json",

    // Map trace config URL.
    mapTraceConfigURL: "https://cdn.coolcalc.com/config/map-trace/v0.3stg/map-trace.json",

    // The URL of the local script that returns the user info for the current session.
    // The output of this script should be something like { "dealerReference": "123-xyz", "userReference": "345-abc" }
    sessionUserURL: "/coolcalc/user/v0.3stg/session-user.php",

    // The URL for the static HTML page where you render HTML MJ8 reports based on the JSON report received from the CoolCalc API.
    MJ8ReportURL: "/coolcalc/ui/mj8/v-unkuna/v0.3stg/mj8-report.php",

    // The URL to the ajax script that downloads MJ8 report JSON from the CoolCalc API.
    // This is normally different from the main CoolCalc client endpoint because it should be accessible without user login.
    MJ8AjaxURL: "/coolcalc/client/v0.3stg/MJ8Reports/index.php",

    // URL to SVG icons for navigation menus and progress bar.
    SVGIconURL: "/coolcalc/local-assets/sprites/coolcalc-resources.svg#"
}



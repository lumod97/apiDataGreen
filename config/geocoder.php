<?php

return [

    /*
     * The api key used when sending Geocoding requests to Google.
     */
    // Operation [operations/akmf.p7-365321137402-bceface1-d57f-48ed-be46-a65fa844dc0a] complete. Result: {
    //     "@type":"type.googleapis.com/google.api.apikeys.v2.Key",
    //     "createTime":"2024-06-27T21:53:45.813196Z",
    //     "etag":"W/\"lsJES1eZNh5VURjnJ2CRzw==\"",
    //     "keyString":"AIzaSyDNMAEz6Rr_3bcQ_X7fZPz2uZHuwgN_sQU",
    //     "name":"projects/365321137402/locations/global/keys/bbd25c55-599b-4691-8153-744d13421db8",
    //     "uid":"bbd25c55-599b-4691-8153-744d13421db8",
    //     "updateTime":"2024-06-27T21:53:45.837841Z"
    // }
    'key' => env('GOOGLE_MAPS_GEOCODING_API_KEY', 'AIzaSyDNMAEz6Rr_3bcQ_X7fZPz2uZHuwgN_sQU'),

    /*
     * The language param used to set response translations for textual data.
     *
     * More info: https://developers.google.com/maps/faq#languagesupport
     */

    'language' => '',

    /*
     * The region param used to finetune the geocoding process.
     *
     * More info: https://developers.google.com/maps/documentation/geocoding/requests-geocoding#RegionCodes
     */
    'region' => '',

    /*
     * The bounds param used to finetune the geocoding process.
     *
     * More info: https://developers.google.com/maps/documentation/geocoding/requests-geocoding#Viewports
     */
    'bounds' => '',

    /*
     * The country param used to limit results to a specific country.
     *
     * More info: https://developers.google.com/maps/documentation/javascript/geocoding#GeocodingRequests
     */
    'country' => '',

];

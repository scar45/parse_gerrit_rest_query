<?php

// *******************************************************
// ******** MUST BE SET AS A CRON JOB! *******************
// *******************************************************

// Download the (invalid) JSON format from Gerrit query function

$sJson = file_get_contents('http://gerrit.aokp.co/query?format=JSON&q=status:merged');


// Write response to file (allows for jQuery to access the data - cross-domain workaround)

file_put_contents('response/aokp-gerrit-query-response.txt', $sJson);


// Parse the HTML file contents (from above) into a PHP array by decoding the JSON

$decodedJsonData = array_map(
    function($jsonString) {
        return json_decode($jsonString, true);
    },
    file('response/aokp-gerrit-query-response.txt')
);


// Clean the last line of the file which is Gerrit's performance data on the query

$clean = array_pop($decodedJsonData);


// Debug: Output the array to the screen

echo json_encode($decodedJsonData);


// Write the JSON-ified array (which is broken because it is nested and the above 'json_decode' will only iterate through the first level of the nest) to a JSON file to be parsed with jQuery on the front-end

file_put_contents('response/aokp-gerrit-merged.json', json_encode($decodedJsonData));

?>

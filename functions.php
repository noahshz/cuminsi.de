<?php
$data = array('error_code' => 1001);

request("URL/error_messages.php", $data);

function request($url, &$data) {
    // build the urlencoded data
    $postvars = http_build_query($data);

    // open connection
    $curl = curl_init();

    // set the url, number of POST vars, POST data
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($data));
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postvars);

    // execute post
    $result = curl_exec($curl);

    // close connection
    curl_close($curl);
}
?>
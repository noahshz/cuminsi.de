<?php
/*
functionality: sends data to url and request the response of page
example usage: for error messages

example:

$data = array('error_code' => 1001);
post_request("URL/error_messages.php", $data);

*/
function post_request($url, $data) {
    $postvars = http_build_query($data);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, count($data));
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postvars);
    $result = curl_exec($curl);
    curl_close($curl);
}
?>
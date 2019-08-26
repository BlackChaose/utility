<?php
function grab_image($url,$saveto){
    $fp = fopen ($saveto, 'w+');

    $ch = curl_init ();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'] . "/cookie_file_cp2");
    curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'] . "/cookie_file_cp2");

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // <-- don't forget this
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // <-- and this
    curl_setopt($ch, CURLOPT_FILE, $fp);          // output to file

    curl_exec($ch);

    curl_close ($ch);

    fclose($fp);
}

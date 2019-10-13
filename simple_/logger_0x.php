<?php
define('LOG_PATH_0x', __DIR__ . '/loggers_0x.log');
function setLog_0x($str, $file = LOG_PATH_0x, $from_fl = "...")
{
    if ($file === "default") {
        $file = LOG_PATH;
    }
    $tmp_file = fopen($file, 'a');
    $date = date('Y-m-d H:i:s');
    $str_res = $date . "(" . $from_fl . ")=> " . $str;
    fwrite($tmp_file, $str_res . "\r\n");
    fclose($tmp_file);
    return 0;
}
?>
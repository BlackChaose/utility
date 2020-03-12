<?php
/**
 * use PHPMailer https://github.com/PHPMailer/PHPMailer
 * attention: add your info in code below!
 */
include_once('PHPMailer/PHPMailerAutoload.php');


define('LOG_PATH_0x', __DIR__ . '/loggers_0x.log');
function setLog_0x($str, $file = LOG_PATH_0x)
{
    if ($file === "default") {
        $file = LOG_PATH;
    }
    $tmp_file = fopen($file, 'a');
    $date = date('Y-m-d H:i:s');
    $str_res = $date . " =>  " . $str;
    fwrite($tmp_file, $str_res . "\r\n");
    fclose($tmp_file);
    return 0;
}

$options = getopt("p:m:");
if(empty($options)){
    echo "-p <message> -m <recepient>\n";
    die;
}

$mailTo = trim($options['m']);
$mailFrom = '<your mail from>';

$mesParam = $options["p"]??null;

$curDate = date('l jS \of F Y h:i:s A');
$tplMes = "Alarm ".$curDate." Message: ".$mesParam;
$php_mailer = new PHPMailer();
$php_mailer->isSMTP();
$php_mailer->Host = '<server mail host>';
$php_mailer->Port = '<port>';
//$php_mailer->SMTPAuth = true;
$php_mailer->CharSet = "utf-8";
$php_mailer->From =$mailFrom;
$php_mailer->FromName = '<mail from>';
$php_mailer->addReplyTo($mailTo);
$php_mailer->AddAddress($mailTo);
$php_mailer->Subject ='Message!' ;
$php_mailer->Body=$tplMes;

if($php_mailer->send()){
    setLog_0x('OK! '.$tplMes);
}else{
    setLog_0x("Error! ".$php_mailer->ErrorInfo." \r\n ".$tplMes);
}

echo "rbLog end work!";
?>

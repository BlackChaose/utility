<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 13.03.20
 * Time: 14:42
 */
ini_set('display_errors','1');
define('LOG_PATH_0x', __DIR__ . '/loggers.log');
require_once ("./vendor/autoload.php");
use \Utility\DbUtility;
function setLog_0x ($str, $file = LOG_PATH_0x)
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

if(!empty($_GET['key']) && $_GET['key']=='9d722304320de0b074d8da74bf680874'){
    $message = (!empty($_GET['mes']))? $_GET['mes']:'no message';
    setLog_0x('Huk was worked! : ' . $message);

    $obj = new DbUtility();
    $pdo = $obj->getPDO();
    try{
        $query = "Insert into nr.timelog (date, message) values(:date, :message)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':date'=>date('Y-m-d H:i:s'), ':message'=>$message]);
        $res = $stmt->fetchAll();
        http_response_code('200');
        echo json_encode(['Ok'=>'Ok!']);
        die;
    }catch(\Exception $e){
        http_response_code(400);
        echo json_encode(['Error!'=>$e->getMessage()]);
    }

}
http_response_code('200');
echo 'err';
die;
?>

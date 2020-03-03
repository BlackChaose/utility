<?php
/**
 * 03-03-2020
 * nikita.s.kalitin@gmail.com
 */
namespace ParserLog;
require_once 'vendor/autoload.php';

use Tightenco\Collect;
$options = getopt("s:d:v:");

if(empty($options)){
    echo "-s <path to source> -d <path to dst> \n";
    die;
}
if(!empty($options["v"])){
    echo "version 0.1 03-03-2020 \n";
    die;
}

$file_path_src = $options["s"]??null;
$file_path_dest = $options["d"]??null;

echo "\033[92m source:  ".$options['s']. "\e[39m\n";
echo "\033[93m destination:  ".$options['d']. "\e[39m\n";

if(empty($file_path_src)){echo "Error! source is null!\n"; die;}


$dataFile = $options['s'];

echo $dataFile;

$res = [];
$datalog = [];
$file = fopen($dataFile,"r");
while(!feof($file)){
    $res[]=explode(' ', fgets($file));
}
fclose($file);
foreach ($res as $el){
    $datalog[] = [
      'day'=> $el[0]??null,
      'time'=> $el[1]??null,
      'id-message' => $el[2]??null,
      'direct' => $el[3]??null,
      'info' => $el[4]??null,
      'reserv' => $el,
    ];
}
$collection  = collect($datalog);

$filtered = $collection -> filter(function($val, $key){
    if(($val['direct'] === '=>' || $val['direct'] ==="Completed\n") && (trim($val['info'])!=='reestr@extech.ru' && trim($val['info']) !== 'kalitinns@extech.ru')){
        return true;
    }
    else return false;
});
$grouped = $filtered ->groupBy('id-message');
$sorted = $grouped -> sortBy('id-message');

/**
<=      message arrival
(=     message fakereject
=>     normal message delivery
->     additional address in same delivery
>>     cutthrough message delivery
*>     delivery suppressed by -N
**     delivery failed; address bounced
==     delivery deferred; temporary problem
 */

$sorted2 = $sorted->filter(function($val){
    return $val->count() >= 2;
});
$out = fopen($file_path_dest,'aw');
$sorted2->each(function($item) use($out){
    echo $item->pluck('info')[0];
    echo "\n";
    fwrite($out,$item->pluck('info')[0]."\n");
});
fclose($out);
echo "\033[92m count:  ".$sorted2->count(). "\e[39m\n";
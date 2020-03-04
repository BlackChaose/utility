<?php
/**
 * 03-03-2020
 * nikita.s.kalitin@gmail.com
 * Parser for get data about send mails from /var/log/exim4/mainlog
 * type in console php pl.php -s <soruce.file> -d <output.file>
 */
//todo add check timeout&message error in log etc.
namespace ParserLog;

require_once 'vendor/autoload.php';
require_once 'env.php';
require_once 'constants.php';

use Tightenco\Collect;
try {
    $options = getopt("s::d::hv::");
//    print_r($options); die;
    if(isset($options["h"])){
        echo ESC."[32m  "."-s <path to source> -d <path to dst>"."\e[39m\n";
        echo ESC."[32m  "."-v <show version>"."\e[39m\n";
        echo ESC."[32m  "."-h <show help>"."\e[39m\n";
        die;
    }

    if (isset($options["v"])) {
        echo VIOLET_FONT."m version 0.1 03-03-2020 ".DEFATTR."\n";
        die;
    }

    if (empty($options)) {
        throw(new \Exception('no path to source and destination! use -h for help!'));
    }
    if(!isset($options['s'])){
        throw(new \Exception('no path to source! use -h for help!'));
    }
    if(!isset($options['d'])){
        throw(new \Exception('no path to destination! use -h for help!'));
    }


    $file_path_src = $options["s"];
    $file_path_dest = $options["d"];

    if(!file_exists($options["s"])){
        throw(new \Exception('source file not exist! use -h for help!'));
    }
    if(file_exists($options["d"])){
        throw(new \Exception('destination file are exist! use -h for help!'));
    }

    echo "\033[92m source:  " . $options['s'] . "\e[39m\n";
    echo "\033[93m destination:  " . $options['d'] . "\e[39m\n";

    $res = [];
    $datalog = [];
    $file = fopen($file_path_src, "r");
    while (!feof($file)) {
        $res[] = explode(' ', fgets($file));
    }
    fclose($file);
    foreach ($res as $el) {
        $datalog[] = [
            'day' => $el[0] ?? null,
            'time' => $el[1] ?? null,
            'id-message' => $el[2] ?? null,
            'direct' => $el[3] ?? null,
            'info' => $el[4] ?? null,
            'reserv' => $el,
        ];
    }
    $collection = collect($datalog);

    /**
     * info from http://www.exim.org/exim-html-current/doc/html/spec_html/ch-log_files.html
     *  <=      message arrival
     *  (=     message fakereject
     *  =>     normal message delivery
     *  ->     additional address in same delivery
     *  >>     cutthrough message delivery
     *  *>     delivery suppressed by -N
     *  **     delivery failed; address bounced
     *  ==     delivery deferred; temporary problem
     */

    if (FILTER_ADDRESS_SERVER !== '' && FILTER_ADDRESS_FROM !== '') {
        $filtered = $collection->filter(function ($val, $key) {
            if (($val['direct'] === '=>' || $val['direct'] === "Completed\n") && (trim($val['info']) !== FILTER_ADDRESS_FROM && trim($val['info']) !== FILTER_ADDRESS_SERVER)) {
                return true;
            } else {
                return false;
            }
        });
    } else {
        throw(new \Exception('Filter constant not set!'));
    }


    $grouped = $filtered->groupBy('id-message');
    $sorted = $grouped->sortBy('id-message');

    $sorted2 = $sorted->filter(function ($val) {
        return $val->count() >= 2;
    });
    $out = fopen($file_path_dest, 'aw');
    $sorted2->each(function ($item) use ($out) {
        //echo $item->pluck('info')[0];
        //echo "\n";
        echo GREEN_FONT.".".DEFATTR;

        fwrite($out, $item->pluck('info')[0] . "\n");
    });
    echo "\n";
    fclose($out);
    echo "\033[92m count:  " . $sorted2->count() . "\e[39m\n";
}
catch(\Exception $e){
    echo "\033[31m  ERR!  <".$e->getMessage().">\e[39m\n";
    die;
}
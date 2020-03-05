<?php
/**
 * 03-03-2020
 * nikita.s.kalitin@gmail.com
 * Parser for get data about send mails from /var/log/exim4/mainlog
 * type in console php pl.php -s <soruce.file> -d <output.file>
 */
namespace ParserLog;

require_once 'vendor/autoload.php';
require_once 'env.php';
require_once 'constants.php';
use Parsers\EximLogParser;
use Tightenco\Collect;
try {
    $options = getopt("s::d::hv::");

    if(isset($options["h"])){
        echo GREEN_FONT."-s <path to source> -d <path to dst>".DEFATTR."\n";
        echo GREEN_FONT."-v <show version>".DEFATTR."\n";
        echo GREEN_FONT."-h <show help>".DEFATTR."\n";
        die;
    }

    if (isset($options["v"])) {
        echo VIOLET_FONT."version 0.1 03-03-2020 ".DEFATTR."\n";
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

    echo YELLOW_FONT."source:  ".$options['s'].DEFATTR."\n";
    echo YELLOW_FONT."destination:  ".$options['d'].DEFATTR."\n";
    EximLogParser::parse($file_path_src,$file_path_dest,['direct_filters'=>["=>","Completed\n"],'exclude_mails'=>[FILTER_ADDRESS_FROM, FILTER_ADDRESS_SERVER]]);
}
catch(\Exception $e){
    echo RED_FONT."ERR!  <".$e->getMessage().">\e[39m\n";
    die;
}
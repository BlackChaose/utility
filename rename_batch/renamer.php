<?php

//require_once ('vendor/autoload.php');
echo "-s <path to source> -d <path to dst> -t <mask pcre for change name> -o <new name> \n";$options = getopt("s:d:t:o:");

if(empty($options)){
    echo "-s <path to source> -d <path to dst> -t <mask pcre for change name> -o <new name> \n";
    die;
}
if(empty($options["t"])){
    echo "enter input mask please";
    die;
}
if(empty($options["o"])){
    echo "enter output mask please";
}

$input_dir_path = $options["s"]??null;
$output_dir_path = $options["d"]??null;
$input_mask = $options["t"]??null;
$output_mask = $options["o"]??null;

$list_dir = scandir($input_dir_path)??null;

echo "\033[92m source:  ".$options['s']. "\e[39m\n";
echo "\033[93m destination:  ".$options['d']. "\e[39m\n";
print_r($list_dir);
echo "\033[93m tpl: ".$input_mask."  ".$output_mask."\e[39m\n";
$list_dir_rename =[];
foreach($list_dir as $item){
	$list_dir_rename[] = ['name_old'=>$input_dir_path.'/'.$item, 'name_new'=>$output_dir_path.'/'.preg_replace("/".$input_mask."/", $output_mask, $item)];
}
array_shift($list_dir_rename);
array_shift($list_dir_rename);
//print_r($list_dir_rename);
foreach($list_dir_rename as $item){
	rename($item['name_old'],$item['name_new']);
}
print("script are stopped!");

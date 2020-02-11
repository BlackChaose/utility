<?php
/**
 * 
 * copy and rename files from ./src to ./out by regular expressions
 * set $regexp_tpl_old - for old file's names
 * set $regexp_tpl_new - for new file's names (getting from old name)
 * 
 **/
 

$list = scandir('./src');
$matrix = [];
$regexp_tpl_old = '/^ТЗ.*\.pdf$/';
$regexp_tpl_new = '/\d+.\d+/';
$extension = '.pdf';

foreach($list as $name){
	preg_match_all($regexp_tpl_old,$name, $diff);
	if(count($diff[0]) === 1){
		preg_match_all($regexp_tpl_new,$name, $new);
		$matrix[] = ['old'=>$name, 'new'=> $new[0][0].$extension]; // <==
		}
	}
foreach($matrix as $val){
	//rename('./src/'.$val['old'], './out/'.$val['new']);
	copy('./src/'.$val['old'], './out/'.$val['new']);
	print('.');
	}
	
print("\n".'ok!'."\n");

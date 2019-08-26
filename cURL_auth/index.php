<?php
//ini_set("display_errors",1);

require_once 'config.php';

//get main page
$ch = curl_init();
$fp_err = fopen($_SERVER['DOCUMENT_ROOT'].'/verbose_file.txt', 'ab+');
fwrite($fp_err, date('Y-m-d H:i:s')."\n\n"); //add timestamp to the verbose log
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_STDERR, $fp_err);

curl_setopt($ch, CURLOPT_URL, URLOPEN);
curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT']."/cookie_file_cp");
$output = curl_exec($ch);
curl_close($ch);

//parse page for get _csrf token;

$doc = new DOMDocument();
$doc->loadHTML($output);
$infcsrf = $doc->getElementsByTagName('meta');
$token = '';
foreach ($infcsrf as $item) {
   $index=0;
   foreach($item->attributes as $el){
       if ($el->name == "name" && $el->value=="csrf-token"){
           $token = $item->attributes[$index+1]->value;
           break;
       }
       $index++;
   }
   if($token !== '') {
       break;
   }
}

//login & auth

$ch = curl_init();
$fp_err = fopen($_SERVER['DOCUMENT_ROOT'].'/verbose_file2.txt','ab+');
fwrite($fp_err,date('Y-m-d H:i:s')."\n\n");
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_STDERR, $fp_err);

curl_setopt($ch, CURLOPT_URL, URLOPENLOGIN);
curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT']."/cookie_file_cp");
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT']."/cookie_file_cp2");
curl_setopt($ch,CURLOPT_POSTFIELDS, ['_csrf'=>$token, 'LoginForm[email]'=>USERNAME,'LoginForm[password]'=>USERPASSWORD,'LoginForm[uniqueIdPrefix'=>'popup_login','forget'=>'forget']);

$output = curl_exec($ch);
curl_close($ch);

// go to lk

$ch = curl_init();
$fp_err = fopen($_SERVER['DOCUMENT_ROOT'].'/verbose_file3.txt','ab+');
fwrite($fp_err,date('Y-m-d H:i:s')."\n\n");
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_STDERR, $fp_err);

curl_setopt($ch, CURLOPT_URL, URLOPENLK);
curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT']."/cookie_file_cp2");
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT']."/cookie_file_cp2");
$output = curl_exec($ch);
curl_close($ch);

$doc->loadHTML($output);
$items = $doc->getElementsByTagName('div');
$FIO = '';
$photo_src='';
$tmp = [];
foreach ($items as $item) {

    foreach($item->attributes as $el){
        if ($el->name == "class" && $el->value=="account__name"){
            $FIO = $item->nodeValue;
            break;
        }
        if($el->name == "class" && $el->value=="account__photo"){
            $arr = $item->childNodes;
            foreach($arr as $a){
              if($a->nodeName == 'img'){
                 foreach($a->attributes as $attr){
                     if($attr->name=="src"){
                         $photo_src=$attr->value;
                     }
                 }
              }
            }
        }
    }
    if($FIO !== '') {
        break;
    }
}
//fixme: получить фото с помощью cURL
$img=base64_encode(file_get_contents(URLOPENLK.$photo_src));
$src = 'data: '.mime_content_type(URLOPENLK.$photo_src).';base64,'.$img;
//TODO - отобразить отпарсенную таблицу с результатми тестов + отдельную таблицу с рекуомендуемыми учебными ресурсами.
//ATTENTION  see https://docs.mitmproxy.org/stable/tools-mitmdump/
//ATTENTION see https://phpclub.ru/talk/threads/%D0%98%D1%81%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5-curl-%D0%B4%D0%BB%D1%8F-%D0%B0%D0%B2%D1%82%D0%BE%D1%80%D0%B8%D0%B7%D0%B0%D1%86%D0%B8%D0%B8.59542/
//ATTENTION see https://unix.stackexchange.com/questions/103037/what-tool-can-i-use-to-sniff-http-https-traffic/103039
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>info about user</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col text-success">Ф.И.О</div>
            <div class="col"><?= $FIO ?></div>
            <div class="col"><img src="<?= $src ?>"</div>
        </div>
    </div>
<footer class="text-info">
    Moscow, 2019
</footer>
</body>
</html>

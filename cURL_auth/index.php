<?php
error_reporting(E_ERROR);
ini_set("display_errors",1);

require_once 'config.php';
require_once 'libutil.php';


//get main page
$ch = curl_init();
/****** debug request ******/
$fp_err = fopen($_SERVER['DOCUMENT_ROOT'] . '/verbose_file.txt', 'ab+');
fwrite($fp_err, date('Y-m-d H:i:s') . "\n\n"); //add timestamp to the verbose log
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_STDERR, $fp_err);
/****** end of debug request ******/

curl_setopt($ch, CURLOPT_URL, URLOPEN);
curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'] . "/cookie_file_cp");
$output = curl_exec($ch);
curl_close($ch);

//parse page for get _csrf token;

$doc = new DOMDocument();
$doc->loadHTML($output);
$infcsrf = $doc->getElementsByTagName('meta');
$token = '';
foreach ($infcsrf as $item) {
    $index = 0;
    foreach ($item->attributes as $el) {
        if ($el->name == "name" && $el->value == "csrf-token") {
            $token = $item->attributes[$index + 1]->value;
            break;
        }
        $index++;
    }
    if ($token !== '') {
        break;
    }
}

//login & auth

$ch = curl_init();
/****** debug request ******/
$fp_err = fopen($_SERVER['DOCUMENT_ROOT'] . '/verbose_file2.txt', 'ab+');
fwrite($fp_err, date('Y-m-d H:i:s') . "\n\n");
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_STDERR, $fp_err);
/****** end of debug request ******/
curl_setopt($ch, CURLOPT_URL, URLOPENLOGIN);
curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'] . "/cookie_file_cp");
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'] . "/cookie_file_cp2");
curl_setopt($ch, CURLOPT_POSTFIELDS, ['_csrf' => $token, 'LoginForm[email]' => USERNAME, 'LoginForm[password]' => USERPASSWORD, 'LoginForm[uniqueIdPrefix' => 'popup_login', 'forget' => 'forget']);

$output = curl_exec($ch);
curl_close($ch);

// go to lk

$ch = curl_init();
/****** debug request ******/
$fp_err = fopen($_SERVER['DOCUMENT_ROOT'] . '/verbose_file3.txt', 'ab+');
fwrite($fp_err, date('Y-m-d H:i:s') . "\n\n");
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_STDERR, $fp_err);
/****** end of debug request ******/

curl_setopt($ch, CURLOPT_URL, URLOPENLK);
curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'] . "/cookie_file_cp2");
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'] . "/cookie_file_cp2");
$output = curl_exec($ch);
curl_close($ch);
//parse Avatar Photo
$doc->loadHTML($output);
$items = $doc->getElementsByTagName('div');
$FIO = '';
$photo_src = '';
$file_name = 'file.tstname.jpg';
$tmp = [];
foreach ($items as $item) {

    foreach ($item->attributes as $el) {
        if ($el->name == "class" && $el->value == "account__name") {
            $FIO = $item->nodeValue;
            break;
        }
        if ($el->name == "class" && $el->value == "account__photo") {
            $arr = $item->childNodes;
            foreach ($arr as $a) {
                if ($a->nodeName == 'img') {
                    foreach ($a->attributes as $attr) {
                        if ($attr->name == "src") {
                            $photo_src = $attr->value;
                        }
                    }
                }
            }
        }
    }
    if ($FIO !== '') {
        break;
    }
}
//get Avatar Photo
$file_name = [];
preg_match_all('/\w*.(jpg|jpeg|gif|png)$/', $photo_src, $file_name);
$img_path = $_SERVER['DOCUMENT_ROOT'] . "/img/" . $file_name[0][0];
grab_image(URLOPEN . $photo_src, $img_path);
//TODO - отобразить отпарсенную таблицу с результатми тестов + отдельную таблицу с рекомендуемыми учебными ресурсами.

//go to tests
$ch = curl_init();
/****** debug request ******/
$fp_err = fopen($_SERVER['DOCUMENT_ROOT'] . '/verbose_file4.txt', 'ab+');
fwrite($fp_err, date('Y-m-d H:i:s') . "\n\n");
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_STDERR, $fp_err);
/****** end of debug request ******/

curl_setopt($ch, CURLOPT_URL, URLOPENTST);
curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'] . "/cookie_file_cp2");
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'] . "/cookie_file_cp2");
$output = curl_exec($ch);
curl_close($ch);

$doc->loadHTML($output);
$tests = $doc->getElementsByTagName('li');
$testsArr = [];
foreach ($tests as $el) {

    foreach ($el->attributes as $a) {
        if ($a->name == "class" && $a->value == 'skills__item') {
            $testsArr[] = ['name_test' => $el->childNodes[0]->nodeValue, 'rating' => $el->childNodes[2]->nodeValue];

        }
    }
}
// end go to tests;

// go to recomended links
$ch = curl_init();
/****** debug request ******/
$fp_err = fopen($_SERVER['DOCUMENT_ROOT'] . '/verbose_file5.txt', 'ab+');
fwrite($fp_err, date('Y-m-d H:i:s') . "\n\n");
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
curl_setopt($ch, CURLOPT_STDERR, $fp_err);
/****** end of debug request ******/

curl_setopt($ch, CURLOPT_URL, URLOPENTST);
curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'] . "/cookie_file_cp2");
curl_setopt($ch, CURLOPT_COOKIEJAR, $_SERVER['DOCUMENT_ROOT'] . "/cookie_file_cp2");
$output = curl_exec($ch);
curl_close($ch);


$doc->loadHTML($output);
$cards = $doc->getElementsByTagName('div');
$cardsArr = [];
$buf = [];
foreach ($cards as $el) {
    if($el->hasChildNodes() && $el->attributes->item(0)->nodeName == "class" && $el->attributes->item(0)->nodeValue=="action-card__texts"){
        $buf[0]=$el->childNodes->item(1)->nodeValue;
    }

    if($el->hasChildNodes() && $el->attributes->item(0)->nodeName == "class" && $el->attributes->item(0)->nodeValue=="content-zone content-zone_small"){

        foreach($el->childNodes->item(5)->childNodes as $elms){
            if($elms->nodeType != 3) {
                //$buf[1][] = $elms->nodeValue;
                $buf[1][] = $elms->childNodes->item(1)->nodeValue;
            }
        }
    }
    if(count($buf) >= 3){
        $cardsArr[]=$buf;
        $buf=[];
    }
}
// end of to recomended links
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>info about user</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">система сбора данных</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </nav>


    <div class="row justify-content-center mt-5 mb-5">
        <div class="col text-success">Ф.И.О</div>
        <div class="col"><?= $FIO ?></div>
        <div class="col">
            <div class="media">
                <img src="img/<?= $file_name[0][0] ?>" class="align-self-start mr-3" style="max-width: 200px;">
            </div>
        </div>
    </div>

    <div class="row justify-content-center m-2">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Test</th>
                <th scope="col">Rating</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < count($testsArr); $i++): ?>
                <tr>
                    <th scope="row"><?= $i ?></th>
                    <td><?= $testsArr[$i]['name_test'] ?></td>
                    <td><?= $testsArr[$i]['rating'] ?></td>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>
    </div>

    <div class="row justify-content-center m-2">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">theme</th>
                <th scope="col">link</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < count($cardsArr); $i++): ?>
                <tr>
                    <th scope="row"><?= $i ?></th>
                    <td><?= $cardsArr[$i][0] ?></td>
                    <td>
                    <?php foreach($cardsArr[$i][1] as $el): ?>
                    <p><?= $el ?></p>
                    <?php endforeach; ?>
                    </td>

                </tr>
            <?php endfor; ?>
            </tbody>
        </table>
    </div>

    <footer class="text-info m-5 p-5">
        Moscow, 2019
    </footer>
</div>

</body>
</html>

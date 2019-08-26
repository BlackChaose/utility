<!doctype HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>test</title>
</head>
<body>
<?php
error_reporting(E_ERROR);
ini_set("display_errors",1);
$str = '<!doctype html> <html><head><meta charset="UTF-8"></head><body><div class="action-card" data-key="27">
    <div class="action-card__texts">
        <h3 class="action-card__title">Паттерны проектирования и основные подходы к архитектуре систем</h3>
        <ul class="action-card__options">
        </ul>
    </div>
    <span>Тест пройден</span>
    <div class="action-card__accordion js-accordion">
        <div class="action-card__accordion-button js-accordion-button">Рекомендуемое обучение
            <svg>
                <use xlink:href="/assets/176fe47dfb13d80fd2858944a93691a0/themes/default/assets/images/icon.svg#icon_arrow_accordion_small"></use>
            </svg>
        </div>
        <div class="action-card__accordion-content js-accordion-content">
            <div class="action-card__accordion-wrapper">
                <div class="content-zone content-zone_small">
                    <h3>Рекомендации:</h3>
                    <h3>Онлайн-курсы:</h3>
                    <ul>
                        <li>ООП и паттерны проектирования в Python: <a
                                href="https://ru.coursera.org/learn/oop-patterns-python">https://ru.coursera.org/learn/oop-patterns-python</a>
                        </li>
                        <li>Паттерны проектирования: <a href="https://refactoring.guru/ru/design-patterns">https://refactoring.guru/ru/design-patterns</a>
                        </li>
                    </ul>
                    <h3>Литература:</h3>
                    <ul>
                        <li>Архитектура корпоративных программных приложений, <span>Мартин Фаулер.</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div></body></html>';

$doc = new DOMDocument();
$doc->loadHTML($str);
$cards = $doc->getElementsByTagName('div');
$cardsArr = [];
echo "<pre>";
$buf = [];
foreach ($cards as $el) {


    if($el->hasChildNodes() && $el->attributes->item(0)->nodeName == "class" && $el->attributes->item(0)->nodeValue=="action-card__texts"){
        $buf[0]=$el->childNodes->item(1)->nodeValue;
    }

    if($el->hasChildNodes() && $el->attributes->item(0)->nodeName == "class" && $el->attributes->item(0)->nodeValue=="content-zone content-zone_small"){

        foreach($el->childNodes->item(5)->childNodes as $elms){
            if($elms->nodeType != 3) {
                $buf[1][] = $elms->nodeValue;
//                $buf[1][]['type']=$elms->nodeType;\
                $buf[2][] = $elms->childNodes->item(1)->nodeValue;
            }
        }
    }
    if(count($buf) >= 2){
        $cardsArr[]=$buf;
        $buf=[];
    }
}
print_r($cardsArr);
echo "</pre>";
?>
</body>
</html>
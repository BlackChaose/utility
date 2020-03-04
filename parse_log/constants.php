<?php
/**
 * User: nikita.s.kalitin@gmail.com
 * Date: 04.03.20
 * Time: 10:00
 */
//FOR CONSOLE
define("ESC","\033");
define("CLEAR", ESC."[2J");
define("HOME", ESC."[0;0f");
//font colors
define("DEFATTR",ESC."[0m"); 				//	  все атрибуты по умолчанию
define("BOLD_FONT",ESC."[1m"); 				//	  жирный шрифт (интенсивный цвет)
define("DARK_GRAY_FONT",ESC."[2m");		    //	  полу яркий цвет (тёмно-серый, независимо от цвета)
define("UNDERLINED_FONT",ESC."[4m");		    //	  подчеркивание
define("BLINKED_FONT",ESC."[5m");			//	  мигающий
define("REVERSE_MODE",ESC."[7m");			//    реверсия (знаки приобретают цвет фона, а фон -- цвет знаков)
define("NORM_INTENSE",ESC."[22m");			//    установить нормальную интенсивность
define("DISABLE_UNDERLINE",ESC."[24m");   	//	  отменить подчеркивание
define("DISABLE_BLINK",ESC."[25m");			//	  отменить мигание
define("DISABLE_REVERSE",ESC."[27m");		//    отменить реверсию
define("BLACK_FONT",ESC."[30m");				//    чёрный цвет знаков
define("RED_FONT",ESC."[31m");				//    красный цвет знаков
define("GREEN_FONT",ESC."[32m");				//    зелёный цвет знаков
define("YELLOW_FONT",ESC."[33m");				//    желтый цвет знаков
define("BLUE_FONT",ESC."[34m");				//    синий цвет знаков
define("VIOLET_FONT",ESC."[35m");				//    фиолетовый цвет знаков
define("SEA_FONT",ESC."[36m");				//    цвет морской волны знаков
define("GRAY_FONT",ESC."[37m");				//    серый цвет знаков
//background colors
define("BLACK_BG",ESC."[40");    			//	  чёрный цвет фона
define("RED_BG",ESC."[41");					//    красный цвет фона
define("GREEN_BG",ESC."[42");				//    зелёный цвет фона
define("YELLOW_BG",ESC."[43");				//    желтый цвет фона
define("BLUE_BG",ESC."[44");					//    синий цвет фона
define("VIOLET_BG",ESC."[45");				//    фиолетовый цвет фона
define("SEA_BG",ESC."[46");    				//    Цвет морской волны фона
define("GRAY_BG",ESC."[47");					//    серый цвет фона
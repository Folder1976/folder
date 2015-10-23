<?php

// Convert digital Russian currency representation
// (Russian rubles and copecks) to the verbal one
// Copyright 2008 Sergey Kurakin
// Licensed under LGPL version 3 or later

define('M2S_KOPS_DIGITS', 0x01); // digital copecks
define('M2S_KOPS_MANDATORY', 0x02); // mandatory copecks
define('M2S_KOPS_SHORT', 0x04); // shorten copecks

function money2str_ru($money, $options = 0) {

$money = preg_replace('/[\,\-\=]/', '.', $money);

$numbers_m = array('', 'один', 'два', 'три', 'чотири', 'п\'ять', 'шiсть', 'сiм',
'вiсiм', 'дев\'ять', 'десять', 'одинадцять', 'дванадцять', 'тринадцять',
'чотирнадцять', 'п\'ятнадцять', 'шiстнадцять', 'сiмнадцять', 'вiсiмнадцять',
'дев\'ятнадцять', 'двадцять', 30 => 'тридцять', 40 => 'сорок', 50 => 'п\'ятдесят',
60 => 'шiстдесят', 70 => 'сiмдесят', 80 => 'вiсiмдесят', 90 => 'дев\'яносто',
100 => 'сто', 200 => 'двiстi', 300 => 'триста', 400 => 'чотириста',
500 => 'п\'ятсот', 600 => 'шiстсот', 700 => 'сiмсот', 800 => 'вiсiмсот',
900 => 'дев\'ятсот');
 
$numbers_f = array('', 'одна', 'двi');
 
$units_ru = array(
(($options & M2S_KOPS_SHORT)
? array('коп.', 'коп.', 'коп.')
: array('копiйка', 'копiйки', 'копiйок')),
array('грн', 'грн', 'грн'),
array('тисяча', 'тисячi', 'тисяч'),
array('мiльон', 'мiльона', 'мiльонiв'),
array('миллиард', 'миллиарда', 'миллиардов'),
array('триллион', 'триллиона', 'триллионов'),
);
 
$ret = '';
 
// enumerating digit groups from left to right, from trillions to copecks
// $i == 0 means we deal with copecks, $i == 1 for roubles,
// $i == 2 for thousands etc.
for ($i = sizeof($units_ru) - 1; $i >= 0; $i--) {
 
// each group contais 3 digits, except copecks, containing of 2 digits
$grp = ($i != 0) ? dec_digits_group($money, $i - 1, 3) :
dec_digits_group($money, -1, 2);
 
// process the group if not empty
if ($grp != 0) {
 
// digital copecks
if ($i == 0 && ($options & M2S_KOPS_DIGITS)) {
$ret .= sprintf('%02d', $grp). ' ';
$dig = $grp;
 
// the main case
} else for ($j = 2; $j >= 0; $j--) {
$dig = dec_digits_group($grp, $j);
if ($dig != 0) {
 
// 10 to 19 is a special case
if ($j == 1 && $dig == 1) {
$dig = dec_digits_group($grp, 0, 2);
$ret .= $numbers_m[$dig]. ' ';
break;
}
 
// thousands and copecks are Feminine gender in Russian
elseif (($i == 2 || $i == 0) && $j == 0 && ($dig == 1 || $dig == 2))
$ret .= $numbers_f[$dig]. ' ';
 
// the main case
else $ret .= $numbers_m[(int) ($dig * pow(10, $j))]. ' ';
}
}
$ret .= $units_ru[$i][sk_plural_form($dig)]. ' ';
}
 
// roubles should be named in case of empty roubles group too
elseif ($i == 1 && $ret != '')
$ret .= $units_ru[1][2]. ' ';
 
// mandatory copecks
elseif ($i == 0 && ($options & M2S_KOPS_MANDATORY))
$ret .= (($options & M2S_KOPS_DIGITS) ? '00' : 'нуль').
' '. $units_ru[0][2];
}
 
return trim($ret);
}
 
// service function to select the group of digits
function dec_digits_group($number, $power, $digits = 1) {
return (int) bcmod(bcdiv($number, bcpow(10, $power * $digits, 8)),
bcpow(10, $digits, 8));
}
 
// service function to get plural form for the number
function sk_plural_form($d) {
$d = $d % 100;
if ($d > 20) $d = $d % 10;
if ($d == 1) return 0;
elseif ($d > 0 && $d < 5) return 1;
else return 2;
}
 
?>
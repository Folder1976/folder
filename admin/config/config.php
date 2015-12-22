<?php

date_default_timezone_set('Europe/moscow');

/* Настройки подключения к БД */
$dbhost = "localhost";
$dbbase = 'armma_db';
$dbuser = "root";
$dbpasswd = "sturm2015";

define("DB_HOST", $dbhost);
define("DB_USER", $dbuser);
define("DB_PASS", $dbpasswd);
define("DB_PREFIX", "tbl_");
define("BASE",$dbbase,true);

define("HOST_URL", "http://armma.ru");

$_SESSION[BASE.'base']=BASE;

error_reporting(E_ALL ^ E_DEPRECATED);

//Подписи и заголовки
define("MAIN_TITLE","Armma");

//Полный адресс сайта
define("MY_URL","armma.ru");
define("MY_EMAIL","email: mail@armma.ru");

//Новое соединение с базой
$folder= mysqli_connect(DB_HOST,DB_USER,DB_PASS,BASE) or die("Error " . mysqli_error($folder)); 
mysqli_set_charset($folder,"utf8");

//Каталог для загрузки файлов
define('UPLOAD_DIR','/var/www/armma.ru/resources/products/');

//Настройки из сетапа
$setup = array(
                'email' => 'mail@armma.ru',
                'email name' => 'Armma',
                'email login' => 'mail@armma.ru',
                'email pass' => 'armma_mail',
                'email port' => '25',
                'email sms login' => '+380**********',
                'email sms name' => 'Armma',
                'email sms pass' => '*****',
                'email sms web' => '@mail.alp***hasms.com.ua',
                'email smtp' => 'mail.nic.ru',
                'klient id pref' => '30011976',
                'load patch' => 'localhost/armma.ru/resources/',
                'price default lang' => '1',
                'price default price' => '2',
                'print default lang' => '1',
                'set oper status after money' => '3',
                'shop default price' => '2',
                'shop default status' => '18',
                'shop money code' => '9874',
                'tovar artikl-size sep' => '#',
                'tovar name sep' => '||',
                'tovar photo patch' => '/armma.ru/docs/resources/products/',
                'web default price' => '2',
                'automail tmp' => 'Privatbank',
                'automail nakl' => 'print'
               );

$curr_name = array(1 => 'руб.',
                   2 => 'usd.',
                   3 => 'euro.',
                   4 => 'zl.',
                   );

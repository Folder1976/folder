<?php

date_default_timezone_set('Europe/Kiev');

/* Настройки подключения к БД */
$dbhost = "localhost";
$dbuser = "folder";
$dbpasswd = "sturm2015";


define("HOST_URL", "http://folder.com.ua");
define("HOST_URL_ST", "folder.com.ua");

define("DB_HOST", $dbhost);
define("DB_USER", $dbuser);
define("DB_PASS", $dbpasswd);
define("DB_PREFIX", "tbl_");
define('BASE','FOLDER');

$_SESSION[BASE.'base']=BASE;

error_reporting(E_ALL ^ E_DEPRECATED);

//Подписи и заголовки
define("MAIN_TITLE","Фолдер");

//Полный адресс сайта
define('MY_URL','folder.com.ua');
define('MY_EMAIL','email: folder.list@gmail.com');
define('MY_PHONE','<br>тел: +38 097 169 51 29');

//Показывать продукты с нулевым остатков
define('VIEW_EMPTY_PRODUCT',true);

//Настройки из сетапа
$setup = array(
                'email' => 'foldersergey@gmail.com',
                'email name' => 'Folder',
                'email login' => 'foldersergey@gmail.com',
                'email pass' => 'ckjyjgjnfv2015',
                'email port' => '465',
                'email sms login' => '+380672586999',
                'email sms name' => 'Folder',
                'email sms pass' => '93tknmye',
                'email sms web' => '@mail.alphasms.com.ua',
                'email smtp' => 'ssl://smtp.gmail.com',
                'klient id pref' => '30011976',
                'load patch' => 'folder.com.ua/resources/',
                'price default lang' => '1',
                'price default price' => '2',
                'print default lang' => '1',
                'set oper status after money' => '3',
                'shop default price' => '2',
                'shop default status' => '18',
                'shop money code' => '9874',
                'tovar artikl-size sep' => '/',
                'tovar name sep' => '||',
                'tovar photo patch' => 'folder.com.ua/resources/products/',
                'web default price' => '2',
                'automail tmp' => 'Privatbank',
                'automail nakl' => 'print'
               );


//Названия валют - чтоб постояннов базу не летать за ними
$curr_name = array(1 => 'грн.',
                   2 => 'usd.',
                   3 => 'euro.',
                   4 => 'zl.',
                   );


//Новое соединение с базой
$folder = mysqli_connect(DB_HOST,DB_USER,DB_PASS,BASE) or die("Error " . mysqli_error($folder)); 
mysqli_set_charset($folder,"utf8");


?>
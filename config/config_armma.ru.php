<?php

date_default_timezone_set('Europe/moscow');
/* Настройки подключения к БД */
$dbhost = "armma.mysql";
$dbbase = 'armma_db';
$dbuser = "armma_sql";
$dbpasswd = "4FhAS+EQ";


define("HOST_URL", "http://armma.ru");
define("HOST_URL_ST", "armma.ru");

define('SKIN_URL','http://armma.ru/skin/');
define('SKIN_PATH','/home/armma/armma.ru/docs/skin/');

define("DB_HOST", $dbhost);
define("DB_USER", $dbuser);
define("DB_PASS", $dbpasswd);
define("DB_PREFIX", "tbl_");
define('BASE',$dbbase);

$_SESSION[BASE.'base']=BASE;

error_reporting(E_ALL ^ E_DEPRECATED);

//Подписи и заголовки
define("MAIN_TITLE","Armma");

//Полный адресс сайта
define('MY_URL','armma.ru');
define('MY_EMAIL','email: mail@armma.ru');
define('MY_PHONE','<br>тел: 8 (989) 520-34-49');

//Показывать продукты с нулевым остатков
define('VIEW_EMPTY_PRODUCT',true);

//Пароль для новых пользователей по дефолту
define('DEFAULT_USER_PASS', 'L2jg#hf%u4y3');

//Настройки из сетапа
$setup = array(
                'email' => 'foldersergey@gmail.com',
                'email name' => 'Armma',
                'email login' => 'foldersergey@gmail.com',
                'email pass' => 'ckjyjgjnfv2015',
                'email port' => '465',
                'email sms login' => '+79895203449*',
                'email sms name' => 'Armma',
                'email sms pass' => '*****',
                'email sms web' => '@mail.alp***hasms.com.ua',
                'email smtp' => 'ssl://smtp.gmail.com',
                'klient id pref' => '30011976',
                'load patch' => '/armma.ru/docs/resources/',
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


//Названия валют - чтоб постояннов базу не летать за ними
$curr_name = array(1 => 'руб.',
                   2 => 'usd.',
                   3 => 'euro.',
                   4 => 'zl.',
                   );


//Новое соединение с базой
$folder = mysqli_connect(DB_HOST,DB_USER,DB_PASS,BASE) or die("Error " . mysqli_error($folder)); 
mysqli_set_charset($folder,"utf8");

$Month_r = array( 
    "01" => "Январь", 
    "02" => "Февраль", 
    "03" => "Март", 
    "04" => "Апрель", 
    "05" => "Май", 
    "06" => "Июнь", 
    "07" => "Июль", 
    "08" => "Август", 
    "09" => "Сентябрь", 
    "10" => "Октябрь", 
    "11" => "Ноябрь", 
    "12" => "Декабрь"); 

    $adapterConfigs = array(
    'vk' => array(
        'client_id'     => '3774741',
        'client_secret' => '3nLWEs45iWeKypmVR2CU',
        'redirect_uri'  => 'http://localhost/auth/?provider=vk'
    ),
    'odnoklassniki' => array(
        'client_id'     => '168635560',
        'client_secret' => 'C342554C028C0A76605C7C0F',
        'redirect_uri'  => 'http://localhost/auth?provider=odnoklassniki',
        'public_key'    => 'CBADCBMKABABABABA'
    ),
    'mailru' => array(
        'client_id'     => '770076',
        'client_secret' => '5b8f8906167229feccd2a7320dd6e140',
        'redirect_uri'  => 'http://localhost/auth/?provider=mailru'
    ),
    'yandex' => array(
        'client_id'     => 'bfbff04a6cb60395ca05ef38be0a86cf',
        'client_secret' => '219ba8388d6e6af7abe4b4b119cbee48',
        'redirect_uri'  => 'http://localhost/auth/?provider=yandex'
    ),
    'google' => array(
        'client_id'     => '333193735318.apps.googleusercontent.com',
        'client_secret' => 'lZB3aW8gDjIEUG8I6WVcidt5',
        'redirect_uri'  => 'http://localhost/auth?provider=google'
    ),
    'facebook' => array(
        'client_id'     => '474854576027946',
        'client_secret' => 'ff561a9bbc42207bdaf4f9e90fe26721',
        'redirect_uri'  => HOST_URL.'/registration?provider=facebook'
    )
);
?>
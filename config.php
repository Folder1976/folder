<?php
/* Основные настройки системы */

/* Настройки подключения к БД */
$shhost="localhost"; //хост
$shuser="order_user"; //User
$shpass="mUJs2oQV"; //Пароль
$shname="orders_utf8"; //Имя БД

$ppt = "dostvk_"; //общепроектный префикс

$citename = "Доставки по Москве"; //Название сайта в админке
define("ADMINICONSINLINE", 6); //Количество иконок на 1 линии в админке

/* Путь к корню сайта */

$SPUrl = "/ssd/www/altakarter/data/www/alta-karter.ru/orders/";
$SRVUrl = "http://alta-karter.ru/orders/";
$SRVAdm = "";
$SRVIMG = "/ssd/www/altakarter/data/www/alta-karter.ru/image/";
$SRVMAN = '/ssd/www/altakarter/data/www/alta-karter.ru/manuals/';
$SCPUrl = "/ssd/www/altakarter/data/www/dep_alta-karter.ru/";
$SharedFLD = "/var/www/altakarter/data/www/dep_thesystem/shared/";
$SRVServiceScriptPath = "/var/www/altakarter/data/www/dep_services/current/";

define("ADMINADDR", "potolok@gmail.com"); //Адрес админа, для скрытой копии заказов курьерам
date_default_timezone_set('Europe/Moscow');

$WeekDays = array(
	"0" => "Понедельник",
	"1" => "Вторник",
	"2" => "Среда",
	"3" => "Четверг",
	"4" => "Пятница",
	"5" => "Суббота",
	"6" => "Воскресенье",
);

$WeekDaysPh = array(
	"0" => "Воскресенье",
	"1" => "Понедельник",
	"2" => "Вторник",
	"3" => "Среда",
	"4" => "Четверг",
	"5" => "Пятница",
	"6" => "Суббота",

);

$WeekDaysSM = array(
	"0" => "Вс",
	"1" => "Пн",
	"2" => "Вт",
	"3" => "Ср",
	"4" => "Чт",
	"5" => "Пт",
	"6" => "Сб",
);


$RashType = array(
	"1" => "Накладная",
	"2" => "Внес в кассу поставщика",
	"3" => "Отдал на руки",
	"4" => "Перевод в банке",
	"5" => "Зарплата",
	"6" => "Обнуление",
	"7" => "Продажа по безналу",
	"8" => "Возврат",
	"55" => "Прочее",
);

$ClitntOplatType = array(
	"1" => "Накладная",
	"2" => "Внес в кассу поставщика",
	"3" => "Отдал на руки",
	"4" => "Перевод в банке",
	"5" => "Зарплата",
	"6" => "Обнуление",
	"7" => "Продажа по безналу",
	"8" => "Возврат",
	"55" => "Прочее",
);

$SkladNames = array(
	"1" => "ТРИАВС",
	"2" => "ТРИО",
	"3" => "ЛЮКС",
	"4" => "ПРОТЕКС",
	"5" => "ЛИДЕР",
	"6" => "КРУЗ",
	"7" => "МЕТАЛЛ",
);

//Для распознавания при импорте
$SkladCodes = array(
	"trio" => "2",
	"sherif" => "1",
	"sheerif" => "1",
	"abc" => "1",
	"atlant" => "1",
	"npl" => "1",
	"nabor" => "1",
	"egr" => "1",
	"sim" => "1",
	"lux" => "3",
	"prot" => "4",
);

$RashStatus = array(
	"0" => "Новый",
	"1" => "Подтвержден"
);

define("MAINIMGCAT", "imgs/"); //путь к картинкам
define("MAINFILESCAT", "files/"); //путь к файлам для скачивания, основная папка

// LETTER COMPILATION CONFIGURATION START
define("SRVMAILADDR", "info@alta-karter.ru"); // обратный адрес  //info@alta-karter.ru
define("SRVMAILPODPIS", "Альта-Картер"); //Название сайта, можно по-русски
// LETTER COMPILATION CONFIGURATION END

//Для системы административного доступа
define("ADMUSERPASSWLEIGHT", 10); //Количество знаков в пароле пользователя (админа) - для генерации

$UserGroups = array(
	"1" => "Курьер",
	"2" => "Оператор",
	"3" => "Админ",
	"4" => "Супер оператор"
);

$ZakStatuses = array(
	"1" => "Доставлен",
	"2" => "Отмена",
	"3" => "Закуп, Отмена",
	"4" => "Закуп, Дост, Отмена"
);

$ZakPoluch = array(
	"1" => "По почте",
	"2" => "По телефону",
);

$DostVremya = array(
	"1" => array("Днем", "14-18"),
	"2" => array("Вечером", "21-23")
);

define('MAGAZSQLPREFIX', '');
define("MAGAZSQLHOST", "localhost"); //Хост БД магазина
define("MAGAZSQLUSER", "alta-user"); //Пользователь БД магазина
define("MAGAZSQLPASS", "TWsDHuGZ"); //Пароль БД магазина
define("MAGAZSQLBASE", "alta_m"); //База магазина
//define("MAGAZSQLBASE", "foruminv_test1"); //База магазина
define("MAGAZPRODUCTSTABLE", "product"); //Таблица с продуктами магазина

define("MAGAZARTIKULFIELD", "model"); //поле, где артикул магаза из таблицы MAGAZPRODUCTSTABLE
define("MAGAZTOVNAZVAN", ""); //поле, где название товара из таблицы MAGAZPRODUCTSTABLE
define("MAGAZTOVPRICE", "price"); //поле, где цена товара из таблицы MAGAZPRODUCTSTABLE
define("MAGAZTOVIDZAP", "product_id"); //поле, где ID товара из таблицы MAGAZPRODUCTSTABLE

define("ADMINID", 1); //ID Админа, которое блокирует изменения расходов


//Для артикула поставщика
define("MAGAZPRODUCTCHARS", "SS_product_options_values"); //Таблица с характеристиками
define("MAGAZDEFAULTCHARID", "14"); //ID характеристики в таблице характеристик магазина по которой будем искать
define("MAGAZCHARIDFIELD", "productID"); //ID товара в таблице характеристик магазина по которой будем искать

//Обработчики заказов по МСК
define("OBRZAKAZNIJNGRAN", 650); //Нижняя граница прибыли по заказу для измениния курьерской комисмии
define("OBRZAKAZLOWPROFITKURIER", 150); //Величина курьерской комиссии в случае низкой прибыли
define("KURIERVOZNAGFORSKLAD", 100); //Вознаграждение за заезд на склад
define("KURIERDEFAULTKOMISS", 250); //Величина курьерской комиссии по умолчанию


// Page maker class
define("PAGENUMBERHTML", "[&nbsp;%PAGENUMBER%&nbsp;]"); //HTML код номера страницы
define("PAGEBLOCKHTML", "<div class=\"pager\">Страница: %PAGENUMBERS%</div>"); //HTML код блока страниц
define("USEBLOCKSINPAGEBLOCK", 1); //Использовать создание блочное разделение номеров страниц
define("BLOCKOFVISIBLEPAGES", 7);//Количество страниц в блоке - проверить наличие!
define("BLOCKTOTALHTML", "<div class=\"pager\">Страницы: %BLOCKCODE%</div>"); //HTML код всего блока
define("BLOCKFIRSTPAGEHTML", "<a href=\"%FIRSTPAGEURL%\"><<</A>"); //HTML код блока 1 страницы
define("BLOCKLASTPAGEHTML", "<a href=\"%LASTPAGEURL%\">>></A>"); //HTML код блока последней страницы
define("BLOCKNEXTBLOCKHTML", "<a href=\"%NEXTBLOCK%\">></a>"); //HTML код следующего блока
define("BLOCKPREVBLOCKHTML", "<a href=\"%PREVBLOCK%\"><</a>"); //HTML код предыдущего блока
//End of page maker class
//Для системы авторизации-регистрации
define("USEUSERAUTH", 1); //Включение пользовательской авторизации
define("ALLUSERPASSWLEIGHT", 8); //Количество знаков в пароле пользователя (рядового) - для генерации
define("COOKIEPREFIX", "ScTDU_"); //префикс куки
define("COOKIE_SALT_CHECK", "Hc8xPd3sM5"); //добавка для верификации пароля
define("ACTIVEISLOGIN", 1); //Использовать авторизацию на сайте для обновления даты активности
define("NONACTIVEPERIOD", "-2 years"); //Время неактивности пользователя на сайте, для функции strtotime
define("NONACTIVEREGISTR", "-6 months"); //Время за которое необходимо активировать аккаунт на сайте, для функции strtotime
define("AUTH_SALT_CHECK", "m2SXu*&dWLws0"); //добавка для верификации пароля


//QIWI Params
$QiwiStatus = array(
	"0" => "Не обработано",
	"1" => "Обработано",
	"2" => "Выставлен счет",
	"3" => "Оплачено",
	"4" => "Отмена",
);
$QiwiRetStat = array(
	"50" => "2",
	"60" => "3",
	"150" => "4",
	"151" => "4",
	"160" => "4",
	"161" => "4"
);

define("PEKLOGIN", "altakarter"); //Логин в систме pecom
define("PEKKEY", "AA3CAB2D4E082392C60E17E74653D388753C9B69");  //API KEY в систме pecom
define("SENDERINN", "7733774382"); //ИНН отправителя
define("SENDERCITY", "Москва Запад"); //город отправителя
define("SENDERNAZV", 'АЛЬТА-КАРТЕР'); //название отправителя
define("SENDERPHONE", "+74993220441"); //телефон отправителя 79191131053

define("LOCALSENDCLASS", 1);//Локальная отправка

$TKStatus = array(
	"0" => "не обработано",
	"1" => "принят",
	"2" => "оформлен",
	"3" => "в пути",
	"4" => "прибыл",
	"5" => "выдан",
);

define("SENDOTLADK", 0); //Режим отладки, 1 - письма отправляются на адрес SRVMAILADDR, в БД флаги не меняются, 0 - рабочий режим
$LetterCodes = array(
	"@ZAKAZNUMBER@" => "ZakazNumMagaz",
	"@ZAKAZDATE@" => "ZKDATE",
	"@PEKDOSTPRICE@" => "UslugPrice",
	"@ARRIVALDATE@" => "ArrivalDate",
	"@PEKIDCODE@" => "PEK_ID",
);


$ZakDelOsonv = array(
	"1" => "Нет товара",
	"2" => "Условия не устроили",
	"3" => "Клиент отменил",
	"4" => "Иное",
);

$DocAlign = array(
	"1" => array("По левому", 'left'),
	"2" => array("По правому", 'right'),
	"3" => array("По центру", 'center')
);

$AllTaskStatus = array(
	"0" => "Новый",
	"1" => "В работе",
	"2" => "Исполнен",
);

$VozvratStatus = array(
	"0" => "Новый возврат",
	"10" => "Отправлено завление",
	"20" => "Товар вернулся",
	"40" => "Оплачен счет",
	"50" => "Отправлен курьер",
	"100" => "Завершен",
);

$VozvratTovarStatus = array(
	"1" => "У курьера",
	"2" => "На нашем сервисе",
	"3" => "Сдан поставщику",
);

$VozvratDisableST = 100;

$VozvratDelOsonv = array(
	"1" => "ОтказВозврата1",
	"2" => "ОтказВозврата2",
	"3" => "ОтказВозврата3",
	"4" => "Иное",
);

$VozvratGruzStatus = array(
	"0" => " --------- ",
	"1" => "В регионе",
	"2" => "В Москве",
);

$TovType = array(
	"0" => "Simple",
	"1" => "Composite",
	"2" => "Element",
	"3" => "Special",
);

define("COMPOSITEPOSAVSHID", 9); //ID поставщика составных товаров

define("PEKOTLADK", 0); //Отладка общения с ПЭК

$RasshKurierSp = array(36, 60, 61, 64, 76, 75, 81, 82, 13); //Перечень ID курьеров, по которым строить отчет в модуле Расчеты

$RashReg = array(
	"1" => "Москва",
	"2" => "Регионы",
);

$RashAccAsRT = array(
'1' => TRUE,
'24' => TRUE,
'5' => TRUE,
'30' => TRUE,
'34' => TRUE,
'63' => TRUE,
'68' => TRUE,
'70' => TRUE,
'37' => TRUE,

);

$PerevozStatus = array(
	"0" => "Закупка и продажа",
	"1" => "Только закупка",
	"2" => "Только продажа"
);

$VeryfyStatus = array(
	"0" => "Новый",
	"1" => "Проверен",
	"2" => "В рассчет",
);

$VozvratMSKSt = array(
	"1" => "У нас",
	"2" => "Сдан поставщику"
);

define("ADDTOCATEGWITHCHILDREN", "1"); //возможность добавлять товары в категорию с потомками
define("INSTRFILEBLOCKCODE", "instructfileblock"); //будет в формате <INSTRFILEBLOCKCODE>
define("INSTRFILELINKHTML", '<br><a style="font-size:18px; color: #F00;" href="http://cdn.alta-karter.ru/@FILEURL@"  target="_blank">Инструкция по установке</a>'); //Текст ссылки для файла, только 1 параметр - @FILEURL@ - ссылка на файл

define("TIMETOSELECT", 3600); //время, в течении которого курьер может выбрать себе заказ - 1 час
define("BLOCKLINKPREFIX", "tovcharsblock"); //Как будет называться тег, обрамляющий блок, окончательный вид: <BLOCKLINKPREFIX> ... </BLOCKLINKPREFIX>
define("REZULTHTMLBLOCK", "<p><table cellspacing=0 cellpadding=3 border=0>@CHARSBLOCKHTML@</table></p>"); //HTML код всего блока
define("REZULTHTMLLINE", " <tr><td class=\"har1\">@CHARNAME@</td><td class=\"har2\">@CHARVALUE@</td></tr>"); //HTML код конкретной характеристики

define("BLOCKOTHERLINKPREFIX", "tovlinksblock"); //Как будет называться тег, обрамляющий блок ссылок, окончательный вид: <BLOCKLINKPREFIX> ... </BLOCKLINKPREFIX>

define("REZULTHTMLBLOCKOTHLINK", "</p><a href=\"#\" style=\"font-size:18px\" onclick=\"return hs.htmlExpand(this, {width: 300, headingText: LnkTxt})\">Найти все товары для этого авто</a><div class=\"highslide-maincontent\"><ul class=\"othertovarsspis\">@LINKBLOCKHTML@</ul></div>"); //HTML код всего блока
define("REZULTHTMLLINEOTHERLINK", "<li><a href=\"@LINKURL@\"><strong>@LINKTEXT@</strong></a></li>"); //HTML код конкретной ссылки

//Типы оплаты для товаров в МСК заказах
$MosZakazPayType = array(
	"1" => "Нал",
	"2" => "Безнал",
	"3" => "Без опл"
);

$NalojRegPlatID = 1; //ID оплаты для наложенного платежа

$MskChangeLimitsOper = array( //список пользователей которым можно менять корзину и курьера в МСК
	"30" => TRUE,
	"1" => TRUE,
	"34" => TRUE,
	"51" => TRUE,
	"63" => TRUE,
	"68" => TRUE,
	"70" => TRUE,
	"24" => TRUE,
);

$MskVozvrAccess = array(//Список пользователей которым разрешены возвраты МСК
	"30" => TRUE,
	"1" => TRUE,
	"34" => TRUE,
	"51" => TRUE,
	"63" => TRUE,
	"68" => TRUE,
);

$RegUnLimitChanges = array( //Список пользователей, которым можно редактировть и удалять 
	"1" => TRUE,
	"30" => TRUE,
	"34" => TRUE,
);
$RegVozvUnLimitChanges = array(
	"1" => TRUE,
	"30" => TRUE,
	"34" => TRUE,
);
define("REGLIMITDATE", "-2 day"); //степень старости рег заказов после чего редактировать низзя
define("CHECKGEOFIELD", "HTTP_X_REAL_IP"); //имя поля, по которому искать регион клиента сайта

$TotalSogl = array( //общее согласие/несогласие
	"0" => "Нет",
	"1" => "Да"
);

//Параметры для подключения к POP3 для проверки E-Kit
$popMailParams = array(
	"login" => "ekit@alta-karter.ru",
	"passw" => "werwer",
);

$paypalConfig = array(
    'debug' => false,
    'admin' => ADMINADDR,
    'cacheDir' => '/ssd/www/altakarter/data/www/alta-karter.ru/temp',
    'client_id'     => 'Ab55VxAxZcj9oojfn6tIXXpOZq3CME4bGJjPHZO9BLy9ohMBkWQghA1P_OW9',
    'client_secret' => 'EDVb4RC_d3kqnbtM7c1kALCHwxP6xU_mnUvcqksSyR1Drz_RJqvExGJmV5CS',
    'merchant_info' => array(
        'email'         => 'info@alta-karter.ru',
        'first_name'    => 'Олег',
        'last_name'     => 'Крутиков',
        'address'       => array(
            'line1'        => 'Врачебный проезд, 10, оф. 1',
            'city'         => 'Москва',
            'country_code' => 'RU',
            'postal_code'  => '125367',
            'state'        => 'Московская обл.'
        ),
        'business_name' => 'ООО Альта-Картер',
        /* switched off until bug will be fixed on paypal's side */
        // 'phone' => array(
            // 'country_code'    => '7',
            // 'national_number' => '4952150245'
        // ),
        'additional_info'  => 'тел.: +7 (495) 215-02-45'
    ),
    'logo' => 'https://alta-market.ru/image/data/logo.jpg'
);

define('UNICODE_NOT_SUPPORTED', true);

define("WAREHOUSEDEF", 1);//Склад, пока он один

// Mem Cache
define('MEMCACHE_HOSTNAME', 'localhost');
define('MEMCACHE_PORT', '11211');
define('MEMCACHE_PREFIX', 'alta-karter-opencart_');

// Store language settings
define('STORE_LANGUAGE_ID', 1);

// Dev mode
define('DEV_MODE', false);
define('DEV_EMAILS_PATH', $SPUrl . 'dev_emails');

// Transamerican import mode
define('IMPORT_MODE_TRANSAMERICAN_IS_LOCAL', 1);

//Константы типов операций транзакций
define('KURIER2KURIER_TYPE', 58);
define('GET_KURIER2KURIER_TYPE', 59);
define('NAKLADNAYA_TYPE', 1);
define('RETURN_TYPE', 63);

$TovForTKChars = array(
	"weight" => 1, //кг
	"length" => 10, //см
	"width" => 10, //см
	"height" => 10, //см
);
?>

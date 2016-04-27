<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../config/config.php';
set_time_limit(1000);

$YmlFile = "../../armma.yml";

$MrKYML = '	</offers>
</shop>
</yml_catalog>';
$FH = fopen($YmlFile, 'a');
fwrite($FH, $MrKYML); //Yandex
fclose($FH);

echo "<li>Завершена генерация файла для Яндекс.Маркет (armma.yml)</li>
";

?>
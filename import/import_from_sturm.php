<?php
header("Content-Type: text/html; charset=UTF-8");

include('../config/config.php');

$STURM= mysqli_connect(DB_HOST,DB_USER,DB_PASS,'STURM') or die("Error " . mysqli_error($folder)); 
mysqli_set_charset($folder,"utf8");

error_reporting(E_ALL ^ E_DEPRECATED);

//Берем атрибуты
$AllAttribute = array();
$sql= "SELECT attribute_id, attribute_name FROM tbl_attribute;";
$tovar = $folder->query($sql);
while($tmp = $tovar->fetch_assoc()){
    $AllAttribute[mb_strtolower($tmp['attribute_name'], 'UTF-8')] = $tmp['attribute_id'];
}

?>
<h2>Загрузите фаил</h2>
    <form name="import_exel_carfit" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                    <td><b>Лист:</b></td>
                    <td><input type="text" name="excel_table_name" style="width:300px;" placeholder="Имя листа! по умолчанию - первый"></td>
                </tr>
                <tr>
                    <td><b>Фаил для импорта :</b></td>
                    <td><input type="file" name="excel_kottem" style="width:300px;"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="Загрузить" style="width:300px;">
                    </td>
                </tr>
                
            </table>
    </form>
    <h2>Памятка по колонкам</h2>
    <h3>Обязательное поле CODE(артикул товара)</h3>
    
    <ul>
        <li><b>id</b> - id товара на Штурме</font></li>
        <li><b>code</b> - Наш артикл (можно ис размером - пофиг)</font></li>
    </ul>

    
<?php

if(!isset( $_FILES['excel_kottem']['tmp_name'])){
    die();
}

$tmpFilename = $_FILES['excel_kottem']['tmp_name'];

require_once ('../docs/PHPExcel/IOFactory.php');
require_once ('../class/class_load_photo.php');
$LoadPhoto = new LoadPhoto($folder);


if(isset($_POST['excel_table_name']) AND $_POST['excel_table_name'] != ''){
    $worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheetByName($_POST['excel_table_name']);
}else{
    $worksheet = PHPExcel_IOFactory::load($tmpFilename)->getSheet(0);
}

if(!$worksheet) {die('<h2>Ошибка: лист c данными не найден</h2>');}
$rows = $worksheet->getHighestRow();

$parent_now = 0; //Тут храним парент на котором находимся при прохождении

//while($count <= $rows){
//Пока не встретим пустую строку
$msg = '';
$row_count=0;
$find = array();
$count = 2;
while('' != $worksheet->getCellByColumnAndRow(0,$count)->getValue()){
    $row_count++;  
     //Прочитаесм строчку
       $x = 0;
    $attributes = array();
    $row = array();
    while('' != $worksheet->getCellByColumnAndRow($x,1)->getValue()){
        $row[$worksheet->getCellByColumnAndRow($x,1)->getValue()] = $worksheet->getCellByColumnAndRow($x,$count)->getCalculatedValue();
        $x++;
    }
 

    //Если это размерный товар - возьмем его парент
    if($row['id'] > 0 AND strpos($row['code'],'#') !== false){
        
        $sql = 'SELECT tovar_inet_id_parent FROM tbl_tovar WHERE tovar_id = \''.$row['id'].'\';';
        $r = $STURM->query($sql);
        
        if($r->num_rows > 0){
            
            $tmp = $r->fetch_assoc();
            $key = 'GR'.$tmp['tovar_inet_id_parent'];
            
            $tmp = explode('#', $row['code']);
            $row['code'] = $tmp[0];
            
        }
    }else{
        $key = $row['id'];
    }
    
    $x = 0;
    
    while($x < 10){
        $www = 'http://sturm.com.ua/resources/products/'.$key.'//'.$key.'.'.$x.'.large.jpg';
            
        if(!isset($find[$key])){
            echo '<br>'. $row['code'].'<img src="'.$www.'" width="100px">'.$www;
        
                $name = $row['code'];
		
                $IMGPath = $www;
                $Tdate = DownloadFile($IMGPath);
                   
                if (!$Tdate === null) {
                    return 0;
                }
                //header("Content-Type: text/html; charset=UTF-8");
                //echo "<pre>";  print_r(var_dump( $Tdate )); echo "</pre>";die();
                if($Tdate != ''){
                    if(!file_put_contents('/var/www/armma.ru/111/'.$name.'#'.$x.'.jpg', $Tdate)){
                        echo 'Не удалось загрузить фаил';
                        //return 0;
                    }
                }

            //Загрузка фоток
            //$LoadPhoto->loadPhoto($row['code'],$www);
        }
        $x++;
    }
    //Флажек что такой фаил уже пролетал
    $find[$key] = '1';
    $count++;
}
?>
<h2>Отчет импорта </h2>
<ul>
    <li>Всего строк в файле : <?php echo $rows.' ('.$row_count.')';?></li>
</ul>
<?php
echo $msg;

function DownloadFile($url)
	{
		if (!extension_loaded('curl')) {
		    return null;
		}
	
		$ch = curl_init();
	       
		curl_setopt_array(
			$ch,
			array(
				CURLOPT_AUTOREFERER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CONNECTTIMEOUT => 10,
				CURLOPT_TIMEOUT => 120,
				CURLOPT_URL => $url,
				CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.155 Safari/537.3',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => array(
				    'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
				    'Accept-Encoding:gzip, deflate, sdch',
				    'Accept-Language:ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
				    'Cache-Control:max-age=0',
				    'Connection:keep-alive',
				    )
			)
		);
	
	
		$data = curl_exec($ch);
		if (curl_errno($ch) != CURLE_OK) {
		    return null;
		}
	
		return $data;
	}
	
?>

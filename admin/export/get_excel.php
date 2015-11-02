<?php

include '../config/config.php';

global $setup, $folder;

//echo '<pre>'; print_r(var_dump($_GET));

//Проверяем что прилетело
$ProductsID = array();
if(!isset($_GET['producs'])){
    header ('Content-Type: text/html; charset=utf8');
    echo '<h1>Нет товаров для экспорта</h1>';
    die();
}

$ProductsID = explode(';', $_GET['producs']);

if(count($ProductsID) == 0){
    header ('Content-Type: text/html; charset=utf8');
    echo '<h1>Чтото пришло но массива не получилось</h1>';
    die();
}

//Если все прилетело и есть массив с иД-шками товаров - работаем

$sql = 'SELECT tovar_id,
                tovar_artkl AS code,
                on_ware,
                seo_alias AS parent,
                tovar_parent_name AS tovar_group,
                tovar_name_1 AS name,
                klienti_name_1 AS supplier,
                price_tovar_1 AS price1,
                price_tovar_2 AS price2,
                price_tovar_3 AS price3,
                price_tovar_4 AS price4,
                price_tovar_curr_1 AS currency,
                tovar_dimension AS dimm,
                description_1 AS memo
                FROM tbl_tovar
                LEFT JOIN tbl_seo_url ON TRIM(LEADING \'parent=\' FROM seo_url) = tovar_inet_id_parent
                LEFT JOIN tbl_klienti ON klienti_id = tovar_supplier
                LEFT JOIN tbl_parent ON tovar_parent_id = tovar_parent
                LEFT JOIN tbl_price_tovar ON price_tovar_id = tovar_id
                LEFT JOIN tbl_description ON description_tovar_id = tovar_id
                 WHERE tovar_id IN ('.implode(',', $ProductsID).')
                 ORDER BY tovar_code ASC;';

$Products = $folder->query($sql);

$sql = 'SELECT  tovar_id,
                A.attribute_id,
                attribute_value,
                attribute_name
                FROM tbl_attribute_to_tovar A2T
                LEFT JOIN tbl_attribute A ON A.attribute_id = A2T.attribute_id
                WHERE tovar_id IN ('.implode(',', $ProductsID).')
                ORDER BY attribute_value ASC;';

$AttributesSQL = $folder->query($sql);

$Attributes = array();
$AttributesName = array();
while($tmp = $AttributesSQL->fetch_assoc()){
    $Attributes[$tmp['tovar_id']][$tmp['attribute_id']]['name'] = $tmp['attribute_name'];
    $Attributes[$tmp['tovar_id']][$tmp['attribute_id']]['value'] = $tmp['attribute_value'];
    
    $AttributesName[$tmp['attribute_id']] = $tmp['attribute_name'];

}

//echo $sql;
//echo '<pre>'; print_r(var_dump($_GET));
//die();

require_once ('../docs/PHPExcel/IOFactory.php');

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Folder")
	 ->setLastModifiedBy("Folder")
	 ->setTitle("Folder")
	 ->setSubject("Folder")
	 ->setDescription("Folder");

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()
	->setTitle('Sheet1')
	->setCellValue('A1', "code")
	->setCellValue('B1', "on_ware")
	->setCellValue('C1', "parent")
	->setCellValue('D1', "group")
	->setCellValue('E1', "name")
	->setCellValue('F1', "supplier")
	->setCellValue('G1', "price1")
	->setCellValue('H1', "price2")
	->setCellValue('I1', "price3")
	->setCellValue('J1', "price4")
	->setCellValue('K1', "currency")
	->setCellValue('L1', "dimm")
	->setCellValue('M1', "Photo")
	->setCellValue('N1', "memo");

//Шапка аттрибутов
$col = 14;
$AttributeOnCol = array(); //Тут индекс номер колонки а в значении ид_атрибута
foreach($AttributesName as $index => $tmp){
    $objPHPExcel->getActiveSheet()
	->setTitle('Sheet1')
	->setCellValueByColumnAndRow($col, '1', $tmp); //setCellValueByColumnAndRow($col, $row, $value);
    
    $AttributeOnCol[$col] = $index;
    
    $col++;
}
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);


// Пишем основные данные
$L = 2;
while($Product = $Products->fetch_assoc()){
    
    $objPHPExcel->getActiveSheet()
		->setCellValue('A'.$L, $Product['code'])
                ->setCellValue('B'.$L, $Product["on_ware"])
                ->setCellValue('C'.$L, $Product["parent"])
                ->setCellValue('D'.$L, $Product["tovar_group"])
                ->setCellValue('E'.$L, $Product["name"])
                ->setCellValue('F'.$L, $Product["supplier"])
                ->setCellValue('G'.$L, $Product["price1"])
                ->setCellValue('H'.$L, $Product["price2"])
                ->setCellValue('I'.$L, $Product["price3"])
                ->setCellValue('J'.$L, $Product["price4"])
                ->setCellValue('K'.$L, $Product["currency"])
                ->setCellValue('L'.$L, $Product["dimm"])
                ->setCellValue('M'.$L, 'insert URL photo')
                ->setCellValue('N'.$L, $Product["memo"]);
		
                
    //Пишем аттрибуты
//echo '<pre>'; print_r(var_dump($AttributesName)); 
//echo '<pre>'; print_r(var_dump($AttributeOnCol)); 
 
    $col = 14;
   foreach($AttributesName as $index => $tmp){
        
        $value = '';
        if(isset($Attributes[$Product['tovar_id']][$AttributeOnCol[$col]]['value']))
            $value = $Attributes[$Product['tovar_id']][$AttributeOnCol[$col]]['value'];
        
        $objPHPExcel->getActiveSheet()
            ->setTitle('Sheet1')
            ->setCellValueByColumnAndRow($col, $L, $value);
        $col++;
    }
    $L += 1;
}

//Данные

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('../tmp/Folder-products.xls');


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Folder-products.xls');
header('Cache-Control: max-age=0');
readfile('../tmp/Folder-products.xls');
unlink('../tmp/Folder-products.xls');

?>
<?php
header ('Content-Type: text/html; charset=utf8');
include '../config/config.php';

global $setup, $folder;

session_start();

if(!isset($_SESSION['export_products'])){
	header ('Content-Type: text/html; charset=utf8');
    echo '<h1>Не нашел списка продуктов</h1>';
    die();
}

//Если все прилетело и есть массив с иД-шками товаров - работаем

$sql = 'SELECT 	T.tovar_id,
                T.tovar_artkl AS code,
		T.tovar_model AS model,
                T.on_ware,
                A.seo_alias AS parent,
                PR.tovar_parent_name AS tovar_group,
                T.tovar_name_1 AS name,
                K.klienti_name_1 AS supplier,
		B.brand_code,
                P.price_tovar_1 AS price1,
                P.price_tovar_2 AS price2,
                P.price_tovar_3 AS price3,
                P.price_tovar_4 AS price4,
                P.price_tovar_curr_1 AS currency,
                T.tovar_dimension AS dimm,
                D.description_1 AS memo,
		T.tovar_size_table,
		T.tovar_video_url
                FROM tbl_tovar T
                LEFT JOIN tbl_seo_url A ON TRIM(LEADING \'parent=\' FROM A.seo_url) = T.tovar_inet_id_parent
                LEFT JOIN tbl_klienti K ON K.klienti_id = T.tovar_supplier
                LEFT JOIN tbl_parent PR ON PR.tovar_parent_id = T.tovar_parent
                LEFT JOIN tbl_brand B ON B.brand_id = T.brand_id
                LEFT JOIN tbl_price_tovar P ON P.price_tovar_id = T.tovar_id
                LEFT JOIN tbl_description D ON D.description_tovar_id = T.tovar_id
                 WHERE T.tovar_id IN ('.$_SESSION['export_products'].')
                 ORDER BY T.tovar_code ASC;';

$Products = $folder->query($sql);

$sql = 'SELECT  tovar_id,
                A.attribute_id,
                attribute_value,
                attribute_name
                FROM tbl_attribute_to_tovar A2T
                LEFT JOIN tbl_attribute A ON A.attribute_id = A2T.attribute_id
                WHERE tovar_id IN ('.$_SESSION['export_products'].')
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
	->setCellValue('B1', "model")
	->setCellValue('C1', "on_ware")
	->setCellValue('D1', "parent")
	->setCellValue('E1', "group")
	->setCellValue('F1', "name")
	->setCellValue('G1', "brand")
	->setCellValue('H1', "price2")
	->setCellValue('I1', "currency")
	->setCellValue('J1', "dimm")
	->setCellValue('K1', "photo")
	->setCellValue('L1', "video")
	->setCellValue('M1', "size")
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
                ->setCellValue('B'.$L, $Product["model"])
                ->setCellValue('C'.$L, $Product["on_ware"])
                ->setCellValue('D'.$L, $Product["parent"])
                ->setCellValue('E'.$L, $Product["tovar_group"])
                ->setCellValue('F'.$L, $Product["name"])
                ->setCellValue('G'.$L, $Product["brand_code"])
                ->setCellValue('H'.$L, $Product["price2"])
                ->setCellValue('I'.$L, $Product["currency"])
                ->setCellValue('J'.$L, $Product["dimm"])
                ->setCellValue('K'.$L, 'insert URL photo')
                ->setCellValue('L'.$L, $Product["tovar_video_url"])
		->setCellValue('M'.$L, $Product["tovar_size_table"])
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
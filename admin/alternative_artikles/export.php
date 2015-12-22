<?php

include '../config/config.php';

global $setup, $folder;

set_time_limit(0);


//Получим поставщиков
$supplier = $folder->query("SELECT `klienti_id`,`klienti_name_1`,`klienti_phone_1` FROM tbl_klienti WHERE `klienti_group`='5' ORDER BY `klienti_name_1` ASC");
if (!$supplier)
{
  echo "Query error - tbl_Supplier";
  exit();
}else{
  $Suppliers = array('0' => 'Для всех (без учета поставщика)');
  while($tmp = $supplier->fetch_assoc()){

	$Suppliers[$tmp['klienti_id']] = $tmp['klienti_name_1'];

  }
}

if(!isset($_GET['klienti_id']) OR (isset($_GET['klienti_id']) AND $_GET['klienti_id'] == '0')){
    
    echo '<form method="GET">
	    <br>Выбрать поставщика:
	    <select name="klienti_id">
	    <option value="0">Выбрать поставщика!</option>
    ';
    
    foreach($Suppliers as $index => $val){
	    echo '<option value="'.$index.'">'.$val.'</option>';
    }
    
    echo '</select>
    <input type="submit" value="export" name="export">
    </form>
    ';
    die();
}

//Получим альтернативные артиклы
$artikles = $folder->query("SELECT * FROM tbl_tovar_postav_artikl WHERE postav_id='".$_GET['klienti_id']."' ORDER BY `tovar_artkl` ASC");
if (!$artikles)
{
  echo "Query error - tbl_tovar_postav_artikl";
  exit();
}

require_once ('../docs/PHPExcel/IOFactory.php');

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Folder")
	 ->setLastModifiedBy("Folder")
	 ->setTitle("Folder")
	 ->setSubject("Folder")
	 ->setDescription("Folder");

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()
	->setTitle('alt_artikles')
	->setCellValue('A1', "tovar_artkl")
	->setCellValue('B1', "tovar_postav_artkl")
	->setCellValue('C1', "Поставщик");
	
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);	

$L = 2;	
while($tmp = $artikles->fetch_assoc()){

   $objPHPExcel->getActiveSheet()
               ->setCellValue('A'.$L, $tmp["tovar_artkl"])
               ->setCellValue('B'.$L, $tmp["tovar_postav_artkl"])
               ->setCellValue('C'.$L, $Suppliers[$tmp["postav_id"]]);
		
		$L++;

}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('../tmp/Folder-alt_articles.xls');


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Folder-alt_articles.xls');
header('Cache-Control: max-age=0');
readfile('../tmp/Folder-alt_articles.xls');
unlink('../tmp/Folder-alt_articles.xls');

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
  $Suppliers = array('0' => 'Всем');
  while($tmp = $supplier->fetch_assoc()){
    $Suppliers[$tmp['klienti_id']] = $tmp['klienti_name_1'];
  }
}

//Получим альтернативные артиклы
$artikles = $folder->query("SELECT * FROM tbl_tovar_postav_artikl ORDER BY `tovat_artkl` ASC");
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
	->setCellValue('A1', "id")
	->setCellValue('B1', "tovat_artkl")
	->setCellValue('C1', "tovar_postav_artkl")
	->setCellValue('D1', "postav_id")
	->setCellValue('E1', "postav_name");
	
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);	

$L = 2;	
while($tmp = $artikles->fetch_assoc()){

   $objPHPExcel->getActiveSheet()
		->setCellValue('A'.$L, $tmp['id'])
                ->setCellValue('B'.$L, $tmp["tovat_artkl"])
                ->setCellValue('C'.$L, $tmp["tovar_postav_artkl"])
                ->setCellValue('D'.$L, $tmp["postav_id"])
                ->setCellValue('E'.$L, $Suppliers[$tmp["postav_id"]]);
		
		$L++;

}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('../tmp/Folder-alt_articles.xls');


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Folder-alt_articles.xls');
header('Cache-Control: max-age=0');
readfile('../tmp/Folder-alt_articles.xls');
unlink('../tmp/Folder-alt_articles.xls');

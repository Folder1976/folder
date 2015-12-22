<?php

include '../config/config.php';

global $setup, $folder;

$sql = 'SELECT `find`, `replace` FROM `tbl_translate`;';

$translate = $folder->query($sql);

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
	->setCellValue('A1', "find")
	->setCellValue('B1', "replace");

$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);

// Пишем основные данные
$L = 2;
while($tmp = $translate->fetch_assoc()){
    
    $objPHPExcel->getActiveSheet()
		->setCellValue('A'.$L, $tmp['find'])
                ->setCellValue('B'.$L, $tmp["replace"]);
    $L += 1;
}

//Данные
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('../tmp/Folder-translate.xls');


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Folder-translate.xls');
header('Cache-Control: max-age=0');
readfile('../tmp/Folder-translate.xls');
unlink('../tmp/Folder-translate.xls');

?>
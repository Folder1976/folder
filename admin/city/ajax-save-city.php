<?php
include ('../config/config.php');
if(isset($_GET['id']) AND isset($_GET['dell'])){
	
	$sql = 'DELETE FROM tbl_citys WHERE CityID=\''.$_GET['id'].'\';';
	$folder->query($sql) or die ("Удаление города :(");	
}


if(isset($_GET['id']) AND 
   isset($_GET['name']) AND
   isset($_GET['translite'])){
	
	$name		= htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8');
	$translite	= htmlspecialchars($_GET['translite'], ENT_QUOTES, 'UTF-8');
	$kuda_name	= htmlspecialchars($_GET['kuda_name'], ENT_QUOTES, 'UTF-8');
	$gde_name	= htmlspecialchars($_GET['gde_name'], ENT_QUOTES, 'UTF-8');
	$days		= htmlspecialchars($_GET['days'], ENT_QUOTES, 'UTF-8');
	$phone		= htmlspecialchars($_GET['phone'], ENT_QUOTES, 'UTF-8');
	$id	= $_GET['id'];

	if(isset($_GET['edit'])){
		$sql = "UPDATE tbl_citys SET
				CityTranslite='$translite',
				CityLable='$name',
				KudaLable='$kuda_name',
				GdeLable='$gde_name',
				DeliveryDays='$days',
				Localphone='$phone'
				WHERE CityID='".$id."';";
		//echo $sql;
		$folder->query($sql) or die ("Обновление города :(");		
	}

}


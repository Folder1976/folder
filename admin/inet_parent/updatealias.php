<?php
include ("../config/config.php");

$sql = "INSERT INTO tbl_seo_url SET seo_url = 'parent=".(int)$_GET['parent']."', seo_alias = '".$_GET['alias']."'
            ON duplicate KEY UPDATE seo_alias = '".$_GET['alias']."';";
//echo $sql;
$folder->query($sql);

?>
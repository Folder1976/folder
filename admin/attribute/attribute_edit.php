<?php
include ("../config/config.php");
echo '<pre>'; print_r(var_dump($_POST));
if(isset($_POST)){
    $id = $_POST['id'];
    if($_POST['key'] == "dell"){
        
        $folder->query("DELETE FROM  tbl_attribute WHERE attribute_id = '".$id."'") or die(mysql_error());
        $folder->query("DELETE FROM  tbl_attribute_to_group WHERE attribute_id = '".$id."'") or die(mysql_error());
        $folder->query("DELETE FROM  tbl_attribute_to_tovar WHERE attribute_id = '".$id."'") or die(mysql_error());
    
    }
}
?>
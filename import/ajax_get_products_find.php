<?php
header ('Content-Type: text/html; charset=utf8');
include '../config/config.php';

global $setup, $folder;

$find = '1111111111fglkjhdf;lkghlk1';

if(isset($_GET['find'])){
    $find = $_GET['find'];
}

$sql = 'SELECT
            `tovar_id`,
            `tovar_artkl`,
            `tovar_name_1`,
            `tovar_model`
             FROM `tbl_tovar`
             WHERE
                tovar_model LIKE \'%'.$find.'%\' OR
                tovar_artkl LIKE \'%'.$find.'%\' OR
                tovar_name_1 LIKE \'%'.$find.'%\' 
             ';

$r = $folder->query($sql);
             
$return = array();             
if($r->num_rows != 0){
    while($value = $r->fetch_assoc()){
        $return[$value['tovar_id']]['id']       = $value['tovar_id'];
        $return[$value['tovar_id']]['artikl']   = $value['tovar_artkl'];
        $return[$value['tovar_id']]['model']    = $value['tovar_model'];
        $return[$value['tovar_id']]['name']     = $value['tovar_name_1'];
    }
    
}

echo json_encode($return);
            
?>

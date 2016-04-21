<?php
include '../config/config.php';

global $folder;

//include '../class/class_category.php';
//$Category = new Category($folder);

//include '../class/class_alias.php';
//$Alias = new Alias($folder);


if(session_id()){
}else{
  session_start();
}


    $sql = "SELECT distinct attribute_value, tovar_model, C.id, S.seo_alias
                    FROM tbl_attribute_to_tovar A
                    LEFT JOIN tbl_colors C ON C.color = A.attribute_value
                    LEFT JOIN tbl_tovar T ON T.tovar_id = A.tovar_id
                    LEFT JOIN tbl_seo_url S ON S.seo_url = CONCAT('tovar_id=', T.tovar_id)
                    WHERE attribute_id = 2 AND T.tovar_artkl LIKE '".$_POST['artkl']."%' LIMIT 0, 1;";
    $r = $folder->query($sql);
 
    $colors = array();
    
    if($r->num_rows > 0){
        
        $tmp = $r->fetch_assoc();
        $main_id = $tmp['id'];
        
        $colors[$tmp['id']]['name']  = $tmp['attribute_value'];
        $colors[$tmp['id']]['alias']  = $tmp['seo_alias'];
         
        
        $sql = "SELECT distinct attribute_value, tovar_model, C.id, S.seo_alias
                        FROM tbl_attribute_to_tovar A
                        LEFT JOIN tbl_colors C ON C.color = A.attribute_value
                        LEFT JOIN tbl_tovar T ON T.tovar_id = A.tovar_id
                        LEFT JOIN tbl_seo_url S ON S.seo_url = CONCAT('tovar_id=', T.tovar_id)
                        WHERE attribute_id = 2 AND T.tovar_model LIKE '".$tmp['tovar_model']."' GROUP BY S.seo_alias;";// LIMIT 0, 7;";
        $r = $folder->query($sql);
 
        if($r->num_rows > 0){
            while($tmp = $r->fetch_assoc()){
                if($tmp['id'] != $main_id){
                    $colors[$tmp['id']]['name']  = $tmp['attribute_value'];
                    $colors[$tmp['id']]['alias']  = $tmp['seo_alias'];
                }
            }
        }
    
    
        $return = array();
        $return['id'] = $_POST['artkl'];
        $return['colors'] = $colors;
        
        echo json_encode($return);
    
        return false;
    }


?>
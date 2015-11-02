<?php
include ("../config/config.php");
if(!isset($_GET['group'])){
    echo 'Нет ИД группы!';
    die();
}
$id = $_GET['id'];
$group = $_GET['group'];

$sql = "SELECT A.attribute_id, A.attribute_name, T.attribute_value FROM tbl_attribute A
        LEFT JOIN tbl_attribute_to_group G ON A.attribute_id = G.attribute_id
        LEFT JOIN tbl_attribute_to_tovar T ON T.attribute_id = A.attribute_id AND T.tovar_id = '$id'
        WHERE G.attribute_group_id = '$group' 
        ORDER BY A.attribute_name ASC;";

$group = $folder->query($sql) or die(mysql_error());

echo '<ul class = "attribute_list">';
        while($attr = $group->fetch_assoc()){
            echo '<li>
                <input type = "text" name = "attr*'.$attr['attribute_id'].'" id = "attr*'.$attr['attribute_id'].'" value = "'.$attr['attribute_value'].'" placeholder = "'.$attr['attribute_name'].'">
                '.$attr['attribute_name'].'</li>';
        }
echo '</ul>';


//echo 'attribute - '.$tovar['attribute_group_id'];
//print_r(var_dump($_GET));

?>

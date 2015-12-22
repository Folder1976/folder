<?php
    include_once ('../config/config.php');
    global $folder, $setup;
    $separator = $setup['tovar artikl-size sep'];

    $res = $folder->query('SELECT `find`, `replace` FROM tbl_translate;') or die('123'.mysql_error());

    if($res->num_rows == 0){
	echo 'Не нащел товар';
	exit();    
    }

    foreach($_POST as $index => $value){
        if($index == 'txt'){
                $text = $_POST['txt'];
        }else{
            $text .= '&' . $index . $value;
        }
        
        
    }

    if($text == '') die();
    
    while($tmp = $res->fetch_assoc()){
        
        $text = str_replace($tmp['find'], $tmp['replace'], $text);
        
    }
 
    $text = str_replace('\\', '', $text);
    $text = str_replace('***', '&', $text);
    
    
    echo $text;
    

?>

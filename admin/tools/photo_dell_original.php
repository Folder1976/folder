<?php
include '../config/config.php';
echo '<h1>Починяем линки фото к товарам</h1>';
set_time_limit(0);
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';


$dir = UPLOAD_DIR;
$dh  = opendir($dir);
$count = 0;
while (false !== ($dir_name = readdir($dh))) {
    $count++;
    if (is_dir(UPLOAD_DIR.$dir_name)){
            
            $dh1  = opendir(UPLOAD_DIR.$dir_name);
            while (false !== ($filename = readdir($dh1))) {
                
                if(strpos($filename, '.orig.') !== false){
                    
                    $file_large = str_replace('.orig.', '.large.', $filename);
                    
                    unlink(UPLOAD_DIR.$dir_name.'/'.$file_large);
                    copy(UPLOAD_DIR.$dir_name.'/'.$filename, UPLOAD_DIR.$dir_name.'/'.$file_large);
                    unlink(UPLOAD_DIR.$dir_name.'/'.$filename);
                    echo '<br>'.UPLOAD_DIR.$dir_name.'/'.$file_large;
                    //echo '<br>'.$filename .' '. $file_large;
                    //echo '<br>'.$filename;
                    //echo '<br>';
                    
                }
                
            }
                
    }
    
    
}




?>
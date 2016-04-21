<?php
include '../config/config.php';
include '../simple_html_dom/simple_html_dom.php';

if(!isset($_POST['id']) AND !isset($_GET['id'])) {
    echo 'no link for pars';
    die();
}

if(isset($_POST['id'])){
    $id = $_POST['id'];
}ELSE{
    $id = $_GET['id'];
}

if(strpos($id, 'row_') !== false){
    $id = (int)(str_replace('row_', '', $id));    
}

$sql = 'DELETE FROM tbl_baner WHERE baner_id = \''.$id.'\';';

$folder->query($sql);

?>
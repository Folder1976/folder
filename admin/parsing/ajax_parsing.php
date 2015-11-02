<?php
include '../config/config.php';
include '../simple_html_dom/simple_html_dom.php';

/*
$html = str_get_html("<div>foo <b>bar</b></div>");
echo $html; // выведет <div>foo <b>bar</b></div>;
$e = $html->find("div", 0);
echo $e->tag; // Вернет: "div"
echo $e->outertext; // Вернет: <div>foo <b>bar</b></div>
echo $e->innertext; // Вернет: foo <b>bar</b>
echo $e->plaintext; // Вернет: foo bar
*/


if(!isset($_POST['link']) AND !isset($_GET['link'])) {
    echo 'no link for pars';
    die();
}

if(isset($_POST['link'])){
    $link = $_POST['link'];
}ELSE{
    $link = $_GET['link'];
}

$return = array();

//Аллегро парсинг
if(strpos($link, 'allegro.pl')){
    
    $return['web'] = 'allegro';
    
    $html = file_get_html($link);
    
    //Взяли прайс
    $price = $html->find('#priceValue');
    
    //Получаем прайс
    list($return['price'], $tmp) = explode(' ',$price[0]->innertext);
    $return['price'] = str_replace(',', '.', $return['price']);
    
    //получаем валюту
    if($tmp[0] == 'z'){
        $return['currency'] = 4;
    }if($tmp == 'usd'){
        $return['currency'] = 2;
    }if($tmp == 'euro'){
        $return['currency'] = 3;
    }if($tmp == 'uah'){
        $return['currency'] = 1;
    }
    
    //Получим доставку
    $deliv = $html->find('table');
    
    foreach($deliv as $index => $value){
         //Найдем таблицу с доставкой
         if(strpos($value, 'Sposób dostawy') !== false){
            
            //Разложим таблицу по ячейкам.
            $value = str_replace('</td>', '<td>', $value);
            $html_tmp = explode('<td>',$value);
        
            //Цена доставки хранится в 3-й ячейке
            list($delive, $tmp) = explode(' ',$html_tmp[3]);
            $return['delive'] = str_replace(',', '.', $delive);
          
            //А тип доставки в первой
             $return['delive_type'] = $html_tmp[1];
          }
    }
    
    //echo '<pre>'; print_r(var_dump($html_tmp));
    
    /*
    if($html->innertext!='' and count($html->find('a'))){
        foreach($html->find('a') as $a){
          echo '<a href="http://xdan.ru/'.$a->href.'">'.$a->plaintext.'</a></br>';
        }
    }
    */
    $html->clear(); // подчищаем за собой
    unset($html);
}

//print_r(var_dump($price));


echo json_encode($return);
?>
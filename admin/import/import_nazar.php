<?php

include 'class/class_product_edit.php';
$ProductEdit = new ProductEdit($folder);

$key = "kljh@sagflhfd@slgh #dfgh;dfsht098342-[054 y]3-5t y]-[23 []-943[f9h p34";

$context = stream_context_create( // создаём контекст 
                             array( 
                                    'http' => array( // в качестве транспортного протокола будет использоваться http:// 
                                     'method' => 'GET', // метод запроса - GET 
                                     'header' => 'Content-Type: text/html', // xml 
                                     'timeout' => 60 // время на соединение с сервером - 8 сек 
                                    ) 
                             ) 
); 
$tmp = file_get_contents('http://46.201.253.108/resources/export.php?key='.$key, false, $context);
$page = (array)json_decode($tmp); 
//echo " - ".$tmp."<br>";
//echo "<pre>";print_r(var_dump($page));

$ParentInet = array();
$res = $folder->query("SELECT * FROM tbl_parent_inet;");
while($tmp = $res->fetch_assoc()){
    $ParentInet[$tmp['parent_inet_id']]['id'] = $tmp['parent_inet_id'];
    $ParentInet[$tmp['parent_inet_id']]['name'] = $tmp['parent_inet_1'];
}

$allProduct = array();
$res = $folder->query("SELECT * FROM tbl_tovar;");
while($tmp = $res->fetch_assoc()){
    $allProduct[$tmp['tovar_barcode']]['id'] = $tmp['tovar_id'];
    $allProduct[$tmp['tovar_barcode']]['name'] = $tmp['tovar_name_1'];
    $allProduct[$tmp['tovar_barcode']]['tovar_parent'] = $tmp['tovar_parent'];
    $allProduct[$tmp['tovar_barcode']]['tovar_inet_id_parent'] = $tmp['tovar_inet_id_parent'];
}


echo "Импорт Назар";

/*
  $out[$tmp['tovar_id']]['tovar_id'] 			= $tmp['tovar_id'];
  $out[$tmp['tovar_id']]['tovar_inet_id_parent'] 	= $tmp['tovar_inet_id_parent'];	
  $out[$tmp['tovar_id']]['tovar_name_1'] 		= $tmp['tovar_name_1'];
  $out[$tmp['tovar_id']]['ware'] 			= $tmp['ware'];
  $out[$tmp['tovar_id']]['price'] 			= $tmp['price'];
  $out[$tmp['tovar_id']]['price_rozn'] 			= $tmp['price_rozn'];
  $out[$tmp['tovar_id']]['tovar_artkl'] 	      	= $tmp['tovar_artkl'];
  $out[$tmp['tovar_id']]['tovar_memo'] 			= $tmp['tovar_memo'];
  $out[$tmp['tovar_id']]['tovar_dimension'] 	      	= $tmp['tovar_dimension'];
  $out[$tmp['tovar_id']]['dimension_name'] 	      	= $tmp['dimension_name'];
*/

echo "<table>
            <tr>
                <th>*</th>
                <th>Баркод</th>
                <th>Название</th>
                <th>--</th>
                <th>Закуп</th>
                <th>Розница</th>
                <th>Рекомендация</th>
                <th></th>
            </tr>";
foreach($page as $index => $value){
    $value = (array)$value;
//echo '<pre>'; print_r(var_dump($value));  die();
$id = 0;
$barcode = $value['tovar_artkl'];
$data = array();
$data['product']['tovar_barcode'] = $barcode;
$data['product']['tovar_name_1'] = $value['tovar_name_1'];
//$data['product']['tovar_dimension'] = $value['dimension_name'];

$data['price']['price_tovar_1'] = $value['price_zakup'];
$data['price']['price_tovar_3'] = $value['price'];
$data['price']['price_tovar_2'] = $value['price_rozn'];


    echo "<tr>";
        echo "<td>";
            echo $index;
        echo "</td>";
        echo "<td>";
            echo $barcode;
        echo "</td>";
        echo "<td>";
            echo $value['tovar_name_1'];
        echo "</td>";
        echo "<td>";
            echo $value['ware']." ".$value['dimension_name'];
        echo "</td>";
        echo "<td>";
            echo $value['price_zakup']. " грн.";
        echo "</td>";
        echo "<td>";
            echo $value['price_rozn']. " грн.";
        echo "</td>";
        echo "<td>";
            echo $value['price']. " грн.";
        echo "</td>";
        echo "<td>";
            //если в масиве товаров есть такое элемент с ключем артикула
            if(isset($allProduct[$barcode])){
                $id = $allProduct[$barcode]['id'];
                echo $id. '';
            }else{
                
               // $last_id = $ProductEdit->addProduct($data['product']);
                
                //$data['price']['price_tovar_id'] = $last_id;
                //$last_id = $ProductEdit->updatePrice($data['price']);
                
                //echo 'новое = '.$last_id;
            }
        echo "</td>";
        
        
        
        
        
        
        
    echo "</tr>";
}
echo "</table>";

?>

<h2>Аналитика продаж.</h2>
<h3>За период. . . . (пока без периода, от самого начала)</h3>

<?php
$operation_status = array(1,2,3,4,1113);

$sql = 'SELECT
        operation_id,
        operation_data,
        operation_klient,
        operation_status,
        operation_summ,
        operation_memo,
        operation_detail_tovar,
        operation_detail_item,
        operation_detail_price,
        operation_detail_zakup,
        operation_detail_summ,
        product_postav_id,
        tovar_artkl,
        tovar_name_1 AS tovar_name,
        klienti_name_1 AS klienti_name,
        operation_status_name
        
        FROM tbl_operation O
        RIGHT JOIN tbl_operation_detail OD ON OD.operation_detail_operation = O.operation_id AND operation_detail_dell = 0
        LEFT JOIN tbl_tovar T ON OD.operation_detail_tovar = T.tovar_id
        LEFT JOIN tbl_klienti K ON O.operation_klient = K.klienti_id
        LEFT JOIN tbl_operation_status OS ON O.operation_status = OS.operation_status_id
        
        WHERE operation_dell = 0 AND operation_status IN ('.implode(',', $operation_status).')
        ORDER BY operation_id DESC
        ';
//echo $sql;
$r = $folder->query($sql);

 $html = '<table>
    <th>Накл</th>
    <th>Дата</th>
    <th>Cтатус</th>
    <th>Клиент</th>
    <th>Товар</th>
    <th>К-во</th>
    <th>Сумм</th>
    <th>Доход</th>';
 
$summ = 0;
$zakup = 0;
$summ = 0;
$items = 0;
$item = 0;
while($operation = $r->fetch_assoc()){ 

    $item = $operation['operation_detail_item'];
    $items += $item;

    $summ += $item * $operation['operation_detail_price'];
    $zakup += $item * $operation['operation_detail_zakup'];
    $summ_s = $item * $operation['operation_detail_price'];
    $zakup_s = $item * $operation['operation_detail_zakup'];
   
   
    $html .= '
            <tr>
                <td>'.$operation['operation_id'] .'</td>
                <td>'.$operation['operation_data'].'</td>
                <td>'.$operation['operation_status_name'].'</td>
                <td>'.$operation['klienti_name'].'</td>
                <td>'.$operation['tovar_artkl'].' '.$operation['tovar_name'].'</td>
                <td>'.$item.'</td>
                <td>'.$operation['operation_detail_zakup'].' / '.$operation['operation_detail_price'].'</td>
                <td>'. ($item * ($summ_s - $zakup_s)).'</td>
             </tr>';
    
    
    
 }

 $html .= '</table>';
 
 echo '<br>Всего закуплено : <b><font color="red">'.number_format((($zakup)),'2', '.', ' ') . ' руб.</font></b>';
 echo '<br>Всего продано : <b><font color="blue">'.number_format((($summ)),'2', '.', ' ') . ' руб.</font></b>';
 echo '<br>Всего продано шт : <b><font color="blue">'.$items . '</font></b>';
 echo '<br>Всего доход : <b><font color="orange">'.number_format((($summ - $zakup)),'2', '.', ' ') . ' руб.</font></b>';
 echo '<br>Всего 25% : <b><font color="green">'.number_format((($summ - $zakup) * 0.25),'2', '.', ' ') . ' руб.</font></b>';
 echo '<hr>'.$html;
 
 //echo "<pre>";  print_r(var_dump( $html )); echo "</pre>";
 
 
 
 
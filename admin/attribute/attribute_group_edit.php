<?php
$group_sel = 1;
echo "<h1>$title</h1>";

if(isset($_GET['attribute_group_id'])) $group_sel = $_GET['attribute_group_id'];

if(isset($_POST['add_new_attr']) AND $_POST['add_new_attr'] != ''){

    include "../class/class_attribute.php";
    $Attribute = new Attribute($folder);
    $Attribute->addAttribute($_POST['add_new_attr']);
    
}else if(isset($_POST['add'])){
    
    include "../class/class_attribute.php";
    $Attribute = new Attribute($folder);
    $Attribute->addAttributeGroup($_POST);

}else if(isset($_POST['save'])){

    include "../class/class_attribute.php";
    $Attribute = new Attribute($folder);
    $Attribute->saveAttributeGroup($_POST);

}else if(isset($_POST['dell'])){

    include "../class/class_attribute.php";
    $Attribute = new Attribute($folder);
    $Attribute->dellAttributeGroup($_POST['id']);

}

$r = $folder->query('SELECT attribute_group_id, attribute_group_name FROM tbl_attribute_group');

$all_attribute_groups = array();
if($r->num_rows == 0) die();
    
    //Берем все группы
    while($grp = $r->fetch_assoc()){
        $all_attribute_groups[$grp['attribute_group_id']] = $grp['attribute_group_name'];
    }
    
    //берем все атрибуты
    $r = $folder->query('SELECT attribute_id, attribute_name FROM tbl_attribute');
    $all_attributes = array();
    while($grp = $r->fetch_assoc()){
        $all_attributes[$grp['attribute_id']] = $grp['attribute_name'];

    }
  
    
    //берем атрибуты для выбранной группы - если она выбрана
    if($group_sel > 0){
        $sql = 'SELECT attribute_id, attribute_filter, attribute_char, attribute_sort
                                FROM tbl_attribute_to_group 
                                WHERE attribute_group_id = "'.$group_sel.'"
                                ORDER BY attribute_sort ASC;';
        
        $r = $folder->query($sql) or die (';oiujlwef=83='.mysql_error());
        $all_attributes_to_group = array();
        while($grp = $r->fetch_assoc()){
            if($grp['attribute_sort'] > 0){
                $all_attributes_to_group[$grp['attribute_id']]['filter'] = $grp['attribute_filter'];
                $all_attributes_to_group[$grp['attribute_id']]['char'] = $grp['attribute_char'];
                $all_attributes_to_group[$grp['attribute_id']]['sort'] = $grp['attribute_sort'];
            }else{
                $all_attributes_to_group_1[$grp['attribute_id']]['filter'] = $grp['attribute_filter'];
                $all_attributes_to_group_1[$grp['attribute_id']]['char'] = $grp['attribute_char'];
                $all_attributes_to_group_1[$grp['attribute_id']]['sort'] = $grp['attribute_sort'];
            }
        }
       // $all_attributes_to_group += $all_attributes_to_group_1;
     }
    
    echo '<div style = "margin-top:20px;float:left;font-size:14px;">
            СПИСОК ГРУПП АТТРИБУТОВ<br><br>';
    $is_group_select = 0;
    foreach($all_attribute_groups as $index_g => $value_g){
        if($group_sel == $index_g) {
                echo ' >> ';
                $is_group_select = 1;
        }
        echo "<a href='main.php?func=attribute_group_edit&attribute_group_id=".$index_g."'>".$value_g."</a><br>";
    }
    echo '</div>';
    
    if($is_group_select == 1){ //Если выбрана группа
        echo '<form method="POST" enctype="multipart/form-data">';
        echo '<div style = "margin-left:40px;margin-top:20px;float:left;font-size:14px;">
                
                <input type = "submit" name = "add" id = "add" style = "width:90px;" value = "Добавить">
                <input type = "submit" name = "save" id = "save" style = "width:90px;" value = "Сохранить">
                <input type = "submit" name = "dell" id = "dell" style = "width:90px;" value = "Удалить"><br><br>
                
                <input type = "text" name = "name" id = "name" style = "width:350px;" value = "'.$all_attribute_groups[$group_sel].'"><br>
                <input type = "hidden" name = "id" id = "id" value = "'.$group_sel.'">
                    <br><br>Атрибуты
                    <table class = "edit_attribute_list"><tr>
                                                    <th>Название атрибута</th>
                                                    <th>Фильтр</th>
                                                    <th>Характеристика</th>
                                                    <th>Сорт</th>
                                                    <th></th>
                                                        </tr>';
              //Добавим НОВЫЙ =======================
              echo '<form method = "POST" enctype="multipart/form-data">
                    <tr><td colspan = "3"><input type = "text" name = "add_new_attr" id = "add_new_attr" style = "width:350px;" value = "" placeholder="Добавить новый аттрибут" onEnter="submit();"></td>
                        <td></td>
                        <td ><input type = "submit" name = "add_new" id = "add_new" style = "width:25px;" value = "+"></td>
                    </tr>
                    <tr><td colspan = "5">&nbsp;</td></tr>
                    </form>';
          
            //Список имеющихся
            $filtered = $filtered_no = '';
                    foreach($all_attributes as $index => $value){
                       
                       if(isset($all_attributes_to_group[$index]['sort'])){
                       
                        $filtered .= '<tr><td>'.$value.'</td>
                                     <td><input type = "checkbox" name = "filter='.$index.'" id = "filter='.$index.'" ';
                                    if(isset($all_attributes_to_group[$index]['filter']) AND $all_attributes_to_group[$index]['filter'] == '1') $filtered .= 'checked';
                                    $filtered .= '></td>
                                    <td><input type = "checkbox" name = "attr='.$index.'" id = "attr='.$index.'" ';
                                    if(isset($all_attributes_to_group[$index]) AND $all_attributes_to_group[$index]['char'] == '1') $filtered .= 'checked';
                                    $filtered .= '></td>
                                    <td ><input type = "text" name = "sort='.$index.'" id = "sort='.$index.'" style = "width:25px;" value = "'.((isset($all_attributes_to_group[$index]['sort'])) ? $all_attributes_to_group[$index]['sort'] : '0') .'"></td>
                                    <td><a href="javascript:" class = "dell_attribute" id = "'.$index.'"><img width = "25" src="'.HOST_URL.'/resources/delete.png" title="dell"></td>
                                    </tr>';
                       }else{
                        $filtered_no .= '<tr><td>'.$value.'</td>
                                    <td><input type = "checkbox" name = "filter='.$index.'" id = "filter='.$index.'" ';
                                    if(isset($all_attributes_to_group_1[$index]['filter']) AND $all_attributes_to_group_1[$index]['filter'] == '1') $filtered_no .=  'checked';
                                    $filtered_no .=  '></td>
                                    <td><input type = "checkbox" name = "attr='.$index.'" id = "attr='.$index.'" ';
                                    if(isset($all_attributes_to_group_1[$index]) AND $all_attributes_to_group_1[$index]['char'] == '1') $filtered_no .=  'checked';
                                    $filtered_no .=  '></td>
                                     <td ><input type = "text" name = "sort='.$index.'" id = "sort='.$index.'" style = "width:25px;" value = "'.((isset($all_attributes_to_group_1[$index]['sort'])) ? $all_attributes_to_group_1[$index]['sort'] : '0') .'"></td>
                                    <td><a href="javascript:" class = "dell_attribute" id = "'.$index.'"><img width = "25" src="'.HOST_URL.'/resources/delete.png" title="dell"></td>
                                    </tr>';
                        
                       }
                    }   
                
        echo $filtered.$filtered_no. '</table></div>
        </form>';
    }
//echo "<pre>"; print_r(var_dump($all_attribute_groups));
//echo "<pre>"; print_r(var_dump($all_attributes));
//echo "<pre>"; print_r(var_dump($all_attributes_to_group));

?>
<script>
    $(".dell_attribute").click(function(){
        
        if(confirm('Внимание! \n\rДанный аттрибут будет удален из всех групп и из всех товаров!!!\n\r Если вы хотите удалить его только из этой группы - просто снимите галочки.\n\r Удалить?')){
            
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                url: "<?php echo HOST_URL;?>/admin/attribute/attribute_edit.php",
                dataType: "text",
                data: "key=dell&id="+id,
                success: function(msg){
                             console.log(msg);
                             location.reload();
                }
             });
        
        }else{
        
            console.log('cansel');
        
        }
    
    });
    
</script>
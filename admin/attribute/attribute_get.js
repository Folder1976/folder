    $(document).ready(function(){
         reloadAttributeList();
         
         //Если меняется группа аттрибутов
        $("#attribute_group_id").change(function(){
            reloadAttributeList();
        });

    });
 
 
//Загружаем атрибуты по группе    
    function reloadAttributeList(){
        var group = $("#attribute_group_id").val();
        var id = $("#tovar_id").val();
            
            $.ajax({
		url: 'attribute/get_attribute_list.php?group='+group+'&id='+id,
		cache: false,
		success: function(html){
                   $('.attribute_list').html(html);
                    //console.log(html);
		}
            });
              
    }
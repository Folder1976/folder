<?php	
    $sql = "SELECT id, wmotmenuid AS parent_id, name
            FROM  menu_word_weights
            WHERE wmotmenuid = '0' ORDER BY name ASC;";
        $rs = $folder->query($sql) or die ("Get product type list ".$sql);
        
        $body = "
                <div id=\"container\" class = \"product-type-tree\">
                <div class='key_close'>Закрыть [x]</div>
                <input type='hidden' id='selected_menu' value=''>
                <ul  id=\"celebTree\"><li><span id=\"span_0\"><a class = \"tree\" href=\"javascript:\" id=\"0\">Категории</a></span><ul>";
        while ($Type = mysqli_fetch_assoc($rs)) {
            if($Type['parent_id'] == 0){
                $body .=  "<li><span id=\"span_".$Type['id']."\"> <a class = \"tree\" href=\"javascript:\" id=\"".$Type['id']."\">".$Type['name']."</a>";
                $body .= "</span>".readTree($Type['id'],$folder);
                $body .= "</li>";
            }
        }
        $body .= "</ul>
            </li></ul></div>";
          
    echo $body;
    
    
    //Рекурсия=================================================================
    function readTree($parent,$folder){
        $sql = "SELECT id, wmotmenuid AS parent_id, name
                FROM  menu_word_weights  WHERE wmotmenuid = '$parent' ORDER BY name ASC;";
        $rs1 = $folder->query($sql) or die ("Get product type list".$sql);
    
        $body = "";
    
        while ($Type = mysqli_fetch_assoc($rs1)) {
            $body .=  "<li><span id=\"span_".$Type['id']."\"><a class = \"tree\" href=\"javascript:\" id=\"".$Type['id']."\">".$Type['name']."</a>";
            $body .= "</span>".readTree($Type['id'],$folder);
            $body .= "</li>";
        }
        if($body != "") $body = "<ul>$body</ul>";
        return $body;

    }
?>

<script>
    $(document).on('click', 'span', function(event){
		
		var id = event.target.id;
		var parent_id = $(this).children("a").first().attr('id');
		console.log(id);
	
		if (id) {
			switch(id){
				case "dell-carfit":
							 //dellItem(parent_id);
				break;
				case "insert-carfit":
							  //insertItem(parent_id);
				break;
				case "new_category":
							  //insertItem(parent_id);
				break;
				default:                  
					var category_id = id;
					$('.category_id').val(category_id);
			}
				}else{
					$(this).toggleClass('closed opened').nextAll('ul').toggle(300);
				}
				   
	});
	//==========Кнопка Закрыть окно редактирования
	$(".key_close").click(function(){
		$("#container").css("display","block").toggle('slow');
	});
	
	
$(document).ready(function(){
	
	//Скрипт дерева ========================
	$('#celebTree ul')
		.hide()
		.prev('span')
		.before('<span></span>')
		.prev()
		.addClass('handle closed')
		.click(function() {
		});
	$('#celebTree ul')
		.prev('span')
		.children('a')
		.toggleClass('tree tree_ul')
		.click(function() {
		});
		
	//Развернем первый уровень
    $("#0").parent('span').parent('li').children('span').first().toggleClass('closed opened').nextAll('ul').toggle();
	console.log('ready');
});
    
</script>
<style>
	.error{
		display: block;
		position: absolute;
		left: 10px;
		top: 10px;
		background-color: #FFCCC9;
		border: 2px solid gray;
		border-radius: 3px;
		padding: 10px;
	}
	.key_close{
		cursor: pointer;
	}
	#container{
		display: none;
		position: absolute;
		left: 10px;
		top: 80px;
		width: 600px;
		background-color: #C9F7FF;
		border: 2px solid gray;
		border-radius: 3px;
		padding-right: 10px;
	}
	
	.tree{
		margin-left: 15px;
	}
	.tree_ul{
		margin-left: 0px;
	}
	.handle {
		background: transparent url(images/tree-handle.png) no-repeat left top;
		display:block;
		float:left;
		width:15px;
		height:17px;
		cursor:pointer;
	}
	    .product-type-edit  li {
        list-style-type: none; 
    }
       
    .product-type-edit ul {
        margin-top: 15px;
        margin-left: 20px; /* Отступ слева в браузере IE и Opera */
        padding-left: 0; /* Отступ слева в браузере Firefox, Safari, Chrome */
    }
	li {
        padding-top: 3px;
        padding-bottom: 4px;
        list-style-type: none; 
    }
	.closed { background-position: left 2px; }
	.opened { background-position: left -13px; }
</style>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
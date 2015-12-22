<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
global $setup;
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';
//echo "<pre>"; print_r(var_dump($_POST));
//die();

//Tree reload

function reload_path(){
  global $folder;
  include_once("../class/class_category.php");
  $Category = new Category($folder);
  $Category->reloadCategoryPatch();
  //echo "Перечитали!";
}

if(isset($_REQUEST['_reload_path'])){
  reload_path();
}


//UPDATE
  if (isset($_REQUEST["_save"])){
 $folder->query("UPDATE tbl_parent_inet SET
		    parent_inet_parent = '".$_POST['parent_inet_parent']."',
		   parent_inet_sort = '".$_POST['parent_inet_sort']."',
		   parent_inet_1 = '".$_POST['parent_inet_1']."',
		   parent_inet_2 = '".$_POST['parent_inet_2']."',
		   parent_inet_3 = '".$_POST['parent_inet_3']."',
		   parent_inet_info = '".$_POST['parent_inet_info']."',
		   parent_inet_type = '".$_POST['parent_inet_type']."',
		   parent_inet_view = '".$_POST['parent_inet_view']."',
		   parent_inet_memo_1 = '".$_POST['parent_inet_memo_1']."',
		   attribute_group_id = '".$_POST['attribute_group_id']."',
		   parent_inet_memo_2 = '".$_POST['parent_inet_memo_2']."',
		   parent_inet_memo_3 = '".$_POST['parent_inet_memo_3']."'
		    WHERE parent_inet_id = '".$_REQUEST['parent_inet_id']."';") or die("Error " . mysqli_error($folder));

    $sql = "INSERT INTO tbl_seo_url SET
	seo_url = 'parent=".$_REQUEST['parent_inet_id']."',
	seo_alias = '".$_POST['parent_alias']."' on duplicate key
	UPDATE seo_alias = '".$_POST['parent_alias']."';";
	//echo $sql;
    $folder->query($sql) or die("Error " . mysqli_error($folder));
reload_path();
//ADD  
  }else if (isset($_REQUEST["_add"])){
    $folder->query("INSERT INTO tbl_parent_inet SET
		   parent_inet_parent = '".$_POST['parent_inet_parent']."',
		   parent_inet_sort = '".$_POST['parent_inet_sort']."',
		   parent_inet_1 = '".$_POST['parent_inet_1']."',
		   parent_inet_info = '".$_POST['parent_inet_info']."',
		   parent_inet_type = '".$_POST['parent_inet_type']."',
		   attribute_group_id = '".$_POST['attribute_group_id']."',
		   parent_inet_view = '".$_POST['parent_inet_view']."',
		   parent_inet_memo_1 = '".$_POST['parent_inet_memo_1']."',
		   parent_inet_memo_2 = '".$_POST['parent_inet_memo_2']."',
		   parent_inet_memo_3 = '".$_POST['parent_inet_memo_3']."'") or die("Error " . mysqli_error($folder));
    $insert = $folder->insert_id;
    $_GET['parent_inet_id'] = $insert;
    $folder->query("INSERT INTO tbl_seo_url SET
			seo_url = 'parent=".$insert."',
			seo_alias = '".$_POST['parent_alias']."'") or die("Error " . mysqli_error($folder));
    
    $_REQUEST['parent_inet_id'] = $insert;
  reload_path();
//DELETE  
  }else if (isset($_REQUEST["_dell"])){
    $folder->query("DELETE FROM tbl_parent_inet WHERE parent_inet_id = '".$_POST['_id_value']."'") or die("Error " . mysqli_error($folder));
    $folder->query("DELETE FROM tbl_seo_url WHERE seo_url = 'parent=".$_POST['_id_value']."'") or die("Error " . mysqli_error($folder));
  reload_path();
  }

//==================================SETUP===========================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
	  WHERE 
	  `setup_menu_name` like '%menu%'

";
//echo $tQuery;
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================
$count = 0;
$this_page_name = "edit_parent_inet.php";
$this_table_id_name = "parent_inet_id";
$this_table_name_name = "parent_inet_1";
$return_page = "";
if(isset($_REQUEST['_return_page'])) $return_page=$_REQUEST['_return_page'];

$this_table_name = "tbl_parent_inet";

$sort_find = "";
if(isset($_REQUEST["_sort_find"]))$sort_find=$_REQUEST["_sort_find"];

$sort_find_deliv = "";
if(isset($_REQUEST["_sort_find_deliv"]))$sort_find_deliv=$_REQUEST["_sort_find_deliv"];

$parent_id = $iKlient_id = $_GET[$this_table_id_name];

//if(!$iKlient_id) $iKlient_id=1;
$iKlient_count = 0;

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM " . $this_table_name . " WHERE " . $this_table_id_name . " = " . $iKlient_id);
//echo "SELECT * FROM " . $this_table_name . " WHERE " . $this_table_id_name . " = " . $iKlient_id;
if (!$ver)
{
  echo "Query error - ", $this_table_name;
  exit();
}
if(mysql_num_rows($ver) == 0){
  echo "<h2>Такой категории не найдено!</h2>";
  exit();
}

$parent = mysql_query("SET NAMES utf8");
$parent = mysql_query("SELECT `parent_inet_id`,`parent_inet_1` FROM `tbl_parent_inet` WHERE `parent_inet_id`='".mysql_result($ver,0,"parent_inet_parent")."'");# WHERE klienti_id = " . $iKlient_id);
if (!$parent)
{
  echo "Query error - tbl_price";
  exit();
}


$p_parent = mysql_query("SET NAMES utf8");
$p_parent = mysql_query("SELECT `parent_inet_id`,`parent_inet_1` FROM `tbl_parent_inet` WHERE `parent_inet_parent`='".mysql_result($ver,0,"parent_inet_id")."' ORDER BY `parent_inet_1` ASC");# WHERE klienti_id = " . $iKlient_id);
$p_tovar = mysql_query("SET NAMES utf8");
$p_tovar = mysql_query("SELECT `tovar_id`,`tovar_name_1` FROM `tbl_tovar` WHERE `tovar_inet_id_parent`='".mysql_result($ver,0,"parent_inet_id")."' ORDER BY `tovar_name_1` ASC");# WHERE klienti_id = " . $iKlient_id);
$p_info = mysql_query("SET NAMES utf8");
$p_info = mysql_query("SELECT `info_id`,`info_header_1` FROM `tbl_info` WHERE `info_key`='size' ORDER BY `info_header_1` ASC");# WHERE klienti_id = " . $iKlient_id);
//===========================================================================================================

echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
echo "\n<script src='../js/jquery-2.1.4.min.js'></script>";
echo "\n<script src='attribute/attribute_get.js'></script>";
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script type='text/javascript'>";
//===================JAVA================================
    echo "
    function find_window_script(tbl,id,name,sel_name,target){
    var div_mas =  document.getElementById('find_window');
      div_mas.innerHTML=sel_name+'<br><input type=\"text\"  style=\"width:600px\" onKeyPress=\"find_script(\''+tbl+'\',\''+id+'\',\''+name+'\',this.value)\">';
     div_mas.innerHTML+='<br><select id=\"find_sel\" size=30 style=\"width:600px\" ondblclick=\"set_select_value(\''+target+'\')\"></select>';
     }";
//===============================================================
    echo "
    function set_select_value(target){
    var div_id =  document.getElementById(target);
    var sel =  document.getElementById('find_sel');
    var div_text =  document.getElementById(target+'_text');
      div_id.value=sel.value;
      div_text.value=sel[sel.selectedIndex].text;
     }";
//===============================================================
    echo "
    function find_script(tbl,id,name,find){
    var div_mas =  document.getElementById('find_sel');
    div_mas.options.length=0;
    var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      if(req.readyState==4){
	var responce=req.responseText;
	var str1=responce.split('||');
	var str2='';
	  var count=0;
	  while(str1[count]){
	  str2=str1[count].split('|');
	    div_mas.options[count]=new Option(str2[1],str2[0]);;
	    count++;
	    }
    }}
    req.open(null,'find_sort.php',true);
    req.send({table:tbl,table_id:id,table_name:name,find_str:find});";
    echo "}";

    echo "\n</script>";
//==================END JAVA ============================================
    
echo "<title>Parent inet edit</title>";
echo "\n<body>\n";


//========================================================================================================
echo "\n<form method='get' action='" , $this_page_name , "'>";
echo "\n<table border = 0 cellspacing='0' cellpadding='0'>";

echo "\n<tr><td>",$m_setup['menu selected'],":</td><td>"; # Group klienti
echo "\n<input type='hidden'  style='width:50px'  name='",$this_table_id_name,"' id='",$this_table_id_name,"' value='" . mysql_result($ver,0,"parent_inet_id") . "' OnChange='submit();'>";
echo "\n<input type='text'  style='width:400px'  name='",$this_table_id_name,"_text' id='",$this_table_id_name,"_text' value='" . mysql_result($ver,0,$this_table_name_name) . "' OnClick='submit();'/>
	<input type='button' style='width:50px' onClick='submit();' value='",$m_setup['menu select'],"'>
   <a href='#none' onClick='find_window_script(\"",$this_table_name,"\",\"",$this_table_id_name,"\",\"",$this_table_name_name,"\",\"Parent inet for edit find/Sort\",\"",$this_table_id_name,"\")'> [",$m_setup['menu find'],"] </a>
    </td></tr></table><br><br>";
echo "\n</form>";
//========================================================================================================
//========================================================================================================
echo "<form enctype='multipart/form-data' method='post' action='load_photo.php'>";
echo "<table border = 0 cellspacing='0' cellpadding='0'>";
echo "<tr><td>Load photo:</td><td>"; # Group name 1
echo "<input type='hidden' name='MAX_FILE_SIZE' value='",1048*1048*1048,"'>";
echo "<input type='hidden' name='tovar_id' value='",$iKlient_id,"'>";
echo "<input type='hidden' name='type' value='parent'>";
echo "<input type='file' min='1' max='999' multiple='true' style='width:200px'  name='userfile[]' OnChange='submit();'/></td></tr>";
echo "</table></form>"; 
echo "</body>";
//========================================================================================================

echo "\n<form method='post' >"; //action='edit_table.php'
echo "\n<input type='submit' name='_save' value='",$m_setup['menu save'],"'/>";
echo "\n<input type='submit' name='_add' value='",$m_setup['menu add'],"'/>";
echo "\n<input type='submit' name='_dell' value='",$m_setup['menu dell'],"'/>";
echo "\n<input type='submit' name='_select' value='",$m_setup['menu select and re'],"'/>";
echo "\n<input type='submit' name='_reload_path' value='Перечитать дерево путей'/>";

//$return_page

echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
//echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr><td valign = top>";//table dla find div
echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr>";

//=====================================================================================================================
echo "\n<td>",$m_setup['menu parent name'],":</td><td>"; # Group klienti
echo "\n<input type='hidden'  style='width:0px'  name='parent_inet_parent' id='parent_inet_parent' value='" . mysql_result($ver,0,"parent_inet_parent") . "'/>";
echo "\n<input type='text'  style='width:400px'  id='parent_inet_parent_text' value='" . mysql_result($parent,0,"parent_inet_1") . "'/>";

echo "</td>
      <td><a href='edit_parent_inet.php?parent_inet_id=", mysql_result($ver,0,'parent_inet_parent'),"' target='_blank'>",$m_setup['menu edit'],"</a>
      <a href='#none' onClick='find_window_script(\"tbl_parent_inet\",\"parent_inet_id\",\"parent_inet_1\",\"Up parent find/Sort\",\"parent_inet_parent\")'> [",$m_setup['menu find'],"] </a>
      </td>";
echo "<td rowspan=\"13\" valign=\"top\">";

$count8=0;
$id = $iKlient_id;
/*
while($count8<8){
    if(mysql_result($ver,0,"parent_inet_type")==1){
	echo "<br><img src=\"".HOST_URL."/resources/products/GR$id/GR$id.$count8.small.jpg\" height=\"100px\">";
    }else{
	echo "<br><img src=\"".HOST_URL."/resources/products/GR$id/GR$id.$count8.large.jpg\" height=\"100px\">";
    }
$count8++;
 } 
*/

echo "</td>";
echo "</tr>";
//======================================================================================================================
$alias = "";
$tmp = $folder->query("SELECT * FROM tbl_seo_url WHERE seo_url = 'parent=".$parent_id."'") or die("Error " . mysqli_error($folder));
if($tmp->num_rows > 0){
  while($res = $tmp->fetch_assoc()){
    $alias = $res['seo_alias'];
  }
}
echo "\n<tr><td><b>Alias :</b></td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='parent_alias' value='" . $alias . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>Sort :</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='parent_inet_sort' value='" . mysql_result($ver,0,"parent_inet_sort") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>",$m_setup['menu name2']," 1:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='parent_inet_1' value='" . mysql_result($ver,0,"parent_inet_1") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>URL с фото (автомат!):</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  class='url_photo' placeholder='Копипаст сюда URL фото. Загруза автоматическая!'/></td>";
echo "<td><span class='url_photo_info'></span></td>";
echo "<td></td>";
echo "</tr>";

/*
echo "\n<tr><td>",$m_setup['menu name2']," 2:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='parent_inet_2' value='" . mysql_result($ver,0,"parent_inet_2") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>",$m_setup['menu name2']," 3:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='parent_inet_3' value='" . mysql_result($ver,0,"parent_inet_3") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
*/

$sql = "SELECT attribute_group_id, attribute_group_name FROM tbl_attribute_group ORDER BY attribute_group_name ASC;";
$group = $folder->query($sql) or die(mysql_error());
echo "\n<tr><td>Группа атрибутов:</td><td>"; # Group name 1
echo "\n<select name='attribute_group_id' id='attribute_group_id' style='width:400px'>";# OnChange='submit();'>";
echo '<option value="0">- - -</option>';
while ($grp = $group->fetch_assoc())
{
  echo "\n<option ";
	if ($grp['attribute_group_id'] == mysql_result($ver,0,"attribute_group_id"))  echo "selected ";
	 
  echo "value=" . $grp['attribute_group_id'] . ">" . $grp['attribute_group_name'] . "</option>";
  $count++;
}
echo "</select></td>";
echo '<td><a href="'.HOST_URL.'/admin/main.php?func=attribute_group_edit&attribute_group_id='.mysql_result($ver,0,"attribute_group_id").'" target = "_blank"> редактировать</a></td>';
echo "<td></td>";
echo "</tr>";


//=====================================================================================================================
echo "\n<tr><td>",$m_setup['menu size'],":</td><td>"; # Group klienti
echo "\n<select name='parent_inet_info' id='parent_inet_info' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($p_info))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"parent_inet_info") == mysql_result($p_info,$count,"info_id")){
	    echo "selected ";
	 }
  
  echo "value=" . mysql_result($p_info,$count,"info_id") . ">" . mysql_result($p_info,$count,"info_header_1") . "</option>";
  $count++;
}
echo "</select></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
//======================================================================================================================


echo "\n<tr><td>",$m_setup['menu type'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='parent_inet_type' value='" . mysql_result($ver,0,"parent_inet_type") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>",$m_setup['menu view'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='parent_inet_view' value='" . mysql_result($ver,0,"parent_inet_view") . "'/></td>";
echo "<td>
<a href=\"get_pic_from_jobe.php?id=",mysql_result($ver,0,"parent_inet_id"),"\" target=\"_blank\">Find pic on Job site</a> 
</td>";
echo "<td></td>";
echo "</tr>";

?>
<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
<script>tinymce.init({selector:'textarea'});</script>
<?php
echo "\n<tr><td>",$m_setup['menu memo']," 1:</td><td>"; # Group name 1
echo "<textarea cols='50' rows='50'  name='parent_inet_memo_1'>" . mysql_result($ver,0,"parent_inet_memo_1") . "</textarea></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>",$m_setup['menu memo']," 2:</td><td>"; # Group name 1
echo "<textarea cols='50' rows='0'  name='parent_inet_memo_2'>" . mysql_result($ver,0,"parent_inet_memo_2") . "</textarea></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n<tr><td>",$m_setup['menu memo']," 3:</td><td>"; # Group name 1
echo "<textarea cols='50' rows='0'  name='parent_inet_memo_3'>" . mysql_result($ver,0,"parent_inet_memo_3") . "</textarea></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";


echo "\n</table></form>"; 
  echo "
  </td><td valign='top'>
  <div id='find_window'></div><br>
  <div id='find_div'></div>
  <div id='view'></div>
  </td><td valign='top'>
  <td valign=\"top\" rowspan=\"2\" class=\"photo_list\">
 <img src='/resources/products/!category/". $iKlient_id. ".small.jpg' id='image'></div><br><br>";
 
$tmp = 0;
while($tmp<mysql_num_rows($p_parent)){
  echo "<a class=\"meddium\" href='edit_parent_inet.php?parent_inet_id=", mysql_result($p_parent,$tmp,"parent_inet_id")," ' target='_blank'>[",
	mysql_result($p_parent,$tmp,"parent_inet_1"),"]</a><br>";
$tmp++;
}
$tmp = 0;
while($tmp<mysql_num_rows($p_tovar)){
  echo "<a class=\"meddium\" href='edit_tovar.php?tovar_id=", mysql_result($p_tovar,$tmp,"tovar_id")," ' target='_blank'>--",
	mysql_result($p_tovar,$tmp,"tovar_name_1"),"</a><br>";
$tmp++;
}
  
  
  
echo "</td></tr></table> ";
  
  
  //===========================PHOTO========================
  $link="GR".mysql_result($ver,0,"parent_inet_id");
    $path_to = "../resources/category/";
    	
    	if(isset($_REQUEST['dellphoto'])){
		  die();
				$tmp2 = explode(".",$_REQUEST['dellphoto']);
				$tmp = substr($_REQUEST['dellphoto'],0,-9);
			  //echo $path_to.$tmp2[3].".small.jpg";
			  $massiv = glob($path_to."*.".$tmp2[3].".*.jpg");
			  $x=-1;
				while ($x++ < count($massiv)-1){
				  echo unlink($massiv[$x]);
				  }
		  
			  echo unlink($tmp,"small.jpg");
				if($tmp2[3]==0){	
				  $massiv = glob($path_to."*.small.jpg");
				  if(count($massiv)>0){
				   rename(substr($massiv[0],0,-9)."small.jpg",$tmp."small.jpg");
				   rename(substr($massiv[0],0,-9)."medium.jpg",$tmp."medium.jpg");
				   rename(substr($massiv[0],0,-9)."large.jpg",$tmp."large.jpg");
				  }
				}
      }
    $massiv = glob($path_to."*.small.jpg");
     $photo = "<table><tr>";
    $x=-1;
    $count=0;
        while ($x++ < count($massiv)-1){
	  if($count > 5){
	      $photo .=  "</tr><tr>";
	      $count=0;}
	      $count++;
	  $photo .= "<td><a href='edit_parent_inet.php?parent_inet_id=".$iKlient_id."&dellphoto=".$massiv[$x]."'>".$m_setup['menu dell']."</a>|<br>";
	  $photo .= "<img src='".$massiv[$x]."' width='150'></td>";
	  }
    $photo .= "</tr></table>";
    echo $photo;
echo "\n</body>";

?>

<script>
   $(document).on('change','.url_photo', function(){
     
     var photo_url = $('.url_photo').val();
	//console.log("import/import_url_photo.php?tovar_id=<?php echo $iKlient_id;?>&url="+photo_url);  
       if (photo_url != '') {
	$.ajax({
              type: "GET",
              dataType: "text",
              url: "import/import_url_photo_category.php",
              data: "category_id=<?php echo $iKlient_id;?>&url="+photo_url,
	      beforeSend: function(msg){
		    $('.url_photo_info').html('<font color="red">Загрузка...</font>');
              },
              success: function(msg){
		    console.log(msg);
			
			$('#image').attr('src' ,msg);
			
		    $('.url_photo_info').html('Готово');
                    $('.url_photo').val('');
		    //$('.photo_list').append("<a href='edit_tovar.php?tovar_id=<?php echo $iKlient_id;?>&dellphoto="+msg+"'>Удалить</a>|");
		    //$('.photo_list').append("<a href='edit_tovar.php?tovar_id=<?php echo $iKlient_id;?>&mainphoto="+msg+"'>Главная</a>|<br>");
		    $('<img />', {
			src: msg,
			width: '150px',
			height: '150px'
		    }).appendTo($('.photo_list'));
		   $('.photo_list').append("<br><br>");
   
                    console.log(msg);
                    //$("#carfit_name_list").html(msg);
              }
	});
      }
      
    });
  
</script>
<?php
header ('Content-Type: text/html; charset=utf8');
include 'init.lib.php';
connect_to_mysql();
session_start();

if(isset($_GET['all_photo_dell'])){
    $name = $_GET['all_photo_dell'];
    
    $dir = UPLOAD_DIR.''.$name;
    $dh = opendir($dir);
    while (false !== ($filename = readdir($dh))) {
        if($filename != '.' AND $filename != '..'){
            //$filetime = filemtime($dir . '/' . $filename);
            //echo '<br>'.$dir.'/'.$filename;
            unlink($dir.'/'.$filename);
        }
    }
    
    header('Location: ?tovar_id='.$_GET['tovar_id']);
}


  include "../class/class_alias.php";
  $Alias = new Alias($folder);
  include ("../class/class_category.php");
  $Category = new Category($folder);
  include ("../class/class_attribute.php");
  $Attribute = new Attribute($folder);
  include ("../class/class_product.php");
  $Product = new Product($folder);
  include ("class/class_product_edit.php");
  $ProductEdit = new ProductEdit($folder);
  

if(isset($_GET['key']) AND $_GET['key'] == 'dellall'){
  
  $tmp = explode('#', $_GET['artkl']);
  $artikl = $tmp[0];
  
  if(isset($_GET['key2']) AND $_GET['key2'] == 'OK'){
		$brothers = $Product->getProductBrotherOnArtikl($_GET['artkl']);

		$products_id = '';
		foreach($brothers as $index => $value){
			$products_id .= $value['tovar_id'].',';
			$ver = mysql_query("DELETE FROM `tbl_seo_url` WHERE `seo_url`='tovar_id=".$value['tovar_id']."'");
		}
		$products_id = trim($products_id, ',');
		
		$sql = 'DELETE FROM `tbl_tovar_postav_artikl` WHERE `tovar_artkl` IN ('.$products_id.');';
		$folder->query($sql) or die('alternative artikles dell ((');
		
		$sql = 'DELETE FROM `tbl_tovar` WHERE `tovar_id` IN ('.$products_id.');';
		$folder->query($sql) or die('links dell (( '.$sql);
		
		$sql = 'DELETE FROM `tbl_tovar` WHERE `tovar_id` IN ('.$products_id.');';
		$folder->query($sql) or die('links dell (( '.$sql);
		
		$sql = 'DELETE FROM `tbl_price_tovar` WHERE `price_tovar_id` IN ('.$products_id.');';
		$folder->query($sql) or die('links dell (( '.$sql);
		
		$sql = 'DELETE FROM `tbl_description` WHERE `description_tovar_id` IN ('.$products_id.')';
		$folder->query($sql) or die('links dell (( '.$sql);
		
		$sql = 'DELETE FROM `tbl_attribute_to_tovar` WHERE `tovar_id` IN ('.$products_id.')';
		$folder->query($sql) or die('links dell (( '.$sql);
		
		$sql = 'DELETE FROM `tbl_tovar_links` WHERE `product_id` IN ('.$products_id.')';
		$folder->query($sql) or die('links dell (( '.$sql);

		echo '<h1>Удалено</h1>';
		
  }else{
    
        $brothers = $Product->getProductBrotherOnArtikl($_GET['artkl']);

		$products_id = '';
        $errors = '';
		foreach($brothers as $index => $value){
			
            $sql = "SELECT operation_detail_operation FROM tbl_operation_detail WHERE operation_detail_tovar='".$value['tovar_id']."'";
			$r = $folder->query($sql);
		
            if($r->num_rows > 0){
                 while($tmp = $r->fetch_assoc()){
                    $errors .= '<h4>'.$tmp['operation_detail_operation'].'</h4>';
                }
            }
        
        }
    
        if($errors == ''){
    
            echo '<h2>Удалить все по артиклу - <b>'.$artikl.'<b></h2>
              <a href="edit_tovar.php?key=dellall&key2=OK&artkl='.$_GET['artkl'].'">ДА</a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <a href="edit_tovar.php?tovar_id='.$_GET['product_id'].'">НЕТ</a>';
        }else{
            echo '<h2><a href="edit_tovar.php?tovar_id='.$_GET['product_id'].'">Вернуться в редактор</a></h2>';
            echo '<h2>Этот товар есть в следующих накладных:</h2>';
            echo '<br>'.$errors;
            
        }
  }
  return false;
}
//header("Content-Type: text/html; charset=UTF-8");
//echo "<pre>";  print_r(var_dump( $_SESSION )); echo "</pre>";

if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';
//echo '<pre>'; print_r(var_dump($_SESSION));

// ======= Массив товара =================================================
$sql = "SELECT * FROM tbl_tovar WHERE tovar_id = '".$_GET['tovar_id']."';";
$r = $folder->query($sql) or die(mysql_error());
$tovar = $r->fetch_assoc();
$product_id = $_GET['tovar_id'];
 

//==================================SETUP===========================================
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$setup_1 = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
	  WHERE 
	  `setup_menu_name` like '%menu%'

";
//echo $tQuery;
$setup_1 = mysql_query($tQuery);
$count=0;
while ($count<mysql_num_rows($setup_1)){
 $setup[mysql_result($setup_1,$count,0)]=mysql_result($setup_1,$count,1);
 $count++;
}


//==================================SETUP=MENU==========================================
$count = 0;
$this_page_name = "edit_tovar.php";
$this_table_id_name = "tovar_id";
$this_table_name_name = "tovar_name_1";
$this_table_name = "tbl_tovar";

$return_page = "";
if(isset($_REQUEST['_return_page'])) $return_page=$_REQUEST['_return_page'];


$sort_find = "";
if(isset($_REQUEST["_sort_find"])) $sort_find=$_REQUEST["_sort_find"];

$sort_find_deliv = "";
if(isset($_REQUEST["_sort_find_deliv"])) $sort_find_deliv=$_REQUEST["_sort_find_deliv"];


$iKlient_id = $_GET[$this_table_id_name];

//$return_page .= $iKlient_id;

$iKlient_count = 0;

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM " . $this_table_name . " WHERE " . $this_table_id_name . " = " . $iKlient_id);
if (!$ver)
{
  echo "Query error - ", $this_table_name;
  exit();
}

$lang = mysql_query("SET NAMES utf8");
$lang = mysql_query("SELECT * FROM tbl_web_lang ORDER BY `web_lang_id` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$lang)
{
  echo "Query error - tbl_web_lang";
  exit();
}


$dimension = mysql_query("SET NAMES utf8");
$dimension = mysql_query("SELECT * FROM tbl_tovar_dimension ORDER BY `dimension_name` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$dimension)
{
  echo "Query error - tbl_dimension";
  exit();
}

$tovar_parent = mysql_query("SET NAMES utf8");
$tovar_parent = mysql_query("SELECT * FROM tbl_parent ORDER BY `tovar_parent_name` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$tovar_parent)
{
  echo "Query error - tbl_parent";
  exit();
}

$parent_inet = mysql_query("SET NAMES utf8");
$parent_inet = mysql_query("SELECT `parent_inet_id`,`parent_inet_1`,`parent_inet_type` FROM tbl_parent_inet ORDER BY `parent_inet_1` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$parent_inet)
{
  echo "Query error - tbl_parent";
  exit();
}

$tovar_supplier = mysql_query("SET NAMES utf8");
$tovar_supplier = mysql_query("SELECT `klienti_id`,`klienti_name_1`,`klienti_phone_1` FROM tbl_klienti WHERE `klienti_group`='5' ORDER BY `klienti_name_1` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$tovar_supplier)
{
  echo "Query error - tbl_Supplier";
  exit();
}else{
  $Suppliers = array('0' => 'Всем');
  while($tmp = mysql_fetch_assoc($tovar_supplier)){
    $Suppliers[$tmp['klienti_id']] = $tmp['klienti_name_1'];
  }
}

//Бренды
$r = mysql_query("SET NAMES utf8");
$r = mysql_query("SELECT `brand_id`,`brand_name` FROM tbl_brand ORDER BY `brand_name` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$r)
{
  echo "Query error - tbl_brand";
  exit();
}else{
  $Brands = array();
  while($tmp = mysql_fetch_assoc($r)){
    $Brands[$tmp['brand_id']] = $tmp['brand_name'];
  }
}




$tQuery="SELECT * FROM `tbl_description` WHERE `description_tovar_id`='".$iKlient_id."'";
$tovar_description = mysql_query("SET NAMES utf8");
$tovar_description = mysql_query($tQuery);# WHERE klienti_id = " . $iKlient_id);
//echo $tQuery;
if (!$tovar_description)
{
  echo "Query error - tbl_Description";
  exit();
}

$price_name = mysql_query("SET NAMES utf8");
$price_name = mysql_query("SELECT * FROM `tbl_price` ORDER BY `price_id` ASC");# WHERE klienti_id = " . $iKlient_id);
if (!$price_name)
{
  echo "Query error - tbl_price";
  exit();
}
$price = mysql_query("SET NAMES utf8");
$price = mysql_query("SELECT * FROM tbl_price_tovar WHERE `price_tovar_id`='".$iKlient_id."'");# WHERE klienti_id = " . $iKlient_id);
if (!$price)
{
  echo "Query error - tbl_price_tovar";
  exit();
}
$curr = mysql_query("SET NAMES utf8");
$curr = mysql_query("SELECT * FROM `tbl_currency`");# WHERE klienti_id = " . $iKlient_id);
if (!$curr)
{
  echo "Query error - tbl_price_tovar";
  exit();
}




echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'>";
echo "<link rel='stylesheet' type='text/css' href='css/style.css'>";
?>


<!-- 2 
<script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
//<![CDATA[
        bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
  //]]>
</script>
-->

<?php
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script src='../js/jquery-2.1.4.min.js'></script>";
echo "<!--script src='attribute/attribute_get.js'></script--></header>";
echo "\n<script type='text/javascript'>
    ";
//===================JAVA================================
//==================TREE =================================
//find_window_tree(\"tbl_parent_inet\",\"parent_inet_id\",\"parent_inet_1\",\"".$setup['menu parent inet']. " - ".$setup['menu find']."\",\"tovar_inet_id_parent\",\"0\"
    echo "function set_tree_select(id){
	  document.getElementById('tovar_inet_id_parent').value=id;
	  document.getElementById('bro_category').value=id;
    }
      ";
    
    echo "
    function find_window_tree(tbl,id,name,sel_name,target,find){
    if(find==0){
	var div_mas =  document.getElementById('find_window');
    }else{
	var div_mas =  document.getElementById('find_window*'+find);
    }
    var info='';
     var req=new JsHttpRequest();
     find='`parent_inet_parent`=\''+find+'\'';
      req.onreadystatechange=function(){
       if(req.readyState==4){
	var responce=req.responseText;
	//alert(responce);
	var str1=responce.split('||');
	var str2='';
	  var count=0;
	  while(str1[count]){
	  str2=str1[count].split('|');
	    info += '<table class=\"menu_top\" cellspacing=\"0\" cellpadding=\"0\">';
	    info += '<tr><td colspan=\"2\"><a href=\'#none\' onClick=\'find_window_tree(\"'+tbl+'\",\"'+id+'\",\"'+name+'\",\"'+sel_name+'\",\"'+target+'\",\"'+str2[0]+'\");\'> [+] </a>';
	    info += '<a href=\'#none\' onClick=\'set_tree_select('+str2[0]+');\'>'+str2[1]+'</a></td></td>';
	    info += '<tr><td width=\"20px\"></td><td><div id=\'find_window*'+str2[0]+'\'></div></td>';
	    count++;
	    }
	   // alert(info);
	    div_mas.innerHTML = info;
    }}
    req.open(null,'find_sort_tree.php',true);
    req.send({table:tbl,table_id:id,table_name:name,find_str:find});
 }
 ";
//==================FIND==================================
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
echo "function set_price_auto(tovar,key){
      alert('Pleace reload this page');
       var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      if(req.readyState==4){
	var responce=req.responseText;
	//alert(responce);
      }}
      req.open(null,'edit_tovar_price.php',true);
      req.send({tovar_id:tovar,operation:key});

}
";
    echo "\n</script>";

//==================END JAVA ============================================
$tovar_artikl =  mysql_result($ver,0,"tovar_artkl") ;
echo "<title>Tovar EDIT</title>";
echo "\n<body>\n";
 //========================================================================================================
echo "<form enctype='multipart/form-data' method='post' action='load_photo.php'>";
echo "<table border = 0 cellspacing='0' cellpadding='0'>";
echo "<tr><td>Load photo:</td><td>"; # Group name 1
echo "<input type='hidden' name='MAX_FILE_SIZE' value='",1048*1048*1048,"'>";
echo "<input type='hidden' name='tovar_id' id='tovar_id' value='",$iKlient_id,"'>";
echo "<input type='hidden' name='tovar_artkl' id='tovar_artkl' value='",$tovar_artikl,"'>";
echo "<input type='hidden' name='type' value='tovar'>";
echo "<input type='file' min='1' max='999' multiple='true' style='width:200px'  name='userfile[]' OnChange='submit();'/></td></tr>";
echo "</table></form>"; 
echo "</body>";
//========================================================================================================

echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr><td valign=\"top\" width=\"750px\">";

echo "\n<form method='post' action='edit_tovar_save.php'>";
echo "\n<input type='submit' name='_save' value='",$setup['menu save'],"'/>";
echo "\n<input type='submit' name='_add' value='",$setup['menu add'],"'/>";
echo "\n<input type='submit' name='_dell' value='",$setup['menu dell'],"'/>";
echo "<br><a href='edit_tovar_history.php?tovar_id=",mysql_result($ver,$count,'tovar_id')," ' target='_blank'>&nbsp;&nbsp;|&nbsp;",$setup['menu history'],"&nbsp;|&nbsp;</a>";
echo "<a href='barcode.php?tovar_id=",mysql_result($ver,$count,'tovar_id')," ' target='_blank'>&nbsp;",$setup['menu print barcode'],"&nbsp;|</a>";
echo "<a href='barcode.php?key=price&tovar_id=",mysql_result($ver,$count,'tovar_id')," ' target='_blank'>&nbsp;",$setup['menu print price'],"&nbsp;|</a>
      <a href='barcode.php?key=price_ware&tovar_id=",mysql_result($ver,$count,'tovar_id')," ' target='_blank'>&nbsp;",$setup['menu print war']," 1&nbsp;|</a>
      <a href=\"barcode.php?key=ware&tovar_id=",mysql_result($ver,$count,'tovar_id')," target=\"_blank\">",$setup['menu print war']," 2</a>";
 
//$return_page  <a href='barcode.php?tovar_id=".mysql_result($ver,$count,'operation_detail_tovar')." ' target='_blank'>barcode</a>

echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

echo "\n<table border = 1 cellspacing='0' cellpadding='0'>";


if(strpos($_SESSION[BASE.'usersetup'],'tovar_name_1')>0){
echo "\n<tr><td>",$setup['menu name1']," ",mysql_result($lang,0,"web_lang_lang"),":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_name_1' value='" . mysql_result($ver,0,"tovar_name_1") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
//======================================group================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_parent')>0){
echo "\n<td>",$setup['menu group'],":</td><td>"; # Group klienti
echo "\n<select name='tovar_parent' id='tovar_parent' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($tovar_parent))
{
  echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if (mysql_result($ver,0,"tovar_parent") == mysql_result($tovar_parent,$count,"tovar_parent_id")) echo "selected ";
  
  echo "value=" . mysql_result($tovar_parent,$count,"tovar_parent_id") . ">" . mysql_result($tovar_parent,$count,"tovar_parent_name") . "</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_nakl-group.php?tovar_parent_id=", mysql_result($ver,0,'tovar_parent'),"' target='_blank'>",$setup['menu edit'],"</a>
    <a href='#none' onClick='find_window_script(\"tbl_parent\",\"tovar_parent_id\",\"tovar_parent_name\",\"".$setup['menu group']. " - ".$setup['menu find']."\",\"tovar_parent\"),0'> [",$setup['menu find'],"] </a>
    </td>";
}

echo "\n<tr><td>Спарсить:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='parsing' class='parsing_link' value='" . mysql_result($ver,0,"parsing") . "'/></td>";
echo "<td><a href='" . mysql_result($ver,0,"parsing") . "'>Линк на парс</a></td>";
echo "<td><a href='javascript:' class='startparsing'>Парс</a></td>";
echo "</tr>";

//=====================================================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_code')>0){
echo "\n<tr><td>",$setup['menu code'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_code' value='" . mysql_result($ver,0,"tovar_code") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_barcode')>0){
echo "\n<tr><td>",$setup['menu barcode'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_barcode' value='" . mysql_result($ver,0,"tovar_barcode") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}

if(strpos($_SESSION[BASE.'usersetup'],'original_supplier_link')>0){
echo "\n<tr><td>Артикл поставщика:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='original_supplier_link' value='" . mysql_result($ver,0,"original_supplier_link") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}


echo "\n<tr><td>Alias:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px' class='tovar_alias' name='tovar_alias' value='" . $Alias->getProductAlias($_GET['tovar_id']) . "'/></td>";
echo "<td><a href='javascript:' class='alias_gen'>генерить</a></td>";
echo "<td></td>";
echo "</tr>";

if(strpos($_SESSION[BASE.'usersetup'],'tovar_model')>0){
echo "\n<tr><td>Модель товара:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_model' value='" . mysql_result($ver,0,"tovar_model") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}

echo "\n<tr><td>Сортировка на сайте:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='sort' value='" . mysql_result($ver,0,"sort") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

$main_model = mysql_result($ver,0,"tovar_model");

if(strpos($_SESSION[BASE.'usersetup'],'tovar_artkl')>0){
  echo "\n<tr><td>Артикул:</td><td>"; # Group name 1
  echo "\n<input type='text'  style='width:400px'  name='tovar_artkl' value='" . mysql_result($ver,0,"tovar_artkl") . "'/></td>";
  echo "<td></td>";
  echo "<td></td>";
  echo "</tr>";
}
//======================================parent inet===============================================================================
?>
<tr style="background-color: #A5CAFF;">
<?php
if(strpos($_SESSION[BASE.'usersetup'],'tovar_inet_id_parent')>0){
echo "\n<td>",$setup['menu parent inet'],":</td><td>"; # Group klienti
echo "\n<select name='tovar_inet_id_parent' id='tovar_inet_id_parent' style='width:300px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($parent_inet))
{
  echo "\n<option ";
	#echo mysql_result($ver,0,"klienti_group") , " " , mysql_result($kli_grp,$count,"klienti_group_id");
	if (mysql_result($ver,0,"tovar_inet_id_parent") == mysql_result($parent_inet,$count,"parent_inet_id")){
	    echo "selected ";
	    $parent_inet_type = mysql_result($parent_inet,$count,"parent_inet_type");
	    $parent_inet_id = mysql_result($parent_inet,$count,"parent_inet_id");
	 }
  
  echo "value=" . mysql_result($parent_inet,$count,"parent_inet_id") . ">".mysql_result($parent_inet,$count,"parent_inet_id")." - " . mysql_result($parent_inet,$count,"parent_inet_1") . "</option>";
  $count++;
}
echo "</select>
	<a href='#none' onClick='find_window_tree(\"tbl_parent_inet\",\"parent_inet_id\",\"parent_inet_1\",\"".$setup['menu parent inet']. " - ".$setup['menu find']."\",\"tovar_inet_id_parent\",\"0\");'> <b>[дерево раскрыть]</b></a>
	</td><td><a href='edit_parent_inet.php?parent_inet_id=", mysql_result($ver,0,'tovar_inet_id_parent'),"' target='_blank'>",$setup['menu edit'],"</a>
    <!--a href='#none' onClick='find_window_script(\"tbl_parent_inet\",\"parent_inet_id\",\"parent_inet_1\",\"".$setup['menu parent inet']. " - ".$setup['menu find']."\",\"tovar_inet_id_parent\")'> [",$setup['menu find'],"] </a-->
    </td>";
echo "<td></td>";
echo "</tr>";
}
//====================================================================================================================

echo "\n<tr><td>URL с фото (автомат!):</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  class='url_photo' placeholder='Копипаст сюда URL фото. Загруза автоматическая!'/></td>";
echo "<td><span class='url_photo_info'></span></td>";
echo "<td></td>";
echo "</tr>";

//=====================================================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_video_url')>0){
echo "\n<tr><td><img src='https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcS8SnZT7r1tXRrAIP5jfMhZcXhtPUBOkmwivur3jDPLxa64Wiqa5A' height='20px'></td><td>"; # Group name 1
echo "\n<input type='text' rows='3' style='width:400px'  name='tovar_video_url' placeholder='Копипаст сюда URL Youtube!' value='" . mysql_result($ver,0,"tovar_video_url") . "'/></td>";
echo "<td><span class='url_youtube'></span></td>";
echo "<td></td>";
echo "</tr>";
}
//=====================================================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_min_order')>0){
echo "\n<tr><td>",$setup['menu min order'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_min_order' value='" . mysql_result($ver,0,"tovar_min_order") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}



//===================================================================================================================
//Линки
$r = mysql_query("SET NAMES utf8");
$r = mysql_query("SELECT * FROM tbl_tovar_links WHERE product_id = '".$iKlient_id."';");
if (!$r){
  echo "Query error - tbl_tovar_links";
  exit();
}else{
  $Links = array();
  while($tmp = mysql_fetch_assoc($r)){
    $Links[] = $tmp;
  }
}


  echo '\n<tr style="background-color:#C0FF6D;"><td valign="top">Линки поставщика:<br>! Один на поставщика</td><td>'; # Group name 1
  echo '<table style="background-color:#C0FF6D;"><tr>
	<th>URL</th>
	<th>Поставщик</th>
	</tr>';
	
if($Links){

    foreach($Links as $value){
		//echo "<pre>";  print_r(var_dump( $value )); echo "</pre>";
		echo '<tr class="row_url'.$value['links_id'].'">
			   <td><input type="text"  style="width:150px"  name="url*'.$value['links_id'].'" value="' . $value['url'] . '"/>';
			  
			  if(strpos($value['url'] , 'militarist.') !== false){	   
			   echo '<a href="http://armma.ru/admin/main.php?func=add_products&supplier=militarist&links&url='.$value['url'].'">перепарс</a>';
			  }
		echo 	'</td>';
		echo '<td><select name="postav_url*'.$value['links_id'].'" style="width:195px">';
	    
		foreach($Suppliers as $id => $name){
	      if($id == $value['postav_id']){
			  echo "<option value=" . $id . " selected>" . $name  . "</option>";  
	      }else{
			  echo "<option value=" . $id . ">" . $name  . "</option>";
	      }
	    }
		
		echo '</td>
	    <td><a href="javascript:" class="dell_url" id="dell_url'.$value['links_id'].'">dell</a></td>
	    </tr>';
    }
}
  
  //Для нового
  echo '<tr><td><input type="text"  style="width:150px"  name="url*0" value="" placeholder="новый адрес"/></td>';
      echo '<td><select name="postav_url*0" style="width:195px">';
	  foreach($Suppliers as $id => $name){
	      echo "<option value=" . $id . ">" . $name  . "</option>";
	  }
      echo '</td></tr>';
  echo '</table>
	<td valign="top"><!--a href="main.php?func=alternative_artikles" target="_blank">Альт.редактор</a--></td>';
  echo "<td></td>";
  echo '</tr>';

//====================================================================================================================
$alternative_artkl = $ProductEdit->getProductAlternativeArtikles(mysql_result($ver,0,"tovar_artkl"));

  echo '\n<tr style="background-color:#FFFACD;"><td valign="top">Альтернативные Артикулы:</td><td>'; # Group name 1
  echo '<table style="background-color:#FFFACD;"><tr>
	<th>Арткл</th>
	<th>Поставщик</th>
	</tr>';
	
if($alternative_artkl){

    foreach($alternative_artkl as $value){
    // echo "<pre>";  print_r(var_dump( $value )); echo "</pre>";
	echo '<tr class="row_alt_artkl_'.$value['id'].'">
	      <td><input type="text"  style="width:150px"  name="alt_artkl*'.$value['id'].'" value="' . $value['tovar_postav_artkl'] . '"/></td>';
	echo '<td><select name="postav_alt_artkl*'.$value['id'].'" style="width:195px">';
	    foreach($Suppliers as $id => $name){
	      if($id == $value['postav_id']){
		echo "<option value=" . $id . " selected>" . $name  . "</option>";  
	      }else{
		echo "<option value=" . $id . ">" . $name  . "</option>";
	      }
	    }
	echo '</td>
	    <td><a href="javascript:" class="alt_artkl_dell" id="dell_alt_artkl_'.$value['id'].'">dell</a></td>
	    </tr>';
    }
  }
  
  //Для нового
  echo '<tr><td><input type="text"  style="width:150px"  name="alt_artkl*0" value="" placeholder="новый альтенативный"/></td>';
      echo '<td><select name="postav_alt_artkl*0" style="width:195px">';
	  foreach($Suppliers as $id => $name){
	      echo "<option value=" . $id . ">" . $name  . "</option>";
	  }
      echo '</td></tr>';
  echo '</table>
	<td valign="top"><a href="main.php?func=alternative_artikles" target="_blank">Альт.редактор</a></td>';
  echo "<td></td>";
  echo '</tr>';

//====================================================================================================================

if(strpos($_SESSION[BASE.'usersetup'],'tovar_size')>0){
echo "\n<tr><td>",$setup['menu size'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_size' value='" . mysql_result($ver,0,"tovar_size") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}

echo "\n<tr><td>Время жизни:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='time_to_kill' value='" . mysql_result($ver,0,"time_to_kill") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

/*
if(strpos($_SESSION[BASE.'usersetup'],'tovar_name_2')>0){
echo "\n<tr><td>",$setup['menu name1']," ",mysql_result($lang,1,"web_lang_lang"),":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_name_2' value='" . mysql_result($ver,0,"tovar_name_2") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_name_3')>0){
echo "\n<tr><td>",$setup['menu name1']," ",mysql_result($lang,2,"web_lang_lang"),":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_name_3' value='" . mysql_result($ver,0,"tovar_name_3") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
*/
if(strpos($_SESSION[BASE.'usersetup'],'tovar_memo')>0){
echo "\n<tr><td>",$setup['menu memo'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_memo' value='" . mysql_result($ver,0,"tovar_memo") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}

//======================================dimension================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_dimension')>0){
echo "\n<td>",$setup['menu dimension'],":</td><td>"; # Group klienti
echo "\n<select name='tovar_dimension' style='width:100px'>";# OnChange='submit();'>";
$dim = '';
$count=0;
while ($count < mysql_num_rows($dimension))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"tovar_dimension") == mysql_result($dimension,$count,"dimension_id")){
	    echo "selected ";
	    $dim = mysql_result($dimension,$count,"dimension_name") ;
	}
  echo "value=" . mysql_result($dimension,$count,"dimension_id") . ">" . mysql_result($dimension,$count,"dimension_name")  . "</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_tovar_dimension.php?klienti_id=", mysql_result($ver,0,'tovar_dimension'),"' target='_blank'>",$setup['menu edit'],"</a></td>";
echo "<td></td>";
echo "</tr>";
}//======================================BRAND================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'brand_id')>0){
echo "\n<td>Производитель:</td><td>"; # Group klienti
echo "\n<select name='brand_id' style='width:400px'>
	  <option value=\"0\">Выбрать бренд!</option>
	  ";# OnChange='submit();'>";
$dim = '';
$count=0;
foreach($Brands as $id => $value)
{
  echo "\n<option ";
	if (mysql_result($ver,0,"brand_id") == $id){
	  echo "selected ";
	}
  echo "value=" . $id . ">" . $value . "</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_brands.php?brand_id=", mysql_result($ver,0,'brand_id'),"' target='_blank'>",$setup['menu edit'],"</a></td>";
echo "<td></td>";
echo "</tr>";
}
//=====================================================================================================================

//====================================Supplier==================================================================================
/*
if(strpos($_SESSION[BASE.'usersetup'],'tovar_supplier')>0){
echo "\n<td>",$setup['menu suppliter'],":</td><td>"; # Group klienti
echo "\n<select name='tovar_supplier' style='width:400px'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($tovar_supplier))
{
  echo "\n<option ";
	if (mysql_result($ver,0,"tovar_supplier") == mysql_result($tovar_supplier,$count,"klienti_id")) echo "selected ";
  echo "value=" . mysql_result($tovar_supplier,$count,"klienti_id") . ">" . mysql_result($tovar_supplier,$count,"klienti_name_1")  . "(" . mysql_result($tovar_supplier,$count,"klienti_phone_1") . ")</option>";
  $count++;
}
echo "</select></td>";
echo "<td><a href='edit_klient.php?klienti_id=", mysql_result($ver,0,'tovar_supplier'),"' target='_blank'>",$setup['menu edit'],"</a></td>";
echo "<td></td>";
echo "</tr>";
}
*/
//=====================================================================================================================
//====================================================================================================================
$alternative_artkl = $ProductEdit->getProductPostavInfo(mysql_result($ver,0,"tovar_id"));

  echo '\n<tr style="background-color:#97FFFF;"><td valign="top">Поставщики:</td><td>'; # Group name 1
  echo '<table style="background-color:#97FFFF;">
	<!--tr>
	<th>Арткл</th>
	<th>Поставщик</th>
	</tr-->';

$r = $folder->query('SELECT currency_id, currency_name_shot FROM tbl_currency ORDER BY currency_id ASC;');
$currency_a = array();
while($tmp = $r->fetch_assoc()){
  $currency_a[$tmp['currency_id']] = $tmp['currency_name_shot'];
}

  
if($alternative_artkl){
global $curr_name;
    foreach($alternative_artkl as $value){
      
	echo '<tr class="row_price_'.$value['postav_id'].'">
	      <td>Закуп : <input type="text"  style="width:60px"  name="zakup_row_postav*'.$value['postav_id'].'" value="' . $value['zakup'] . '"/>
		  <select name="zakup_curr_'.$value['postav_id'].'" style="width:45px">';
			foreach($curr_name as $id => $name){
			  if($id == $value['zakup_curr']){
			echo "<option value=" . $id . " selected>" . $name . "</option>";  
			  }else{
			echo "<option value=" . $id . ">" . $name  . "</option>";
			  }
			}
	echo '</select></td>';
	
	echo '<td><select name="row_postav*'.$value['postav_id'].'" style="width:155px">';
	    foreach($Suppliers as $id => $name){
	      if($id == $value['postav_id']){
		echo "<option value=" . $id . " selected>" . $name  . "</option>";  
	      }else{
		echo "<option value=" . $id . ">" . $name  . "</option>";
	      }
	    }
	echo '</select></td>
	    <td rowspan="2"><a href="javascript:" class="row_dell" id="dell_price_'.$value['postav_id'].'">dell</a></td>
	    </tr>';
	echo '<tr class="row_price_'.$value['postav_id'].'">
	      <td>Цена : <input type="text"  style="width:70px"  name="price_row_postav*'.$value['postav_id'].'" value="' . $value['price_1'] . '"/> '.$curr_name[1].'</td>
	      <td>К-во : <input type="text"  style="width:70px"  name="items_row_postav*'.$value['postav_id'].'" value="' . $value['items'] . '"/> '.$dim.'</td>
	    </tr>
	    <tr class="row_price_'.$value['postav_id'].'"><td colspan="3" height="5px" style="background-color:gray;"></td></tr>
	    ';
    }
  }
  
  //Для нового
  
 echo '<tr><td>Закуп : <input type="text"  style="width:60px"  name="zakup_row_postav*0" value="" placeholder="0.00"/>
	  <select name="zakup_curr_0" style="width:45px">';
				 foreach($curr_name as $id => $name){
				   if($id == $value['zakup_curr']){
				 echo "<option value=" . $id . " selected>" . $name . "</option>";  
				   }else{
				 echo "<option value=" . $id . ">" . $name  . "</option>";
				   }
				 }
		 echo '</select></td>';
  echo '<td><select name="row_postav*0" style="width:155px">';
	  foreach($Suppliers as $id => $name){
	      echo "<option value=" . $id . ">" . $name  . "</option>";
	  }
      echo '</td>
	  <td rowspan="2">&nbsp;</td>
	  </tr>';
      echo '<tr class="row_price_0">
	    <td>Цена : <input type="text"  style="width:70px"  name="price_row_postav*0" value="" placeholder="0.00"/> '.$curr_name[1].'</td>
	    <td>К-во : <input type="text"  style="width:70px"  name="items_row_postav*0" value="" placeholder="0"/> '.$dim.'</td>
	  </tr>';
	    
  echo '</table>
	<td valign="top"><a href="main.php?func=suppliers_editor" target="_blank">Поставщики</a></td>';
  echo "<td></td>";
  echo '</tr>';



//echo "</select></td>";//======================================on warehouse inet===============================================================================
if(strpos($_SESSION[BASE.'usersetup'],'on_ware')>0){
echo "\n<td>Наличие на сайте:</td><td>"; # Group klienti
echo "\n<select name='on_ware' id='on_ware' style='width:400px'>";# OnChange='submit();'>";
  echo "\n<option ";if (mysql_result($ver,0,"on_ware") == 0) echo "selected ";
    echo "value='0'>Товара нет в наличии</option>";
  echo "\n<option ";if (mysql_result($ver,0,"on_ware") == 1) echo "selected ";
    echo "value='1'>Товара всегда Есть!</option>";
  echo "\n<option ";if (mysql_result($ver,0,"on_ware") == 2) echo "selected ";
    echo "value='2'>Наличие товара реальное от остатков на складах</option>";
  

echo "</select></td>";
echo "<td><a href='edit_parent_inet.php?parent_inet_id=", mysql_result($ver,0,'tovar_inet_id_parent'),"' target='_blank'>",$setup['menu edit'],"</a>
    <a href='#none' onClick='find_window_script(\"tbl_parent_inet\",\"parent_inet_id\",\"parent_inet_1\",\"".$setup['menu parent inet']. " - ".$setup['menu find']."\",\"tovar_inet_id_parent\")'> [",$setup['menu find'],"] </a>
    <a href='#none' onClick='find_window_tree(\"tbl_parent_inet\",\"parent_inet_id\",\"parent_inet_1\",\"".$setup['menu parent inet']. " - ".$setup['menu find']."\",\"tovar_inet_id_parent\",\"0\");'> [+] </a>
    
    </td>";
echo "<td></td>";
echo "</tr>";
}
//=====================================================================================================================
if(strpos($_SESSION[BASE.'usersetup'],'tovar_seazon')>0){
echo "\n<tr><td>Tovar seazon:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='tovar_seazon' value='" . mysql_result($ver,0,"tovar_seazon") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_purchase_currency')>0){
echo "\n<tr><td>Tovar Purchase Cur:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px' disabled name='tovar_purchase_currency' value='" . mysql_result($ver,0,"tovar_purchase_currency") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_sale_currency')>0){
echo "\n<tr><td>Tovar Sale Cur:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px' disabled name='tovar_sale_currency' value='" . mysql_result($ver,0,"tovar_sale_currency") . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_last_edit')>0){
  
$r = mysql_query("SET NAMES utf8");
$r = mysql_query("SELECT `klienti_name_1` FROM tbl_klienti WHERE `klienti_id`='".mysql_result($ver,0,"tovar_last_edit_user")."'");# WHERE klienti_id = " . $iKlient_id);
$user_edit = '';
if(mysql_num_rows($r) > 0){
	$tmp = mysql_fetch_assoc($r);
    $user_edit = ' ('. $tmp['klienti_name_1'].')';
}
  
  
echo "\n<tr><td>Последнее изменение:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px' disabled name='tovar_last_edit' value='" . mysql_result($ver,0,"tovar_last_edit") . $user_edit . "'/></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
}
if(strpos($_SESSION[BASE.'usersetup'],'tovar_inet_id')>0){
echo "\n<tr><td>Уровень отображения товара<br> (0) не показывать</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px' name='tovar_inet_id' value='" . mysql_result($ver,0,"tovar_inet_id") . "'/></td>";
echo "<td>
      <a href=\"get_pic_from_jobe.php?id=",mysql_result($ver,0,"tovar_inet_id"),"\" target=\"_blank\">Find pic on Job site</a> </td>";
echo "<td></td>";
echo "</tr>";
}


//=========================================================================================================================
//=========================================================================================================================
//============================================PRICE=============================================================================
//=========================================================================================================================
echo "\n<tr style=\"background-color:#FFFACD;\"><td>Цены товара</td><td>"; # Group name 1


    echo "\n<table border = 1 cellspacing='0' cellpadding='0'>";
    
    //======================================================================================================================
    $count=0;
    while ($count < mysql_num_rows($price_name)){
      
      if(strpos($_SESSION[BASE.'usersetup'],"price_tovar_".strval($count+1))>0){
      
	echo "\n
	<tr>
	  <td>",mysql_result($price_name,$count,"price_name"),":</td>
	  <td> 
	    <input style='width:100px' type='text' name='price_tovar_".strval($count+1),"' id='price_tovar_".strval($count+1),"' value='",number_format(mysql_result($price,0,"price_tovar_".strval($count+1)),2,".",""),"'/>
	  </td>
	  <td> 
	    <select name='price_tovar_curr_",$count+1,"' style='width:100px'>";# OnChange='submit();'>";
	    $count_t=0;
	  while ($count_t < mysql_num_rows($curr)){
	  echo "<option ";
	    if (mysql_result($curr,$count_t,"currency_id") == mysql_result($price,0,"price_tovar_curr_".strval($count+1))) echo "selected ";
	    echo "value=" . mysql_result($curr,$count_t,"currency_id")  . ">" . mysql_result($curr,$count_t,"currency_name")."</option>";
	    $count_t++;
	  }
	  echo "</select></td>";
      
	     $html = "<td>
	     <input type='text' style='width:40px;background:#9e9e9e;text-align:right' name='price_tovar_cof_".strval($count+1)."' id='price_tovar_cof_".strval($count+1)."' 
	    value='".number_format(mysql_result($price,0,"price_tovar_cof_".strval($count+1)),"3",".","")."' 
	    onChange='update(this.value,this.id)'/>
	    </td>";
	    
	    /*if($count == 0){
	    $html .="<td>
		    <a href=\"javascript:set_price_auto('$iKlient_id','coef');\">".$setup['menu generate coef']."</a>
		    </td><td>
		    <a href=\"javascript:set_price_auto('$iKlient_id','price');\">".$setup['menu generate price']."</a>
		    ".$setup['menu generate warning']."
		    </td>";
	    }*/
	    
	    $html .= "</tr>";
	    
	    echo $html;
      }
    $count++;
    }
    echo "\n<input type='hidden' name='_price_count' value='",$count,"'/>";
    
    echo "</table>";//</form>";
echo "</td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

//============================================DESCRIPTION=============================================================================
//=========================================================================================================================
echo "\n<tr style=\"background-color:#FFFACD;\"><td colspan=\"4\">"; # Group name 1
echo "<table border = 1 cellspacing='0' cellpadding='0' width='100%'>";
 $count=0;
  while ($count<1){
    $lang_id = mysql_result($lang,$count,"web_lang_id");
    $lang_name = mysql_result($lang,$count,"web_lang_lang");
   // echo "description_".$count+1;
   if(strpos($_SESSION[BASE.'usersetup'],'tovar_size_table')>0){
   
      echo "<tr><td><b>Таблица размеров товара</b>
	    <a href='javascript:' class='translate' data-find-id='size_table'><b>Перевести</b></a>
 	    &nbsp;&nbsp;&nbsp;&nbsp;(<a href='tools/translate_edit.php' target='_blank'>Редактировать</a>)
      <br> 
      <textarea cols='100' width='1000' rows='10' id='size_table' name='tovar_size_table'>",mysql_result($ver,0,"tovar_size_table"),"</textarea>
	</td></tr>";
   }
    echo "<tr><td>&nbsp;</td></tr>
	<tr><td><b>Описание товара</b>
	  <a href='javascript:' class='translate' data-find-id='description_1'><b>Перевести</b></a>
	<br> 
      <textarea cols='100' rows='40' id='description_1' name='description_",($count+1),"'>",mysql_result($tovar_description,0,"description_".($count+1)),"</textarea>
	</td></tr>";
	
    $count++;}
echo "\n<input type='hidden' name='_description_count' value='",$count,"'/></table>";
echo "</td>";
echo "</tr>";  


echo "\n</table>";//</form>"; 
//=========================================================================================================================
//=========================================================================================================================
echo '<!--Фильтры и атрибуты-->';

echo '</td><td valign="top" rowspan = "2">';

  $main_art = $ProductEdit->getProductArtkl(mysql_result($ver,0,"tovar_id"));
  $brothers = $Product->getProductBrotherOnArtikl($main_art);

  $id = $_GET['tovar_id'];
  
  //Получим цвет товара
  $sql = "SELECT attribute_value FROM tbl_attribute_to_tovar 
	  WHERE attribute_id = '2' AND tovar_id = '$id' LIMIT 0, 1";
    $r = $folder->query($sql) or die(mysql_error());
  $main_color = '';
  if($r->num_rows > 0){
    $tmp = $r->fetch_assoc();
    $main_color = $tmp['attribute_value'];
  }
  
 $bro_pro = array();
//Если вообще стоит это выводить!
if(count($brothers) > 0){

	  if(count($brothers) == 1){
		$bro_pro[] = mysql_result($ver,0,"tovar_id");
	  }else{
		foreach($brothers as $index => $value){
		  $bro_pro[] = $value['tovar_id'];
		}
	  }
}		
	  ?>
	  <table class="main_filds">
		<tr>
		  <th colspan="2">Важные поля (Смена)</th>
		</tr>
		<tr>
		  <th width="40px">Название
			<input type="hidden" id="old_art" value="<?php echo $main_art; ?>">
			<input type="hidden" id="bro_prods" value="<?php echo implode(',', $bro_pro); ?>">
		  </th>
		  <th>Параметн</th>
		</tr>
		<tr>
		  <td>Артикл</td>
		  <td align="left"><input type="text" id="bro_art" style="width: 250px;" value="<?php echo $main_art; ?>"></td>
		</tr>
		<tr>
		  <td colspan="2" align="center"><b></b><a href="javascript:" class="dell_all_photo">Удалить все фото товара</a></b></td>
		</tr>
        <script>
            $(document).on('click', '.dell_all_photo', function(){
                if(confirm("Удалить все фото?")){
                    location.href = "?all_photo_dell=<?php echo $main_art;?>&tovar_id=<?php echo $product_id; ?>";
                }
            });
            
        </script>
		<tr>
		  <td>Модель</td>
		  <td align="left"><input type="text" id="bro_model" style="width: 250px;" value="<?php echo $main_model; ?>"></td>
		</tr>
		<tr>
		  <td>Цвет</td>
		  <td align="left"><input type="text" id="bro_color" style="width: 250px;" value="<?php echo $main_color; ?>"></td>
		</tr>
		<tr>
		  <td>Название</td>
		  <td align="left"><input type="text" id="bro_name" style="width: 250px;" value='<?php echo str_replace("'", '\'',mysql_result($ver,0,"tovar_name_1")); ?>'></td>
		</tr>
		<tr>
		  <td>Бренд</td>
		  <td align="left">
			  <select id='bro_brand' style='width:250px'>
			  <option value=\"0\">Выбрать бренд!</option>
			  <?php			  
			  $dim = '';
			  $count=0;
			  foreach($Brands as $id => $value)
			  {
				echo "\n<option ";
				  if (mysql_result($ver,0,"brand_id") == $id){
					echo "selected ";
				  }
				echo "value=" . $id . ">" . $value . "</option>";
				$count++;
			  }
			  echo "</select>";	  
		  ?></td>
		</tr>
	   <tr>
		  <td>Категория</td>
		  <td align="left">
			  <select id='bro_category' style='width:190px'>
			  <?php
			  $count=0;
			  while ($count < mysql_num_rows($parent_inet))
			  {
				echo "\n<option ";
				  if (mysql_result($ver,0,"tovar_inet_id_parent") == mysql_result($parent_inet,$count,"parent_inet_id")){
					  echo "selected ";
					  $parent_inet_type = mysql_result($parent_inet,$count,"parent_inet_type");
					  $parent_inet_id = mysql_result($parent_inet,$count,"parent_inet_id");
				   }
				echo "value=" . mysql_result($parent_inet,$count,"parent_inet_id") . ">".mysql_result($parent_inet,$count,"parent_inet_id")." - " . mysql_result($parent_inet,$count,"parent_inet_1") . "</option>";
				$count++;
			  }
			  ?>
			  </select>
				  <a href='#none' onClick='find_window_tree("tbl_parent_inet","parent_inet_id","parent_inet_1","<?php echo $setup['menu parent inet']. " - ".$setup['menu find']; ?>","bro_category","0");'><b>[дерево]</b></a>
		  </td>
		</tr>
       	<tr>
		  <td>Показывать ( > 0)</td>
		  <td align="left"><input type="text" id="show" style="width: 250px;" value="<?php echo mysql_result($ver,0,"tovar_inet_id"); ?>"></td>
		</tr>
		<tr>
		  <td colspan="2" align="center" height="30px"><b><a href="javascript:" class="change_name">[ Изменить родственные товары ]</a></b></td>
		</tr>
	  </table>
	  
	  
	  
	  <hr>
	  <style>
		.main_filds {
		  background-color: #FF9999;
		  border-spacing: 0;
		  border-collapse: collapse;
		}
		.main_filds th{
		  border: 1px solid black;
		}
		.main_filds td{
		  padding-bottom: 3px;
		  padding-top: 3px;
		  border: 1px solid black;
		}
	  </style>
	  <script>
		$(document).on('click', '.change_name', function(){
		  var ids 	= $('#bro_prods').val();	
		  var old_art = $('#old_art').val();	
		  var new_art = $('#bro_art').val();	
		  var model = $('#bro_model').val();	
		  var color = $('#bro_color').val();	
		  var name 	= $('#bro_name').val();	
		  var show 	= $('#show').val();	
		  var brand 	= $('#bro_brand').val();	
		  var category 	= $('#bro_category').val();	
		  
		  $.ajax({
			type: "POST",
			url: "ajax/ajax_brother_rename.php",
			dataType: "text",
			data: "old_art="+old_art+"&show="+show+"&new_art="+new_art+"&color="+color+"&model="+model+"&brand="+brand+"&category="+category+"&name="+name+"&ids="+ids,
			beforeSend: function(){
				$('.change_name').html('[ Ждите! Страница будет перезагружена... ]');
			},
			success: function(msg){
			  console.log( msg );
			  //location.reload();
			}
		  });
		  
		
		});
	  </script>

<b>Аттрибуты товара <font color="red">Ajax!</font></b><br>
<?php
  $attr_group_id = $Category->getCategoryAttributeGroupID(mysql_result($ver,0,'tovar_inet_id_parent'));
  $group_name = $Category->getAttributeGroupNameOnCategoryID($attr_group_id);
  
  //echo "<pre>"; print_r(var_dump($group_name));
  
  $id = $_GET['tovar_id'];
  
  $sql = "SELECT A.attribute_id, A.attribute_name, T.attribute_value FROM tbl_attribute A
	  LEFT JOIN tbl_attribute_to_group G ON A.attribute_id = G.attribute_id
	  LEFT JOIN tbl_attribute_to_tovar T ON T.attribute_id = A.attribute_id AND T.tovar_id = '$id'
	  WHERE G.attribute_group_id = '$attr_group_id' 
	  ORDER BY G.attribute_sort ASC";
  //echo $sql;
  $group = $folder->query($sql) or die(mysql_error());
  ?>
  <script>
	$(document).on('change', '.attr', function(){
		
		var value = $(this).val();
		var id = $(this).attr('id');
			id = id.replace('attr*', '');
			value = $('.attr'+id).val();
	
		var product_id = "<?php echo $product_id; ?>";
		
		$.ajax({
		  type: "POST",
		  url: "attribute/ajax_edit_attribute.php",
		  dataType: "text",
		  data: "id="+id+"&product_id="+product_id+"&value="+value+"&key=edit",
		  beforeSend: function(){
		  },
		  success: function(msg){
			console.log(  msg );
		  }
		});
		
	});
  </script>
  <?php
  echo '<ul class = "attribute_list"><b>'.$group_name.'</b> <a href="'.HOST_URL.'/admin/main.php?func=attribute_group_edit&attribute_group_id='.$attr_group_id.'" target = "_blank"> редактировать</a>';
	  while($attr = $group->fetch_assoc()){
	      echo '<li>
		  <input type = "text" name = "attr*'.$attr['attribute_id'].'" class = "attr attr'.$attr['attribute_id'].'" id = "attr*'.$attr['attribute_id'].'" value = "'.$attr['attribute_value'].'" placeholder = "'.$attr['attribute_name'].'">
		  '.$attr['attribute_name'].'';
		  $values = $Attribute->getAttributeValues($attr['attribute_id']);
		  
		  
		  
		  if(count($values) > 0){
		    echo '<br>
		      <select id="select_attr'.$attr['attribute_id'].'" class="select_attr" style>';
		      echo '<option value="">------</option>';
		      foreach($values as $val){
			echo '<option value="'.$val.'">'.$val.'</option>';
		      }
		    echo '</select>';
		  }
		  echo '</li>';
	  }
  
  //Тут выведем список товаров братьев
  $brothers = $Product->getProductBrotherOnArtikl(mysql_result($ver,0,"tovar_artkl"));
  if(count($brothers) > 1){
    echo '</ul class = "attribute_list"><b>Список родственных товаров</b> => ';
	echo '</ul class = "attribute_list"><a href="edit_tovar.php?product_id='.$product_id.'&key=dellall&artkl='.mysql_result($ver,0,"tovar_artkl").'" class="dellall">Удалить все</a></b>
		  <br>&nbsp;';
	foreach($brothers as $index => $value){
	  echo '<li><a href=edit_tovar.php?tovar_id='.$value['tovar_id'].'>'.$value['tovar_artkl'].' '.$value['tovar_name_1'].'</a></li>';
	  
	}
    
    echo '<ul>';
  }
  
echo '</td>
  <td valign="top" rowspan = "2">
  <div id="find_window"></div><br>
  <div id="find_div"></div>
  <div id="view"></div>
  </td>
  <td valign="top" rowspan="2" class="photo_list">';
  
  //===========================PHOTO========================
  if ($parent_inet_type==2){
    $link="GR".$parent_inet_id;
  }else{
      //Разделитель артикула на Артикул и размер
      $separator = $setup['tovar artikl-size sep'];
      //Разбиваем атрикл на тело и размер
        $artkl = $tovar_artikl;
        $size = "none";
        if(strpos($tovar_artikl,$separator) !== false){
            $x = explode($separator, $tovar_artikl);
            $artkl = $x[0];
            $size = $x[1];
        }
	$link = $artkl;
  }
  //echo $parent_inet_type;
    $path_to = "../resources/products/".$link."/";
    	
    	if(isset($_REQUEST['dellphoto'])){ 
	  $tmp2 = explode(".",$_REQUEST['dellphoto']);
	  $tmp = substr($_REQUEST['dellphoto'],0,-9);
	//echo $path_to.$tmp2[3].".small.jpg";
	$massiv = glob($path_to."*.".$tmp2[3].".*.jpg");
	$x=-1;
	  while ($x++ < count($massiv)-1){
	     unlink($massiv[$x]);
	    }

	 //unlink($tmp,"small.jpg");
	  if($tmp2[3]==0){	
	    $massiv = glob($path_to."*.small.jpg");
	    if(count($massiv)>0){
	     rename(substr($massiv[0],0,-9)."small.jpg",$tmp."small.jpg");
	     rename(substr($massiv[0],0,-9)."medium.jpg",$tmp."medium.jpg");
	     rename(substr($massiv[0],0,-9)."large.jpg",$tmp."large.jpg");
	    }
	  }
      echo "<meta http-equiv=\"refresh\" content=\"0;URL=edit_tovar.php?tovar_id=$iKlient_id\">";
      //header('Refresh:0;  url=edit_tovar.php?tovar_id='.$iKlient_id);
      }
    	if(isset($_REQUEST['mainphoto'])){ 
	  $tmp2 = explode(".",$_REQUEST['mainphoto']);
	  $tmp = substr($_REQUEST['mainphoto'],0,-9);
	  $massiv = glob($path_to."*.".$tmp2[3].".*.jpg");
	  if($tmp2[3]>0){	
	    $massiv = glob($path_to."*.small.jpg");
	    if(count($massiv)>0){
	     rename($tmp."small.jpg",$tmp."small_tmp.jpg");
	     rename($tmp."medium.jpg",$tmp."medium_tmp.jpg");
	     rename($tmp."large.jpg",$tmp."large_tmp.jpg");
	    
    	     rename(substr($massiv[0],0,-9)."small.jpg",$tmp."small.jpg");
	     rename(substr($massiv[0],0,-9)."medium.jpg",$tmp."medium.jpg");
	     rename(substr($massiv[0],0,-9)."large.jpg",$tmp."large.jpg");

	     rename($tmp."small_tmp.jpg",substr($massiv[0],0,-9)."small.jpg");
	     rename($tmp."medium_tmp.jpg",substr($massiv[0],0,-9)."medium.jpg");
	     rename($tmp."large_tmp.jpg",substr($massiv[0],0,-9)."large.jpg");
	    }
	  }
      }

      
     $massiv = glob($path_to."*.small.jpg");
     $photo = "";
    $x=-1;
    $count=0;
        while ($x++ < count($massiv)-1){
	  if($count > 5){
	      $photo .=  "";
	      $count=0;}
	      $count++;
	  $photo .= "<a href='edit_tovar.php?tovar_id=".$iKlient_id."&dellphoto=".$massiv[$x]."'>".$setup['menu dell']."</a>|";
	  $photo .= "<a href='edit_tovar.php?tovar_id=".$iKlient_id."&mainphoto=".$massiv[$x]."'>".$setup['menu main']."</a>|<br>";
	  $photo .= "<img src='".$massiv[$x]."' width='150'><br><br>";
	  }
    $photo .= "";
    echo $photo;
  
echo '</td><td valign="top" rowspan="2" class="photo_list">
		<b>Напоминалка цветов</b><br>
	  ';
		  
		  $colors = $Attribute->getAttributeValues(2);
		  $Attribute->resetColorsVariants($colors);
		  $values = $Attribute->getColorsVariants();
		  
		  foreach($values as $count => $val){
			if($count < 10){
			  echo '<br>00'.$count.' = '.$val;  
			}elseif($count < 100){
			  echo '<br>0'.$count.' = '.$val;
			}else{
			  echo '<br>'.$count.' = '.$val;  
			}
		  }
	

echo '</td></tr><tr>
  <td>';
  //echo "
  //</td><td valign='top'>
  //<div id='find_window'></div><br>
  //<div id='find_div'></div>
  //<div id='view'></div>
  echo "</td></tr></table></form> ";
  

  
echo "\n</body>";

?>
<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
<script>
tinymce.init({selector:'textarea'});


  $(document).on('change','.select_attr', function(){
   
      var id = $(this).attr('id');
      
      id = id.replace('select_', '');

	  var value = $(this).val();
	  console.log(id+' '+value);
	 
	   $('.'+id).val(value);
	  $('.'+id).trigger('change');
     
    });
  
  $(document).on('click','.startparsing', function(){
      var link = $('.parsing_link').val();
      
      if (link != '') {
		$.ajax({
				  type: "POST",
				  dataType: "text",
				  url: "parsing/ajax_parsing.php",
				  data: "link="+link,
				  success: function(msg){
						console.log(msg);
						//$("#carfit_name_list").html(msg);
				  }
		});
      }
    
    });
  
   $(document).on('change','.url_photo', function(){
     
     var photo_url = $('.url_photo').val();
	//console.log("import/import_url_photo.php?tovar_id=<?php echo $iKlient_id;?>&url="+photo_url);  
       if (photo_url != '') {
	$.ajax({
              type: "GET",
              dataType: "text",
              url: "import/import_url_photo.php",
              data: "tovar_id=<?php echo $iKlient_id;?>&url="+photo_url,
	      beforeSend: function(msg){
		    $('.url_photo_info').html('<font color="red">Загрузка...</font>');
              },
              success: function(msg){
		    console.log(msg);
		    $('.url_photo_info').html('Готово');
                    $('.url_photo').val('');
		    $('.photo_list').append("<a href='edit_tovar.php?tovar_id=<?php echo $iKlient_id;?>&dellphoto="+msg+"'>Удалить</a>|");
		    $('.photo_list').append("<a href='edit_tovar.php?tovar_id=<?php echo $iKlient_id;?>&mainphoto="+msg+"'>Главная</a>|<br>");
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
   
    $(document).on('click','.alias_gen', function(){
     
   	$.ajax({
              type: "GET",
              dataType: "text",
              url: "alias/get_tovar_alias.php",
              data: "tovar_id=<?php echo $iKlient_id;?>",
	      beforeSend: function(msg){
		    //$('.url_photo_info').html('<font color="red">Загрузка...</font>');
              },
              success: function(msg){
		    console.log(msg);
		    $('.tovar_alias').val(msg);
              }
	});
      
    });
    
    $(document).on('click','.translate', function(){

 	var find_id = $(this).attr('data-find-id');
  	var find_txt = tinyMCE.get(find_id).getContent();
	
	find_txt = find_txt.replace(/&/g, '***')
	
	$.ajax({
              type: "POST",
              dataType: "text",
              url: "tools/translate_ajax.php",
              data: "txt="+find_txt,
	      beforeSend: function(msg){
		    //$('.url_photo_info').html('<font color="red">Загрузка...</font>');
              },
              success: function(msg){
		   //msg = msg.replace(/***/g, '&');
		   tinyMCE.get(find_id).setContent(msg, {format : 'raw'});
              }
	});
      
    });
   
   $(document).on('click','.dell_url', function(){
      var id = $(this).attr('id');
      id = id.replace('dell', 'row');
      
      $('.'+id).remove();
      
    });
   
   $(document).on('click','.alt_artkl_dell', function(){
      var id = $(this).attr('id');
      id = id.replace('dell', 'row');
      
      $('.'+id).remove();
      
    });
   
  $(document).on('click','.row_dell', function(){
      var id = $(this).attr('id');
      id = id.replace('dell', 'row');
      
      $('.'+id).remove();
      
    });
   
</script>

<?php

include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
echo '<h3><a href=\'/admin/setup.php\'>>> Настройки</a></h3>';

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
//$iStatusSelect = $_POST["iStatus"];
//$iKlient_id = $_GET["operation_id"];


//$find_supplier = ""; 
//if(isset($_GET["_supplier"])){$find_supplier=$_GET["_supplier"];}



//$find_supplier = $_GET["_supplier"];
//$find_parent = $_GET["_parent"];

$find_str_sql="";
$return_page = "edit_tovar_table.php";//?operation_id=" . $iKlient_id."&_from=".$tmp_from."&_to=".$tmp_to."&_find=".$find_str."&_supplier=".$find_supplier."&_parent=".$find_parent;
$warehouse_count=0;
$color_null = "transparent";
$color_from = "peachpuff";
$color_to = "darkseagreen1";
$color_tovar1 = "#ADD8E6";
$color_tovar2 = "#ADD8D0";
$color_tovar_now = $color_tovar1;
$warehouse_row_limit = 15;

$tmp= 1;

$sql = "SELECT attribute_group_id, attribute_group_name FROM tbl_attribute_group ORDER BY attribute_group_name ASC;";
  $groups = $folder->query($sql) or die("attribute_group_id".mysql_error());
  $group = array();
  while ($grp = $groups->fetch_assoc())
  {
    $group[$grp['attribute_group_id']] = $grp['attribute_group_name'];
  }

$find_str_sql = "";
$find_str ="";
if(isset($_GET["_find"])){
$find_str = $_GET["_find"];
$find_str_sql .= " and (upper(parent_inet_1) like '%" . mb_strtoupper($_GET["_find"],'UTF-8') . "%' or upper(parent_inet_2) like '%" . mb_strtoupper($_GET["_find"],'UTF-8') . "%')";
}

$find_parent = "0";
if(isset($_GET["_parent"])){
$find_parent = $_GET["_parent"];
}
$find_str_sql .= " and (`parent_inet_parent`='" . $find_parent . "')";

$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `tbl_parent_inet`.*, `parent_inet_type_id`,`parent_inet_type_name` FROM `tbl_parent_inet`,`tbl_parent_inet_type` WHERE `parent_inet_type_id`=`parent_inet_type` " . $find_str_sql . " ORDER BY `parent_inet_sort` ASC";
$ver = mysql_query($tQuery);
if (!$ver)
{
  echo "<br>",$tQuery;
  exit();
}
if($find_parent > 0){
      $parent = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT `parent_inet_parent` FROM `tbl_parent_inet` WHERE `parent_inet_id`='$find_parent'";
      $parent = mysql_query($tQuery);
	if (!$ver)
	{
	  echo "<br>",$tQuery;
	  exit();
	}
}	
//echo mysql_num_rows($ver));

//header ('Content-Type: text/html; charset=utf8');
echo "<header><title>Edit parent inet</title><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

//echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
//==================JAVA===========================================
echo "\n<script src='JsHttpRequest.js'></script>
<script type=\"text/javascript\" src=\"../js/jquery-2.1.4.min.js\"></script>";
echo "\n<script type='text/javascript'>";
//================================SET COLOR=====================================
//================================SET PRICE===============kogda vibor konkretnoj ceni
echo "\nfunction update(value,name){
     var tovar = name.split('*');
    var table = name.split('_');
    var id='';
    if (table[0]=='price'){
      table='tbl_price_tovar';
      id='price_tovar_id';
    }else{
      table='tbl_parent_inet';
      id='parent_inet_id';
    }
    
 //   document.getElementById('test').innerHTML = 'value='+value+'<br>tovar[0]='+tovar[0]+'<br>tovar[1]='+tovar[1];
  
  if(tovar[0]=='menu'){
	if(value==2){ //EDIT
	  var params='parent_inet_id='+tovar[1];
	  var win = window.open('edit_parent_inet.php?'+params,'_blank');
	  win.focus();
	  }
	if(value==1){ //COPY
	 edit_table_call('edit_table.php',tovar[1],'copy','tbl_parent_inet','parent_inet_id');
	}
	if(value==3){ //DELL
	 edit_table_call('edit_table.php',tovar[1],'dell','tbl_parent_inet','parent_inet_id');
	 //window.location.reload(true);
 	}
    }else{
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
        if(req.readyState==4){
	var responce=req.responseText;
	document.getElementById('test').innerHTML=responce;
	
      }}
      req.open(null,'save_table_field.php',true);
      req.send({table:table,name:tovar[0],value:value,w_id:id,w_value:tovar[1]});
      
    }
    }";
    
echo "\nfunction edit_table_call(webe,row,key,tbl,id){
      if(key=='dell'){
 	  document.getElementById('parent_inet_1*'+row).value = 'delleted';
	  var sender = {_dell:key,_table_name:tbl,_id_name:id,_id_value:row};
	}else{
	  var sender = {_copy:key,_table_name:tbl,_id_name:id,_id_value:row};
	}
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      window.location.reload(true);
      if(req.readyState==4){
	var responce=req.responseText;
	document.getElementById('test').innerHTML=responce+'0000';
	window.opener.location.reload();
      }}
 	  req.open(null,'edit_table.php',true);
	  req.send(sender);
      
 
    }";

    echo "\n</script></header>";
//================== END JAVA ================================
echo "\n<body>\n";//<p  style='font-family:vendana;font-size=22px'>";
//============FIND====================================================================================
echo "\n<form method='get' action='edit_inet_parent_table.php'>";
//echo "\n<input type='hidden' name='operation_id' value='"  , $iKlient_id  , "'/>";
echo "\nString:<input type='text' style='width:250px' name='_find' value='" , $find_str , "' onChange='submit'/>";

echo "\nParent:<select name='_parent' style='width:550px' onChange='submit();'>";
    $count=0;
     
  echo "<option value='0' selected>parent</option>";
  echo "<option value='".mysql_result($parent,0,0)."'>..</option>";
  
  while ($count < mysql_num_rows($ver))
    {
	echo "\n<option ";
	echo "value=" . mysql_result($ver,$count,"parent_inet_id") . ">" . mysql_result($ver,$count,"parent_inet_1") . "</option>";
	$count++;
    }
echo "</select>";

echo "\n</form>";
//=====================================================================================================
  include ("../class/class_category.php");
  $Category = new Category($folder);


echo "\n<form method='post' action='edit_table_table.php'>";
//echo "\n<input type='submit' name='_add' value='add'/>";
//$return_page
//echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
//echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
//echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";
  
echo "\n<input type='hidden' name='_page_to_return' value='" , $return_page , "'/>";

echo "<div id='row*table'><table width=100% cellspacing='0' cellpadding='0' style='border-left:1px solid;border-right:1px solid;border-top:1px solid'></div>"; //class='table'
$html = "<tr>
      <th>id</th>
      <th>menu</th>
      <th>id</th>
      <th>parent_id</th>      
      <th>Sort</th>
      <th>Товаров</th>
      <th>RUS</th>
      <th>Аттрибуты</th>
      <th>Alias</th>
      <!--th>UKR</th-->
      <!--th>USD</th-->
      <th>TYPE</th>
      <th>Memo RUS</th>
      <!--th>Memo UKR</th-->
      <!--th>Memo USD</th-->
      <th>View Level</th>
      </tr>";
echo "<div id='row*0'>",$html,"</div>";      
      //****************************************************** ROWS

$count=0;
$TotalProducts = 0;
while($count<mysql_num_rows($ver)){ 
$html="";
$html .= "<tr>"; 

$html .= "<td align='right'><font size='1'>"
    .mysql_result($ver,$count,"parent_inet_id").
    "</font></td>";
    //----
  $html .= "<td>
    <select style='width:60px' id='menu*".mysql_result($ver,$count,"parent_inet_id")."'
    onChange='update(this.value,this.id)'>
      <option selected value=0>menu</option>
      <option value=1>".$m_setup['menu copy']."</option>
      <option value=2>".$m_setup['menu edit']."</option>
      <option value=3>".$m_setup['menu dell']."</option>
    </select></td>"; 
  //----
  $html .= "<td>
    <input type='text' style='width:45px' id='parent_inet_id*".mysql_result($ver,$count,"parent_inet_id")."' 
      value='" . mysql_result($ver,$count,"parent_inet_id") . "' />
    </td>";
  //----
  $html .= "<td>
    <input type='text' style='width:45px' id='parent_inet_parent*".mysql_result($ver,$count,"parent_inet_id")."' 
      value='" . mysql_result($ver,$count,"parent_inet_parent") . "' onChange='update(this.value,this.id)'/>
    </td>";
  //----
  $html .= "<td>
    <input type='text' style='width:45px' id='parent_inet_sort*".mysql_result($ver,$count,"parent_inet_id")."' 
      value='" . mysql_result($ver,$count,"parent_inet_sort") . "' onChange='update(this.value,this.id)'/>
    </td>";
 //----
  $html .= '<td>';
      $tmp = $Category->getProductCountInCategory(mysql_result($ver,$count,"parent_inet_id"));
      $TotalProducts += $tmp;
      $html .= '<b>'.$tmp.'</b>';
  $html .= '</td>';
  //----
  $html .= "<td>
    <input type='text' style='width:200px' id='parent_inet_1*".mysql_result($ver,$count,"parent_inet_id")."' 
      value='" . mysql_result($ver,$count,"parent_inet_1") . "' onChange='update(this.value,this.id)'/>
    </td>";

  //---- Аттрибуты
  $html .= "<td><select id='attribute_group_id*".mysql_result($ver,$count,"parent_inet_id")."' style='width:300px' onChange='update(this.value,this.id)'>";# OnChange='submit();'>";
  $html .= '<option value="0">- - -</option>';
  
  foreach($group as $index => $value)
  {
    $html .= "\n<option ";
	  if ($index == mysql_result($ver,$count,"attribute_group_id"))  $html .= "selected ";
    $html .= "value=" . $index . ">" . $value . "</option>";
  }
  $html .= "</select></td>";
  //---- Alias
  $alias = '';
    $sql = "SELECT seo_alias FROM tbl_seo_url WHERE seo_url = 'parent=".mysql_result($ver,$count,"parent_inet_id")."'";
    $result = $folder->query($sql) or die('Seo error '.mysql_error());
    if($result->num_rows > 0){
	$tmp = $result->fetch_assoc();
	$alias = $tmp['seo_alias'];
    }
    
  $html .= "<td>
    <input type='text' style='width:150px' id='alias*".mysql_result($ver,$count,"parent_inet_id")."' 
      value = '" . $alias . "' onChange='updateAlias(this.value,this.id)'/>
    </td>";
  //----
  /*
  $html .= "<td>
    <input type='text' style='width:200px' id='parent_inet_2*".mysql_result($ver,$count,"parent_inet_id")."' 
      value='" . mysql_result($ver,$count,"parent_inet_2") . "' onChange='update(this.value,this.id)'/>
    </td>";
  //----
  $html .= "<td>
    <input type='text' style='width:200px' id='parent_inet_3*".mysql_result($ver,$count,"parent_inet_id")."' 
      value='" . mysql_result($ver,$count,"parent_inet_3") . "' onChange='update(this.value,this.id)'/>
    </td>";
  */
  //----
  $html .= "<td>
    <input type='text' style='width:30px' id='parent_inet_type*".mysql_result($ver,$count,"parent_inet_id")."' 
      value='" . mysql_result($ver,$count,"parent_inet_type") . "' onChange='update(this.value,this.id)'/>
    </td>";
  //----
  $html .= "<td>
    <input type='text' style='width:600px' id='parent_inet_memo_1*".mysql_result($ver,$count,"parent_inet_id")."' 
      value='" . mysql_result($ver,$count,"parent_inet_memo_1") . "' onChange='update(this.value,this.id)'/>
    </td>";
  //----
  /*
  $html .= "<td>
    <input type='text' style='width:200px' id='parent_inet_memo_2*".mysql_result($ver,$count,"parent_inet_id")."' 
      value='" . mysql_result($ver,$count,"parent_inet_memo_2") . "' onChange='update(this.value,this.id)'/>
    </td>";
  //----
  $html .= "<td>
    <input type='text' style='width:200px' id='parent_inet_memo_3*".mysql_result($ver,$count,"parent_inet_id")."' 
      value='" . mysql_result($ver,$count,"parent_inet_memo_3") . "' onChange='update(this.value,this.id)'/>
    </td>";
  */
  //----
  $html .= "<td>
    <input type='text' style='width:40px' id='parent_inet_view*".mysql_result($ver,$count,"parent_inet_id")."' 
      value='" . mysql_result($ver,$count,"parent_inet_view") . "' onChange='update(this.value,this.id)'/>
    </td>";
  //----

 /* $html .= "<td>
    <select style='width:60px' id='tovar_dimension*".mysql_result($ver,$count,"parent_inet_id")."'
    onChange='update(this.value,this.id)'>";
      $count1=0;
      while ($count1 < mysql_num_rows($dim))
	{ $html .= "\n<option ";
	if (mysql_result($ver,$count,"tovar_dimension") == mysql_result($dim,$count1,"dimension_id")) $html .= "selected ";
      $html .= "value=" . mysql_result($dim,$count1,"dimension_id") . ">".mysql_result($dim,$count1,"dimension_name")."</option>";
      $count1++;
      }
    $html .= "</select></td>"; */
  //----




$html .= "</tr>";
echo "<div id='row*".mysql_result($ver,$count,"parent_inet_id")."'>",$html,"</div>";
$count++;
}
echo "<div id='row*table_end'></table></div>
<h3>Всего товаров на сайте: $TotalProducts</h3>
<div id='test'>-></div>
      \n</form>
      \n</body>";
?>
<!--Сохранения алиаса-->
<script>
function updateAlias(alias, parent){
  parent = parent.split('*');
    console.log('update');
  	$.ajax({
		url: 'inet_parent/updatealias.php?parent='+parent[1]+'&alias='+alias,
		cache: false,
		success: function(html){
                  console.log(html);
                        
		}
	});
}
</script>


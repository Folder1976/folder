<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
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
$this_page_name = "edit_habibulin_parent.php";
$this_table_id_name = "habibulin_parent_id";
$this_table_name_name = "habibulin_parent_name";
$return_page = "";
if(isset($_REQUEST['_return_page'])) $return_page=$_REQUEST['_return_page'];

$this_table_name = "tbl_habibulin_parent";

$sort_find = "";
if(isset($_REQUEST["_sort_find"]))$sort_find=$_REQUEST["_sort_find"];

//$sort_find_deliv = "";
//if(isset($_REQUEST["_sort_find_deliv"]))$sort_find_deliv=$_REQUEST["_sort_find_deliv"];

$iKlient_id = $_REQUEST[$this_table_id_name];
if(!$iKlient_id) $iKlient_id=1;
$iKlient_count = 0;

$ver = mysql_query("SET NAMES utf8");
if($iKlient_id=="last"){
  $ver = mysql_query("SELECT * FROM " . $this_table_name . " ORDER BY `$this_table_id_name` DESC LIMIT 1");
}else{
  $ver = mysql_query("SELECT * FROM " . $this_table_name . " WHERE " . $this_table_id_name . " = " . $iKlient_id);
}
  $ver_all = mysql_query("SELECT `$this_table_id_name`, `$this_table_name_name` FROM " . $this_table_name);

//echo "SELECT * FROM " . $this_table_name . " WHERE " . $this_table_id_name . " = " . $iKlient_id;
if (!$ver)
{
  echo "Query error - ", $this_table_name;
  exit();
}

header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
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
    
echo "<title>",$m_setup['setup habibulin parent'],"</title>";
echo "\n<body>\n";


//========================================================================================================
echo "\n<form method='get' action='" , $this_page_name , "'>";
echo "\n<table border = 0 cellspacing='0' cellpadding='0'>";

echo "\n<tr><td>",$m_setup['menu selected'],":</td><td>"; # Group klienti
echo "\n<input type='hidden'  style='width:50px'  name='",$this_table_id_name,"' id='",$this_table_id_name,"' value='" . mysql_result($ver,0,"klienti_id") . "' OnChange='submit();'/>";
echo "\n<input type='text'  style='width:400px'  name='",$this_table_id_name,"_text' id='",$this_table_id_name,"_text' value='" . mysql_result($ver,0,$this_table_name_name) . "' OnClick='submit();'/>
	<input type='button' style='width:50px' onClick='submit();' value='",$m_setup['menu select'],"'>
   <a href='#none' onClick='find_window_script(\"",$this_table_name,"\",\"",$this_table_id_name,"\",\"",$this_table_name_name,"\",\"Parent inet for edit find/Sort\",\"",$this_table_id_name,"\")'> [",$m_setup['menu find'],"] </a>
    </td></tr></table><br><br>";
echo "\n</form>";
//========================================================================================================
//========================================================================================================
//========================================================================================================

echo "\n<form method='post' action='edit_table.php'>";
echo "\n<input type='submit' name='_add' value='",$m_setup['menu add'],"'/>";
echo "\n<input type='submit' name='_save' value='",$m_setup['menu save'],"'/>";
echo "\n<input type='submit' name='_dell' value='",$m_setup['menu dell'],"'/>";
echo "\n<input type='submit' name='_select' value='",$m_setup['menu select and re'],"'/>";

//$return_page

echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
//echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr><td>";//table dla find div
echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr>";

//=====================================================================================================================

echo "\n<tr valign=\"top\"><td>",$m_setup['menu name1'],":</td><td>"; # Group name 1
echo "<input type=\"text\" name=\"habibulin_parent_name\" style=\"width:450px\" value=\"" . mysql_result($ver,0,"habibulin_parent_name") . "\" /></td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr valign=\"top\"><td></td><td>"; # Group name 1
echo "---</td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

$tmp = 0;
while($tmp < mysql_num_rows($ver_all)){
  echo "<tr><td></td>
	<td><a href=\"".$this_page_name."?".$this_table_id_name."=".mysql_result($ver_all,$tmp,$this_table_id_name)."\">"
  .mysql_result($ver_all,$tmp,$this_table_name_name)."</a>
	</td>
	<td></td>
	<td></td>
	</tr>";

$tmp++;
}

echo "\n</table></form>"; 
  echo "
  </td><td valign='top'>
  <div id='find_window'></div><br>
  <div id='find_div'></div>
  <div id='view'></div>
  </td><td valign='top'>";

  
  
  
echo "</td></tr></table> ";


echo "\n</body>";

?>

<?php
//header ('Content-Type: text/html; charset=utf8');
include 'init.lib.php';


connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
echo "<link rel='stylesheet' type='text/css' href='sturm.css'>";
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script type='text/javascript'>";
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
$this_page_name = "edit_info.php";
$this_table_id_name = "info_id";
$this_table_name_name = "info_header_1";
$return_page = "";
if(isset($_REQUEST['_return_page'])) $return_page=$_REQUEST['_return_page'];

$this_table_name = "tbl_info";

$sort_find = "";
if(isset($_REQUEST["_sort_find"]))$sort_find=$_REQUEST["_sort_find"];

$info_list_sort = "news";
if(isset($_REQUEST['info_list_sort'])) $info_list_sort=$_REQUEST['info_list_sort'];

$iKlient_id=1;
if(isset($_REQUEST[$this_table_id_name])) $iKlient_id=$_REQUEST[$this_table_id_name];


$ver = mysql_query("SET NAMES utf8");
  $sql = "SELECT * FROM " . $this_table_name . " WHERE " . $this_table_id_name . " = " . $iKlient_id . " ORDER BY $this_table_id_name DESC";
  $ver = mysql_query($sql);

  $info_list = mysql_query("SELECT * FROM " . $this_table_name . " WHERE `info_key` = '" . $info_list_sort . "'  ORDER BY `$this_table_id_name` DESC");
  $info_key = mysql_query("SELECT `info_key` FROM " . $this_table_name . " GROUP BY `info_key`");


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
    
echo "<title>",$m_setup['menu news']," - ",$m_setup['menu help'],"</title>";
echo "\n<body>\n";


//========================================================================================================
echo "\n<form method='get' action='" , $this_page_name , "'>";
echo "\n<table border = 0 cellspacing='0' cellpadding='0'>";

echo "\n<tr><td>",$m_setup['menu selected'],":</td><td>"; # Group klienti
//=====================================================================================================================
echo "\n<td>Type: </td><td>"; # Group klienti
echo "<select name='info_list_sort' style='width:400px' onChange=\"submit();\">";# OnChange='submit();'>";
$tmp=0;
while ($tmp < mysql_num_rows($info_key))
{
  echo "\n<option ";
	//if (mysql_result($ver,0,"info_key") ==
	  if($info_list_sort == mysql_result($info_key,$tmp,"info_key")) echo "selected ";
  
  echo "value=" . mysql_result($info_key,$tmp,"info_key") . ">" . mysql_result($info_key,$tmp,"info_key") . "</option>";
  $tmp++;
}
echo "</select></td>";
echo "<td></td>";
echo "</tr><table>";
echo "\n</form>";
//========================================================================================================
//========================================================================================================
//========================================================================================================

echo "\n<form method='post' action='edit_table.php'>";
echo "\n<input type='submit' name='_add' value='",$m_setup['menu add'],"'/>";
echo "\n<input type='submit' name='_save' value='",$m_setup['menu save'],"'/>";
echo "\n<input type='submit' name='_dell' value='",$m_setup['menu dell'],"'/>";
echo "\n<input type='submit' name='_select' value='",$m_setup['menu select and re'],"'/>";
echo "\n<input type='hidden' name='_id_value' value='"  , $iKlient_id  , "'/>";
echo "\n<input type='hidden' name='_id_name' value='" , $this_table_id_name , "'/>";
echo "\n<input type='hidden' name='_table_name' value='" , $this_table_name , "'/>";
//echo "\n<input type='hidden' name='_select' value='" , $return_page , "'/>";

echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr><td>";//table dla find div
echo "\n<table border = 1 cellspacing='0' cellpadding='0'><tr>";

//=====================================================================================================================

echo "\n<tr valign=\"top\"><td></td><td>"; # Group name 1
echo "<td></td>";
echo "<td rowspan=15>-";

echo "</td>";
echo "<td rowspan=15>";
$tmp = 0;
while($tmp < mysql_num_rows($info_list)){
    echo  "<a href=\"$this_page_name?$this_table_id_name=",
	  mysql_result($info_list,$tmp,"info_id"),"&info_list_sort=$info_list_sort\"> ",
	  mysql_result($info_list,$tmp,"info_date")," ",
	  mysql_result($info_list,$tmp,"info_header_1"),"</a><br>";
    $tmp++;
}
echo "</td>";
echo "</tr>";

echo "\n<tr valign=\"top\"><td></td><td>"; # Group name 1
echo "---</td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>",$m_setup['menu date']," :</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:100px'  name='info_date' value='" . mysql_result($ver,0,"info_date") . "'/></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>",$m_setup['menu pic']," :</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:100px'  name='info_pic' value='" . mysql_result($ver,0,"info_pic") . "'/></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>",$m_setup['menu sort'],":</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:100px'  name='info_sort' value='" . mysql_result($ver,0,"info_sort") . "'/></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>",$m_setup['menu type']," 3:</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:100px'  name='info_key' value='" . mysql_result($ver,0,"info_key") . "'/></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>ЧПУ (без .html) :</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:100px'  name='info_link' value='" . mysql_result($ver,0,"info_link") . "'/></td>";
echo "<td></td>";
echo "</tr>";

//==================================================================================
echo "\n<tr><td>Заголовк H1 :</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='info_header_1' value='" . mysql_result($ver,0,"info_header_1") . "'/></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>Заголовок TITLE :</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='info_header_2' value='" . mysql_result($ver,0,"info_header_2") . "'/></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td>Meta Description :</td><td>"; # Group name 1
echo "\n<input type='text'  style='width:400px'  name='info_header_3' value='" . mysql_result($ver,0,"info_header_3") . "'/></td>";
echo "<td></td>";
echo "</tr>";
//==================================================================================
?>
  <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
  <script>
    /*
	function elFinderBrowser (field_name, url, type, win) {
                tinymce.activeEditor.windowManager.open({
                  file: '/admin/elFinder-master/elfinder.html',// use an absolute path!
                  title: 'elFinder 2.0',
                  width: 900,  
                  height: 450,
                  resizable: 'yes'
                }, {
                  setUrl: function (url) {
                    win.document.getElementById(field_name).value = url;
                  }
                });
                return false;
            }
      */
  
  
    function elFinderBrowser (field_name, url, type, win) {
            tinymce.activeEditor.windowManager.open({
              file: '/admin/elFinder-master/elfinder.html',// use an absolute path!
              title: 'elFinder 2.0',
              width: 900,  
              height: 450,
              resizable: 'yes'
            }, {
              setUrl: function (url) {
                win.document.getElementById(field_name).value = 'elFinder-master/'+url;
              }
            });
            return false;
    }
  
            
  
	tinymce.init({
			selector: "textarea",
			height: 500,
            file_browser_callback : elFinderBrowser,
			plugins: [
			  "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
			  "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			  "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
			],
		  
			toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
			toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
			toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
		  
			menubar: false,
			toolbar_items_size: 'small',
		  
			style_formats: [{
			  title: 'Bold text',
			  inline: 'b'
			}, {
			  title: 'Red text',
			  inline: 'span',
			  styles: {
				color: '#ff0000'
			  }
			}, {
			  title: 'Red header',
			  block: 'h1',
			  styles: {
				color: '#ff0000'
			  }
			}, {
			  title: 'Example 1',
			  inline: 'span',
			  classes: 'example1'
			}, {
			  title: 'Example 2',
			  inline: 'span',
			  classes: 'example2'
			}, {
			  title: 'Table styles'
			}, {
			  title: 'Table row 1',
			  selector: 'tr',
			  classes: 'tablerow1'
			}],
		  
			templates: [{
			  title: 'Test template 1',
			  content: 'Test 1'
			}, {
			  title: 'Test template 2',
			  content: 'Test 2'
			}],
			content_css: [
			  '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
			  '//www.tinymce.com/css/codepen.min.css'
			]
	});
  
  
  </script>
  		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="/elFinder-master/css/elfinder.full.css">
		<link rel="stylesheet" type="text/css" href="/elFinder-master/css/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script src="/elFinder-master/js/elfinder.min.js"></script>

		<!-- elFinder translation (OPTIONAL) -->
		<script src="/elFinder-master/js/i18n/elfinder.ru.js"></script>

		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript" charset="utf-8">
			// Documentation for client options:
			// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
			$(document).ready(function() {
				$('#elfinder').elfinder({
					url : '/elFinder-master/php/connector.minimal.php'  // connector URL (REQUIRED)
					// , lang: 'ru'                    // language (OPTIONAL)
				});
			});
            
            
		</script>
  
  
  
<?php 
echo "\n<tr><td valign=\"top\">",$m_setup['menu memo']," 1:</td><td>"; # Group name 1
echo "\n<textarea  cols='100' rows='80' name='info_memo_1' >" . mysql_result($ver,0,"info_memo_1") . "</textarea></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr><td colspan='3'>"; # Group name 1
echo "<div id=\"elfinder\"></div>
</td>";
echo "</tr>";

echo "\n<tr style=\"display:none;\"><td>",$m_setup['menu memo']," 2:</td><td>"; # Group name 1
echo "\n<textarea  cols='100' rows='1' name='info_memo_2' >" . mysql_result($ver,0,"info_memo_2") . "</textarea></td>";
echo "<td></td>";
echo "</tr>";

echo "\n<tr style=\"display:none;\"><td>",$m_setup['menu memo']," 3:</td><td>"; # Group name 1
echo "\n<textarea  cols='100' rows='1' name='info_memo_3' >" . mysql_result($ver,0,"info_memo_3") . "</textarea></td>";
echo "<td></td>";
echo "</tr>";



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

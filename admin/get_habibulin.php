<?php

include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
$html_out ="";
$habibulin_parent=1;


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
  $parent = mysql_query("SET NAMES utf8");
  $tQuery = "SELECT * FROM `tbl_habibulin_parent`
	    ORDER BY `habibulin_parent_id` DESC LIMIT 0, 10
	    ";
  $parent = mysql_query($tQuery); 
if(isset($_REQUEST['_parent'])){
    $habibulin_parent=$_REQUEST['_parent'];  
}else{
    $habibulin_parent=mysql_result($parent,0,0);  
}
 
$userid = $_SESSION[BASE.'userid'];
if(isset($_REQUEST['_user'])){
    $userid = $_REQUEST['_user'];
}
//==============================================================
$ver = mysql_query("SET NAMES utf8");
$tQuery = "SELECT * FROM `tbl_habibulin`,`tbl_klienti`,`tbl_klienti_group`,`tbl_operation`
	    WHERE 
	      `operation_id`=`habibulin_operation` and
	      `klienti_id`=`operation_klient` and
	      `klienti_group`=`klienti_group_id` and
	      `habibulin_user` = '".$userid."' and
	      `habibulin_parent`='$habibulin_parent'
	    
	    ORDER BY `habibulin_data` DESC
	    ";
$ver = mysql_query($tQuery);
if (!$ver)
{
  echo "<br>",$tQuery;
  exit();
}
$user = mysql_query("SET NAMES utf8");
$tQuery = "SELECT * FROM `tbl_klienti`
	    WHERE `klienti_setup` like '%habibulin%'
	    ORDER BY `klienti_name_1` ASC
	    ";
$user = mysql_query($tQuery);
if (!$user)
{
  echo "<br>",$tQuery;
  exit();
}


$html_out .= "<header><title>***HABIBULIN***</title><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

//echo "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
//==================JAVA===========================================
$html_out .= "\n<script src='JsHttpRequest.js'></script>";
$html_out .= "\n<script type='text/javascript'>";
//================================SET COLOR=====================================
//================================SET PRICE===============kogda vibor konkretnoj ceni
$set = -1;
if(isset($_REQUEST['_parent'])) $set = $_REQUEST['_parent'];
//$userid = $_SESSION[BASE.'userid'];
//if(isset($_REQUEST['_user'])) $user = $_REQUEST['_user'];


$html_out .= "
  function open_excel(){
  var user = ".$userid.";
  var set = ".$set.";
  
  var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      //alert(req.readyState);
      if(req.readyState==4){
	var responce=req.responseText;
	alert(responce);
	document.location.href = responce;
      }}
      req.open(null,'get_excel_habibulin.php',true);
      req.send({_user:user,_set:set});  
    }
  ";
$html_out .= "\nfunction update(value,name){
     var tovar = name.split('*');
   // var table = name.split('_');
    var id='';
    var table = 'tbl_habibulin';
    var id = 'habibulin_id';
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
        if(req.readyState==4){
	var responce=req.responseText;
	document.getElementById('test').innerHTML=responce;
	
      }}
      req.open(null,'save_table_field.php',true);
      req.send({table:table,name:tovar[0],value:value,w_id:id,w_value:tovar[1]});
   
    }";
$html_out .= "function viewNakl(value,flag){
      var div_mas =  document.getElementById('div_view_'+value);
      var div_menu =  document.getElementById('div_menu_'+value);
      var div1 =  document.getElementById('key_'+value);
      
     if(div1.innerHTML=='[-]'){
      div_mas.innerHTML='';
      div1.innerHTML='[+]';
      div_menu.innerHTML='';
	}else{
	var req=new JsHttpRequest();
	req.onreadystatechange=function(){
	//alert(value+' '+flag+' =>'+req.readyState);
	if(req.readyState==4){
	  var responce=req.responseText;
	  div_mas.innerHTML=responce;
	  div1.innerHTML='[-]';
      }}
      req.open(null,'get_quic_view.php',true);
      req.send({nakl:value});
      }}";

$find_str="";
    $html_out .= "\n</script></header>";
//================== END JAVA ================================



$html_out .= "\n<form>";

$html_out .= "<div id='row*table'><table width=100% cellspacing='0' cellpadding='0' style='border-left:1px solid;border-right:1px solid;border-top:1px solid'></div>"; //class='table'
$html = "<tr>
      <th width=\"50px\">date</th>
      <th width=\"50px\">Habibulin type</th>
      <th width=\"60px\">Nakl</th>
      <th width=\"10px\"></th>
      <th width=\"50px\">SUMM</th>      
      <th width=\"50px\">%</th>
      <th width=\"50px\">Memo</th>
      <th width=\"50px\">Comments</th>
      <th width=\"150px\">Info</th>
      <th width=\"100px\">Sotrudnik</th>
      <th>Klient</th>
      <th width=\"250px\">Period</th>
      </tr>";
$html_out .= "<div id='row*0'>".$html."</div>";      
      //****************************************************** ROWS

 $habibulin_summ = 0;
 $habibulin_usd = 0;
      
$count=0;
while($count<mysql_num_rows($ver)){ 
$html="";
$html .= "<tr>"; 

  $html .= "<td>
    <input type='text' style='width:130px' id='habibulin_data*".mysql_result($ver,$count,"habibulin_id")."' 
      value='" . mysql_result($ver,$count,"habibulin_data") . "' onChange='update(this.value,this.id);' />
    </td>";
  //----
 $html .= "<td>
    <select style='width:130px' id='habibulin_user*".mysql_result($ver,$count,"habibulin_id")."'
    onChange='update(this.value,this.id);'>";
      $tmp=0;
      while ($tmp < mysql_num_rows($user))
	{ $html .= "\n<option ";
	if (mysql_result($ver,$count,"habibulin_user") == mysql_result($user,$tmp,"klienti_id")) $html .= "selected ";
      $html .= "value=" . mysql_result($user,$tmp,"klienti_id") . ">".mysql_result($user,$tmp,"klienti_name_1")."</option>";
      $tmp++;
      }
    $html .= "</select></td>";
  //-----  
  $html .= "<td>
    <input type='text' style='width:50px' id='habibulin_operation*".mysql_result($ver,$count,"habibulin_id")."' 
      value='" . mysql_result($ver,$count,"habibulin_operation") . "' onChange='update(this.value,this.id);'/>
      </td><td>
      <a href='#none' onClick='viewNakl(".mysql_result($ver,$count,'habibulin_operation').",1)'>
	  <div id='key_".mysql_result($ver,$count,'habibulin_operation')."'>[+]</div></a>
      </td>";
  //-----  
  $html .= "<td>
    <input type='text' style='width:70px' id='habibulin_money*".mysql_result($ver,$count,"habibulin_id")."' 
      value='" . mysql_result($ver,$count,"habibulin_money") . "' onChange='update(this.value,this.id);'/>
    </td>";
  //-----  
  $html .= "<td>
    <input type='text' style='width:30px' id='habibulin_user_usd*".mysql_result($ver,$count,"habibulin_id")."' 
      value='" . mysql_result($ver,$count,"habibulin_user_usd") . "' onChange='update(this.value,this.id);'/>
    </td>";
  //-----  
  $html .= "<td>
    <input type='text' style='width:150px' id='habibulin_operation_description*".mysql_result($ver,$count,"habibulin_id")."' 
      value='" . mysql_result($ver,$count,"habibulin_operation_description") . "' onChange='update(this.value,this.id);'/>
    </td>";
  //-----  
  $html .= "<td>
    <input type='text' style='width:150px' id='habibulin_description*".mysql_result($ver,$count,"habibulin_id")."' 
      value='" . mysql_result($ver,$count,"habibulin_description") . "' onChange='update(this.value,this.id);'/>
    </td>";
      //-----  
   $html .= "<td>
    <input type='text' style='width:150px' id='habibulin_description*".mysql_result($ver,$count,"habibulin_id")."' 
      value='" . mysql_result($ver,$count,"habibulin_description") . "' onChange='update(this.value,this.id);'/>
    </td>";
 
 //----
 $html .= "<td>";
      $tmp=0;
      while ($tmp < mysql_num_rows($user))
	{ 
	if (mysql_result($ver,$count,"habibulin_sotrudnik") == mysql_result($user,$tmp,"klienti_id")) $html .= mysql_result($user,$tmp,"klienti_name_1");
      $tmp++;
      }
    $html .= "</td>";
 //-----
 $html .= "<td><a href=\"edit_klient.php?klienti_id=".mysql_result($ver,$count,"klienti_id")."\" target=\"_blank\">
	    (".mysql_result($ver,$count,"klienti_group_name").") ".mysql_result($ver,$count,"klienti_name_1")." tel: ".mysql_result($ver,$count,"klienti_phone_1")."
	    </a>
    </td>";
 //----   =========================================
 $html .= "<td><select name='_parent' style='width:250px' id='habibulin_parent*".mysql_result($ver,$count,"habibulin_id")."'  onChange='update(this.value,this.id);'>";
    $tmp=0;
    while ($tmp < mysql_num_rows($parent))
    {
	$html .= "\n<option ";
	if($habibulin_parent==mysql_result($parent,$tmp,"habibulin_parent_id")) $html .= " selected ";
	$html .= "value=" . mysql_result($parent,$tmp,"habibulin_parent_id") . ">" . mysql_result($parent,$tmp,"habibulin_parent_name") . "</option>";
	$tmp++;
    }
$html .= "</select></td>";
//----   =========================================
  
 
 
 
 
 $habibulin_summ += (float)mysql_result($ver,$count,"habibulin_money");
 $habibulin_usd += (float)mysql_result($ver,$count,"habibulin_money")/100 * (float)mysql_result($ver,$count,"habibulin_user_usd");
 
//================================



$html .= "</tr>";


$html .= "<tr><td colspan=\"3\"></td><td colspan=\"9\">
	  <div id='div_view_".mysql_result($ver,$count,'habibulin_operation')."'></div></td></tr>";

$html_out .= "<div id='row*".mysql_result($ver,$count,"habibulin_id")."'>".$html."</div>";
$count++;
}


echo  "<body align=\"left\">
	<div><table><tr><td>";
echo "Habibulin summ = <b>",$habibulin_summ, "</b> UAH</td><td>";
echo "Habibulin % = <b>",$habibulin_usd, "</b> UAH</td><td width=\"150\" align=\"center\">
    <a href=\"#none\" >Print</a>&nbsp&nbsp
    <a href=\"javascript:open_excel();\"><img src=\"../resources/img/excel.jpg\" width=\"50px\"></a>
    </td><td>";
//======================================================================
    echo "<form method='get' action='get_habibulin.php'>
  
      Group :<select name='_parent' style='width:300px' onChange='submit();'>";
    $tmp=0;
    while ($tmp < mysql_num_rows($parent))
    {
	echo "\n<option ";
	if($habibulin_parent==mysql_result($parent,$tmp,"habibulin_parent_id")) echo " selected ";
	echo "value=" . mysql_result($parent,$tmp,"habibulin_parent_id") . ">" . mysql_result($parent,$tmp,"habibulin_parent_name") . "</option>";
	$tmp++;
    }
    echo "</select>";    
//=========================================================================
    
    if($_SESSION[BASE.'userlevel']>900000){
	//$h_users = mysql_query("SET NAMES utf8");
	//$h_users = mysql_query("SELECT * FROM `tbl_klienti` WHERE `klienti_setup` like '%habibulin%' ORDER BY `klienti_name_1` ASC ");
    
	echo "User :<select name='_user' style='width:300px' onChange='submit();'>";
	  $tmp=0;
	
	    while ($tmp < mysql_num_rows($user))
	    {
	      echo "\n<option ";
	      if($userid==mysql_result($user,$tmp,"klienti_id")) echo " selected ";
	      echo "value=" . mysql_result($user,$tmp,"klienti_id") . ">" . mysql_result($user,$tmp,"klienti_name_1") . "</option>";
	      $tmp++;
	    }
	echo "</select></form>";    

     }
//=======================================================================    

echo "</td></tr></table>
      </div>";
echo $html_out;
echo "<div id='row*table_end'></table></div>
<div id='test'>-></div>
      \n</form>
      \n</body>";
?>

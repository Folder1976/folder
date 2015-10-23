<?php

//include 'init.lib.user.php';
function user_item_view($id){
//if ($id){
//echo "Tovar_detail = ",$id, " sesion lang = ", $_SESSION[BASE.'lang'];}

connect_to_mysql();
//$operation_id = $_GET["operation_id"];
//$template_name = $_GET["tmp"];
//if ($template_name=="")$template_name="print";
$temp_header = "admin/template/tovar_view_header.html";
//$temp_fields = "admin/template/".$template_name."_fields.html";
//==================================MAIL===========================================
    /*var pass1 = document.getElementById('userpass');
    var pass2 = document.getElementById('userpass1');
    var div_pas = document.getElementById('div_pass');
    //var test = document.getElementById('username');
    //test.value = pass1.value + ' '+ pass2.value;
    
    //if(pass1.length==5){
      if (pass1.value==pass2.value){
	  div_pas.innerHTML = 'OK';
	  test.value = 'OK';
	  }else{
	  div_pas.innerHTML = 'none';
	  }
      //}
    }";*/
 echo "</script>";
//=======================================================
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_name`, 
	  `setup_param`
	  FROM `tbl_setup`
	  WHERE 
	  `setup_name` like '%tovar%'

";
$setup = mysql_query($tQuery);
$m_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $m_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//==================================SETUP=MENU==========================================
$setup = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	  `setup_menu_name`, 
	  `setup_menu_".$_SESSION[BASE.'lang']."`
	  FROM `tbl_setup_menu`
	  ";

$setup = mysql_query($tQuery);
$k_setup = array();
$count=0;
while ($count<mysql_num_rows($setup)){
 $k_setup[mysql_result($setup,$count,0)]=mysql_result($setup,$count,1);
 $count++;
}
//=================================================================
/*,
				IF(`tovar_inet_id_parent`='".$id."',
				  `description_".$_SESSION[BASE.'lang']."`,
				  (SELECT `parent_inet_memo_".$_SESSION[BASE.'lang']."`
					  FROM `tbl_parent_inet`
					  WHERE `parent_inet_id` = `tovar_inet_id_parent`)) AS tovar_memo
			*/

//==================================================================
$fields = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 	`tovar_inet_id_parent`,
			`tovar_artkl`,
			`tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_2`,
			`currency_name_shot`,
			`description_".$_SESSION[BASE.'lang']."` AS tovar_description
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_currency`,
			`tbl_description`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`tovar_id`=`description_tovar_id` and
			`price_tovar_curr_2`=`currency_id` and
			`tovar_id`='".$id."'
			";

$ver = mysql_query($tQuery);
//==================================================================
$parent = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	    `parent_inet_type`
	  FROM 
	    `tbl_parent_inet`
	  WHERE
	     `parent_inet_id`='".mysql_result($ver,0,"tovar_inet_id_parent")."'
";

// get template================================
$tmp_header = file_get_contents($temp_header);


$parent = mysql_query($tQuery);
$size = mysql_result($parent,0,0);
// set size ======================================================
if ($size==2){
    $id = mysql_result($ver,0,"tovar_inet_id_parent");
    $id_inet = $id;
      $sql_size = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT 
		    `tovar_id`,
		    `tovar_artkl`,
		    `price_tovar_2`,
		    `currency_name_shot`
		  FROM 
		     `tbl_tovar`,
		     `tbl_price_tovar`,
		     `tbl_currency`
		  WHERE
			`tovar_id`=`price_tovar_id` and
			`price_tovar_curr_2`=`currency_id` and
		      `tovar_inet_id_parent`='".$id_inet."'
";
    $sql_size = mysql_query($tQuery);
   // echo $tQuery;
      $count=0;
      //tovar_on_ware(1);
      $str_size="<table>";
	  while($count < mysql_num_rows($sql_size)){
	    $str_size .= "<tr><td>".mysql_result($sql_size,$count,"tovar_artkl")."</td>";  //SIZE and ORDER ====
	    $str_size .= "<td>";
	    $war_sum = tovar_on_ware(mysql_result($sql_size,$count,"tovar_id"));
	      if ($war_sum <1) $str_size .= $k_setup['tovar none'];
	      elseif ($war_sum >5) $str_size .= $k_setup['tovar more'];
	      else $str_size .= $k_setup['tovar many'];
	    $str_size .= "</td>";  //SIZE and ORDER ====
	    
	      $str_size .= "<td><input type='text' ";
		if ($_SESSION[BASE.'username']==null) $str_size .= " disabled='disabled' ";
	      $str_size .= " style='width:50px' id='".mysql_result($sql_size,$count,"tovar_id")."' value='1'/></td>";  //SIZE and ORDER ====
	    
	    $str_size .= "<td>".mysql_result($sql_size,$count,"price_tovar_2")." ".mysql_result($sql_size,$count,"currency_name_shot")."</td>";  //SIZE and ORDER ====
	    $str_size .= "<td>";
	      if($war_sum > 0){
		 $str_size .= "<input type='button' ";
		  if ($_SESSION[BASE.'username']==null) $str_size .= " disabled='disabled' ";
		 $str_size .= "value='".$k_setup['menu order']."' id='add*".mysql_result($sql_size,$count,"tovar_id")."' OnClick='addtovar(this.id);'/></td></tr>";  //SIZE and ORDER ====
	      }else{
		 $str_size .= "<input type='button' ";
		  if ($_SESSION[BASE.'username']==null) $str_size .= " disabled='disabled' ";
		 $str_size .= "value='".$k_setup['tovar opa add']."' id='opa*".mysql_result($sql_size,$count,"tovar_id")."' OnClick='addtovar(this.id);'/></td></tr>";  //SIZE and ORDER ====
	      }
	    $str_size .= "</td></tr>";
	    $count++;
	  }
      $str_size.="</table>";
	  
    $tmp_header = str_replace("&tovar_size",$str_size,$tmp_header);
}else{
    $id_inet = mysql_result($ver,0,"tovar_inet_id");
	      //==================================
	      $war_sum = tovar_on_ware(mysql_result($ver,0,"tovar_id"));
	      $str_size .= mysql_result($ver,0,"tovar_artkl");
	      //echo $war_sum;
	      $str_size .= " (";
		 if ($war_sum <1) $str_size .= $k_setup['tovar none'];
		  elseif ($war_sum >5) $str_size .= $k_setup['tovar more'];
		    else $str_size .= $k_setup['tovar many'];
	      $str_size .= ") ";
	      
	      $str_size .= "<input type='text' ";
		if ($_SESSION[BASE.'username']==null) $str_size .= " disabled='disabled' ";
	      $str_size .= " style='width:50px' id='".mysql_result($ver,0,"tovar_id")."' value='1'/>";  //SIZE and ORDER ====
	    
	    $str_size .= "".mysql_result($ver,0,"price_tovar_2")." ".mysql_result($ver,0,"currency_name_shot")." ";  //SIZE and ORDER ====
	    $str_size .= "";
	      if($war_sum > 0){
		 $str_size .= "<input type='button' ";
		  if ($_SESSION[BASE.'username']==null) $str_size .= " disabled='disabled' ";
		 $str_size .= "value='".$k_setup['menu order']."' id='add*".mysql_result($ver,0,"tovar_id")."' OnClick='addtovar(this.id);'/>";  //SIZE and ORDER ====
	      }else{
		 $str_size .= "<input type='button' ";
		  if ($_SESSION[BASE.'username']==null) $str_size .= " disabled='disabled' ";
		 $str_size .= "value='".$k_setup['tovar opa add']."' id='opa*".mysql_result($ver,0,"tovar_id")."' OnClick='addtovar(this.id);'/>";  //SIZE and ORDER ====
	      }
	      //=============================
    $tmp_header = str_replace("&tovar_size",$str_size,$tmp_header); //NO SIZE and ORDER ====
}
//echo $size," == ", $id," - ",$id_inet;
//==================================================================
//==================================================================
//echo $tQuery;
header ('Content-Type: text/html; charset=utf8');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css' media='all'></header>
      <title></title>";

     // echo $template_name;

//echo $tmp_header;
      $tovar_name = explode($m_setup['tovar name sep'], mysql_result($ver,0,"tovar_name"));
      $artkl=explode($m_setup['tovar artikl-size sep'],mysql_result($ver,0,"tovar_artkl"));
//echo mysql_result($parent,0,0);
$tmp_header = str_replace("&tovar_name",$tovar_name[0],$tmp_header);
$tmp_header = str_replace("&tovar_artkl",$artkl[0],$tmp_header);
$tmp_header = str_replace("&price_tovar",mysql_result($ver,0,"price_tovar_2"),$tmp_header);
$tmp_header = str_replace("&currency_name_shot",mysql_result($ver,0,"currency_name_shot"),$tmp_header);
$tmp_header = str_replace("&tovar_description",mysql_result($ver,0,"tovar_description"),$tmp_header);

// set pfoto ====================================================
$path_to = "resources/products/".$id_inet."/";
$massiv = glob($path_to."*.small.jpg");
$photo = "
<img src='".$path_to."".$id_inet.".0.medium.jpg' width='300' height='300'>";
$x=-1;
while ($x++ < count($massiv)-1){
$photo .= "<img src='".$massiv[$x]."' width='100'>";
//echo $massiv[$x],"<br>";
}
$tmp_header = str_replace("&photo",$photo,$tmp_header);

echo $tmp_header;


}

function tovar_on_ware($id){
      $ver = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT 
		    `tovar_parent_nom`
		  FROM 
		     `tbl_tovar`,
		     `tbl_parent`
		  WHERE
			`tovar_parent`=`tovar_parent_id` and
			`tovar_id`='".$id."'
";
    $ver = mysql_query($tQuery);
$war_key=mysql_result($ver,0,0)
;

$tmp="";
$count=0;
  while($war_key[$count] <> null){
    if($war_key[$count]=="1") $tmp .= " `warehouse_unit_".($count+1)."` +";
     $count++;
  }
     $ver = mysql_query("SET NAMES utf8");
      $tQuery = "SELECT 
		    (".substr($tmp,0,-1).") as war_sum
		  FROM 
		     `tbl_warehouse_unit`
		  WHERE
		      `warehouse_unit_tovar_id`='".$id."'
";
    $ver = mysql_query($tQuery);


return  mysql_result($ver,0,0);

}


function verify_order_and_send_email($order) {
  $nakl = mysql_query("SET NAMES utf8");
    $tQuery = "SELECT 
	  `operation_detail_tovar`
	  FROM
	  `tbl_operation_detail`
	  WHERE
	  `operation_detaol_dell`='0' and
	  `operation_detail_operation`='".$order."'
	  ";
  $nakl = mysql_query($tQuery);
  
$count=0;
while($count<mysql_num_rows($nakl)){
  
  echo tovar_on_ware(mysql_result($nakl,$count,0));

$count++;
}
  
  
}
?>

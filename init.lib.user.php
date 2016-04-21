<?php

function user_menu_top(){
//session_start();

$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT 
		    `web_menu_link`,
		    `web_menu_lang_".$_SESSION[BASE.'lang']."` 
		    FROM `tbl_web_menu` ORDER BY `web_menu_sort` ASC");
if (!$ver)
{
  echo "Query error - MENU";
  exit();
}

$out = "<table width=\"100%\" class=\"menu_main\" cellspacing='0px' cellpadding='0px'><tr>";
$count=0;
  while ($count < mysql_num_rows($ver)){
$out .= "<td align=\"center\" valign=\"middle\">
<a href='".mysql_result($ver,$count,0)."'>".mysql_result($ver,$count,1)."</a>
   </td><td align=\"center\">|</td>";
  $count++;
  }
if(strpos($_SESSION[BASE.'usergroup_setup'],"price_excel",0)>0){
$out .= "<td align=\"center\" valign=\"middle\">
<a href=\"javascript:open_excel_price()\"><img src=\"".HOST_URL."/resources/img/excel.jpg\" width=\"70px\"></a>
   </td><td align=\"center\">|</td>";

}
  
$out = substr($out,0,-25);
$out .= "</tr></table>";

if(strpos($_SESSION[BASE.'usergroup_setup'],"price_excel",0)>0){
  $out .= "<script>
	      function open_excel_price(){
		    info('Wait');
		    var req=new JsHttpRequest();
		    req.onreadystatechange=function(){
			  if(req.readyState==4){
			      var responce=req.responseText;
				  //alert(responce);
			      info('');
			      document.location.href = responce;
		      }}
		req.open(null,'get_excel_price.php',true);
		req.send();  
	      }
  
  </script>
  ";
}
return $out;

}
function info($limit,$setup,$key){
//session_start();
//if($key=="")
$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * 
		    FROM `tbl_info` 
		    WHERE `info_key` = '$key'
		    ORDER BY `info_sort` ASC, `info_date` DESC
		    LIMIT 0,$limit");
if (!$ver)
{
  echo "Query error - NEWS";
  exit();
}
if(mysql_num_rows($ver)<1){
}else{

$out =  "<script>
	  function view_all_info(id){
	      var div_info = document.getElementById('div_info*'+id);
	      //alert(div_info.style.height);
	      if(div_info.style.height=='100%'){
		  div_info.style.height = '100px';
	      }else{
		  div_info.style.height = '100%';
	      }
	  }
</script>
"; 


$out .= "<table width=\"965\" class=\"menu_main\" cellspacing=\"0px\" cellpadding=\"0px\"><tr>";

if($key != "top_main_info"){
	$out .= "
	    <td colspan='2' align='center'>
	    <br>
	    ".mb_strtoupper($setup['menu '.$key],"utf-8")."
	    <hr></td>
	</tr>";
}

$count=0;
  while ($count < mysql_num_rows($ver)){
$out .= "<tr>";  

      $info_link = "";
      $info_link_head = "<font class=\"news_header\">";
      $link_close = "";
      $link_close_header = "</font>";
      if(mysql_result($ver,$count,"info_link") <> "")
      {
	    $info_link = "<a href='".HOST_URL."/index.php?key=".mysql_result($ver,$count,"info_link")."'>";
	    $info_link_head = "<a href='".HOST_URL."/index.php?key=".mysql_result($ver,$count,"info_link")."' class='news_header'>";
	    $link_close = "</a>";
 	    $link_close_header = "</a>";
      }
  
      $full_link = "".HOST_URL."/resources/info/info_".mysql_result($ver,$count,"info_id").".jpg";
      if(@fopen($full_link,"r")){
      }else{
	$full_link="".HOST_URL."/resources/info/info.png";
      }
  
$out .= "<td align=\"center\" valign=\"top\" width=\"120\">
	<a href=\"javascript:view_all_info(".mysql_result($ver,$count,"info_id").")\">
	$info_link<img src=\"$full_link\" width=\"100px\">$link_close
	</a></td>";
$out .= "
    <td align=\"left\" valign=\"top\" id=\"row_info*".mysql_result($ver,$count,"info_id")."\">
    <div class=\"div_info\" id=\"div_info*".mysql_result($ver,$count,"info_id")."\">
       ";
//$out .= "<i><a href=\"javascript:view_all_info(".mysql_result($ver,$count,"info_id").")\">";
// if(mysql_result($ver,$count,"info_date") > "2000.01.01")
//	$out .= "".mysql_result($ver,$count,"info_date")."";
//$out .= " >> ".$setup['menu view all']."</a></i>";
	
$out .= "<b>$info_link_head
	".mysql_result($ver,$count,"info_header_".$_SESSION[BASE.'lang'])."$link_close_header
	</b><br><br>$info_link
	".mysql_result($ver,$count,"info_memo_".$_SESSION[BASE.'lang'])."$link_close
	
	<div>
    </td>
    <tr><td colspan=\"2\">";
    if($key == "top_main_info"){
	$out .= "<br>";
    }else{
	$out .= "<hr>";
    }
    
$out .= "</td></tr>
    </tr>
  ";   
   
  $count++;
  }

  
  if($key != "top_main_info"){
$out .= "<tr>
	  <td colspan='2' align='right'>
	    <a href='index.php?key=$key' class='news_header'>
	      <img src='".HOST_URL."/resources/arrow-r.png'>
	      ".$setup['menu more']."
	    </a>
	  </td>
	</tr></table>
	
	<br>";
}
//$tmp = "";//user_operation_list_view(3320,$setup);	
//$tmp = user_operation_list_view(3320,$setup);
//$tmp= array();
//echo preg_match_all('#[operation_id](.+?)[/operation_id]#isU',$out,$arr);

//echo  strpos($out,"[/operation_id]");
//print_r($arr);
//$out = str_replace("[operation_id]",$tmp,$out);
	
return $out;
}
}
function user_menu_logo(){
//session_start();

return  "<a href=\"http://".MY_URL."\"><img src='".HOST_URL."/resources/logo.png' alt='logo' width='80' style=\"padding-left:20px;padding-top:10px;\"></a>";

}
function tovar_view_old___($getName,$title,$setup){
//=========================SELECT curr=============================================
$html = "";

//=========================SELECT TOVAR=============================================
$curr = mysql_query("SET NAMES utf8");
$SgetName = "SELECT *
		 FROM 
		`tbl_currency`
		";
$curr = mysql_query($SgetName);

if (!$curr){
  echo "Query error - tbl_currency - ",$SgetName;
  exit();
}


$count=0;
$str="";
$artkl1[0]="";
$size_prezent=0;
$Cols=4;
$ColsCount=$Cols;
$ColsW=200;


$count_yes = $Cols;
$count_wait = $Cols;
$count_none = $Cols;
$tovar_yes = "";
$tovar_wait="";
$tovar_none = "";
//$header
$tovar_tmp="";


if(strpos($title,"none")===false)
{
  $html .= "<p class='tovar_list_title'>".$setup[$title]."<hr></p>";
}


$html .= "<table class='menu_tovar_list' width='100%' cellpadding='15px'><tr>";

while($count < mysql_num_rows($getName)){

  $str = explode($setup['tovar name sep'],mysql_result($getName,$count,"tovar_name"));
  $artkl=explode($setup['tovar artikl-size sep'],mysql_result($getName,$count,"tovar_artkl"));
  $next=0;
  $war_sum = 0;
  $artkl1[0] = $artkl[0];
 
      while ($artkl[0] == $artkl1[0]){// and ($count+$next+1)<mysql_num_rows($getName)){

	$war_sum += tovar_on_ware(mysql_result($getName,$count+$next,"tovar_id"));
	if($war_sum > 0) $war_sum = 999999;
	
	$next++;
	    if(($count+$next) < mysql_num_rows($getName)){
		$artkl1 = explode($setup['tovar artikl-size sep'],mysql_result($getName,$count+$next,"tovar_artkl"));
	    }else{
		$artkl1[0] = "";
	    }
	}
	
	$str_size = "";
	  $str_size .= tovar_on_ware_name($war_sum,$setup);
	$str_size .= "";

	$link = "";
	if(mysql_result($getName,$count,"parent_inet_type") == 2)
	{
	  $link .= "GR".mysql_result($getName,$count,"tovar_inet_id_parent");
	}else{
	  $link .= mysql_result($getName,$count,"tovar_id");
	}
	$size_prezent="";//$setup['tovar have size'];
//=================PRICE =========================================      
$price1=mysql_result($getName,$count,"price1");//Price for web
$pricename1="";
$priceex1 = 1;
$price2=mysql_result($getName,$count,"price2");//Price for user
$pricename2="";
$priceex2 = 1;

$count_tmp=0;
      while($count_tmp<mysql_num_rows($curr))
      {
	if(mysql_result($curr,$count_tmp,"currency_id")==mysql_result($getName,$count,"curr1"))
	{
	  $pricename1=mysql_result($curr,$count_tmp,"currency_name_shot");
	  $priceex1=mysql_result($curr,$count_tmp,"currency_ex");
	 }
	
	if(mysql_result($curr,$count_tmp,"currency_id")==mysql_result($getName,$count,"curr2"))
	{
	  $pricename2=mysql_result($curr,$count_tmp,"currency_name_shot");
	  $priceex2=mysql_result($curr,$count_tmp,"currency_ex");
	 }
	 
      $count_tmp++;
      }
      
$price2 = $price2*$priceex2/$priceex1; //Set web exange
$price2 = $price2/100*(100-$_SESSION[BASE.'userdiscount']);
$price2 = number_format($price2,2,'.','');
//================END=PRICE =========================================      
$tovar_tmp = "<td width=\"".$ColsW."\" align=\"center\" valign=\"top\" height=\"390px\">";
      $tovar_tmp .= "<table width=\"200px\"  class=\"menu_top\" height=\"100%\" border=\"0px\"><tr>
		      <td valign=\"top\" align=\"center\" height=\"160px\" >";
	  
	  if(strpos($title,"add dell")!==false)
	  {	
	    $tovar_tmp .= "
			  <a href=\"".HOST_URL."/index.php?parent=selected&dell=".urlencode(mysql_result($getName,$count,"tovar_id"))."\">
			  <img src=\"".HOST_URL."/resources/delete.png\" width=\"45px\">
			  </a><br>";
	  }    
     
     
      if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){
	if(mysql_result($getName,$count,"parent_inet_type") == 2)
	  {$tovar_tmp .= "<a href='".HOST_URL."/admin/edit_parent_inet.php?parent_inet_id=".mysql_result($getName,$count,"tovar_inet_id_parent")."'>edit</a><br>";
	  }else{
	  $tovar_tmp .= "<a href='".HOST_URL."/admin/edit_tovar.php?tovar_id=".mysql_result($getName,$count,"tovar_id")."'>edit</a>
	  <br>";}
	}
	$url_str = "?tovar=".urlencode(mysql_result($getName,$count,"tovar_id"));//."&amp;parent=".urlencode($id);//."&tovar=".urlencode(mysql_result($getName,$count,"tovar_id"));
	$tovar_tmp .= "<a href=\"".HOST_URL."/index.php".htmlentities($url_str)."\">";
	
	$img = "".HOST_URL."/resources/products/".$link."/".$link.".0.medium.jpg";
	    
	      if(@fopen($img,"r")){
	      }else{
		$img = "".HOST_URL."/resources/img/no_photo.png";
	      }
	$tovar_tmp .= "<img src=\"$img\" width=\"150\" height=\"150\">
	    	    </a>
	    </td></tr><tr><td valign=\"top\"><font size='2'>
	    <a href=\"".HOST_URL."/index.php".htmlentities($url_str)."\">
	    (".$artkl[0].") ".
	    $str[0]."</a></font>
	    <br><font size='1'>
	";
        $memo=mysql_result($getName,$count,"tovar_memo");
	  $memo = str_replace("<li>","",$memo);
	  $memo = str_replace("<ul>","",$memo);
	  $memo = str_replace("\r\n","<br>",$memo);
	  //$memo = str_replace("\r","<br>",$memo);
	
	  $find =array("<a",
		      "<A",
		      "<IMG",
		      "<img",
		      "<ul>",
		      "<li>",
		      "</a>",
		      "</A>",
		      "<iframe"
		      );	
	  $memo = str_replace($find,"<br><br><br>",$memo);
	  
	  $memo = explode("<br>",$memo);
	  if(!empty($memo[0])){
	      if(mb_strlen($memo[0],"UTF-8") > 31){
		  $tovar_tmp .= mb_substr($memo[0],0,30,"UTF-8")."...<br>";
	      }else{
		  $tovar_tmp .= $memo[0]."<br>";
	      }
	  }
	  if(!empty($memo[1])){
	      if(mb_strlen($memo[1],"UTF-8") > 31){
		  $tovar_tmp .= mb_substr($memo[1],0,30,"UTF-8")."...<br>";
	      }else{
		  $tovar_tmp .= $memo[1]."<br>";
	      }
	  }
	  if(!empty($memo[2])){
	      if(mb_strlen($memo[2],"UTF-8") > 31){
		  $tovar_tmp .= mb_substr($memo[2],0,30,"UTF-8")."...<br>";
	      }else{
		  $tovar_tmp .= $memo[2]."<br>";
	      }
	  }
	  if(!empty($memo[3])){
	      if(mb_strlen($memo[3],"UTF-8") > 31){
		  $tovar_tmp .= mb_substr($memo[3],0,30,"UTF-8")."...<br>";
	      }else{
		  $tovar_tmp .= $memo[3]."<br>";
	      }
	  }
	
      $tovar_tmp .= "</font>
		    </td></tr>
		    <tr>
		    <td valign=\"bottom\"  class=\"tovar_list_size\" >
		    $str_size
		    </td></tr>
      
		    <tr><td valign=\"middle\" align=\"center\" class=\"tovar_list_price\">   
		    <a href='index.php".htmlentities($url_str)."' class='tovar_price'>
	  ";
     if($price1==$price2){
	$tovar_tmp .= "".$price1." ".$pricename1."";//Default price
      }else{
	$tovar_tmp .= "".$price2." ".$pricename1." (".$price1." ".$pricename1.")";//User price
      }
      $tovar_tmp .= "</a>								
		    ".$size_prezent."
		    </td></tr>
	    <tr><td height=\"30px\">
	    <button class='order'
		   OnClick=\"location.href='".HOST_URL."/index.php".htmlentities($url_str)."'\"><img src='".HOST_URL."/resources/cart.png'> ".$setup['tovar bay']."</button>
	    </td></tr></table>
      </td>";
     // $ColsCount--; 
      
      
  $count += $next;
  
  if($war_sum > 0){
      $tovar_yes .= $tovar_tmp;
      $count_yes --;
      if($count_yes == 0)
      {
	$tovar_yes .= "</tr><tr>";
	$count_yes = $Cols;
      }
  }
  elseif($war_sum == 0){
     if($tovar_none == ""){
	$tovar_none .= "<td colspan=\"$Cols\" align=\"center\" class=\"tovar_list_price\">".
	  tovar_on_ware_name($war_sum,$setup).
	 "<hr></td></tr><tr>";
      }
      $tovar_none .= $tovar_tmp;
      $count_none --;
      if($count_none == 0)
      {
	$tovar_none .= "</tr><tr>";
	$count_none = $Cols;
      }
  }
  elseif($war_sum < 0){
      if($tovar_wait == ""){
	$tovar_wait .= "<td colspan=\"$Cols\" align=\"center\" class=\"tovar_list_price\">".
	  tovar_on_ware_name($war_sum,$setup).
	 "<hr></td></tr><tr>";
      }
      $tovar_wait .= $tovar_tmp;
      $count_wait --;
      if($count_wait == 0)
      {
	$tovar_wait .= "</tr><tr>";
	$count_wait = $Cols;
      }
  }
  
/*  if($ColsCount==0){
	$tovar_tmp .= "</tr><tr>";
  $ColsCount=$Cols;
  }*/
 
}
if($tovar_yes <> ""){
$html .= $tovar_yes . "</tr>";
}
if($tovar_wait <> ""){
$html .= $tovar_wait . "</tr>";
}
if($tovar_none <> ""){
$html .= $tovar_none . "</tr>";
}

//$html .= $tovar_yes . "</tr></table><hr>". $tovar_wait . "</tr></table><hr>" . $tovar_none;
$html .= "</table>";

return $html;
}
function tovar_view_on_id_1($tovar_id,$title,$setup){
//=========================SELECT curr=============================================
$html = "";
$getName = mysql_query("SET NAMES utf8");
$SgetName = "SELECT 	`tovar_inet_id_parent`,
			`tovar_artkl`,
			`tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_".$setup['web default price']."` as price1,
			`price_tovar_".$_SESSION[BASE.'userprice']."` as price2,
			`price_tovar_curr_".$setup['web default price']."` as curr1,
			`price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
			`description_".$_SESSION[BASE.'lang']."` as tovar_memo,
			`parent_inet_type`
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_description`, 
			`tbl_parent_inet`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`tovar_inet_id`>0 and
			`parent_inet_id`=`tovar_inet_id_parent` and
			`tovar_id`=`description_tovar_id` and
			`tovar_id`='$tovar_id'
			ORDER BY `tovar_name_1` ASC,
				  `tovar_artkl` ASC
			LIMIT 0, 80
			";
$getName = mysql_query($SgetName);

//=========================SELECT TOVAR=============================================
$curr = mysql_query("SET NAMES utf8");
$SgetName = "SELECT *
		 FROM 
		`tbl_currency`
		";
$curr = mysql_query($SgetName);

if (!$curr){
  echo "Query error - tbl_currency - ",$SgetName;
  exit();
}

$count=0;
$str="";
$artkl1[0]="";
$size_prezent=0;
$Cols=4;
$ColsCount=$Cols;
$ColsW=200;


$count_yes = $Cols;
$count_wait = $Cols;
$count_none = $Cols;
$tovar_yes = "";
$tovar_wait="";
$tovar_none = "";
//$header
$tovar_tmp="";

if($title == "none")
{
 // $html .= "<p align='left'>".$setup[$title]."</p>";
}else{
  $html .= "<p class='tovar_list_title'>".$setup[$title]."<hr></p>";
}


$html .= "<table class='menu_top' width='100%' cellpadding='15px'><tr>";

while($count<mysql_num_rows($getName)){

  $str = explode($setup['tovar name sep'],mysql_result($getName,$count,"tovar_name"));
  $artkl=explode($setup['tovar artikl-size sep'],mysql_result($getName,$count,"tovar_artkl"));
  $next=0;
  $war_sum = 0;
  $artkl1[0] = $artkl[0];
  
      while ($artkl[0] == $artkl1[0]){// and ($count+$next+1)<mysql_num_rows($getName)){
	
	$war_sum += tovar_on_ware(mysql_result($getName,$count+$next,"tovar_id"));
	if($war_sum > 0) $war_sum = 999999;
	
	$next++;
	    if(($count+$next) < mysql_num_rows($getName)){
		$artkl1 = explode($setup['tovar artikl-size sep'],mysql_result($getName,$count+$next,"tovar_artkl"));
	    }else{
		$artkl1[0] = "";
	    }
	}
	
	$str_size = "";
	  $str_size .= tovar_on_ware_name($war_sum,$setup);
	$str_size .= "";

	$link = "";
	if(mysql_result($getName,$count,"parent_inet_type") == 2)
	{
	  $link .= "GR".mysql_result($getName,$count,"tovar_inet_id_parent");
	}else{
	  $link .= mysql_result($getName,$count,"tovar_id");
	}
	$size_prezent="";//$setup['tovar have size'];
//=================PRICE =========================================      
$price1=mysql_result($getName,$count,"price1");//Price for web
$pricename1="";
$priceex1 = 1;
$price2=mysql_result($getName,$count,"price2");//Price for user
$pricename2="";
$priceex2 = 1;

$count_tmp=0;
      while($count_tmp<mysql_num_rows($curr))
      {
	if(mysql_result($curr,$count_tmp,"currency_id")==mysql_result($getName,$count,"curr1"))
	{
	  $pricename1=mysql_result($curr,$count_tmp,"currency_name_shot");
	  $priceex1=mysql_result($curr,$count_tmp,"currency_ex");
	 }
	
	if(mysql_result($curr,$count_tmp,"currency_id")==mysql_result($getName,$count,"curr2"))
	{
	  $pricename2=mysql_result($curr,$count_tmp,"currency_name_shot");
	  $priceex2=mysql_result($curr,$count_tmp,"currency_ex");
	 }
	 
      $count_tmp++;
      }
      
$price2 = $price2*$priceex2/$priceex1; //Set web exange
$price2 = $price2/100*(100-$_SESSION[BASE.'userdiscount']);
$price2 = number_format($price2,2,'.','');
//================END=PRICE =========================================      
$tovar_tmp = "<td width=\"".$ColsW."\" align=\"center\" valign=\"top\" height=\"390px\">";
      $tovar_tmp .= "<table width=\"200px\"  class=\"menu_top\" height=\"100%\" border=\"0px\"><tr>
		      <td valign=\"top\" align=\"center\" height=\"160px\" >";
     
      if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){
	if(mysql_result($getName,$count,"parent_inet_type") == 2)
	  {$tovar_tmp .= "<a href='".HOST_URL."/admin/edit_parent_inet.php?parent_inet_id=".mysql_result($getName,$count,"tovar_inet_id_parent")."'>edit</a><br>";
	  }else{
	  $tovar_tmp .= "<a href='".HOST_URL."/admin/edit_tovar.php?tovar_id=".mysql_result($getName,$count,"tovar_id")."'>edit</a>
	  <br>";}
	}
	$url_str = "?tovar=".urlencode(mysql_result($getName,$count,"tovar_id"));//."&amp;parent=".urlencode($id);//."&tovar=".urlencode(mysql_result($getName,$count,"tovar_id"));
	$tovar_tmp .= "<a href=\"".HOST_URL."/index.php".htmlentities($url_str)."\">";
	
	$img = "".HOST_URL."/resources/products/".$link."/".$link.".0.medium.jpg";
	    
	      if(@fopen($img,"r")){
	      }else{
		$img = "".HOST_URL."/resources/img/no_photo.png";
	      }
	$tovar_tmp .= "<img src=\"$img\" width=\"150\" height=\"150\">
	    	    </a>
	    </td></tr><tr><td valign=\"top\"><font size='2'>
	    <a href=\"".HOST_URL."/index.php".htmlentities($url_str)."\">
	    (".$artkl[0].") ".
	    $str[0]."</a></font>
	    <br><font size='1'>
	";
        $memo=mysql_result($getName,$count,"tovar_memo");
	  $memo = str_replace("<li>","",$memo);
	  $memo = str_replace("<ul>","",$memo);
	  $memo = str_replace("\r\n","<br>",$memo);
	  //$memo = str_replace("\r","<br>",$memo);
	
	  $find =array("<a",
		      "<A",
		      "<IMG",
		      "<img",
		      "<ul>",
		      "<li>",
		      "</a>",
		      "</A>",
		      "<iframe"
		      );	
	  $memo = str_replace($find,"<br><br><br>",$memo);
	  
	  $memo = explode("<br>",$memo);
	  if(!empty($memo[0])){
	      if(mb_strlen($memo[0],"UTF-8") > 31){
		  $tovar_tmp .= mb_substr($memo[0],0,30,"UTF-8")."...<br>";
	      }else{
		  $tovar_tmp .= $memo[0]."<br>";
	      }
	  }
	  if(!empty($memo[1])){
	      if(mb_strlen($memo[1],"UTF-8") > 31){
		  $tovar_tmp .= mb_substr($memo[1],0,30,"UTF-8")."...<br>";
	      }else{
		  $tovar_tmp .= $memo[1]."<br>";
	      }
	  }
	  if(!empty($memo[2])){
	      if(mb_strlen($memo[2],"UTF-8") > 31){
		  $tovar_tmp .= mb_substr($memo[2],0,30,"UTF-8")."...<br>";
	      }else{
		  $tovar_tmp .= $memo[2]."<br>";
	      }
	  }
	  if(!empty($memo[3])){
	      if(mb_strlen($memo[3],"UTF-8") > 31){
		  $tovar_tmp .= mb_substr($memo[3],0,30,"UTF-8")."...<br>";
	      }else{
		  $tovar_tmp .= $memo[3]."<br>";
	      }
	  }
	
      $tovar_tmp .= "</font>
		    </td></tr>
		    <tr>
		    <td valign=\"bottom\"  class=\"tovar_list_size\" >
		    $str_size
		    </td></tr>
      
		    <tr><td valign=\"middle\" align=\"center\" class=\"tovar_list_price\">   
		    <a href='index.php".htmlentities($url_str)."' class='tovar_price'>
	  ";
     if($price1==$price2){
	$tovar_tmp .= "".$price1." ".$pricename1."";//Default price
      }else{
	$tovar_tmp .= "".$price2." ".$pricename1." (".$price1." ".$pricename1.")";//User price
      }
      $tovar_tmp .= "</a>								
		    ".$size_prezent."
		    </td></tr>
	    <tr><td height=\"30px\">
	    <button class='order'
		   OnClick=\"location.href='".HOST_URL."/index.php".htmlentities($url_str)."'\"><img src='".HOST_URL."/resources/cart.png'> ".$setup['tovar bay']."</button>
	    </td></tr></table>
      </td>";
     // $ColsCount--; 
      
      
  $count += $next;
  
  if($war_sum > 0){
      $tovar_yes .= $tovar_tmp;
      $count_yes --;
      if($count_yes == 0)
      {
	$tovar_yes .= "</tr><tr>";
	$count_yes = $Cols;
      }
  }
  elseif($war_sum == 0){
     if($tovar_none == ""){
	$tovar_none .= "<td colspan=\"$Cols\" align=\"center\" class=\"tovar_list_price\">".
	  tovar_on_ware_name($war_sum,$setup).
	 "<hr></td></tr><tr>";
      }
      $tovar_none .= $tovar_tmp;
      $count_none --;
      if($count_none == 0)
      {
	$tovar_none .= "</tr><tr>";
	$count_none = $Cols;
      }
  }
  elseif($war_sum < 0){
      if($tovar_wait == ""){
	$tovar_wait .= "<td colspan=\"$Cols\" align=\"center\" class=\"tovar_list_price\">".
	  tovar_on_ware_name($war_sum,$setup).
	 "<hr></td></tr><tr>";
      }
      $tovar_wait .= $tovar_tmp;
      $count_wait --;
      if($count_wait == 0)
      {
	$tovar_wait .= "</tr><tr>";
	$count_wait = $Cols;
      }
  }
  
/*  if($ColsCount==0){
	$tovar_tmp .= "</tr><tr>";
  $ColsCount=$Cols;
  }*/
 
}
if($tovar_yes <> ""){
$html .= $tovar_yes . "</tr>";
}
if($tovar_wait <> ""){
$html .= $tovar_wait . "</tr>";
}
if($tovar_none <> ""){
$html .= $tovar_none . "</tr>";
}

//$html .= $tovar_yes . "</tr></table><hr>". $tovar_wait . "</tr></table><hr>" . $tovar_none;
$html .= "</table>";

return $html;

}
function no_photo_list_tovar($setup) {
  if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}

$sup = "";
if(isset($_REQUEST['sup']))$sup = " and `tovar_supplier`='".mysql_real_escape_string($_REQUEST['sup'])."' ";
//echo $sup;
$sup_sql = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `klienti_id`,`klienti_name_1` FROM `tbl_klienti` WHERE `klienti_group`='5' ORDER BY `klienti_name_1` ASC";
$sup_sql = mysql_query($tQuery);

$getName = mysql_query("SET NAMES utf8");
$SgetName = "SELECT 	`tovar_inet_id_parent`,
			`tovar_id`,
			`tovar_artkl`,
			`tovar_inet_id`,
			`tovar_name_1`,
			`parent_inet_1`,
			`parent_inet_type`		  
			FROM 
			`tbl_tovar`,
			`tbl_parent_inet`
			WHERE 
			`tovar_inet_id`>0 and
			`parent_inet_id`=`tovar_inet_id_parent` $sup
			ORDER BY `parent_inet_type` DESC, 
				  `tovar_parent` ASC,
				  `tovar_name_1` ASC
			";
$getName = mysql_query($SgetName);

if (!$getName){
  echo "Query error - tbl_tovar 001 - ",$SgetName;
  exit();
}  


$count=0;
$http = "";
$http .= "<form method='post' action='".HOST_URL."/index.php'>
<input type='hidden' name='parent' value='-3'>
<select name='sup' id='sup' style='width:400px' OnChange='submit();'>
";

while($count < mysql_num_rows($sup_sql)){
  $http .=  "<option ";
      if(isset($_REQUEST['sup'])){
	  if (mysql_result($sup_sql,$count,"klienti_id") == $_REQUEST['sup']) $http .= "selected ";
      }
  $http .=  "value=" . mysql_result($sup_sql,$count,"klienti_id") . ">" . mysql_result($sup_sql,$count,"klienti_name_1") . "</option>";
    
$count++;
}

$http .= "</select></form>";

$count=0;
while($count < mysql_num_rows($getName)){
	$link = mysql_result($getName,$count,"tovar_id");
	$linkG = mysql_result($getName,$count,"tovar_inet_id_parent");
	
	if(mysql_result($getName,$count,"parent_inet_type")==2){
	    $img = "".HOST_URL."/resources/products/GR".$linkG."/GR".$linkG.".0.small.jpg";
	}else{
	    $img = "".HOST_URL."/resources/products/".$link."/".$link.".0.small.jpg";
	}    
	      if(@fopen($img,"r")){
	      }else{
		  if(mysql_result($getName,$count,"parent_inet_type")==2){//<img src=\"http://folder.com.uaresources/products/$linkG/$linkG.0.small.jpg\" height=\"30px\">
		      $http .= "
				  <a href=\"".HOST_URL."/admin/edit_parent_inet.php?parent_inet_id=".mysql_result($getName,$count,"tovar_inet_id_parent")."\" target=\"_blank\">
				  Group - ".mysql_result($getName,$count,"parent_inet_1")."</a>
				  --> 
				  <a href=\"".HOST_URL."/admin/edit_tovar.php?tovar_id=".mysql_result($getName,$count,"tovar_id")."\" target=\"_blank\">
				  Tovar : ".mysql_result($getName,$count,"tovar_artkl")."</a>
				  <br>";
		  }else{
		  $tmp = mysql_result($getName,$count,"tovar_inet_id"); //<img src=\"http://folder.com.uaresources/products/$tmp/$tmp.0.small.jpg\" height=\"30px\">
		      $http .= "
			    <a href=\"".HOST_URL."/admin/edit_tovar.php?tovar_id=".mysql_result($getName,$count,"tovar_id")."\" target=\"_blank\">
			  Tovar - ".mysql_result($getName,$count,"tovar_name_1")." : ".mysql_result($getName,$count,"tovar_artkl")."</a>
			  <br>";
		  }
		/*$http .= tovar_view_on_id_1($link,"NO PHOTO",$setup);
			  {$tovar_tmp .= "<a href='/admin/edit_parent_inet.php?parent_inet_id=".mysql_result($getName,$count,"tovar_inet_id_parent")."'>edit</a><br>";
			  }else{
			  $tovar_tmp .= "<a href='/admin/edit_tovar.php?tovar_id=".mysql_result($getName,$count,"tovar_id")."'>edit</a>
		    */
	      }

$count++;
}

return $http;
}
function user_item_list_view($id,$setup){
  global $folder;
  $Category = new Category($folder);
  $children = $Category->getCategoryChildren($id);
   
 //echo "<pre>===".$id; print_r(var_dump($children));
   
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$getName = mysql_query("SET NAMES utf8");
/*
$SgetName = "SELECT 	`tovar_inet_id_parent`,
			`tovar_artkl`,
			`tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_".$setup['web default price']."` as price1,
			`price_tovar_".$_SESSION[BASE.'userprice']."` as price2,
			`price_tovar_curr_".$setup['web default price']."` as curr1,
			`price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
				IF(`tovar_inet_id_parent`='".$id."',
				  `description_".$_SESSION[BASE.'lang']."`,
				  (SELECT `parent_inet_memo_".$_SESSION[BASE.'lang']."`
					  FROM `tbl_parent_inet`
					  WHERE `parent_inet_id` = `tovar_inet_id_parent`)) AS tovar_memo,
			`parent_inet_type`		  
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_description`,
			`tbl_parent_inet`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`tovar_inet_id`>0 and
			`parent_inet_id`=`tovar_inet_id_parent` and
			`tovar_id`=`description_tovar_id` and
			(`tovar_inet_id_parent` IN (".implode(",", $children).") or 
			`tovar_inet_id_parent` IN (SELECT
						  `parent_inet_id`
						   FROM `tbl_parent_inet`
						   WHERE
						   `parent_inet_parent` = '".$id."' and
						   `parent_inet_type`='2')
			)
			ORDER BY `tovar_name_1` ASC
			";
*/
$SgetName = "SELECT 	`tovar_inet_id_parent`,
			`tovar_artkl`,
			`tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_curr_".$setup['web default price']."` as curr1,
			`price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2
			FROM 
			`tbl_tovar`
			LEFT JOIN tbl_price_tovar ON price_tovar_id = tovar_id
			WHERE 
			`tovar_inet_id_parent` IN (".implode(",", $children).")
			and `tovar_inet_id` > 0
			ORDER BY `tovar_name_1` ASC
			";

$getName = mysql_query($SgetName);

if (!$getName){
  echo "Query error - tbl_price - ",$SgetName;
  exit();
}

return tovar_view($getName,"none",$setup);

}
function last_operation_list_tovar($setup){
 $ver = mysql_query("SET NAMES utf8");
$SgetName = "SELECT `operation_id`, `operation_data`
	      FROM
	      `tbl_operation`
	      WHERE
	      `operation_on_web`>'0'
	      ORDER BY `operation_data` DESC
	      LIMIT 0,5";
$ver = mysql_query($SgetName);
//echo $SgetName;
$html="";
$count=0;
while($count<mysql_num_rows($ver)){
    $date = new DateTime(mysql_result($ver,$count,"operation_data"));
    $date = $date->format('Y-m-d');
    $html .= "<table width=\"100%\" class=\"menu_top\"><tr><td align=\"center\" class=\"tovar_list_price\">".
	      $date.
	      "<hr></td></tr></table>";
    $id = mysql_result($ver,$count,"operation_id");
    $html .= user_operation_list_view($id,$setup);
$count++;
}
return $html;	      
   
}
function user_operation_list_view($id,$setup){
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
$getName = mysql_query("SET NAMES utf8");
$SgetName = "SELECT 	`tovar_inet_id_parent`,
			`tovar_artkl`,
			`tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_".$setup['web default price']."` as price1,
			`price_tovar_".$_SESSION[BASE.'userprice']."` as price2,
			`price_tovar_curr_".$setup['web default price']."` as curr1,
			`price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
			`description_".$_SESSION[BASE.'userprice']."` as tovar_memo,
			`parent_inet_type`		  
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_description`,
			`tbl_operation_detail`,
			`tbl_parent_inet`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`tovar_inet_id`>0 and
			`parent_inet_id`=`tovar_inet_id_parent` and
			`tovar_id`=`description_tovar_id` and
			`operation_detail_tovar`=`tovar_id` and
			`operation_detail_dell`='0' and
			`operation_detail_operation`='$id'
			ORDER BY `tovar_name_1` ASC, `tovar_artkl` ASC
			";

if(!$id){
$SgetName .= " ,`tovar_id` DESC LIMIT 0, 80";
}
$getName = mysql_query($SgetName);

if (!$getName){
  echo "Query error - tbl_price - ",$SgetName;
  exit();
}

return tovar_view($getName,"none",$setup);

}
function user_menu_catalog($setup, $Alias){
 // echo "ЗАХОЖУ СЮДА =>".$_SESSION[BASE.'user menu'] ." ".$_SESSION[BASE.'userlevel'];
if (!isset($_SESSION[BASE.'lang'])){
  $_SESSION[BASE.'lang']=1;
}
$html="";
if (isset($_SESSION[BASE.'user menu']) AND $_SESSION[BASE.'user menu'] != null){
  $html .= $_SESSION[BASE.'user menu'];
}else{

$Menu = mysql_query("SET NAMES utf8");
$tQuery="SELECT 
	`parent_inet_id`,
	`parent_inet_parent`,
	`parent_inet_".$_SESSION[BASE.'lang']."` as parent_name 
	FROM `tbl_parent_inet` 
	WHERE `parent_inet_type`='1' and
	      `parent_inet_view`<'".$_SESSION[BASE.'userlevel']."'
	ORDER BY `parent_inet_sort` ASC";
$Menu = mysql_query($tQuery);


$html = "
  <div id=\"w\" style=\"Z-INDEX:1000;\">
    <nav>
    <ul id=\"ddmenu\">";
  $div="";
  $sep = 0;
   $count=0;
  

	      
  while($count < mysql_num_rows($Menu)){

    if(mysql_result($Menu,$count,"parent_inet_parent")==0){
    $parent_id = mysql_result($Menu,$count,"parent_inet_id");
	      $html .= "\n<li>";

	      $tmp = $Alias->getAlias("parent=".$parent_id);
	      if($tmp == false){
		$html .= "<a href='".HOST_URL."/index.php?parent=".$parent_id."'>";
	      }else{
		$html .= "<a href='".HOST_URL."/".$tmp."'>";
	      }
	      //echo "<pre>".$tmp;
	      $html .= mb_strtoupper(mysql_result($Menu,$count,"parent_name"),'UTF-8')."</a>";
		   // if($count+1 == mysql_num_rows($Menu)) $html = substr($html,0,-1);
		  $html .= get_subparent_menu(mysql_result($Menu,$count,"parent_inet_id"),$Menu,$setup, $Alias);
	      $html .= "</li>\n";
    }
    $count++;
  }
      
     $html .="</ul>
    </nav>
 </div>
	  ";
}

$_SESSION[BASE.'user menu']=$html;	

return $_SESSION[BASE.'user menu'];  
}
function user_menu_catalog2(){
//session_start();
$html ="<script>
var lastOBJ = 0;
var Top = 0;
var Down = 0;
var TopUP = 30;
var LeftSrc = 20;
	jsHover = function() {
	var hEls = document.getElementById('menu').getElementsByTagName('li');
	var len=hEls.length;
	for (var i=0; i<len; i++) {
	    hEls[i].onmouseenter=function() { this.className+=' jshover'; }
	    }
	}
	if (window.attachEvent && navigator.userAgent.indexOf('Opera')==-1) window.attachEvent('onload', jsHover);
	</script>
	<script>
	function submenu_set(objID){
	  Down = 1;

	}
	function submenu_view(objID){
	  //Down = 1;
	  //Top = 1;
		  
	  var elem = document.getElementById(objID+'*sub');
	  var elemHOME = document.getElementById(objID);
	  var test = document.getElementById('test');
	  var x = elemHOME.offsetTop;
	  var y = elemHOME.offsetLeft;
	  
	  elem.style.top=x+TopUP;
	  
	  elem.style.display = 'block';
	  elem.style.position = 'absolute';
	 
	  var WhideScr = document.body.clientWidth;
	  var WhideDiv = elem.offsetWidth;
	  //alert(WhideDiv);
	 
	 if(WhideScr > WhideDiv+y)
	  {
	    elem.style.left=y;
	  }else{
	    elem.style.left=WhideScr-WhideDiv-LeftSrc;
	  }
	 
	 elemHOME.style.background='#7f7F7F'; // select
	 
	 if(lastOBJ > 0 && lastOBJ != objID) hidde(lastOBJ);
	 lastOBJ = objID;

	 }
	 
	function sleep(ms){
	  ms += new Date().getTime();
	  while (new Date() < ms){}	
	}
	
	function menu_hidde(odjID){ //if mouse UP
	    Top = 0;
	  // sleep(1000);
	   // hidde(odjID);
	}

	function submenu_hidde(odjID){ // submenu if mouse leave
	    Down = 0;
	    //pause(1000);
	    hidde(odjID);
	}
	function hidde(odjID){
	//alert('gg');
	    var elemHOME = document.getElementById(odjID);
	    var elem = document.getElementById(odjID+'*sub');
	  if(Top==0 && Down == 0){
	      elem.style.display = 'none';
	      elemHOME.style.background = '#171717'; // UNselect
	  }
	}
	</script>
	<div id='test'></div>	";

if (!isset($_SESSION[BASE.'lang'])){
  $_SESSION[BASE.'lang']=1;
}
//$html="";
if (isset($_SESSION[BASE.'user menu'])){
  $html .= $_SESSION[BASE.'user menu'];
}else{

$Menu = mysql_query("SET NAMES utf8");
$tQuery="SELECT 
	`parent_inet_id`,
	`parent_inet_parent`,
	`parent_inet_".$_SESSION[BASE.'lang']."` as parent_name 
	FROM `tbl_parent_inet` 
	WHERE `parent_inet_type`='1' and
	      `parent_inet_view`<'".$_SESSION[BASE.'userlevel']."'
	ORDER BY `parent_inet_sort` ASC";
$Menu = mysql_query($tQuery);
if (!$Menu)
{	
  echo "Query error - LANG";
  exit();
}
  $div="";
  $html .= "<ul id='menu' >";
  $count=0;
  while($count < mysql_num_rows($Menu)){

    if(mysql_result($Menu,$count,"parent_inet_parent")==0){
    $parent_id = mysql_result($Menu,$count,"parent_inet_id");
	  $html .= "<li id='$parent_id'  onmouseenter='submenu_view(this.id);' onmouseleave='menu_hidde(this.id);'><a href='".HOST_URL."/index.php?parent=".$parent_id."'>
		    ".
		//$html .= "<ul><li>";  
		  mb_strtoupper(mysql_result($Menu,$count,"parent_name"),'UTF-8')."</a>";
		//$html .= "</li></ul>";  
	      $html .= "</li>";
		$div .= get_subparent_menu(mysql_result($Menu,$count,"parent_inet_id"),$Menu);
	  
    }
    $count++;
  }
  $html .= "</ul>";
$_SESSION[BASE.'user menu']=$html .=$div;	
}
return $_SESSION[BASE.'user menu'];  
}
function get_subparent_menu($id,$Menu,$setup, $Alias){
$html = "";
  $i_find_somefing=0;
  $count=0;
  while($count < mysql_num_rows($Menu)){
 	if(mysql_result($Menu,$count,"parent_inet_parent")==$id){
	    if($i_find_somefing==0) $html.="<ul>";//<li><div id='".$id."*sub' class='unvis'  onmouseenter='submenu_set();' onmouseleave='submenu_hidde($id);'>";
 	    
	    $i_find_somefing++;
	    //S kartinkami
	    //$html .= user_subcatalog_view(mysql_result($Menu,$count,"parent_inet_id"));
	    
	    //SPISKOM
	    $parent = mysql_result($Menu,$count,"parent_inet_id");
	    //echo "<br>img src=\"".substr($setup['tovar photo patch'],1)."GR$parent/GR$parent.0.small.jpg\"";
	    //<img src=\"resources/products/GR$parent/GR$parent.0.small.jpg\" width=\"25px\">
	    $tmp = $Alias->getAlias("parent=".$parent);
	    if($tmp == false){
	      $html .= "<li><a href='".HOST_URL."/index.php?parent=".$parent."'>";
	    }else{
	      $html .= "<li><a href='".HOST_URL."/".$tmp."'>";
	    }

	   $html .= "".mysql_result($Menu,$count,"parent_name")."</a>";
	    $html .= "</li>";
	}
  $count++;
  }
  
if($i_find_somefing>0) $html.="</ul>";//</div>";

return $html;
}


function user_catalog_path($parent){
  return "<ul class = 'kroshki'><li>".MAIN_TITLE."</li>".user_catalog_path_recursion($parent). "</ul>";
}

function user_catalog_path_recursion($id){
global $Alias;
$html = "";
if(isset($id)){
//echo "ll",$id;
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}

      $getName = mysql_query("SET NAMES utf8");
      $getName = mysql_query("SELECT `parent_inet_id`,
				`parent_inet_parent`,
				`parent_inet_type`,
				`parent_inet_".$_SESSION[BASE.'lang']."` 
				FROM `tbl_parent_inet` 
				WHERE `parent_inet_id`='".$id."'");
      if (!$getName)
      {
	  echo "Query error - PATH";
	  exit();
      }
      
      
	 // $html = "<ul class = 'kroshki'>";
	    if($id>0) {
	      $html .=user_catalog_path_recursion(mysql_result($getName,0,"parent_inet_parent"));
		if(mysql_result($getName,0,"parent_inet_type")<2){
		    $html .= "<li> &nbsp;&nbsp;>>&nbsp; <a href='".HOST_URL.'/'.$Alias->getAlias('parent='.$id)."'>";
		    $html .= mysql_result($getName,0,"parent_inet_".$_SESSION[BASE.'lang']);
		    $html .="</a></li>";
		}    
	  }
	  //$html .= "</ul>";
      
}      
return $html;
}
function user_catalog_path_memo($id){
$html = "";
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}
if(!$id)$id=0;


$getName = mysql_query("SET NAMES utf8");
$sql = "SELECT
				`parent_inet_memo_".$_SESSION[BASE.'lang']."` 
				FROM `tbl_parent_inet` 
				WHERE `parent_inet_id`='".$id."'";
$getName = mysql_query($sql);
if (!$getName)
{
  echo "Query error - PATH";
  exit();
}
  $memo=mysql_result($getName,0,"parent_inet_memo_".$_SESSION[BASE.'lang']);
  $memo_s="";
  if($memo != ""){
      $memo_s = "<font class=\"information\">".$memo."</font>"; //implode(array_slice(explode("<br>",wordwrap($memo,21,"<br>",false)),0,1));
  }
  $html = $memo_s."";
return $html;

}
function set_space($i) {
    $count=0;
    while ($count<$i){
    echo "&nbsp";
    $count++;
    }
    
}
function tmp(){

$sel = mysql_query("SET NAMES utf8");
$sel = mysql_query("SELECT
				`parent_inet_parent`, 
				`parent_inet_id` 
				FROM `tbl_parent_inet` 
				");
$count=0;
while ($count<mysql_num_rows($sel)){
$ins = mysql_query("INSERT INTO `tbl_description` VALUES
				('".mysql_result($sel,$count,0)."','','','".mysql_result($sel,$count,1)."')
				");
$count++;
}
echo "TMP Function finish!<br>";
}
function banerMain($BanerType,$width,$height) {
 if(!isset($_SESSION[BASE.$BanerType]))
  $_SESSION[BASE.$BanerType]=1;
    
    $banerN = $_SESSION[BASE.$BanerType];
    $full_link = "".HOST_URL."/resources/baner/".$BanerType.$banerN.".swf";
     
     if(@fopen($full_link,"r")){
      	//$parent =mysql_result($tovar,$count,"tovar_inet_id_parent");
      }else{
 	$_SESSION[BASE.$BanerType]=1;
	$banerN = $_SESSION[BASE.$BanerType];
	$full_link = "".HOST_URL."/resources/baner/".$BanerType.$banerN.".swf";
      }
      
 $html = "
	<param name=\"wmode\" value=\"transparent\"/>
	<embed src=\"$full_link\" wmode=\"transparent\" width=\"$width\" height=\"$height\">
	</embed>
	";


	$_SESSION[BASE.$BanerType] = $banerN + 1; 


return $html;

}
function order_item_view() {
  //connect_to_mysql(); 
  $html = "";
$tovar = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	    `tovar_id`,
	    `tovar_inet_id`,
	    `tovar_artkl`,
	    `tovar_inet_id_parent`,
	    `operation_detail_item`,
	    `operation_detail_price`,
	    `operation_detail_discount`,
	    `operation_detail_summ`,
	    `tovar_name_".$_SESSION[BASE.'lang']."` AS name
	    FROM
	    `tbl_tovar`,
	    `tbl_operation_detail`,
	    `tbl_operation`
	    WHERE
	    `tovar_id`=`operation_detail_tovar` and
	    `operation_detail_operation`='".$_SESSION[BASE.'userorder']."' and
	    `operation_detail_dell`='0' and
	    `operation_id`=`operation_detail_operation` and
	    `operation_status`='16'
";
$tovar = mysql_query($tQuery);
//echo $tQuery;
$count=0;
while($count < mysql_num_rows($tovar)){
    global $setup, $Alias;
         //Разбиваем атрикл на тело и размер
        $artkl = mysql_result($tovar,$count,"tovar_artkl");
        $size = "нет";
        if(strpos($artkl,$setup['tovar artikl-size sep']) !== false){
            $x = explode($setup['tovar artikl-size sep'], $artkl);
            $artkl = $x[0];
            $size = $x[1];
        }
	$link = $artkl;
	$alias = $Alias->getProductAlias(mysql_result($tovar,$count,"tovar_id"));
    $full_link = "".HOST_URL."/resources/products/".$link."/".$link.".0.small.jpg";
    //echo $link;
      if(@fopen($full_link,"r")){
      	//$parent =mysql_result($tovar,$count,"tovar_inet_id_parent");
      }else{
	//$parent=0;
	$full_link = "".HOST_URL."/resources/img/no_photo.png";
      }
    
    $name=explode(" ", mysql_result($tovar,$count,"name"));
    
    $html .=  "<font size=1><a href='".HOST_URL."/$alias'>
      <br><img src='$full_link' width='70'>";
    $html .=  "<br>".$name[0]."<br>
    ".mysql_result($tovar,$count,"operation_detail_item")." X 
    ".mysql_result($tovar,$count,"operation_detail_price")." 
      (".mysql_result($tovar,$count,"operation_detail_summ")." ".$_SESSION[BASE.'usercurr'].")";//,$link,mysql_result($tovar,$count,"tovar_inet_id");
      
    $html .=   "</a></font>
      ";

//echo mysql_result($tovar,$count,0);
$count++;
}

return $html;
}
function utilites($setup) {
    $html = "";
    
    $html .= "<table><tr><td width=100% align=center>";
    
	$html .= "<table><tr><td align=center>";
	  $html .= "<input type='button' class='key_button_small' value='".$setup['menu selected tovar']."' onClick='window.location.href=\"".HOST_URL."/myaccount\"'></td><td>";
	  $html .= "<input type='button' class='key_button_small' value='".$setup['menu user setup']."' onClick='window.location.href=\"".HOST_URL."/useredit\"'></td><td>";
	  $html .= "<input type='button' class='key_button_small' value='".$setup['menu user orders']."' onClick='window.location.href=\"".HOST_URL."/user_order_list\"'></td><td>";
	  $html .= "<input type='button' class='key_button_small' value='".$setup['menu carts']."' onClick='window.location.href=\"".HOST_URL."/user_order_view\"'></td><td>";
	$html .= "</td></tr></table>";
    
    $html .= "</td></tr></table>";
    
    return $html;
}
function user_price_excel($setup){
if(strpos($_SESSION[BASE.'usergroup_setup'],"price_excel",0)>0){
      
      
      
}
}
function user_order_list($setup){
  $html="";
  
  
  $html .= "
  <script>
  function open_excel(id){
    var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      if(req.readyState==4){
	var responce=req.responseText;
	//alert(responce);
	document.location.href = responce;
      }}
      req.open(null,'get_excel.php',true);
      req.send({id:id});  
    }
  
  </script>
  ";
  
  
  $sum = 0;
if(isset($_SESSION[BASE.'userid'])){
    $getName = mysql_query("SET NAMES utf8");
    $SgetName = "SELECT *
	      FROM `tbl_operation`, `tbl_operation_status`
	      WHERE 
	      `operation_status`=`operation_status_id` and
	      `operation_dell`='0' and
	      `operation_klient` = '".$_SESSION[BASE.'userid']."'
	      ORDER BY `operation_id` DESC";
    $getName = mysql_query($SgetName);
    
    $count = 0;
    $html .= "<table class='order_list'>";
	while($count < mysql_num_rows($getName)){
	   $sum += mysql_result($getName,$count,'operation_summ');
	   $html .= "<tr><td width='150px'>".mysql_result($getName,$count,'operation_data')
		    ."</td>";
	   $html .= "<td width='70px'>".mysql_result($getName,$count,'operation_id')
		    ."</td>";
	   $html .= "<td width='100px'>".mysql_result($getName,$count,'operation_summ')." UAH"
		    ."</td>";
	   $html .= "<td  width=\"50px\">
		      <a href=\"javascript:open_excel(".mysql_result($getName,$count,'operation_id').");\"><img src=\"".HOST_URL."/resources/img/excel.jpg\" width=\"50px\"></a>
		      </td><td>
		      ".mysql_result($getName,$count,'operation_status_name')
		    ."</td>";
	   $html .= "<td>
		    ".mysql_result($getName,$count,'operation_memo')
		    ."</td></tr>";
	  
		    $Fields = "
		      `operation_detail_discount`,
		      `operation_detail_item`,
		      `operation_detail_memo`,
		      `operation_detail_price`,
		      `operation_detail_summ`,
		      `tovar_artkl`,
		      `tovar_name_1`";
		    $tQuery = "SELECT " . $Fields . " 
			FROM `tbl_operation_detail`,`tbl_tovar`
			WHERE 
			`operation_detail_dell`='0' 
			and `operation_detail_tovar`=`tovar_id` 
			and `operation_detail_operation`='" . mysql_result($getName,$count,'operation_id') . "' 
			ORDER BY `tovar_name_1` ASC";
		    $ver = mysql_query($tQuery);
		 $count2 = 0;
		  $html .= "</tr><td colspan='6'><table class='order_list_tovar'>";
		  while($count2 < mysql_num_rows($ver)){
		      $html .= "<tr><td width='150px'>".mysql_result($ver,$count2,'tovar_artkl')."</td>";
		      $str = explode($setup['tovar name sep'],mysql_result($ver,$count2,"tovar_name_1"));
		      $html .= "<td>".$str[0]."</td>";
		      $html .= "<td align='right'>".mysql_result($ver,$count2,'operation_detail_price')." uah</td>";
		      $html .= "<td width='70px' align='center'><b>".mysql_result($ver,$count2,'operation_detail_item')." </b></td>";
		      $html .= "<td width='30px' align='center'>".mysql_result($ver,$count2,'operation_detail_discount')."%</td>";
		      $html .= "<td width='100px' align='right'>".mysql_result($ver,$count2,'operation_detail_summ')." uah</td>";
		      $html .= "<td>".mysql_result($ver,$count2,'operation_detail_memo')."</td>";
		      
		  $count2++;
		  }
		  $html .= "</table></td></tr>";
	      
	      
	  $count++;
	}
    $html .= "</table>";
} 
  return utilites($setup).$setup['menu saldo']. " -> " .$sum." UAH<br>".$html;
}
function user_last_item_list_view($last,$setup) {
if ($_SESSION[BASE.'lang'] <1){
  $_SESSION[BASE.'lang']=1;
}

//echo "ggg",  $_SESSION[BASE.'userprice'],$setup['web default price']; ;

if (!isset($_SESSION[BASE.'userprice'])){
  $_SESSION[BASE.'userprice'] = $setup['web default price'];
}
if (isset($_SESSION[BASE.'userprice'])&&$_SESSION[BASE.'userprice']<1){
  $_SESSION[BASE.'userprice'] = $setup['web default price'];
}

$getName = mysql_query("SET NAMES utf8");

if($last < 10){
    //Выборка RND
    $row_count = mysql_result(mysql_query("SELECT COUNT(tovar_id) FROM `tbl_tovar`"),0);
    $SgetName = array();
    while(count($SgetName)<$last){
	 $SgetName[] = "(SELECT 	
			`tovar_inet_id_parent`,
			`tovar_artkl`,
			`tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_".$setup['web default price']."` as price1,
			`price_tovar_".$_SESSION[BASE.'userprice']."` as price2,
			`price_tovar_curr_".$setup['web default price']."` as curr1,
			`price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
			`description_".$_SESSION[BASE.'lang']."` as tovar_memo,
			`parent_inet_type`
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_description`, 
			`tbl_parent_inet`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`tovar_inet_id`>0 and
			`parent_inet_id`=`tovar_inet_id_parent` and
			`tovar_id`=`description_tovar_id` 
			ORDER BY `tovar_id` DESC
			LIMIT ".rand(0,$row_count/20).",1)";
    }
    $SgetName = implode(" UNION ", $SgetName);
    //echo $SgetName;
}else{
$SgetName = "SELECT 	`tovar_inet_id_parent`,
			`tovar_artkl`,
			`tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_".$setup['web default price']."` as price1,
			`price_tovar_".$_SESSION[BASE.'userprice']."` as price2,
			`price_tovar_curr_".$setup['web default price']."` as curr1,
			`price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
			`description_".$_SESSION[BASE.'lang']."` as tovar_memo,
			`parent_inet_type`
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_description`, 
			`tbl_parent_inet`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`tovar_inet_id`>0 and
			`parent_inet_id`=`tovar_inet_id_parent` and
			`tovar_id`=`description_tovar_id` 
			ORDER BY `tovar_id` DESC,
				  `tovar_name_1` ASC,
				  `tovar_artkl` ASC
			LIMIT 0, $last
			";
}
$getName = mysql_query($SgetName);

if (!$getName){
  echo "Query error - tbl_price - ",$SgetName;
  exit();
}

$html = tovar_view($getName,"last tovar",$setup);;

return $html;

}
function user_selected_item_list_view($setup,$setup) {

if(!isset($_SESSION[BASE.'userid'])) {
      return "none";
      exit();
      }
//include 'init.lib.php';
//echo "ggg";
//connect_to_mysql();


$getName = mysql_query("SET NAMES utf8");
$SgetName = "SELECT 	`tovar_inet_id_parent`,
			`tovar_artkl`,
			`tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_".$setup['web default price']."` as price1,
			`price_tovar_".$_SESSION[BASE.'userprice']."` as price2,
			`price_tovar_curr_".$setup['web default price']."` as curr1,
			`price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
			`description_".$_SESSION[BASE.'lang']."` as tovar_memo,
			`parent_inet_type`
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_description`, 
			`tbl_parent_inet`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`tovar_inet_id`>0 and
			`parent_inet_id`=`tovar_inet_id_parent` and
			`tovar_id`=`description_tovar_id` 
			ORDER BY `tovar_name_1` ASC,
				  `tovar_artkl` ASC
			LIMIT 0, 80
			";

$SgetName = "SELECT 	`tovar_inet_id_parent`,
			`tovar_artkl`,
			`tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_".$setup['web default price']."` as price1,
			`price_tovar_".$_SESSION[BASE.'userprice']."` as price2,
			`price_tovar_curr_".$setup['web default price']."` as curr1,
			`price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
			`description_".$_SESSION[BASE.'lang']."` as tovar_memo,
			`parent_inet_type`
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_description`,
			`tbl_parent_inet`,
			`tbl_currency`,
			`tbl_opa`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`tovar_inet_id`>0 and
			`parent_inet_id`=`tovar_inet_id_parent` and
			`tovar_id`=`description_tovar_id` and
			`tovar_id`=`opa_tovar` and
			`opa_klient`='".$_SESSION[BASE.'userid']."'
				ORDER BY `tovar_name_1` ASC,
				`tovar_artkl` ASC
			";
//echo $SgetName;
$getName = mysql_query($SgetName);

if (!$getName)
{
  echo "Query error - tbl_price - ",$SgetName;
  exit();
}
return utilites($setup).tovar_view($getName,"none add dell",$setup);

}
function find_tovar($find,$setup,$setup){
   /* $top = mysql_query("SET NAMES utf8");
    $SgetName = "SELECT `find_user` FROM `tbl_find` ORDER BY `find_status` DESC LIMIT 0,10;
		  ";
    $top = mysql_query($SgetName);*/
    
    
$html = "";
$searchq="";
$html .= "
       <script src='".HOST_URL."/admin/JsHttpRequest.js'></script>";
$html .= "<form id='searchForm' name='searchForm' method='get'>
      <table width='400px' cellpadding='0px' cellspacing='0px'><tr><td>
         <input class='find_string' id='city' 
	   size='80' 
	    autocomplete='OFF' 
	      onkeyup='PressKey(event)' 
	      placeholder='".$setup['menu find-str']."'
		name='find' 
		    value='' 
		      onEnter='submit();' 
			><br>
        <select class='find_string' id='info' size='10' style='visibility:hidden;position:absolute;z-index:1100;'
                onkeyup='PressKey2(event)' onclick='PressKey2(event)'>
                </select>
	</td><!--td width='40px'>
		<input type='image' onClick='submit();' src='resources/find.gif' alt='find' height='40' class=\"find_key\">      
        </td--><tr></table>
	  </form>
        ";

$html .= "
  <script type='text/javascript'><!--
    var ot='', timer=0, x=-1,y=0;
 
    function PressKey2(e){ // вызывается при нажатии клавиши в select
	//alert(e.keyCode);
        e=e||window.event;
        t=(window.event) ? window.event.srcElement : e.currentTarget; // объект для которого вызывно
        if(e.keyCode==13||e.keyCode==39){ // Enter
	    getObj('info').style.visibility = 'hidden'; // спрячем select
	    getObj('city').focus();
           // t=(window.event) ? window.event.srcElement : e.currentTarget; // объект для которого вызывно
           // t.form.onsubmit();
            return;
        }else if(e.keyCode==27){ // ESC
	    getObj('info').style.visibility = 'hidden'; // спрячем select
	    getObj('city').focus();
	}else if(e.keyCode==40||e.keyCode==38){// Up or Down
            //getObj('city').focus();
            getObj('city').value=getObj('info').options[getObj('info').selectedIndex].text;
            //getObj('info').style.visibility = 'hidden'; // спрячем select
        }else{
           getObj('city').value=getObj('info').options[getObj('info').selectedIndex].text;
        }
       
       
    }
    // Определение координаты элемента
    function pageX(elem) {
        return elem.offsetParent ?
            elem.offsetLeft + pageX( elem.offsetParent ) :
            elem.offsetLeft;
    }
    function pageY(elem) {
        return elem.offsetParent ?
            elem.offsetTop + pageY( elem.offsetParent ) :
            elem.offsetTop;
    }

    function PressKey(e){
    //alert(e.keyCode);
        e=e||window.event;
        t=(window.event) ? window.event.srcElement : e.currentTarget; // объект для которого вызвано
        g=getObj('info');
        if(x==-1&&y==0){// при первом обращении просчитываю координаты
            x=pageX(t); y=pageY(t);
            g.style.top = y -40 +'px';//+ t.clientHeight + 100 + 'px';
            g.style.left = x - 200 + 'px';
	    console.log(x);
        }
        if(e.keyCode==40){
	    g.focus();
	    g.selectedIndex=0;
	    getObj('city').value=getObj('info').options[getObj('info').selectedIndex].text;
	    return;}
        if(ot==t.value)return; // если ничего не изменилось не 'замучить' сервер
        ot=t.value;
        if(timer){clearTimeout(timer);timer=0;}
        if(ot.length<3){
            getObj('info').style.visibility = 'hidden'; // спрячем select
            return;}
        timer=window.setTimeout('Load()',300);  // загружаю через 0.3 секунду после последнего нажатия клавиши
    }
  
  
 /* function setclear(a){
      if(a==1&&document.getElementById('city').value=='".$setup['menu find-str']."')
	{document.getElementById('city').value='';}
      else if(a==0&&document.getElementById('city').value=='')
      {
	document.getElementById('city').value='".$setup['menu find-str']."';
      }
  }*/
 
    function Load(){
        timer=0;
        o=getObj('info');
        o.options.length=0;
        ajaxLoad('info', 'find_server.php?city_name='+ot, '','','');
   }
    getObj('city').focus();

    function getObj(objID)
    {
      if (document.getElementById) {return document.getElementById(objID);}
      else if (document.all) {return document.all[objID];}
      else if (document.layers) {return document.layers[objID];}
    }

    function ajaxLoad(obj,url,defMessage,post,callback){
        var ajaxObj;
        if (defMessage) document.getElementById(obj).innerHTML=defMessage;
        if(window.XMLHttpRequest){
            ajaxObj = new XMLHttpRequest();
        } else if(window.ActiveXObject){
            ajaxObj = new ActiveXObject('Microsoft.XMLHTTP');
        } else {
            return;
        }
        ajaxObj.open ((post?'POST':'GET'), url);
        if (post&&ajaxObj.setRequestHeader)
            ajaxObj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=windows-1251;');

        ajaxObj.onreadystatechange = ajaxCallBack(obj,ajaxObj,(callback?callback:null));
        ajaxObj.send(post);
        return false;
    }

    function updateObj(obj, data, bold, blink){
        if(bold)data=data.bold();
        if(blink)data=data.blink();
        data=data.split('***');
        document.getElementById(obj).innerHTML = data[0]; // упрощенный вариант, работает не во всех браузерах
	document.getElementById(obj).size=data[1];
      //alert(data[0]+' === '+data[1]);
      if(data[1]>0)
	{o.style.visibility='visible';}
      else
	{o.style.visibility='hidden';}
    }

    function ajaxCallBack(obj, ajaxObj, callback){
        return function(){
            if(ajaxObj.readyState == 4){
                if(callback) if(!callback(obj,ajaxObj))return;
                if (ajaxObj.status==200)
                    updateObj(obj, ajaxObj.responseText);
                else updateObj(obj, ajaxObj.status+' '+ajaxObj.statusText,1,1);
            }
        }}


    //-->
</script>";
return $html;
}
function find_result($find) {
global $setup;
$html = "";

if(!isset($_SESSION[BASE.'userprice'])) $_SESSION[BASE.'userprice']=$setup['web default price'];
if(!empty($find))
{ 
    $dell = array("<",
		">",
		"img",
		"src",
		"script",
		"php",
		"\"",
		"'",
		"href"
		);
    $find = str_replace($dell,"",$find);
    $find = str_replace(" ","%",$find);

    $searchq = $find;
    if(strlen(addslashes($searchq))<4) {
      $html = "<br><table class=\"error_msg\"><tr><td>
	  <img src=\"".HOST_URL."/resources/info/info.png\">
	  </td><td>
	  ".$setup['menu short find']."
	  </td></tr></table>";
      return $html;
      exit();
    }

    $getName = mysql_query("SET NAMES utf8");
    $SgetName = "SELECT 	`tovar_inet_id_parent`,
			`tovar_artkl`,
			`tovar_name_".$_SESSION[BASE.'lang']."` AS tovar_name,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_".$setup['web default price']."` as price1,
			`price_tovar_".$_SESSION[BASE.'userprice']."` as price2,
			`price_tovar_curr_".$setup['web default price']."` as curr1,
			`price_tovar_curr_".$_SESSION[BASE.'userprice']."` as curr2,
			`description_".$_SESSION[BASE.'lang']."` as tovar_memo,
			`parent_inet_type`
			FROM 
			`tbl_tovar`,
			`tbl_klienti`,
			`tbl_price_tovar`,
			`tbl_description`,
			`tbl_parent_inet`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`tovar_supplier`=`klienti_id` and
			`tovar_inet_id`>0 and
			`parent_inet_id`=`tovar_inet_id_parent` and
			`tovar_id`=`description_tovar_id` and (
			upper(`tovar_artkl`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
			upper(`tovar_name_1`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
			upper(`klienti_name_1`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
			upper(`klienti_name_2`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
			upper(`klienti_name_3`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
			upper(`tovar_name_2`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%')
			ORDER BY `tovar_name_1` ASC,
				  `tovar_artkl` ASC
			LIMIT 0, 1000
			";
//echo $SgetName;
    $getName = mysql_query($SgetName);
    if (!$getName)
    {
      echo "Query error - FIND - ",$SgetName;
      exit();
    }
    $html .= tovar_view($getName,"find result",$setup);

    if(strlen(addslashes($searchq))>3)
    {
	$find_wer = str_replace("%"," ",$searchq); 
	$find_table = mysql_query("SET NAMES utf8");
	$SgetName = "SELECT `find_id`
			FROM 
			`tbl_find`
			WHERE 
			`find_user`= '".(string)$find_wer."'
			";

	$find_table = mysql_query($SgetName);
	if (!$find_table)
	{
	    echo "Query error - tbl_find - ",$SgetName;
	    exit();
	}elseif (mysql_num_rows($find_table)>0){
	    $up_find = mysql_query("SET NAMES utf8");
	    $SgetName = "UPDATE `tbl_find`
			SET `find_status`=`find_status`+1
			WHERE
			`find_id`='".mysql_result($find_table,0,0)."'";
	    $up_find = mysql_query($SgetName);
	}else{
	    $SgetName = "SELECT 
			`tovar_id`
			FROM 
			`tbl_tovar`
			WHERE 
			upper(`tovar_name_1`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')." %' or
			upper(`tovar_name_2`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')." %'
		  LIMIT 0,1";
		  
	    $getName = mysql_query("SET NAMES utf8");
	    $getName = mysql_query($SgetName);
	    if(mysql_num_rows($getName)>0){
		$added = mysql_query("SET NAMES utf8");
		$SgetName = "INSERT INTO `tbl_find`
			     (`find_user`)
			      VALUES
			      ('$find_wer')";
		$added = mysql_query($SgetName);
	    }
	}
    }
}
return $html;
  
}
function user_registration($setup,$setup) {
    $html = "";
global $setup;

$html .= "<table><tr><td>
	  <img src=\"".HOST_URL."/resources/info/info.png\">
	  </td><td>
	  <font class=\"information\">".$setup['info pleace remembe']."</font>
	  </td></tr></table>";
//echo "\n<script src='JsHttpRequest.js'></script>";
$html .= "\n<script type='text/javascript'>";
//===================JAVA================================
 /*   $html .= "
    function find_window_script(tbl,id,name,sel_name,target){
    var div_mas =  document.getElementById('find_window');
      div_mas.innerHTML=sel_name+'<br><input type=\"text\"  style=\"width:600px\" onKeyPress=\"find_script(\''+tbl+'\',\''+id+'\',\''+name+'\',this.value)\">';
     div_mas.innerHTML+='<br><select id=\"find_sel\" size=30 style=\"width:600px\" onclick=\"set_select_value(\''+target+'\')\" onChange=\"set_select_value(\''+target+'\')\"></select>';
     }";*/
//===============================================================
    $html .="
    function pass_verify(a){
    var pass1 = document.getElementById('userpass');
    var pass2 = document.getElementById('userpass1');
    var div_pas = document.getElementById('div_pass');
    //var test = document.getElementById('username');
    //test.value = pass1.value + ' '+ pass2.value;
    
    //if(pass1.length==5){
      if (pass1.value==pass2.value){
	  div_pas.innerHTML = 'OK';
	  test.value = 'OK';
	  }else{
	  div_pas.innerHTML = '".$setup['menu a not b']."';
	  }
      //}
    }";
//===============================================================
    $html .= "
    function set_select_value(target){
    //alert(target);
    var div_id =  document.getElementById(target);
    var sel =  document.getElementById('find_sel');
    var div_text =  document.getElementById(target+'_text');
      div_id.value=sel.value;
      div_text.type = 'text';
      div_text.value=sel[sel.selectedIndex].text;
     }";
//===============================================================
    $html .="
	function set_city_find(){
	//alert('ggg');
	//<a href='#none' onClick='find_window_script(\"tbl_delivery\",\"delivery_id\",\"delivery_name\",\"".$setup['menu delivery find']."\",\"klienti_delivery_id\")'> [".$setup['menu find']." ".$setup['menu sity']."] </a>
	var x = document.getElementById('find_sity').selectedIndex;
	var value = document.getElementById('find_sity').options[x].text;
	//alert(value);
	find_script(\"tbl_delivery\",\"delivery_id\",\"delivery_name\",value);
    }
    ";
    $html .= "
    function find_window_script_sity(tbl,id,name,sel_name,find){
    //alert(tbl+id+name+sel_name+find);
    var div_mas =  document.getElementById('find_window');
     div_mas.innerHTML = '<select id=\"find_sity\" style=\"width:400px\" onChange=\"set_city_find();\"></select>';
     //alert(tbl+id+name+sel_name+target);
     info('Wait...');
	var div_mas_sell =  document.getElementById('find_sity');
	  var req=new JsHttpRequest();
	    req.onreadystatechange=function(){
	      //alert(req.readyState);
		if(req.readyState==4){
		    //div_mas_sell.remove(0);
		    var responce=req.responseText;
		    var str1=responce.split('||');
		    var str2='';
		    var count=0;
		      while(str1[count]){
			  str2=str1[count].split('|');
			  //alert(str2[0]+' '+count);
			  div_mas_sell.options[count]=new Option(str2[0],count);
		      count++;
		      }
	    div_mas.innerHTML+='<br><select id=\"find_sel\" size=30 style=\"width:400px\" onclick=\"set_select_value(\'klienti_delivery_id\')\" onChange=\"set_select_value(\'klienti_delivery_id\')\"></select>';
	    set_city_find();
	    info('');
	  }}
    req.open(null,'".HOST_URL."/find_sort.php',true);
    req.send({table:tbl,table_id:id,table_name:name,find_str:find});
    
 
     }";
//=======================================================================
    $html .= "
     function find_script(tbl,id,name,find){
    if(find.length > 2){
	info('Wait...');
	var div_mas =  document.getElementById('find_sel');
	  //div_mas.options[0]=new Option('Loading...',0);
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
			  div_mas.options[count]=new Option(str2[1],str2[0]);
		      count++;
		      }
		      if(count>0){
			  div_mas.options.selectedIndex=0;
			  set_select_value('klienti_delivery_id');
		      }  
		      info('');

	  }}
    req.open(null,'".HOST_URL."/find_sort.php',true);
    req.send({table:tbl,table_id:id,table_name:name,find_str:find});
    }
    }";

    $html .= "\n</script>";

 
 $errors = ""; 
 if(isset($_REQUEST['submit'])){
 //echo "registiring....";

 if($_REQUEST['username']==null){
    $errors .= $setup['user name']." - error<br>";
 }
 if($_REQUEST['userphone']==null){
    $errors .= $setup['user tel']." - error<br>";
 }
 if($_REQUEST['useremail']==null){
    $errors .= $setup['user email']." - error<br>";
 }
 if($_REQUEST['userpass']==null){
    $errors .= $setup['user pass']." - error<br>";
 }
 if($_REQUEST['userpass']<>$_REQUEST['userpass1']){
    $errors .= $setup['user pass1']." - error<br>";
 }
 if($_REQUEST['klienti_delivery_id']==null){
    $errors .= $setup['user delive']." - error<br>";
 }
 
 //proverka ili uze netu!====================================================
 if($errors==""){
 $ver = mysql_query("SET NAMES utf8");
  $tQuery = "SELECT 
	  `klienti_id`	  
	  FROM `tbl_klienti`
	  WHERE 
	  `klienti_email` = '".mysql_real_escape_string($_REQUEST['useremail'])."'
";
$ver = mysql_query($tQuery);
  if(mysql_num_rows($ver)>0) $errors .= "ERROR! - ".$setup['user wrong email']."<br><a href='/remember.php'>".$setup['login remembe pass']."</a><br>";
}  //proverka ili uze netu!====================================================
    if($errors==""){
  $ver = mysql_query("SET NAMES utf8");
    $tQuery = "SELECT 
	  `klienti_id`	  
	  FROM `tbl_klienti`
	  WHERE 
	  `klienti_phone_1` = '".mysql_real_escape_string($_REQUEST['userphone'])."'
";
$ver = mysql_query($tQuery);
if(mysql_num_rows($ver)>0) $errors .= "ERROR! - ".$setup['user wrong phone']."<br><a href='/remember.php'>".$setup['login remembe pass']."</a><br>";
}
//--------------------------------------------------------------------------------------

    if($errors==""){
    $date = date("Y-m-d G:i:s");
      $user_add = mysql_query("SET NAMES utf8");
      $tQuery = "INSERT INTO `tbl_klienti`
      (`klienti_id`,
	`klienti_group`,
	`klienti_name_1`,
	`klienti_name_2`,
	`klienti_name_3`,
	`klienti_pass`,
	`klienti_phone_1`,
	`klienti_email`,
	`klienti_edit`,
	`klienti_delivery_id`,
	`klienti_inet_id`,
	`klienti_price`,
	`klienti_discount`,
	`klienti_ip`
	
      )VALUES(
      '',
      '3',
      '".mysql_real_escape_string($_REQUEST['username'])."',
      '".mysql_real_escape_string($_REQUEST['username'])."',
      '".mysql_real_escape_string($_REQUEST['username'])."',
      '".md5(mysql_real_escape_string($_REQUEST['userpass']))."',
      '".mysql_real_escape_string($_REQUEST['userphone'])."',
      '".mysql_real_escape_string($_REQUEST['useremail'])."',
      '".$date."',
      '".mysql_real_escape_string($_REQUEST['klienti_delivery_id'])."',
      '10',
      '".$setup['price default price']."',
      '0',
      '".$_SERVER['REMOTE_ADDR']."'
      )
";
      $user_add = mysql_query($tQuery);
    $errors = "<br>New user added - OK -".mysql_insert_id();
	if(mysql_insert_id()>0){ 
	  $_SESSION[BASE.'login']=mysql_real_escape_string($_REQUEST['useremail']);
	  $_SESSION[BASE.'username']=mysql_real_escape_string($_REQUEST['username']);
	  $_SESSION[BASE.'userid']=mysql_insert_id();
	  $_SESSION[BASE.'usersetup']="";
	  header ('Refresh: 0; url=index.php');}
     }
 
 
 }
 //========================================================================================================
    $region = mysql_query("SET NAMES utf8");
    $SgetName = "SELECT `ukraina_id`,`ukraina_region` 
		  FROM `tbl_ukraina`
		  GROUP BY `ukraina_region`
		  ORDER BY `ukraina_region` ASC
		";
    $region = mysql_query($SgetName); 
 
 
$html .= "<table width=100% height=100% ><tr><td align='center' valign='middle'>";
$html .= session_verify($_SERVER["PHP_SELF"]."?".$_SERVER['QUERY_STRING']);
$html .= "<br>".$errors."<br><br>";
//============FIND====================================================================================
$html .= "<form method='POST' action='".HOST_URL."/index.php'>
	  <table class=\"newuser\"><tr><td>
      ";
//echo "\n<input type='hidden' name='web' value='"  , $_REQUEST["web"]  , "'/>";
$user_name="";
if(isset($_REQUEST['username']))$user_name = mysql_real_escape_string($_REQUEST['username']);
$user_phone="";
if(isset($_REQUEST['userphone']))$user_phone = mysql_real_escape_string($_REQUEST['userphone']);
$user_email="";
if(isset($_REQUEST['useremail']))$user_email = mysql_real_escape_string($_REQUEST['useremail']);
$user_pass="";
if(isset($_REQUEST['userpass']))$user_pass = mysql_real_escape_string($_REQUEST['userpass']);
$user_pass1="";
if(isset($_REQUEST['userpass1']))$user_pass1 = mysql_real_escape_string($_REQUEST['userpass1']);
$user_delivery_name="";
if(isset($_REQUEST['delivery_name']))$user_delivery_name = mysql_real_escape_string($_REQUEST['delivery_name']);
$user_delivery_id="";
if(isset($_REQUEST['klienti_delivery_id']))$user_delivery_name = mysql_real_escape_string($_REQUEST['klienti_delivery_id']);

$html .= $setup['user name'].":</td><td width=300><input type='text' style='width:300px' id='username' name='username' value='".$user_name."'/>

</td></tr><tr><td>";
$html .= $setup['user tel'].",0671723638:</td><td><input type='text' style='width:300px' id='userphone' name='userphone' value='".$user_phone."'/>
</td><td>
</td></tr><tr><td>";
$html .= $setup['user email'].":</td><td><input type='text' style='width:300px' id='useremail' name='useremail' value='".$user_email."'/>
</td><td>
</td></tr><tr><td>";
$html .= $setup['user pass']. ":</td><td><input type='password' style='width:300px' id='userpass' onKeyUp='pass_verify(this.value);' name='userpass' value='".$user_pass."'/>
</td><td align=left><div id='div_pass'></div>
</td></tr><tr><td>";
$html .= $setup['user pass1'].":</td><td><input type='password' style='width:300px' id='userpass1' onKeyUp='pass_verify(this.value);' name='userpass1' value='".$user_pass1."'/>
</td><td><div id='div_pass'></div>
</td></tr><tr><td valign=\"top\">";

if (!isset($_REQUEST['delivery_name'])){
$delive="hidden";
}else{
$delive="text";
}

$html .= $setup['user delive'].":</td><td>
	<input type='hidden'  style='width:0px'  name='klienti_delivery_id' id='klienti_delivery_id' name='delivery_id' value='" . $user_delivery_id. "'/>
	<input type='".$delive."' disabled style='width:400px'  id='klienti_delivery_id_text' name='delivery_name' value='" . $user_delivery_name. "'/>
  <select name='ukraina_region' style='width:400px' onChange='find_window_script_sity(\"tbl_ukraina\",\"ukraina_city\",\"ukraina_region\",\"".$setup['menu delivery find']."\",this.value);'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($region))
{
  $html .= "<option ";
  $html .= "value=" . mysql_result($region,$count,"ukraina_region") . ">" . mysql_result($region,$count,"ukraina_region") . "</option>";
  $count++;
}
$html .= "</select><br>";

//<a href='#none' onClick='find_window_script(\"tbl_delivery\",\"delivery_id\",\"delivery_name\",\"".$setup['menu delivery find']."\",\"klienti_delivery_id\")'> [".$setup['menu find']." ".$setup['menu sity']."] </a>
 
$html .= "<div id='find_window'></div><br>
  <div id='find_div'></div>
  <div id='view'></div>

</td></tr><tr><td align='center' colspan=2>";
$html .= "<input type='submit' name='submit' value='".$setup['menu user register']."'/>
      \n";
//=====================================================================================================
$html .= "</td></tr></table></form>";
$html .= "</td></tr></table>";
   
    
    
    return $html;
}
function user_edit($setup) {
$count = 0;
$this_page_name = "edit_klient.php";
$this_table_id_name = "klienti_id";
$this_table_name_name = "klienti_name_1";

$this_table_name = "tbl_klienti";
$sort_find_deliv = "";
if(isset($_GET["_sort_find_deliv"]))$sort_find_deliv =mysql_real_escape_string($_GET["_sort_find_deliv"]);
$iKlient_id = $_SESSION[BASE.'userid'];
$html = "";
$err = "";
$iKlient_count = 0;

if(isset($_REQUEST['submit'])) {
$new_pass_str="";
if(isset($_REQUEST['klienti_pass'])){ //если пароль есть и не пустой
  if($_REQUEST['klienti_pass']!="") $new_pass_str = " `klienti_pass`='".md5(mysql_real_escape_string($_REQUEST['klienti_pass']))."', ";
}

  $ver = mysql_query("SET NAMES utf8");
  $tQuery = "UPDATE `tbl_klienti` SET
	    `klienti_name_1`='".mysql_real_escape_string($_REQUEST['klienti_name_1'])."',
	    `klienti_name_2`='".mysql_real_escape_string($_REQUEST['klienti_name_2'])."',
	    `klienti_name_3`='".mysql_real_escape_string($_REQUEST['klienti_name_3'])."',
	    `klienti_delivery_id`='".mysql_real_escape_string($_REQUEST['klienti_delivery_id'])."',
	    $new_pass_str
	    `klienti_adress`='".mysql_real_escape_string($_REQUEST['klienti_adress'])."',
	    `klienti_sity`='".mysql_real_escape_string($_REQUEST['klienti_sity'])."',
	    `klienti_country`='".mysql_real_escape_string($_REQUEST['klienti_country'])."',
	    `klienti_phone_1`='".mysql_real_escape_string($_REQUEST['klienti_phone_1'])."',
	    `klienti_phone_2`='".mysql_real_escape_string($_REQUEST['klienti_phone_2'])."',
	    `klienti_phone_3`='".mysql_real_escape_string($_REQUEST['klienti_phone_3'])."'    
	    WHERE `klienti_id`='".$_SESSION[BASE.'userid']."'
  ";
  $ver = mysql_query($tQuery);

  if($ver){
    $err .= $setup['menu profile']." ".$_REQUEST['klienti_name_1']." - SAVE OK";
    $_SESSION[BASE.'username'] = mysql_real_escape_string($_REQUEST['klienti_name_1']);
  }else{
    $err .= $setup['menu profile']." ".$_REQUEST['klienti_name_1']." - ERROR!";
  }
}

if(isset($_REQUEST['submit'])) {
//  header ('Refresh: 0; url=index.php');
}


$ver = mysql_query("SET NAMES utf8");
$ver = mysql_query("SELECT * FROM " . $this_table_name . " WHERE " . $this_table_id_name . " = " . $iKlient_id);

if (!$ver)
{
  echo "Query error - ", $this_table_name;
  exit();
}

$deliv = mysql_query("SET NAMES utf8");
$deliv = mysql_query("SELECT `delivery_id`,`delivery_name` FROM `tbl_delivery` WHERE `delivery_id`='".mysql_result($ver,0,"klienti_delivery_id")."'");# WHERE klienti_id = " . $iKlient_id);
if (!$deliv)
{
  echo "Query error - tbl_price";
  exit();
}
 //========================================================================================================
    $region = mysql_query("SET NAMES utf8");
    $SgetName = "SELECT `ukraina_id`,`ukraina_region` 
		  FROM `tbl_ukraina`
		  GROUP BY `ukraina_region`
		  ORDER BY `ukraina_region` ASC
		";
    $region = mysql_query($SgetName); 

//header ('Content-Type: text/html; charset=utf8');
//$html .= "<header><link rel='stylesheet' type='text/css' href='sturm.css'></header>";
//$html .= "\n<script src='JsHttpRequest.js'></script>";
$html .= "\n<script type='text/javascript'>";
//===================JAVA================================
    $html .= "
    function find_window_script(tbl,id,name,sel_name,target){
    var div_mas =  document.getElementById('find_window');
      div_mas.innerHTML=sel_name+'<br><input type=\"text\"  style=\"width:600px\" onKeyPress=\"find_script(\''+tbl+'\',\''+id+'\',\''+name+'\',this.value)\">';
     div_mas.innerHTML+='<br><select id=\"find_sel\" size=30 style=\"width:600px\" onclick=\"set_select_value(\''+target+'\')\" ></select>';
     }";
     
    $html .="
	function set_city_find(){
	//alert('ggg');
	//<a href='#none' onClick='find_window_script(\"tbl_delivery\",\"delivery_id\",\"delivery_name\",\"".$setup['menu delivery find']."\",\"klienti_delivery_id\")'> [".$setup['menu find']." ".$setup['menu sity']."] </a>
	var x = document.getElementById('find_sity').selectedIndex;
	var value = document.getElementById('find_sity').options[x].text;
	//alert(value);
	find_script(\"tbl_delivery\",\"delivery_id\",\"delivery_name\",value);
    }
    ";

   $html .= "
  function find_window_script_sity(tbl,id,name,sel_name,find){
    //alert(tbl+id+name+sel_name+find);
    var div_mas =  document.getElementById('find_window');
     div_mas.innerHTML = '<select id=\"find_sity\" style=\"width:400px\" onChange=\"set_city_find();\"></select>';
     //alert(tbl+id+name+sel_name+target);
     info('Wait...');
	var div_mas_sell =  document.getElementById('find_sity');
	  var req=new JsHttpRequest();
	    req.onreadystatechange=function(){
	      //alert(req.readyState);
		if(req.readyState==4){
		    //div_mas_sell.remove(0);
		    var responce=req.responseText;
		    var str1=responce.split('||');
		    var str2='';
		    var count=0;
		      while(str1[count]){
			  str2=str1[count].split('|');
			  //alert(str2[0]+' '+count);
			  div_mas_sell.options[count]=new Option(str2[0],count);
		      count++;
		      }
	    div_mas.innerHTML+='<br><select id=\"find_sel\" size=30 style=\"width:400px\" onclick=\"set_select_value(\'klienti_delivery_id\')\" onChange=\"set_select_value(\'klienti_delivery_id\')\"></select>';
	    set_city_find();
	    info('');
	  }}
    req.open(null,'".HOST_URL."/find_sort.php',true);
    req.send({table:tbl,table_id:id,table_name:name,find_str:find});
    }";
//===============================================================
    $html .= "
    function set_select_value(target){
    var div_id =  document.getElementById(target);
    var sel =  document.getElementById('find_sel');
    var div_text =  document.getElementById(target+'_text');
      div_id.value=sel.value;
      div_text.value=sel[sel.selectedIndex].text;
     //alert('select');
     }";
//===============================================================
    $html .= "
    function find_script(tbl,id,name,find){
        if(find.length > 2){
	info('Wait...');
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
	    
	    if(count>0){
		div_mas.options.selectedIndex=0;
		set_select_value('klienti_delivery_id');
	    }  
	    info('');
    }}
    req.open(null,'".HOST_URL."/find_sort.php',true);
    req.send({table:tbl,table_id:id,table_name:name,find_str:find});
    }
    }";

    $html .= "\n</script>";
//==================END JAVA ============================================
    
//echo "<title>Klient edit</title>";
//echo "\n<body>\n";
$html .= "\n<table width=\"100%\" cellspacing='0' cellpadding='0' class=\"edituser\"><tr><td align=\"center\">
	  $err<br>";
$html .= "<form method='POST' action='index.php'>
      <input type='submit' name='submit' class='key_button' value='".$setup['menu user edit']."'/>";
      //<input type='submit' name='_save' value='".$setup['menu goto main page']."'/>";

//echo "\n<input type='hidden' name='_page_to_return' value='" , $this_page_name , "?" , $this_table_id_name, "='/>";

//$html .= "\n<table border = 1 cellspacing='0' cellpadding='0' width=\"100%\"><tr><td align=\"center\">";//table dla find div
$html .= "\n<table cellspacing='0' cellpadding='0' class=\"edituser\">";

//$html .= "</tr>";


$html .= "\n<tr><td>".$setup['user name']." 1:</td><td>"; # Group name 1
$html .= "\n<input type='text'  style='width:400px'  name='klienti_name_1' value='" . mysql_result($ver,0,"klienti_name_1") . "'/></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";

$html .= "\n<tr><td>".$setup['user name']." 2:</td><td>"; # Group name 1
$html .= "\n<input type='text'  style='width:400px'  name='klienti_name_2' value='" . mysql_result($ver,0,"klienti_name_2") . "'/></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";


$html .= "\n<tr><td>".$setup['user name']." 3:</td><td>"; # Group name 1
$html .= "\n<input type='text'  style='width:400px'  name='klienti_name_3' value='" . mysql_result($ver,0,"klienti_name_3") . "'/></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";

$html .= "\n<tr><td>".$setup['menu pass'].":</td><td>"; # Group name 1
$html .= "\n<input type='text'  style='width:400px'  name='klienti_pass' placeholder='Оставьте пустым если не хотите менять!' value=''/></td>"; //" . mysql_result($ver,0,"klienti_pass") . "
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";


$html .= "\n<tr><td>".$setup['menu adress'].":</td><td>"; # Group name 1
$html .= "\n<input type='text'  style='width:400px'  name='klienti_adress' value='" . mysql_result($ver,0,"klienti_adress") . "'/></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";


$html .= "\n<tr><td>".$setup['menu sity'].":</td><td>"; # Group name 1
$html .= "\n<input type='text'  style='width:400px'  name='klienti_sity' value='" . mysql_result($ver,0,"klienti_sity") . "'/></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";


$html .= "\n<tr><td>".$setup['menu country'].":</td><td>"; # Group name 1
$html .= "\n<input type='text'  style='width:400px'  name='klienti_country' value='" . mysql_result($ver,0,"klienti_country") . "'/></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";


$html .= "\n<tr><td>".$setup['menu phone']." 1:</td><td>"; # Group name 1
$html .= "\n<input type='text'  style='width:400px'  name='klienti_phone_1' value='" . mysql_result($ver,0,"klienti_phone_1") . "'/></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";


$html .= "\n<tr><td>".$setup['menu phone']." 2:</td><td>"; # Group name 1
$html .= "\n<input type='text'  style='width:400px'  name='klienti_phone_2' value='" . mysql_result($ver,0,"klienti_phone_2") . "'/></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";


$html .= "\n<tr><td>".$setup['menu phone']." 3:</td><td>"; # Group name 1
$html .= "\n<input type='text'  style='width:400px'  name='klienti_phone_3' value='" . mysql_result($ver,0,"klienti_phone_3") . "'/></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";

//=====================================================================================================================
$html .= "\n<tr><td valign=\"top\">".$setup['menu delivery'].":</td><td>"; # Group klienti
$html .= "\n<input type='hidden'  style='width:0px'  name='klienti_delivery_id' id='klienti_delivery_id' value='" . mysql_result($ver,0,"klienti_delivery_id") . "'/>";
$html .= "\n<input type='text'  style='width:400px'  id='klienti_delivery_id_text' value='" . mysql_result($deliv,0,"delivery_name") . "'/>";

$html .= "<br>	
	<select name='ukraina_region' style='width:400px' onChange='find_window_script_sity(\"tbl_ukraina\",\"ukraina_city\",\"ukraina_region\",\"".$setup['menu delivery find']."\",this.value);'>";# OnChange='submit();'>";
$count=0;
while ($count < mysql_num_rows($region))
{
  $html .= "<option ";
  $html .= "value=" . mysql_result($region,$count,"ukraina_region") . ">" . mysql_result($region,$count,"ukraina_region") . "</option>";
  $count++;
}
$html .= "</select><br>";
$html .= "
  <div id='find_window'></div><br>
  <div id='find_div'></div>
  <div id='view'></div>
  </td>";
$html .= "</tr>";
//======================================================================================================================


$html .= "\n</table></form>"; 
  $html .= "
  </td></tr></table> ";
//echo "\n</body>";

    return  utilites($setup).$html;
    
}
function user_rem_pass($setup) {
  global $setup;
include 'admin/libmail.php';
$m = new Mail("UTF-8");
$html = "";
$errors = ""; 
 
 if(isset($_REQUEST['submit'])){
 
 if($_REQUEST['useremail']==null){
    $errors .= $setup['email not found']." - error<br>";
 }
 //proverka ili uze netu!====================================================
 if($errors==""){
 $ver = mysql_query("SET NAMES utf8");
  $tQuery = "SELECT 
	  `klienti_id`	  
	  FROM `tbl_klienti`
	  WHERE 
	  `klienti_email` = '".mysql_real_escape_string($_REQUEST['useremail'])."'
	    ";
  $ver = mysql_query($tQuery);
  } 
  
if(mysql_num_rows($ver)>0){// esli naszli ========================

$new_pass=substr(uniqid(rand(),true),0,6);

  $ver1 = mysql_query("SET NAMES utf8");
    $tQuery = "UPDATE 
	  `tbl_klienti` 
	  SET
	  `klienti_pass`='".md5((string)$new_pass)."'
	  WHERE 
	  `klienti_id`='".mysql_result($ver,0,0)."'
    ";
  $ver1 = mysql_query($tQuery);
//echo $setup['email name'] . ' '. $setup['email'];
  $m->From($setup['email name'].";".$setup['email']);
  $m->smtp_on($setup['email smtp'],$setup['email login'],$setup['email pass'],$setup['email port']);//465 587
  $m->Priority(2);
  $m->Body($setup['email user newpass']."<br>login: <b>".$_REQUEST['useremail']."</b><br>pass: <b>".$new_pass."</b>");
  $m->text_html="text/html";
  $m->Subject($setup['email name']." Password");
  $m->To($_REQUEST['useremail']);
  $error = $m->Send();
    if($error==1){
	$html .= "<table width=100% height=100% class=\"edituser\"><tr><td align='center' valign='middle'>";
	$html .= $setup['email sended'];
	$html .= "</td></tr></table>";
	//header ('Refresh: 2; url=index.php');
      }
      else{
	$html .= "<br><b>Email DONT send!!! Error email - ". $error; }
}else{
  $errors .= "ERROR! - ".$setup['email not found'];
}
} 
$html .= "<table width=100% height=100% class=\"edituser\"><tr><td align='center' valign='middle'>";
$html .= "<br>".$errors."<br><br>";
//============FIND====================================================================================
$html .= "<form method='POST' action='".HOST_URL."/index.php?user=rem_pass'><table  class=\"edituser\"><tr><td align=\"center\">
      ";
$html .= $setup['email write'].":</td></tr>
	<tr><td align=\"center\"><input type='text' style='width:300px' id='useremail' name='useremail' value=''/>
      </td></tr>

<tr><td align='center'>";
$html .= "<input type='submit' name='submit' class='key_button' value='".$setup['menu send']."'/>
      \n";
//=====================================================================================================
$html .= "</td></tr></table></form>";
$html .= "</td></tr></table>";



return $html;

}
function view_order($setup) {
if(isset($_SESSION[BASE.'userorder'])){
global $Alias;
$html = "";
$user = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	    `klienti_name_1`,
	    `klienti_email`,
	    `klienti_phone_1`,
	    `delivery_name`
	    FROM
	     `tbl_klienti`,`tbl_delivery` 
	    WHERE
	    `klienti_id`='".$_SESSION[BASE.'userid']."' and
	    `klienti_delivery_id`=`delivery_id`";
$user = mysql_query($tQuery);

$tovar = mysql_query("SET NAMES utf8");
$tQuery = "SELECT 
	    `tovar_id`,
	    `tovar_inet_id`,
	    `tovar_artkl`,
	    `tovar_inet_id_parent`,
	    `operation_detail_item`,
	    `operation_detail_price`,
	    `operation_detail_discount`,
	    `operation_detail_summ`,
	    `tovar_name_".$_SESSION[BASE.'lang']."` AS name
	    FROM
	    `tbl_tovar`,
	    `tbl_operation_detail`
	    WHERE
	    `tovar_id`=`operation_detail_tovar` and
	    `operation_detail_operation`='".$_SESSION[BASE.'userorder']."' and
	    `operation_detail_dell`='0'
";
$tovar = mysql_query($tQuery);

$html .= "
      <script>
	  function addtovar(id){
	      var id=id.split('*');
	      var value = 0;
      
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      if(req.readyState==4){
	 var responce=req.responseText;
	 alert(responce);
	 window.location.reload();
    }}
    req.open(null,'".HOST_URL."/user_order.php',true);
    req.send({value:value,id:id[1],order:id[0]});
    }     
        function send_order(id){
	    window.open(\"".HOST_URL."/index.php?user=send_order\");
    }  
      </script>
";

$name = '';
$tel = '';
$email = '';
$deliv = '';

if(mysql_num_rows($user) > 0){

  $name = mysql_result($user,0,"klienti_name_1");
  $tel = mysql_result($user,0,"klienti_phone_1");
  $email = mysql_result($user,0,"klienti_email");
  $deliv = mysql_result($user,0,"delivery_name");
  
}

$html .= "<table class=\"edituser\"><tr>";
$html .= "<td>".$setup['user name']." : </td><td>".$name."</td></tr><tr>";
$html .= "<td>".$setup['user tel']." : </td><td>".$tel."</td></tr><tr>";
$html .= "<td>".$setup['user email']." : </td><td>".$email."</td></tr><tr>";
$html .= "<td>".$setup['user delive']." : </td><td>".$deliv."</td></tr><tr>";
$html .= "<td><a href='".HOST_URL."/user_edit.php'> (".$setup['menu edit'].")</a></td><td align=right><b>".$setup['user order sum']." ".$_SESSION[BASE.'userordersumm']." ".$_SESSION[BASE.'usercurr']."</b></td></tr><tr>";
$html .= "</tr></table>";


$count=0;
$html .= "<table class=\"edituser\">";
while($count < mysql_num_rows($tovar)){
       //Разбиваем атрикл на тело и размер
        $artkl = mysql_result($tovar,$count,"tovar_artkl");
        $size = "нет";
        if(strpos($artkl,$setup['tovar artikl-size sep']) !== false){
            $x = explode($setup['tovar artikl-size sep'], $artkl);
            $artkl = $x[0];
            $size = $x[1];
        }
	$link = $artkl;
	$alias = $Alias->getProductAlias(mysql_result($tovar,$count,"tovar_id"));
    
    $full_link = "".HOST_URL."/resources/products/".$link."/".$link.".0.small.jpg";
    //echo $link;
      if(@fopen($full_link,"r")){
      	//$parent =mysql_result($tovar,$count,"tovar_inet_id_parent");
      }else{
	$full_link = "".HOST_URL."/resources/img/no_photo.png";
	//$link=mysql_result($tovar,$count,"tovar_inet_id_parent");
      }
    
    $name=explode("||", mysql_result($tovar,$count,"name"));
    
    $html .= "<tr><td while=110>";
    $html .=  "<a href='".HOST_URL."/$alias'>
      <br><img src='$full_link' width='70'></a>";
    $html .= "</td><td>".$name[0]."</td><td>
    ".mysql_result($tovar,$count,"tovar_artkl")."</td><td>
       - ".mysql_result($tovar,$count,"operation_detail_item")." X 
      ".mysql_result($tovar,$count,"operation_detail_price")." 
      ( = ".mysql_result($tovar,$count,"operation_detail_summ")." ".$_SESSION[BASE.'usercurr']." ) </td><td>
       - ".mysql_result($tovar,$count,"operation_detail_discount")."%
      ";
      
    $html .= "
        </td><td>
        <input type='button' class='key_button' value='".$setup['menu dell']."' id='del*".mysql_result($tovar,$count,"tovar_id")."' OnClick='addtovar(this.id);'/>
        </td></tr>
      ";
 
$count++;
}
$html .= "<tr><td colspan=5>".$setup['user menu order send']."</td></tr>";
$html .= "<tr><td colspan=5 align=center><input type='button' class='key_button' value='".$setup['menu order send']."' id='send*".$_SESSION[BASE.'userorder']."' OnClick='send_order(this.id);'/>
	  </td></tr>";
$html .= "</table><br><hr>";



return utilites($setup).$html;
}
}
function send_order($setup) {
include 'lib.set_status.php';
include 'lib.send_mail.php';

$operation_id = $_SESSION[BASE.'userorder'];

$oper = mysql_query("SET NAMES utf8");
$tQuery = "SELECT `operation_detail_tovar`,`operation_detail_item` FROM `tbl_operation_detail` 
	    WHERE `operation_detail_operation`='".$operation_id."' and `operation_detail_dell`='0'";
$oper = mysql_query($tQuery);
$html = "";
$count = 0;
$war_sum=0;
while($count<mysql_num_rows($oper)){
    if((tovar_on_ware(mysql_result($oper,$count,"operation_detail_tovar"))-mysql_result($oper,$count,"operation_detail_item"))>2){
	$war_sum = 1;
    }else{
	$war_sum=0;
	$count = mysql_num_rows(($oper))+1;
    }
  $count++;
 }
//echo $operation_id,$war_sum;
 set_status($operation_id,$war_sum);
 
 if($war_sum>0){
    $html = send_mail($operation_id,$setup['automail nakl'],$setup['automail tmp']);
 }else{
    $html = $setup['order need manager'];
 }
  $_SESSION[BASE.'userorder']=null;
  $_SESSION[BASE.'userordersumm']=null;
  
 return "".$html."";

}
function chat(){
global $setup;
$html="";
//if($_SESSION[BASE.'chat']=="close"){
    $html .= "<div id='chat_key' class='chat_key'>
	      <a href='Javascript:chat_set_size(\"small\");'>
		  <img src=\"".HOST_URL."/resources/img/chat.png\" width=\"70\">
	      </a>
	   </div>";

//====================================================================================
$html .= "<div id='chat_main' class='chat_main'>
		<div align='right' id='chat_menu' style='width:100%;background-color:white;align:right;'>
		<a href='Javascript:chat_set_size(\"large\");'>".$setup['chat large']."</a> |
		<a href='Javascript:chat_set_size(\"small\");'>".$setup['chat small']."</a> |
		<a href='Javascript:chat_set_size(\"close\");'>".$setup['chat close']." </a> 
		<hr></div>";

$html .= "		<div id='chat' class='chat'>chat";
$html .= "		</div>";
$html .= "
	    <hr>
	    <input type='hidden' id='chat_user_id' name='chat_user_id'>
	    <input type='text' id='chat_user_name' name='chat_user_name' disabled='disabled' value='".$setup['chat to all']."' style='width:70%;background-color:transparent;border:none;color:red;' >
	    <a href='Javascript:chat_set_user(0,\"".$setup['chat to all']."\");'>".$setup['chat to all']."</a>
	    <input type='text' id='chat_txt' style='width:100%;' onKeyDown='sent_chat_msg(event);'>
	  
	  </div>";
	  
	  if(isset($_SESSION[BASE.'usersetup'])){
	      if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){
		  $tmp = mysql_query("SET NAMES utf8");
			  $tQuery = "SELECT 
				      `chat_tmp_id`,
				      `chat_tmp_name`
				      FROM `tbl_chat_tmp`
				      ORDER BY `chat_tmp_name` ASC";
		  $tmp = mysql_query($tQuery);
		  $html .= "<div id='chat_tmp' class='chat_tmp'>";
		  
		    $count=0;
		    while($count<mysql_num_rows($tmp)){
			 $html .= "<a href='Javascript:set_chat_tmp(\"".mysql_result($tmp,$count,"chat_tmp_id")."\");'>".mysql_result($tmp,$count,"chat_tmp_name")."</a><br>";   
		      $count++;
		    }
		  $html .= "</div>";
	      }
	  }
return $html."";
 

}
function error_msg($msg,$setup){
$html = "";
  $html = "<br><table class=\"error_msg\"><tr><td>
	  <img src=\"".HOST_URL."/resources/info/info.png\">
	  </td><td>
	  ".$setup['error 404']."
	  </td></tr></table>";
return $html;
}
?>

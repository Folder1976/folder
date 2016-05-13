<?php
include 'init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

//echo $_SESSION[BASE.'usersetup'];
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
$iStatusSelect = "";
if(isset($_REQUEST["iStatus"])) $iStatusSelect=$_REQUEST["iStatus"];

$operation_sort = "";
if(isset($_REQUEST["operation_sort"])) $operation_sort=$_REQUEST["operation_sort"];

$iKlientSelect = "";
if(isset($_REQUEST["iKlient"])) $iKlientSelect=$_REQUEST["iKlient"];

$page = "";
if(isset($_REQUEST["page"])) $page=$_REQUEST["page"];

$operation_id = 0;
if(isset($_REQUEST['operation_id'])) $operation_id = $_REQUEST['operation_id'];

$iKlientGroupSelect = "";
if(isset($_REQUEST["iGroupSelected"])) $iKlientGroupSelect=$_REQUEST["iGroupSelected"];

$group="";

$function = new Functions();

$page_limit=40;

$tQuery = "";
//echo "efdfdf",$iStatusSelect;
if ($page==0)$page=0;
$limit = " LIMIT ".$page*$page_limit.", ".($page+1)*$page_limit;

// 


if (strpos($_SESSION[BASE.'usersetup'],"NAKL_VIEW_ALL")>0)
{
}else{
  $tQuery =  "and `operation_sotrudnik` = '" . $_SESSION[BASE.'userid'] . "' 
	      ";
	    //echo $tQuery;
}

if($operation_id > 0){
     $tQuery =  "and (`operation_id` = '" . $operation_id . "' 
		   or `operation_beznal_rah` = '" . $operation_id . "' 
		   or `operation_beznal_nakl` = '" . $operation_id . "' 
	    )
     ";
}else{
    if ($iStatusSelect > 0)
    {
	  $tQuery =  "and `operation_status` = '" . $iStatusSelect . "' ";
    }
    if ($iKlientSelect > 0)
    {
	  $tQuery = $tQuery . "and `operation_klient` = '" . $iKlientSelect . "' ";
    }
    if ($iKlientGroupSelect > 0)
    {
	  $tQuery = $tQuery . "and `klienti_group` = '" . $iKlientGroupSelect . "' ";
    }
}    

$where_products = '';
$products = array();
header ('Content-Type: text/html; charset=utf8');


if(isset($_POST['find'])){
    
    $sql = 'SELECT tovar_id FROM tbl_tovar WHERE tovar_artkl LIKE "%'.$_POST['find'].'%" OR tovar_name_1 LIKE "%'.$_POST['find'].'%" ';
    $r = $folder->query($sql);
    //echo $sql;
    if($r->num_rows > 0){
        while($tmp = $r->fetch_assoc()){
            $products[$tmp['tovar_id']] = $tmp['tovar_id'];            
        }
    }
    
    if(count($products) > 0){
        $where_products = ' AND operation_detail_tovar IN ('.implode(',', $products).') ';
    }
}


$sql_count = mysql_query("SET NAMES utf8");
$tQuery2 = "SELECT distinct `operation_id`
                    FROM `tbl_operation`
                    LEFT JOIN `tbl_klienti` ON `operation_klient`=`klienti_id`
                    LEFT JOIN tbl_operation_detail ON operation_detail_operation = operation_id
                    WHERE `operation_dell`='0' ". $tQuery ."  ".$where_products."  ";
//echo $tQuery2;
$sql_count = mysql_query($tQuery2);
if (!$sql_count)
{
  echo "Query error 1";
  exit();
}
$page_count = mysql_num_rows($sql_count);
if ($page==0)$page=0;
$limit = " LIMIT ".$page*$page_limit.", ".$page_limit;

if($operation_sort != ""){
    $operation_sort = "`$operation_sort` DESC, ";
}

$ver = mysql_query("SET NAMES utf8");
$tQuery1 = "SELECT
            distinct `operation_id`,
            `operation_data`,
		    `operation_on_web`, 
		    `operation_summ`,
		    `operation_status`,
		    `operation_klient`,
		    `klienti_name_1`,
		    `klienti_group`,
		    `klienti_id`,
		    `klienti_phone_1`,
		    `operation_memo`,
		    `operation_save`,
		    `operation_beznal_nakl`,
		    `operation_beznal_rah`
		    FROM `tbl_operation`
		    LEFT JOIN `tbl_klienti` ON `operation_klient`=`klienti_id`
            LEFT JOIN tbl_operation_detail ON operation_detail_operation = operation_id
		    WHERE 
		    `operation_dell`='0' " . $tQuery . " 
                ".$group." 
                ".$where_products." 
		    ORDER BY $operation_sort `operation_id` DESC ".$limit;
//echo $tQuery1;
$ver = mysql_query($tQuery1);
if (!$ver)
{
  echo "Query error 2";
  exit();
}

$summ = mysql_query("SET NAMES utf8");
$summ = mysql_query("SELECT `operation_klient`,SUM(`operation_summ`) as summ_all FROM `tbl_operation` WHERE `operation_dell`='0' GROUP BY `operation_klient`");
if (!$summ)
{
  echo "Query error 3";
  exit();
}
$level = mysql_query("SET NAMES utf8");
$level = mysql_query("SELECT `operation_klient`,SUM(`operation_summ`) as summ_all FROM `tbl_operation`,`tbl_operation_status` WHERE `operation_dell`='0' and `operation_status`=`operation_status_id` and `operation_status_level`='1' GROUP BY `operation_klient`");
if (!$level)
{
  echo "Query error 4";
  exit();
}

$stat = mysql_query("SET NAMES utf8");
$stat = mysql_query("SELECT `operation_status_id`,`operation_status_name` FROM `tbl_operation_status` ORDER BY `operation_status_name` ASC ");
if (!$stat)
{
  echo "Query error 5";
  exit();
}


//header ('Content-Type: image/jpeg');
echo "<header><link rel='stylesheet' type='text/css' href='sturm.css' media='all'>
<title>", mysql_result($ver,0,'klienti_name_1'),"</title>
</header>";
//==================JAVA===========================================
echo "\n<script src='JsHttpRequest.js'></script>";
echo "\n<script type='text/javascript'>";
echo "function info(msg){
	  document.getElementById('info').innerHTML = msg;
	  if(msg==''){
	  	  document.getElementById('info').style.display = 'none';
	  }else{
	  	  document.getElementById('info').style.display = 'block';
	  }
}

";
echo "\nfunction viewNakl(value,flag,sort){
	info('Wait...');
	var res_key = document.getElementById('restore').checked;
	";
    echo "\nvar div_mas =  document.getElementById('div_view_'+value);";    
    echo "\nvar div_menu =  document.getElementById('div_menu_'+value);";    
     echo "\nvar div1 =  document.getElementById('key_'+value);";  
      //echo "\nif(div1.innerHTML=='[+]'){ ";
  echo "\n var menu_txt = '<a href=\"script_internet.php?operation_id='+value+'\" target=\"_blank\">",$m_setup['menu script internet'],"</a><br>';";
  echo "\n menu_txt = menu_txt+ '<br><br><a href=\"edit_nakl_oplata.php?operation_id='+value+'\" target=\"_blank\">",$m_setup['menu money'],"</a><br>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"edit_nakl_print.php?tmp=print&operation_id='+value+'\" target=\"_blank\">",$m_setup['menu print sale'],"</a>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"edit_nakl_print.php?tmp=warehouse&operation_id='+value+'\" target=\"_blank\">",$m_setup['menu print ware'],"</a>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"edit_nakl_print.php?tmp=bay&operation_id='+value+'\" target=\"_blank\">",$m_setup['menu print bay'],"</a>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"edit_nakl_print.php?tmp=analytics&operation_id='+value+'\" target=\"_blank\">",$m_setup['menu print analytics'],"</a>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"edit_nakl_print_beznal.php?operation_id='+value+'\" target=\"_blank\">",$m_setup['menu print beznal'],"</a>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"edit_nakl_print.php?tmp=forshop&operation_id='+value+'\" target=\"_blank\">",$m_setup['menu print for shop'],"</a>';";
  echo "\n menu_txt = menu_txt+ '<br>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"barcode.php?operation_id='+value+'\" target=\"_blank\">",$m_setup['menu print barcode'],"</a>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"barcode.php?key=price&operation_id='+value+'\" target=\"_blank\">",$m_setup['menu print price'],"</a> <a href=\"barcode.php?key=price&item&operation_id='+value+'\" target=\"_blank\"> >> nakl+</a>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"barcode.php?key=price_ware&operation_id='+value+'\" target=\"_blank\">",$m_setup['menu print price war'],"</a> <a href=\"barcode.php?key=price_ware&item&operation_id='+value+'\" target=\"_blank\"> >> nakl+</a>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"barcode.php?key=ware&operation_id='+value+'\" target=\"_blank\">",$m_setup['menu print war'],"</a> <a href=\"barcode.php?key=ware&item&operation_id='+value+'\" target=\"_blank\"> >> nakl+</a>';";
  echo "\n menu_txt = menu_txt+ '<br>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"edit_nakl_delive.php?operation_id='+value+'\" target=\"_blank\">",$m_setup['menu delivery'],"</a>';";
  echo "\n menu_txt = menu_txt+ '<br>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"send_mail.php?operation_id='+value+'\" target=\"_blank\">",$m_setup['menu send mail'],"</a>';";
  echo "\n menu_txt = menu_txt+ '<br>';";
  echo "\n menu_txt = menu_txt+ '<br><a href=\"../index.php?operation_id='+value+'\" target=\"_blank\">",$m_setup['menu view nak on web'],"</a>';";
  echo "\n menu_txt = menu_txt+ '<br>';";
    
      //echo "\n}";
    echo "\n if(div1.innerHTML=='[-]'){";
      echo "\ndiv_mas.innerHTML='';";
      echo "\ndiv1.innerHTML='[+]';";
      echo "\ndiv_menu.innerHTML='';
	      info('');";

    echo "\n}else{";
      echo "\nvar req=new JsHttpRequest();";
      echo "\nreq.onreadystatechange=function(){";
      echo "\nif(req.readyState==4){";
	echo "\n var responce=req.responseText;";
	echo "\ndiv_mas.innerHTML=responce;";
	echo "\ndiv1.innerHTML='[-]';";
	echo "\ndiv_menu.innerHTML=menu_txt;
	      info('');
	";
	
    echo "}}
	    if(document.getElementById('operation_memo*'+value).value=='auto sale') sort='time';
	    req.open(null,'get_quic_view.php',true);";
    echo "req.send({nakl:value,restore:res_key,sort:sort});";
    echo "}}";
//===================STATUS================================
    echo "\nfunction set_new_status(value,value2){
	    if(confirm('",$m_setup['menu change'],"?')){
	      info('Wait...');";
    echo "\nvar div_mas =  document.getElementById('div_view_'+value);";    
    echo "\ndiv_mas.innerHTML='wait...';";
      echo "\nvar req=new JsHttpRequest();";
      echo "\nreq.onreadystatechange=function(){";
      echo "\nif(req.readyState==4){";
	echo "\n var responce=req.responseText;";
	echo "\ndiv_mas.innerHTML=responce;
		info('');";
    echo "\n}}";
    echo "\nreq.open(null,'set_status.php',true);";
    echo "\nreq.send({nakl:value,stat:value2});";
    echo "\n
	}
    }";
    echo "function close_find_window(){
	document.getElementById('find-result').style.display = 'none';
    }
    ";
    echo "function set_field_clear(id){
	    document.getElementById('find*'+id).value = '';
    }
    ";
    echo "function add_tovar(tovar,nakl){
	//alert(tovar+' '+nakl);
	info('Wait...');
	document.getElementById('find-result').style.display = 'none';
	    var req=new JsHttpRequest();
	    req.onreadystatechange=function(){
	    //alert(req.readyState);
		if(req.readyState==4){
		    var responce=req.responseText;
		    info('');
		   // alert(responce);
		    var div1 =  document.getElementById('key_'+nakl);
		    div1.innerHTML='[+]';
		    viewNakl(nakl,'1','0');
	    }}
	    req.open(null,'get_quic_tovar_add.php',true);
	    req.send({tovar_id:tovar,operation_id:nakl});
 
    }
    ";
    echo "function find(value,nakl){
	    var res = document.getElementById('find-result');
	    
	    var req=new JsHttpRequest();
	    req.onreadystatechange=function(){
		if(req.readyState==4){
		    var responce=req.responseText;
		    info('');
		    if(responce != ''){
			if(responce[0]=='*'){
			    var tovar = responce.split('*');
			    add_tovar(tovar[1],nakl);
			}else{  
			    res.innerHTML = responce;
			    res.style.display = 'block';
			}    
		    }
		  //alert(value+' '+nakl);
	    }}
	    req.open(null,'get_quic_tovar_find.php',true);
	    req.send({_find1:value,operation_id:nakl});
    }
    ";
 //=============SET NAKL FIELD====================================
        echo "\nfunction set_nakl_field(value,value2,field){";
      echo "info('Wait...');
	      var req=new JsHttpRequest();";
      echo "\nreq.onreadystatechange=function(){";
      echo "\nif(req.readyState==4){";
	echo "\n var responce=req.responseText;
		  info('');    
		  //alert(responce+' '+value+' '+value2+' '+field);";
    echo "\n}}";
    echo "\nreq.open(null,'set_nakl_field.php',true);";
    echo "\nreq.send({nakl:value,stat:value2,edit:field});";
    echo "\n}"; 
//================================SET PRICE===============kogda vibor konkretnoj ceni
echo "function reload_nakl(id,key){
      info('Wait...');
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
      if(req.readyState==4){
	 var responce=req.responseText;
	 //alert(responce); 
	 var div1 =  document.getElementById('key_'+id);
	 div1.innerHTML='[+]';
	 viewNakl(id,'1','0');
	 info('');
    }}
    req.open(null,'edit_table_nakl_quic.php',true);
    req.send({operation_id:id,key:key});
	
}
";
echo "function update_detail_fil(id,set,update,nakl){
	info('Wait...');
	if(update == 'operation_detail_price' || update == 'operation_detail_item' || update == 'operation_detail_discount'){
	    var price = document.getElementById('operation_detail_price*'+id).value;
	    var item = document.getElementById('operation_detail_item*'+id).value;
	    var discount = document.getElementById('operation_detail_discount*'+id).value;
	    var summ = (item * price) / 100 * (100 - discount);
	    document.getElementById('operation_detail_summ*'+id).value = summ.toFixed(2);
	    update_detail_fil(id,summ.toFixed(2),'operation_detail_summ',nakl)
	}
     var tovar = id;
    var table = 'tbl_operation_detail';
    var id = 'operation_detail_id';

    var req=new JsHttpRequest();
      req.onreadystatechange=function(){
        if(req.readyState==4){
	    var responce=req.responseText;
	    set_nakl_field(nakl,\"1\",\"operation_save\");
	    info('');
	}}
      req.open(null,'save_table_field.php',true);
      req.send({table:table,name:update,value:set,w_id:id,w_value:tovar});
   
    }
    ";
echo "function update_detail(id,set,nakl){
      info('Wait...');
     var tovar = id;
    var table = 'tbl_operation_detail';
    var update = 'operation_detail_dell';
    var id = 'operation_detail_id';
    //var set = '0';
      var req=new JsHttpRequest();
      req.onreadystatechange=function(){
        if(req.readyState==4){
	    var responce=req.responseText;
	    set_nakl_field(nakl,\"1\",\"operation_save\");
	    info('');
	}}
      req.open(null,'save_table_field.php',true);
      req.send({table:table,name:update,value:set,w_id:id,w_value:tovar});
   
    }
    ";
    echo "
    var check_list='*';
    function remembe_check(id,value){
	
	if(value){
	    check_list += ''+id+'*';
	}else{
	    check_list = check_list.replace('*'+id+'*','*');
	}
    }
    
    function more_nakl_operation(key){
      if(key=='dell'){
	  if(confirm('",$m_setup['menu dell'],"?')){
	      var nakl = check_list.split('*');
	      var count=1;
		  while(nakl[count]!=''){
			//alert('dell '+nakl[count]);
			set_new_status(nakl[count],-1);
		  count++;  
		  }
	     
	  location.reload();  
	  check_list='';  
	  }
      }else if(key=='add'){
	if(confirm('",$m_setup['menu change'],"?')){
	  var req=new JsHttpRequest();
	  req.onreadystatechange=function(){
	    if(req.readyState==4){
	      var responce=req.responseText;
	     //alert(responce);
		if(responce=='true'){
		  check_list='';
		  location.reload();
		}
	    }}
	  req.open(null,'more_nakl_operation.php',true);
	  req.send({nakl:check_list,key:key});
	  
	}
      }
    }
    ";
    
echo "\n</script>";
//==================END JAVA ============================================
    echo "\n<body>\n";
?>
<style>
    .list_menu{
       position: fixed;
       margin-top: 20px;
       top:-20px;
       
    }
</style>

<?php
echo "\n<div class=\"list_menu\"><table class='main'><tr><td>";
echo "\n<table  class='key'>
      <tr>";
echo "<td class='key'>
	<a class='key' href='#none' onClick='more_nakl_operation(\"add\")'>",$m_setup['menu add nakl'],"</a>
      </td>";
echo "<td class='key'>
	<a class='key' href='#none' onClick='more_nakl_operation(\"dell\")'>",$m_setup['menu dell'],"</a>
      </td>";
echo "<td class='key'>
	<input type='checkbox' id=\"restore\"> restore mode
      </td>";
if($iKlientSelect > 0){
  echo "<td class='key'>";
  echo "<a class='key' href='edit_klient.php?klienti_id=$iKlientSelect' target='_blank'>",$m_setup['menu klient']," ",$m_setup['menu edit'],"</a>
	</td>";
}
?>
    <td class='key'>
        <form method="post" action="operation_list.php" target="operation_list">
            Найти товары <input type="text" name="find" style="width:270px" placeholder="Название товара или артикул" value="<?php echo (isset($_POST['find'])) ? $_POST['find'] : ''; ?>">
            <input type="submit" name="operation_id" style="width:70px" method="post" onenter="submit();" value="Найти">
        </form>
    </td>

<?php
echo "</tr></table>";

echo "</td><td>";
$function -> page_set($page,$page_count,$page_limit,$iStatusSelect,$iKlientGroupSelect,$iKlientSelect);
echo "</td></tr></table>
</div>";


echo "\n<table style='margin-top:50px;'>"; //",$m_setup['menu menu'],"
echo "<tr>
      <th>",$m_setup['menu date-time'],"</th>
      <th>",$m_setup['menu order'],"</th>
      <th></th>
      <th>",$m_setup['menu summ'],"</th>
      <th>",$m_setup['menu saldo'],"</th>
      <th>",$m_setup['menu level'],"</th>
      <th>",$m_setup['menu status'],"</th>
      <th>",$m_setup['menu klient'],"</th>
      <th>",$m_setup['menu memo'],"</th>
      </tr>";
#echo mysql_num_rows($ver);

while ($count < mysql_num_rows($ver))
{
  echo "\n<tr>";
  echo "\n<td >";
  echo "\n<a href='#none' onClick='set_new_status(".mysql_result($ver,$count,'operation_id').",-1)'>
	  <img src=\"../resources/delete.png\" width=\"15\"> </a>";
  echo mysql_result($ver,$count,'operation_data'), "</td>";

  echo "\n<td>
	<input type='checkbox' id='",mysql_result($ver,$count,'operation_id'),"' onClick='remembe_check(this.id,this.checked)'>
	<a href='edit_nakl.php?operation_id=", mysql_result($ver,$count,'operation_id'),"&_klienti_group=", mysql_result($ver,$count,'klienti_group'),"' target='_blank'>", 
	      mysql_result($ver,$count,'operation_id');
	 if(mysql_result($ver,$count,'operation_on_web')>0) echo "-W";	 
	 if(mysql_result($ver,$count,'operation_save')>0) echo "<font color='red'>S</font>";
	 if(mysql_result($ver,$count,'operation_beznal_rah')>0) echo "<font color='green'>R</font>";
	 if(mysql_result($ver,$count,'operation_beznal_nakl')>0) echo "<font color='green'>N</font>";
  echo "</a></td>";
  echo "\n<td width='30' align='center'>";
   if(mysql_result($ver,$count,'operation_status')!='9'){//if not oplata
    echo "<a href='#none' onClick='viewNakl(".mysql_result($ver,$count,'operation_id').",1,0)'><div id='key_",mysql_result($ver,$count,'operation_id'),"'>[+]</div></a>"; 
   }else{
   echo "<a href='send_history.php?klienti_id=".mysql_result($ver,$count,'operation_klient')."' target='_blank'>[->]</a>"; 
   }
  echo "</td>";
  //echo "\n(<a href='#none' onClick='viewNakl(".mysql_result($ver,$count,'operation_id').",0)'>close</a>)</td>";
  
  echo "\n<td>";
  echo " ", mysql_result($ver,$count,'operation_summ');
 // if(mysql_result($ver,$count,'operation_status')!='9'){//if not oplata
 // echo "\n<a href='edit_nakl_oplata.php?operation_id=", mysql_result($ver,$count,'operation_id'),"' target='_blank'>kesh</a>";}
  echo "</td>";
  echo "\n<td>";
  $count_sum=0;
    while ($count_sum < mysql_num_rows($summ))
    { 
      if (mysql_result($ver,$count,'operation_klient')==mysql_result($summ,$count_sum,'operation_klient')){
	echo "\n(", number_format(mysql_result($summ,$count_sum,'summ_all'),"2",".",""), ")";
	$count_sum=999999;
      }
    $count_sum++;
    }
  echo "\n</td>";  
  echo "\n<td>";
    $count_sum=0;
    while ($count_sum < mysql_num_rows($level))
    { 
      if (mysql_result($ver,$count,'operation_klient')==mysql_result($level,$count_sum,'operation_klient')){
	echo "\n", number_format(mysql_result($level,$count_sum,'summ_all'),"2",".",""), "";
	$count_sum=999999;
      }
    $count_sum++;
    }
  
  
  echo "</td>";
 // echo "\n<td>", mysql_result($ver,$count,'klienti_saldo'),"(lev:",mysql_result($ver,$count,'klienti_prioritet'), ")</td>";
 
 echo "\n<td>";
//==================STATUS==================================================================================================
   echo "\n<select style='width:150px' id='stat_".mysql_result($ver,$count,'operation_id')."' onChange='set_new_status(".mysql_result($ver,$count,'operation_id').",this.value)'>";
    $count1=0;
    while ($count1 < mysql_num_rows($stat))
    {
    echo "\n<option ";
	if (mysql_result($ver,$count,"operation_status") == mysql_result($stat,$count1,"operation_status_id")) echo "selected ";
    echo "value=" . mysql_result($stat,$count1,"operation_status_id") . ">" . mysql_result($stat,$count1,"operation_status_name") . "</option>";
    $count1++;
    }
  echo "</select>";
 //=====================================================================================================================
  echo "</td>";
    
  echo "<td>
	<a href='operation_list.php?iKlient=", mysql_result($ver,$count,'klienti_id'),"'>[+]</a>
	<a href='edit_nakl_add_new.php?klienti_id2=", mysql_result($ver,$count,'klienti_id'),"' target='_blank'>[N]</a>
	<a href='edit_klient.php?klienti_id=", mysql_result($ver,$count,'klienti_id'),"' target='_blank'>", mysql_result($ver,$count,'klienti_name_1'),"(",mysql_result($ver,$count,'klienti_phone_1'), ")</a></td>";
  //echo "<a href='edit_klient_history.php?klienti_id=", mysql_result($ver,$count,'klienti_id'),"' target='_blank'>", mysql_result($ver,$count,'klienti_name_1'),"</a></td>";
  echo "<td><input type='text' style='width:400px' value='", mysql_result($ver,$count,'operation_memo'),  "' id='operation_memo*".mysql_result($ver,$count,'operation_id')."' ' onChange='set_nakl_field(",mysql_result($ver,$count,'operation_id'),",this.value,\"operation_memo\")'/></td>";
  #echo "<td>", mysql_result($ver,$count,7), "</td>";
  #echo "<td>", mysql_result($ver,$count,8), "</td>";
  echo "\n</tr>";
  echo "\n<tr><td colspan='1' valign=\"top\"><div id='div_menu_",mysql_result($ver,$count,'operation_id'),"'></div></td>
  <td valign=\"top\" colspan='8'><div id='div_view_",mysql_result($ver,$count,'operation_id'),"'></div></td></tr>";
 
$count++;
}

echo "\n</table>";


$function -> page_set($page,$page_count,$page_limit,$iStatusSelect,$iKlientGroupSelect,$iKlientSelect);

echo "<div id='info' class='info'></div>";
echo "<div id='find-result' class='find-result'></div>";
echo "\n</body>";
//print_r("test");

class Functions
{
function page_set($page,$page_count,$page_limit,$iStatusSelect,$iKlientGroupSelect,$iKlientSelect) {
//======================PAGE SET=======================================
echo "<div id='page_list' class='page_list'>";
echo "page: ";
if ($page==0)$page=0;
$page_count_www=0;
while($page_count>0){
  if($page==$page_count_www){
      echo " [",$page_count_www,"] ";
  }else{
      echo "<a href='operation_list.php?iStatus=",$iStatusSelect,"&iKlient=",$iKlientSelect,"&iGroupSelected=",$iKlientGroupSelect,"&page=",$page_count_www,"'> ",$page_count_www, " </a>";
  //echo "---",$page_count,"--";
  }

  //echo "<a href='operation_list.php?iStatus=",$iStatusSelect,"&iKlient=",$iKlientSelect,"&iGroupSelected=",$iKlientGroupSelect,"&page=",$page_count_www,"&l=all'> еще </a>";
  
$page_count_www++;
$page_count=$page_count-$page_limit;
}
echo "</div>";
//=================================================================
}  
}
?>
<div class="msg_back"></div>
<div class="msg">загрузка...</div>

<style>
  .msg_back{width: 100%;height: 100%;opacity: 0.7;display: none;position: fixed;background-color: gray;top:0;left:0;}
  .msg{
  display: none;
  overflow: auto;
  position: fixed;
  top: 5%;
  left: 50%;
  margin-left: -600px;
  padding: 20px;
  width: 1200px;
  height: 700px;
  text-align: center;
  border: 2px solid gray;
  background-color: #FFC87C;
  }
  .find_result {
	  border-collapse: collapse;
	  border: 1px solid gray;
  }
  .find_result tr{
	border-collapse: collapse;
	margin: 0px;
	border: 1px solid gray;
  }
  .find_result td{
	border-collapse: collapse;
	margin: 0px;
	padding: 4px;
	border: 1px solid gray;
	/*background-color: #ffffff;*/
  }
  .find_result tr:hover{
	background-color: #CCF9A9;
  }
  .postav{
	cursor: pointer;
  }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
  $(document).on('click', '.msg_back', function(){
	$('.msg_back').hide();
	$('.msg').hide();
  });
  
  $(document).on('change', '.find_add', function(){
	
	var id = $(this).data('id');
	var value = $(this).val();
	
	$.ajax({
	  type: "POST",
	  url: "ajax/find_product.php",
	  dataType: "text",
	  data: "id="+id+"&value="+value,
	  beforeSend: function(){
	  },
	  success: function(msg){
		$('.msg_back').show();
		$('.msg').html(msg);
		$('.msg').show();
		//console.log( msg );
	  }
	});
	
  });
  
  $(document).on('click', '.select_product', function(){
        
        var tovar_id = $(this).data('id');
        var postav = $(this).data('postav');
        var zakup = $(this).data('zakup');
        var price = $(this).data('price');
        var operation = $(this).data('operation');
        var days = $(this).data('days');
        
        $.ajax({
            type: "POST",
            url: "ajax/add_product_to_order.php",
            dataType: "text",
            data: "tovar_id="+tovar_id+"&postav_id="+postav+"&zakup="+zakup+"&price="+price+"&days="+days+"&operation="+operation,
            beforeSend: function(){
            },
            success: function(msg){
                console.log( msg );
                viewNakl(operation,1,0);
                viewNakl(operation,1,0);
                $('.msg_back').trigger('click');
            }
            
        });
        
  });
  
  
</script>

<?php
include 'init.lib.php';
//include 'init.lib.user.php';
//include 'init.lib.user.tovar.php';

session_start();
connect_to_mysql(); 

header ('Content-Type: text/html; charset=utf8');
echo "<header>
<title>sturm.com.ua</title>
      <link rel='stylesheet' type='text/css' href='admin/sturm.css'></header>
      <script src='admin/JsHttpRequest.js'>    </script>";
 
 echo "
 <form onsubmit='alert('Выбран город с кодом '+getObj('city').value);return false;'>
    <p><b>Введите город:</b>
        <input class='find_string' id='city' name='find' size='80' autocomplete='OFF' onkeyup='PressKey(event)'  onEnter='this.form.onsubmit()' /><br>
        <select class='find_string' id='info' size=10 style='visibility:hidden;position:absolute;z-index:999;'
                onkeyup='PressKey2(event)' onclick='PressKey2(event)'>
        </select>
</form>";

echo "
  <script type='text/javascript'><!--
    var ot='', timer=0, x=-1,y=0;
    //g=getObj('city').onkeyup=PressKey; // альтернативный вариант назначения обработчика


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
        e=e||window.event;
        t=(window.event) ? window.event.srcElement : e.currentTarget; // объект для которого вызвано
        g=getObj('info');
        if(x==-1&&y==0){// при первом обращении просчитываю координаты
            x=pageX(t); y=pageY(t);
            g.style.top = y + t.clientHeight+1 + 'px';
            g.style.left = x + 'px';
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

    function Load(){
        timer=0;
        o=getObj('info');
        o.options.length=0;
        ajaxLoad('info', '/find_server.php?city_name='+ot, '','','');
        //o.style.visibility='visible';
        //alert(o.size);
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

if(isset($_REQUEST['find']))
{  $find_str = $_REQUEST['find'];
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
  $find_str = str_replace($dell,"",$find_str);
  $find_str = str_replace(" ","%",$find_str);

 echo $find_str; 
 $searchq = $find_str;
 if(strlen(addslashes($searchq))<4) exit();

$getName = mysql_query("SET NAMES utf8");
$SgetName = "SELECT 
			`tovar_name_1`,
			`tovar_artkl`,
			`tovar_id`,
			`tovar_inet_id`,
			`price_tovar_2`,
			`currency_name_shot`,
			`tovar_inet_id_parent`
			FROM 
			`tbl_tovar`,
			`tbl_price_tovar`,
			`tbl_currency`
			WHERE 
			`tovar_id`=`price_tovar_id` and
			`price_tovar_curr_2`=`currency_id` and (
			upper(`tovar_artkl`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
			upper(`tovar_name_1`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%' or
			upper(`tovar_name_2`) LIKE '%".mb_strtoupper(addslashes($searchq),'UTF-8')."%')";

$getName = mysql_query($SgetName);
echo $SgetName;
if (!$getName)
{
  echo "Query error - tbl_price - ",$SgetName;
  exit();
}

$count=0;
$str[0]="";
$str1[0]="";
echo "<table  class='menu_top'>";
while($count<mysql_num_rows($getName)){
  $str = explode("||",mysql_result($getName,$count,"tovar_name_1"));
  $artkl = explode("/",mysql_result($getName,$count,"tovar_artkl"));
  
  if($count>0) 
      $str1 =explode("||",mysql_result($getName,$count-1,"tovar_name_1"));
  
  if ($str[0]!=$str1[0]){
      $link=mysql_result($getName,$count,"tovar_id");
      $price=mysql_result($getName,$count,"price_tovar_2");
      $pricename=mysql_result($getName,$count,"currency_name_shot");
      
      $full_link = "resources/products/".$link."/".$link.".0.small.jpg";
      if(@fopen($full_link,"r")){
      	//$parent =mysql_result($tovar,$count,"tovar_inet_id_parent");
      }else{
	$link="GR".mysql_result($getName,$count,"tovar_inet_id_parent");
      }

      echo "<tr><td><a href='/index.php?tovar=",mysql_result($getName,$count,"tovar_id"),"'>
      <img src='/resources/products/",$link,"/",$link,".0.small.jpg' width='70' height='70'></a>
      
      </td><td valign='middle'><font size='4'><b>
      <a href='/index.php?tovar=",mysql_result($getName,$count,"tovar_id"),"'>
      ",
	$artkl[0], " ",
	$str[0],
	"<b></font></a>
 
     </td><td valign='middle'><font size='4'><b>
      <a href='/index.php?tovar=",mysql_result($getName,$count,"tovar_id"),"'>
      ",
      $price," ",$pricename,"<b></font></a>
 
      </td></tr>";
     }
  $count++;
}
echo "</table>";

}  
?>
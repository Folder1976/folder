<?php

 

include 'init.lib.php';
include 'nakl.lib.php';
connect_to_mysql();
session_start();

if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'].'can_shop')>0){
}else{
  echo "access denied for this user";
  exit();
}
//==================================SETUP=MENU==========================================
if (!session_verify($_SERVER["PHP_SELF"],"+")){
  exit();
}
//==================================SETUP=MENU==========================================
$user_txt = "НЕТ АКТИВНОЙ ОПЕРАЦИИ!!!";
$operation_info_style = "Style=\"background:#ff0000\"";
if(isset($_SESSION[BASE.'shop_operation_id'])){
    $user = $_SESSION[BASE.'shop_user_id'];
    $operation_id = $_SESSION[BASE.'shop_operation_id'];      
    
    $tmp = mysql_query("SET NAMES utf8");
    $tQuery = "SELECT * FROM `tbl_klienti` WHERE `klienti_id`='$user'";
    $tmp = mysql_query($tQuery);			
    
    $user_txt = "<a href=\"#\" onclick=\"javascript:menu_open();\">".mysql_result($tmp,0,"klienti_name_1")." (".mysql_result($tmp,0,"klienti_phone_1").") -> ".$_SESSION[BASE.'shop_operation_id'].
      " *** MENU ***</a>";
    if(mysql_result($tmp,0,"klienti_index")<>""){
	$operation_info_style = "Style=\"background:".mysql_result($tmp,0,"klienti_index")."\"";
    }else{
	$operation_info_style = "";
    }
    
}      
//==================================SETUP=MENU==========================================
$html = "";
header ('Content-Type: text/html; charset=utf-8');

//==================================SETUP=MENU==========================================
//==================================SETUP=MENU==========================================
//==================================SETUP=MENU==========================================
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
//==================================SETUP=MENU==========================================
//==================================SETUP=MENU==========================================
//==================================SETUP=MENU==========================================
$html .= "<title>Магазин</title>";
$html .= "<header><link rel='stylesheet' type='text/css' href='shop.css'>
	  <link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"styles.css\">
	  </header>";
//==================JAVA===========================================
$html .= "<script src='JsHttpRequest.js'></script>";
$html .= "<script type='text/javascript'>";

  $html .= "function info(msg){
	  document.getElementById('info_').innerHTML = msg;
	  if(msg==''){
	  	  document.getElementById('info_').style.display = 'none';
	  }else{
	  	  document.getElementById('info_').style.display = 'block';
	  }
    }
    ";
  $html .= "function close_find_window(){
	document.getElementById('find-result').style.display = 'none';
	getObj('find').focus();
    }
    ";
  $html .= "function find(){
	    var res = document.getElementById('find-result');
	    var value = document.getElementById('find').value;
	    var operation_info = document.getElementById('operation_info');
	    var nakl = 0;
	    var msg = '';
	    //alert(value);
	    var req=new JsHttpRequest();
	    req.onreadystatechange=function(){
		      
		      //alert(value+' '+req.readyState);
			if(req.readyState==4){
				var responce=req.responseText;
				info('');
				
				if(responce != ''){
				      if(responce[0]=='*'){
					  var msg = responce.split('*');
					  //alert(msg[0]+' '+msg[1]);
					    if(msg[1]=='reload'){
						operation_info.innerHTML = msg[2];
						operation_info.style.background = msg[3];
						list_div_reload();
					    }else if(msg[2]=='alert'){
						alert(msg[1]);
					    }else{
					      //alert(msg[1]);
					      add_tovar(msg[1]);
					    }  
				      }else{  
					  res.innerHTML = responce;
					  res.style.display = 'block';
				      }    
				}
			}
	      }
	    req.open(null,'shop_get_find.php',true);
	    req.send({_find1:value,operation_id:nakl});
    }
    
  function add_tovar(tovar_id){
	    getObj('item_id').value = tovar_id;
  	    getObj('item_div').style.display = 'block';
 	    getObj('item_text').focus();
 	    getObj('item_text').select();
  }
    
    function add_tovar2(item,e){
       if(e.keyCode==13||e.keyCode==39){ // Enter
	  getObj('find-result').style.display = 'none';
	  getObj('item_div').style.display = 'none';
	  
	  var id = getObj('item_id').value;
	  
	  var req=new JsHttpRequest();
	  req.onreadystatechange=function(){
	
		      //alert(req.readyState);
			if(req.readyState==4){
				var responce=req.responseText;
				responce=responce.split('*');
				if(responce[0]=='error'){
				    alert(responce[1]);
				}else{
				    list_div_reload();
				}
			}
	    }
	    req.open(null,'shop_add_tovar.php',true);
	    req.send({id:id,item:item});
      }
  }

     function list_div_reload(){
	  var req=new JsHttpRequest();
	  req.onreadystatechange=function(){
		      //alert(req.readyState);
			if(req.readyState==4){
				var responce=req.responseText;
				getObj('list_div').innerHTML=responce;
				
			}
	    }
	    req.open(null,'shop_list_reload.php',true);
	    req.send();
  }
  
      function money_key(){
	    getObj('money_text').value = getObj('item_summ').value;
 	    getObj('money_div').style.display = 'block';
 	    getObj('money_text').focus();
 	    getObj('money_text').select();
  }
     function add_money(e){
	calculator(e);
	//var item = 
	//alert(e.keyCode);
	if(e.keyCode==27){
	    money_div_close();
	}else if(e.keyCode==38){
	    getObj('rabat_text').focus();
 	    getObj('rabat_text').select();
	 }else if(e.keyCode==40){
	    getObj('money_dano').focus();
 	    getObj('money_dano').select();
	 }else if(e.keyCode==13 || e.keyCode==undefined){ // Enter ||e.keyCode==39
	    
	  var rabat = getObj('rabat_text').value;
	  var summ = getObj('money_text').value;
	  var dano = getObj('money_dano').value;
	  var comm = getObj('money_coment').value;
	  var oper = getObj('item_summ').value;
	  
	  var req=new JsHttpRequest();
	  req.onreadystatechange=function(){
		      //alert(req.readyState);
			if(req.readyState==4){
				var responce=req.responseText;
				//alert(responce);
				responce=responce.split('*');
				if(responce[0]=='error'){
				    alert(responce[1]);
				}else{
				    list_div_reload();
				}
			}
	    }
	    req.open(null,'shop_add_money.php',true);
	    req.send({rabat:rabat,summ:summ,dano:dano,comm:comm,oper:oper});
      money_div_close();
      }
  }
    function setrabat(e){
      var summ = getObj('item_summ').value * ((100 - getObj('rabat_text').value)/100);
      getObj('money_text').value = summ.toFixed(2);
       if(e.keyCode==13||e.keyCode==40){ // Enter
 	    getObj('money_text').focus();
 	    getObj('money_text').select();
	}else if(e.keyCode==27){
	    money_div_close();
	}
	
  }
    function set_select(id){
	getObj(id).focus();
	getObj(id).select();
    }
    function calculator(e){
	getObj('money_zdacha').value = getObj('money_dano').value - getObj('money_text').value;
	  if(e.keyCode==38){
	    getObj('money_text').focus();
 	    getObj('money_text').select();
	 }else if(e.keyCode==40){
	    getObj('money_coment').focus();
 	    getObj('money_coment').select();
	 }else if(e.keyCode==27){
	    money_div_close();
	 }
    }
    function coment(e){
	  if(e.keyCode==38){
	    getObj('money_dano').focus();
 	    getObj('money_dano').select();
	  }else if(e.keyCode==27){
	    money_div_close();
	  }
    }
    function money_div_close(){
	  getObj('money_div').style.display = 'none';
    }
    function set_rabat(id){
	var rabat = getObj('rabat*'+id).value;
	//alert(id+' '+rabat);
	  var req=new JsHttpRequest();
	  req.onreadystatechange=function(){
		      //alert(req.readyState);
			if(req.readyState==4){
				list_div_reload();
				//var responce=req.responseText;
				//alert(responce);
			}
	    }
	    req.open(null,'shop_rabat_set.php',true);
	    req.send({rabat:rabat,id:id});
   }
  ";

$html .= "</script>";
$html .= "
  <script type='text/javascript'>
    var ot='', timer=0, x=-1,y=0;
 
    function PressKey2(e){ // вызывается при нажатии клавиши в select
	//alert(e.keyCode);
        e=e||window.event;
        t=(window.event) ? window.event.srcElement : e.currentTarget; // объект для которого вызывно
        if(e.keyCode==13||e.keyCode==39){ // Enter
	    getObj('info').style.visibility = 'hidden'; // спрячем select
	    getObj('find').focus();
	    getObj('find').select();
	   
	   find();
           
           // t=(window.event) ? window.event.srcElement : e.currentTarget; // объект для которого вызывно
           // t.form.onsubmit();
            return;
            
        }else if(e.keyCode==27){ // ESC
	    getObj('info').style.visibility = 'hidden'; // спрячем select
	    getObj('find').focus();
	    getObj('find').select();
	}else if(e.keyCode==40||e.keyCode==38){// Up or Down
            //getObj('find').focus();
            getObj('find').value=getObj('info').options[getObj('info').selectedIndex].text;
            //getObj('info').style.visibility = 'hidden'; // спрячем select
        }else{
           getObj('find').value=getObj('info').options[getObj('info').selectedIndex].text;
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
            g.style.top = y + t.clientHeight + 22 + 'px';
            g.style.left = x + 'px';
        
        }
        if(e.keyCode==13){ // Enter //||e.keyCode==39
	    getObj('info').style.visibility = 'hidden'; // спрячем select
	    getObj('find').focus();
	    getObj('find').select();
	   
	   find();
	}
        if(e.keyCode==40){
	    g.focus();
	    g.selectedIndex=0;
	    getObj('find').value=getObj('info').options[getObj('info').selectedIndex].text;
	       //find();
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
        ajaxLoad('info', 'shop_find_server.php?city_name='+ot, '','','');
   }
    getObj('find').focus();

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
   function menu_open(){
   
      alert('ggg');
   }
   function start(){
      list_div_reload();
      getObj('find').focus();
   }
</script>";
//==================JAVA===========================================
//==================JAVA===========================================
//==================JAVA===========================================

$html .="<body onload='start();';>";
	  
	 $html .= "<div id='operation_info' class='operation_info' $operation_info_style>$user_txt</div>";
	 
	  $html .= "<br>
	  <table class=\"tovar_list\" width=\"100%\">
	    <tr><td align=\"left\">
		<input type='text' class='find_string' id='find'
		    placeholder='".$m_setup['menu find']."...'
		      onkeyup='PressKey(event)' 
		    /> 
			<select class='find_string' id='info' size='10' style='visibility:hidden;position:absolute;z-index:999;'
			    onkeyup='PressKey2(event)' onclick='PressKey2(event)' >
			</select>
	    </td><td align=\"right\" width=\"25%\">
		    <input type='button' class='find_key' 
			value='".$m_setup['menu find']."' 
			id='find_key' 
			OnClick='find();'/>
	    </td></tr></table>
		    ";
		    
		    
$html .= "<div id='list_div'></div>";
		    
$html .= "<div id='info_' class='info'></div>";

$html .= "<div id='find-result' class='find-result'></div>";

$html .= "<div id='item_div' class='item_div'>
	 <input type='text' class='item_text' id='item_text'
		    value='1' onkeyup='add_tovar2(this.value,event);'/> 
	 <input type='hidden' id='item_id' value='0'/> 
	  </div>";
$html .= "<div id='money_div' class='money_div'>
		  <table class=\"main\" width=\"100%\">
		      <tr><td align=\"left\" width=\"30%\">
		      </td><td align=\"right\" onClick=\"javascript:money_div_close();\"><a href=\"#\" onclick=\"javascript:money_div_close();\"><font size=\"20px\">CLOSE [ESC]</font></a>
		      </td></tr>
		      <tr><td align=\"left\" width=\"30%\">Знижка %:
		      </td><td><input type='text' class='item_text' id='rabat_text' value='0' onkeyup='setrabat(event);' onClick='set_select(this.id);'/>
		      </td></tr>
		      <tr><td align=\"left\">Оплочено [Entr]:
		      </td><td><input type='text' class='item_text_money' id='money_text' value='0' onkeyup='add_money(event);' /> 
		      </td></tr>
		      <tr><td align=\"left\">Калькулятор:
		      </td><td>
		      </td></tr>
		      <tr><td align=\"left\">Дано:
		      </td><td><input type='text' class='item_text' id='money_dano' value='0' onkeyup='calculator(event);' onClick='set_select(this.id);' /> 
		      </td></tr>
		      <tr><td align=\"left\">Здача:
		      </td><td><input type='text' class='item_text' id='money_zdacha' value='0'/> 
		      </td></tr>
		      <tr><td align=\"left\">Комент:
		      </td><td><input type='text' class='item_text' id='money_coment' value='' onkeyup='coment(event);'/> 
		      </td></tr>
		      <tr><td align=\"center\" colspan=\"2\">
		      <input type='button' class='item_text_money' value='O K' onClick='add_money(40);'/> 
		      </td></tr>
		      </table>
	
	  </div>";
$html .="</body>";

echo $html;
?>

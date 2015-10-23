<?php
include 'init.lib.php';
//include 'mail_read.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

//==================================SETUP===========================================
if (!isset($_SESSION[BASE.'lang'])){
  $_SESSION[BASE.'lang']=1;
}
header ('Content-Type: text/html; charset=utf8');

    
echo "<title>Get mail</title>";
echo "\n<body>\n";
$mail = $_GET['mail'];


if($mail>0) echo "<a href='get_mail.php?mail=",($mail-1)."'><<-- PREW </a>";
echo "<a href='get_mail.php?mail=",($mail+1)."'> NEXT -->></a>
<br>";
echo get_mail($mail);
$host = "{imap.gmail.com:993/imap/ssl}";
$mail = "folder.list@gmail.com";
$login = "folder.list@gmail.com";
$pass = "folder1976";




echo "</body>";

function get_mail($i) {
$host = "{imap.gmail.com:993/imap/ssl}INBOX";
$mail = "mail@sturm.com.ua";
$login = "mail@sturm.com.ua";
$pass = "natalia2002";

$html = ""; 

$inbox = imap_open($host,$login,$pass) or die ('Not connected');
$emails = imap_search($inbox,'ALL');

if($emails){

   //Берем пятое письмо с конца 
   $msg_num = imap_num_msg($inbox);
   if($msg_num>$i) $msg_num = $i;
      
   
   //Шапка письма в масиве
  // $h = imap_header($inbox,$msg_num);
  // $h = $h->from;
   
   //Получаем количество писем
   $html .= "<br>Всего писем в ящике - ".imap_num_msg($inbox);
 
   //Получаем количество писем
   $html .= "<br>Письмо номер - ".$msg_num;
 
   //Получаем тему письма
   $html .= "<br>Тема письма - ".get_mail_subj($inbox,$msg_num);
   
   //Получить имя ящика и домен = адрес
   $html .= "<br>Адрес - ".get_mail_email($inbox,$msg_num);
   
   //Получить имя от кого
   $html .= "<br>От кого - ".get_mail_from($inbox,$msg_num);
   
   //Дата
   $html .= "<br>Дата - ".get_mail_date($inbox,$msg_num);
  
  //Дата
   $html .= "<br><br>Сообщение - ".get_mail_msg_text($inbox,$msg_num);
  

   
   //Структура письма
   $struct = imap_fetchstructure($inbox,$msg_num);
  
	
    }
    
    

$html .= "<br>end";


imap_close($inbox);
     
return $html;    
}
function get_mail_msg_text($mail,$n){
  
  $st = imap_fetchstructure($mail, $n);
if (!empty($st->parts)) {
      for ($i = 0, $j = count($st->parts); $i < $j; $i++) {
	$part = $st->parts[$i];
	if ($part->subtype == 'PLAIN') {
	    $body = imap_fetchbody($mail, $n, $i+1);
	}
      }
} else {
$body = imap_body($mail, $n);
}
 
 
 return imap_qprint($body); 
}

function get_mail_date($inbox,$msg_num){
    $s = imap_fetch_overview($inbox,$msg_num);
    //Получить имя ящика и домен = адрес
    $html = $s[0]->date;
 
return $html;
}
function get_mail_email($inbox,$msg_num){
    $h = imap_header($inbox,$msg_num);
    $h = $h->from;
    //Получить имя ящика и домен = адрес
    $html = $h[0]->mailbox."@".$h[0]->host;
 
return $html;
}
function get_mail_from($inbox,$msg_num){
    $s = imap_fetch_overview($inbox,$msg_num);
    $from = $s[0]->from;
    //echo $from;
    $from = imap_mime_header_decode($from);  
      //Получить от кого письмо
     if($from[0]->charset!='default' ){
	  $html = iconv($from[0]->charset,"UTF-8",$from[0]->text);
	}else{
	  $html = $from[0]->text;
	}
return $html;
}
function get_mail_subj($inbox,$msg_num){
    $s = imap_fetch_overview($inbox,$msg_num);
    //Получить тему письма
    $subj = $s[0]->subject;
    $html = "";
    $subj = imap_mime_header_decode($subj);
    $tmp = 0;
    while($tmp<count($subj)){
	$html .= iconv($subj[0]->charset,"UTF-8",$subj[$tmp]->text)."(".$subj[$tmp]->text.")";
	$tmp++;
    }
return $html;
}
?>

<?
$server_email="info@rus-zabor.ru"; //исходящий адрес письма
$server_name="rus-zabor.ru";
$mail_to="info@rus-zabor.ru";//куда отправить письмо
$charset = "windows-1251";
$content = "text/plain";
$price_message="Просьба выслать прайс на e-mail: ";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: $content charset=$charset\r\n";
$headers .= "Date: ".date("Y-m-d (H:i:s)",time())."\r\n";
$headers .= "From: \"".$server_name."\" <".$server_email.">\r\n";
$headers .= "X-Mailer: My Send E-mail\r\n";

if($_POST["button"]==GetMessage("FORM_PRICE_SUBMIT"))
{
 if($_POST["email"]=="")
 $error="Введите email!";
 if(strstr($_POST["email"], "@")===false)
 $error="Неверный email!";
 if($error=="")
 mail("$mail_to","Запрос прайса","$price_message","$headers");
}

if($_POST["button"]==GetMessage("FORM_CALL_SUBMIT"))
{
 if($_POST["phone"]=="")
 $error="Введите номер телефона!";
 $call_message="Просьба перезвонить на телефон: ".$_POST["phone"]."
 по вопросу: ".$_POST["question"];
 if($error=="")
 mail("$mail_to","Запрос звонка","$call_message","$headers");
}

?>
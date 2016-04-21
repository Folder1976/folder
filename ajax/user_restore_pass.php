<?php
include '../config/config.php';
if(session_id()){
}else{
  //session_start();
}
global $folder;

$return = array();

$email = mysqli_real_escape_string($folder, $_POST['email']);

$sql = "SELECT `klienti_id`, `klienti_pass` FROM `tbl_klienti` WHERE `klienti_email` = '".$email."';";
$r = $folder->query($sql);

if($r->num_rows == 0){
    $return['msg'] = "Данный емаил <b>$email</b> не найден!"; 
    $return['err'] = true;
    echo json_encode($return);
    die();
}

$tmp = $r->fetch_assoc();
$id = $tmp['klienti_id'];

$pass = $tmp['klienti_pass'];
if(strlen($tmp['klienti_pass']) < 10){
    $pass = 'jsfd74kjh';
}else{
    $pass = substr($pass, 0, 9);
}

        $sql = "UPDATE `tbl_klienti`SET 
            `klienti_pass` = '".md5($pass)."'
            WHERE `klienti_id` = '$id';";

        //Нормальная регистрация
        if($folder->query($sql)){
            $html = '<h1>Смена пароля на Armma.ru</h1>
                    <ul>Ваши новые данные для авторизации:
                        <li>Логин : <b>'.$email.'</b></li>
                        <li>Пароль : <b>'.$pass.'</b></li>
                    </ul>
            ';

            include '../admin/libmail.php';
            $m = new Mail("UTF-8");
            $m->From($setup['email name'].";".$setup['email']);
            $m->smtp_on($setup['email smtp'],$setup['email login'],$setup['email pass'],$setup['email port']);//465 587
            $m->Priority(2);
            $m->Body($html);
            $m->text_html="text/html";
            $m->Subject('Armma.ru, смена пароля');
            $m->To($email);
            $error = $m->Send();
            
            $return['msg'] = 'Новый пароль отправлен успешно! Проверьте <b>'.$email.'</b>.';
            $return['err'] = false;        
            echo json_encode($return);
            die();
        }
        
    $return['msg'] = 'Чтото пошло не так! Отправить не получилось!';
    $return['err'] = true;        
    echo json_encode($return);
            
?>
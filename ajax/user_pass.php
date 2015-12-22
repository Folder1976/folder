<?php
include '../config/config.php';
if(session_id()){
}else{
  session_start();
}
global $folder;

$return = array();

$id = mysqli_real_escape_string($folder, $_POST['id']);
$pass = mysqli_real_escape_string($folder, $_POST['pass']);

$sql = "SELECT `klienti_email` FROM `tbl_klienti` WHERE `klienti_id` = '".$id."';";

$r = $folder->query($sql);

if($r->num_rows == 0){
    $return['msg'] = "У Вас не указан емаил!\n\rЭто можно сделать в разделе ЛИЧНЫЕ ДАННЫЕ."; 
    $return['err'] = true;
    echo json_encode($return);
    die();
}

$tmp = $r->fetch_assoc();
$email = $tmp['klienti_email'];
if($tmp['klienti_email'] == ''){
    $return['msg'] = "У Вас не указан емаил!\n\rЭто можно сделать в разделе ЛИЧНЫЕ ДАННЫЕ."; 
    $return['err'] = true;
    echo json_encode($return);
    die();
}

if($pass == ''){
    $return['msg'] = 'Нельзя оставлять пароль пустым!';
    $return['err'] = true;        
    echo json_encode($return);
    die();
}

if(strlen($pass) < 8){
    $return['msg'] = 'Слишком короткий пароль! 8 - символов.';
    $return['err'] = true;        
    echo json_encode($return);
    die();
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
      
            $return['msg'] = 'Данные сохранил успешно!';
            $return['err'] = false;        
            echo json_encode($return);
            die();
        }
        
    $return['msg'] = 'Чтото пошло не так! Сохранить не получилось!';
    $return['err'] = true;        
    echo json_encode($return);
            
?>
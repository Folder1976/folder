<?php
include '../config/config.php';

global $folder;

$return = array();

$name = mysqli_real_escape_string($folder, $_POST['name']);
$email = mysqli_real_escape_string($folder, $_POST['email']);
$phone = mysqli_real_escape_string($folder, $_POST['phone']);
$pass_no = mysqli_real_escape_string($folder, $_POST['pass']);
$pass = md5(mysqli_real_escape_string($folder, $pass_no));

$sql = "SELECT `klienti_id` FROM `tbl_klienti` WHERE `klienti_email` = '".$email."';";
$r = $folder->query($sql);

if($r->num_rows > 0){
    $return['msg'] = 'Этот емаил уже занят!';
    $return['err'] = true;
    echo json_encode($return);
die();
}

$sql = "INSERT INTO `tbl_klienti`(
            `klienti_id`,
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
              '".$name."',
              '".$name."',
              '".$name."',
              '".$pass."',
              '".$phone."',
              '".$email."',
              '".date("Y-m-d G:i:s")."',
              '0',
              '10',
              '".$setup['price default price']."',
              '0',
              '".$_SERVER['REMOTE_ADDR']."'
              )
        ";

        $folder->query($sql);
        $id = $folder->insert_id;

        //Нормальная регистрация
        if($id > 0){
            
            $_SESSION[BASE.'login']     = $email;
            $_SESSION[BASE.'username']  = $name;
            $_SESSION[BASE.'userid']    = $id;
            $_SESSION[BASE.'usersetup'] = "";
        
            $html = '<h1>Регистрация на Armma.ru</h1>
                    <ul>Ваши данные для авторизации:
                        <li>Логин : <b>'.$email.'</b></li>
                        <li>Пароль : <b>'.$pass_no.'</b></li>
                    </ul>
            ';
            
            include '../admin/libmail.php';
            $m = new Mail("UTF-8");
            $m->From($setup['email name'].";".$setup['email']);
            $m->smtp_on($setup['email smtp'],$setup['email login'],$setup['email pass'],$setup['email port']);//465 587
            $m->Priority(2);
            $m->Body($html);
            $m->text_html="text/html";
            $m->Subject('Регистрация на Armma.ru');
            $m->To($email);
            $u = $error = $m->Send();
            
            $return['user'] = $id;
            $return['msg'] = 'Регистрация прошла успешно!';
            $return['err'] = false;        
            echo json_encode($return);
            die();
        }
        
    $return['msg'] = 'Чтото пошло не так! Регистрация не получилась!';
    $return['err'] = true;        
    echo json_encode($return);
            
?>
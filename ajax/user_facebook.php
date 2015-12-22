<?php
include '../config/config.php';
if(session_id()){
}else{
  session_start();
}
global $folder;

$return = array();

$name = mysqli_real_escape_string($folder, $_POST['name']);
$id = mysqli_real_escape_string($folder, $_POST['id']);
$phone = '';
$email = '';

$sql = "SELECT * FROM `tbl_klienti` WHERE `klienti_name_1` = '$name' AND social_key = '$id';";
$r = $folder->query($sql);

if($r->num_rows > 0){
    $tmp = $r->fetch_assoc();
    
            $_SESSION[BASE.'login']     = $tmp['klienti_email'];
            $_SESSION[BASE.'username']  = $name;
            $_SESSION[BASE.'userid']    = $tmp['klienti_id'];
            $_SESSION[BASE.'usersetup'] = $tmp['klienti_setup'];
 
    $return['msg'] = 'Добро пожаловать, '.$name.'!';
    $return['url'] = HOST_URL; 
    $return['err'] = false;
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
            `klienti_ip`,
            `social_key`
              )VALUES(
              '',
              '3',
              '".$name."',
              '".$name."',
              '".$name."',
              '',
              '".$phone."',
              '".$email."',
              '".date("Y-m-d G:i:s")."',
              '0',
              '10',
              '".$setup['price default price']."',
              '0',
              '".$_SERVER['REMOTE_ADDR']."',
              '".$id."'
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
         
            $return['msg'] = "Добро пожаловать, $name!\n\rМы будем благодарны если Вы предоставите Вашу контантную информацию.\n\rВы можете сделать это в личном кабинете.";
            $return['url'] = HOST_URL.'/myaccount.html';        
            $return['err'] = false;        
            echo json_encode($return);
            die();
        }
        
    $return['msg'] = 'Чтото пошло не так!\n\rЗалогиньтесь у себя на Facebook и обновите данную страницу!';
    $return['url'] = '';
    $return['err'] = true;        
    echo json_encode($return);
            
?>
<?php

global $folder;

$return = array();

$provider = $user['provider'];
$name     = $user['name'];
$id       = $user['id'];
$phone    = '';
$email    = $user['email'];

$sql = "SELECT * FROM `tbl_klienti` WHERE `provider` = '$provider' AND social_key = '$id';";
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
    //echo json_encode($return);

}else{

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
            `social_key`,
            `provider`
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
              '".$id."',
              '".$provider."'
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
            $return['url'] = HOST_URL.'/account_personal.html';        
            $return['err'] = false;        
            //echo json_encode($return);
            //die();
            
         }else{
        
          $return['msg'] = 'Чтото пошло не так!\n\rЗалогиньтесь у себя на Facebook и обновите данную страницу!';
          $return['url'] = HOST_URL.'/registration.html'; 
          $return['err'] = true;        
          //echo json_encode($return);
        }
        
        header('Location: '.$return['url']);
}            
?>
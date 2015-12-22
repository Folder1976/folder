<?php
class User {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
	
	public function getActiveUserKey(){
	
		return md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

	}

	public function getProductCountInCategory($parent_id){
		
		$sql = "SELECT count(tovar_id) as products
			FROM tbl_tovar
			LEFT JOIN tbl_parent_inet_path ON path_id = '".$parent_id."'
			WHERE tovar_inet_id_parent = category_id";
		$sql = $this->base->query($sql);
		
		if($sql->num_rows == 0){
			return 0;
		}else{
			$res = $sql->fetch_assoc();
			return $res['products'];
		}
		
	}
	
	public function userLoginOnCookie(){
		if(isset($_COOKIE[BASE.'user_id'])){
			return false;
		}
		$sql = "SELECT 
		    `klienti_name_1`,
		    `klienti_id`,
		    `klienti_setup`,
		    `klienti_email`,
		    `klienti_discount`,
		    `klienti_inet_id`,
		    `klienti_price`,
		    `klienti_group_setup`
		    FROM `tbl_klienti`
			LEFT JOIN `tbl_klienti_group` ON `klienti_group`=`klienti_group_id`
		    WHERE klienti_id = '". $_COOKIE[BASE.'userid'] ."'		    
		    ";
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return 0;
		}else{
			$this->setUser($r->fetch_assoc());
		}
	}
	
	public function userLogin(){
		if($_POST['email'] == '' OR $_POST['pass'] == ''){
			return false;
		}
		
		$sql = "SELECT 
		    `klienti_name_1`,
		    `klienti_id`,
		    `klienti_setup`,
		    `klienti_email`,
		    `klienti_discount`,
		    `klienti_inet_id`,
		    `klienti_price`,
		    `klienti_group_setup`
		    FROM `tbl_klienti`
			LEFT JOIN `tbl_klienti_group` ON `klienti_group`=`klienti_group_id`
		    WHERE upper(`klienti_email`)='".mb_strtoupper(addslashes(mysqli_real_escape_string($this->base, $_POST["email"])))."' 
		    and `klienti_pass`='".md5((string)mysqli_real_escape_string($this->base, $_POST["pass"]))."'
		    ";
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return 0;
		}else{
			$this->setUser($r->fetch_assoc());
		}
	}
	
	public function logoutUser(){
		
		setcookie(BASE.'userid', $_SESSION[BASE.'userid'], -(3600 * 600 * 600));
		
		$_SESSION[BASE.'login']		  	= null;
		$_SESSION[BASE.'userlevel']		= null;
		$_SESSION[BASE.'username']		= null;
		$_SESSION[BASE.'userid']		= null;
		$_SESSION[BASE.'usersetup']		= null;
		$_SESSION[BASE.'userdiscount'] 	= null;
		$_SESSION[BASE.'userprice']		= null;
		$_SESSION[BASE.'usercurr']		= null;
		$_SESSION[BASE.'usergroup_setup']	= null;
		
	}
	
	private function setUser($user){
	
		//echo "<pre>";  print_r(var_dump( $user )); echo "</pre>";
		$_SESSION[BASE.'login']		= $user['klienti_email'];
		$_SESSION[BASE.'userlevel']	= $user['klienti_inet_id'];
		$_SESSION[BASE.'username']	= $user['klienti_name_1'];
		$_SESSION[BASE.'userid']	= $user['klienti_id'];
		$_SESSION[BASE.'usersetup']	= $user['klienti_setup'];
		$_SESSION[BASE.'userdiscount'] = $user['klienti_discount'];
		$_SESSION[BASE.'userprice']	= $user['klienti_price'];
		$_SESSION[BASE.'usercurr']	= '1';
		$_SESSION[BASE.'usergroup_setup']	= $user['klienti_group_setup'];
		
		if(!isset($_COOKIE[BASE.'userid'])){
			//echo BASE.'userid', $user['klienti_id'];
			setcookie(BASE.'userid', $user['klienti_id'], 3600 * 600 * 600);
		}
		
		$sql = "UPDATE `tbl_klienti` SET `klienti_ip`='".$_SERVER['REMOTE_ADDR']."' WHERE `klienti_id`='".$_SESSION[BASE.'userid']."'";
		$this->base->query($sql);
	}
	
	public function loadUserSetting($id){
		$sql = "SELECT `klienti_setup` FROM `tbl_klienti` WHERE `klienti_id`='".$id."'";
		//echo $sql;
		$r = $this->base->query($sql);
		
		if($r->num_rows == 0){
			return false;
		}else{
			$tmp = $r->fetch_assoc();
			$_SESSION[BASE.'usersetup'] = $tmp['klienti_setup'];
			return true;
		}
	}
	
	public function getLoginedUserInfo(){
		
		if(isset($_SESSION[BASE.'userid'])){
			$sql = "SELECT * FROM `tbl_klienti` WHERE `klienti_id`='".$_SESSION[BASE.'userid']."'";
			//echo $sql;
			$r = $this->base->query($sql);
			
			if($r->num_rows == 0){
				return false;
			}else{
				$tmp = $r->fetch_assoc();
				return $tmp;
			}
		}
		return false;
	}

	
	public function getKlientName($id){
		$sql = 'SELECT klienti_name_1 FROM tbl_klienti WHERE klienti_id = \''.$id.'\';';
		$r = $this->base->query($sql);
		
		$name = $r->fetch_assoc();
		
		return $name['klienti_name_1'];
	}
	
	public function userAddNew($data){
		global $setup;
		
		$city = '';
		$address= '';
		$deliv = '0';
		if(isset($data['city'])){
			$city = mysqli_real_escape_string($this->base, $data['city']);
		}
		
		if(isset($data['address'])){
			$address = mysqli_real_escape_string($this->base, $data['address']);
		}
		
		if(isset($data['TranspComp'])){
			$deliv = mysqli_real_escape_string($this->base, $data['TranspComp']);
		}
		
		$name = mysqli_real_escape_string($this->base, $data['name']);
		$email = mysqli_real_escape_string($this->base, $data['email']);
		$phone = mysqli_real_escape_string($this->base, $data['phone']);
		$pass_no = mysqli_real_escape_string($this->base, $data['pass']);
		$pass = md5(mysqli_real_escape_string($this->base, $pass_no));
		
		$sql = "SELECT `klienti_id` FROM `tbl_klienti` WHERE `klienti_email` = '".$email."';";
		$r = $this->base->query($sql);
		
		if($r->num_rows > 0){
			return false;
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
			`klienti_sity`,
			`klienti_adress`,
			`klienti_delivery_id`,
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
			  '$city',
			  '$address',
			  '$deliv',
              '".$_SERVER['REMOTE_ADDR']."'
              )
        ";

        $this->base->query($sql);
        $id = $this->base->insert_id;
    
        //Нормальная регистрация
        if($id > 0){
            $_SESSION[BASE.'login']     = $email;
            $_SESSION[BASE.'username']  = $name;
            $_SESSION[BASE.'userid']    = $id;
            $_SESSION[BASE.'usersetup'] = "";
           
            return $id;
        }
        
    return false;

	}
/*	
//$menu_information = "";
$temp_header = "admin/template/main.html";
$tmp_header = file_get_contents($temp_header);  
$user_level=10;
//if(!isset($_SESSION[BASE.'userlevel']))$_SESSION[BASE.'userlevel']=1;
if(!isset($_SESSION[BASE.'username']))$_SESSION[BASE.'username']=null; 
if(!isset($_SESSION[BASE.'usersetup']))$_SESSION[BASE.'usersetup']=null; 
if(!isset($_SESSION[BASE.'userlevel']))$_SESSION[BASE.'userlevel']=$user_level; 
if(!isset($_SESSION[BASE.'userdiscount']))$_SESSION[BASE.'userdiscount']=0; 
if(!isset($_SESSION[BASE.'usergroup_setup']))$_SESSION[BASE.'usergroup_setup']=null; 



$key = "";
if(isset($_REQUEST['key'])) $key=(string)$_REQUEST['key'];
if($key=="exit"){
$_SESSION[BASE.'login']=null;
$_SESSION[BASE.'userid']=null;
$_SESSION[BASE.'username']=null;
$_SESSION[BASE.'usersetup']=null;
$_SESSION[BASE.'pass']=null;
$_SESSION[BASE.'userorder']=null;
$_SESSION[BASE.'userordersumm']=null;
$_SESSION[BASE.'userlevel']=$user_level;
$_SESSION[BASE.'userprice']=null;
$_SESSION[BASE.'userdiscount']=null;
$_SESSION[BASE.'usergroup_setup']=null;
//Перечитаем меню каталогов если человек залогинился. Для этого обнулим переменную сессии.
$_SESSION[BASE.'user menu'] = null;
}

  if(!isset($_SESSION[BASE.'lang']))$_SESSION[BASE.'lang']=1;
  
  if (isset($_REQUEST['lang'])){
    $_SESSION[BASE.'lang'] = (int)mysql_real_escape_string($_REQUEST['lang']);
    $_SESSION[BASE.'user menu']=null;
	if ($_SESSION[BASE.'lang'] <1){
	      $_SESSION[BASE.'lang']=1;
	}
	if ($_SESSION[BASE.'lang'] >3){
	      $_SESSION[BASE.'lang']=1;
	}
  }
   

if(isset($_REQUEST['comment_txt'])){
  $comment_txt = (string)mysql_real_escape_string($_REQUEST['comment_txt']);
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
  $comment_txt = str_replace($dell,"*",$comment_txt);
  
  $tmp = mysql_query("SET NAMES utf8");
  $tmp = mysql_query("SELECT `comments_klient` FROM `tbl_comments` WHERE `comments_memo`='".$comment_txt."' and `comments_tovar`='".mysql_real_escape_string($_REQUEST['tovar_id'])."'");
 
  if(mysql_num_rows($tmp)==0){
      $tmp = mysql_query("SET NAMES utf8");
      $tmp = mysql_query("INSERT INTO `tbl_comments` 
		      (`comments_tovar`,`comments_klient`,`comments_memo`)
		      VALUES
		      ('".(int)mysql_real_escape_string($_REQUEST['tovar_id'])."',
			'".$_SESSION[BASE.'userid']."',
			'".$comment_txt."')
		      ");
  }
}
//==================================================================
//echo $_REQUEST["login"],$_REQUEST["pass"],$_REQUEST["logining"]=='1',"<br>";

if(isset($_POST["pass"]) and !empty($_POST["login"]) and $_POST["logining"]=='1'){
$ver = mysql_query("SET NAMES utf8");
$sqlStr = "SELECT 
		    `klienti_name_1`,
		    `klienti_id`,
		    `klienti_setup`,
		    `klienti_email`,
		    `klienti_discount`,
		    `klienti_inet_id`,
		    `klienti_price`,
		    `klienti_group_setup`
		    FROM `tbl_klienti`,`tbl_klienti_group` 
		    WHERE upper(`klienti_email`)='".mb_strtoupper(addslashes(mysql_real_escape_string($_REQUEST["login"])),'UTF-8')."' 
		    and `klienti_pass`='".md5((string)mysql_real_escape_string($_REQUEST["pass"]))."'
		    and `klienti_group`=`klienti_group_id`
		    ";
		    
$ver = mysql_query($sqlStr);
if (!$ver)
{

//echo "1 User not found or login+pass not corect!";
if(strpos($_REQUEST['web'],"key=exit")) $web = "index.php?user=new";
if(!isset($_SESSION[BASE.'userid'])) $web = "index.php?user=new";
header ('Refresh: 0; url='.$web);
}
$curr = mysql_query("SET NAMES utf8");
$curr = mysql_query("SELECT 
		    `currency_name_shot` FROM `tbl_currency` 
		    WHERE `currency_id`='1'");
		    
if(mysql_num_rows($ver)==0){
//echo "<b> User not found or login+pass not corect!</b>";
$web = $_REQUEST['web'];
if(strpos($_REQUEST['web'],"key=exit")) $web = "".HOST_URL."/index.php";
if(!isset($_SESSION[BASE.'userid'])) $web = "".HOST_URL."/index.php?user=new";
header ('Refresh: 0; url='.$web);
}else{
    if(empty($_SESSION[BASE.'userorder'])){
	$oper = mysql_query("SET NAMES utf8");
	$oper = mysql_query("SELECT 
		    `operation_id`,
		    `operation_summ`
		    FROM `tbl_operation` 
		    WHERE `operation_klient`='".mysql_result($ver,0,"klienti_id")."' 
		    and `operation_status`='16'
		    and `operation_dell`='0'");
	if(mysql_num_rows($oper)>0){
	      $_SESSION[BASE.'userorder']=mysql_result($oper,0,"operation_id");
	      $_SESSION[BASE.'userordersumm']=mysql_result($oper,0,"operation_summ");
	}else{
	      $_SESSION[BASE.'userorder']=null;
	      $_SESSION[BASE.'userordersumm']=null;
	}
    }

$_SESSION[BASE.'login']=mysql_result($ver,0,"klienti_email");
$_SESSION[BASE.'userlevel']=mysql_result($ver,0,"klienti_inet_id");
$_SESSION[BASE.'username']=mysql_result($ver,0,"klienti_name_1");
$_SESSION[BASE.'userid']=mysql_result($ver,0,"klienti_id");
$_SESSION[BASE.'usersetup']=mysql_result($ver,0,"klienti_setup");
$_SESSION[BASE.'userdiscount']=mysql_result($ver,0,"klienti_discount");
$_SESSION[BASE.'userprice']=mysql_result($ver,0,"klienti_price");
$_SESSION[BASE.'usercurr']=mysql_result($curr,0,0);
$_SESSION[BASE.'usergroup_setup']=mysql_result($ver,0,"klienti_group_setup");

$sSQL = "UPDATE `tbl_klienti` SET `klienti_ip`='".$_SERVER['REMOTE_ADDR']."' WHERE `klienti_id`='".$_SESSION[BASE.'userid']."'";
$ver = mysql_query($sSQL);

//Перечитаем меню каталогов если человек залогинился. Для этого обнулим переменную сессии.
$_SESSION[BASE.'user menu'] = null;
}
$web = mysql_real_escape_string($_REQUEST['web']);
if(strpos($_REQUEST['web'],"key=exit")) $web = "index.php";
if(!isset($_SESSION[BASE.'userid'])) $web = "index.php?user=new";
header ('Refresh: 0; url='.$web);
}
//==================================================================

if (!isset($_SESSION[BASE.'userprice'])){
  $_SESSION[BASE.'userprice'] = $setup['web default price'];
}*/

}
?>
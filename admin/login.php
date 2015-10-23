<?php

include 'init.lib.php';
connect_to_mysql();
//session_start();
//echo "User: ",$_SESSION[BASE.'username'];
//if ($_SESSION[BASE.'username']==null){
//echo "No User found/";
//header ('Refresh: 1; url=login.php');
//exit();
//}
session_start();
session_destroy();

header ('Content-Type: text/html; charset=utf8');
echo "<header><title>Edit table tovar</title><link rel='stylesheet' type='text/css' href='sturm.css'></header>";

echo "\n<body>\n";//<p  style='font-family:vendana;font-size=22px'>";
echo "<table width=100% height=100%><tr><td align='center' valign='middle'>";
//============FIND====================================================================================
echo "<table><tr><td>
      <form method='POST' action='login_ver.php'>";
echo "\n<input type='hidden' name='web' value='"  , $_REQUEST["web"]  , "'/>";
echo "\nbase:<input type='text' style='width:100px' name='base' value=''/>
</td></tr><tr><td>";
echo "\nlogin:<input type='text' style='width:100px' name='login' value=''/>
</td></tr><tr><td>";
echo "\npass:<input type='password' style='width:100px' name='pass' value=''/>
</td></tr><tr><td align='center'>";
echo "<input type='submit' value='login'/>
      \n</form>";
//=====================================================================================================
echo "</td></tr></table>";
echo "</td></tr></table>";

echo "\n</body>";
?>

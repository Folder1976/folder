<?php
include "../config.php";
include '../init.lib.php';
connect_to_mysql();
session_start();
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

//Новое соединение с базой
$folder= mysqli_connect(DB_HOST,DB_USER,DB_PASS,BASE) or die("Error " . mysqli_error($folder)); 
mysqli_set_charset($folder,"utf8");

//define("UPLOAD_DIR", "/home/armma/armma.ru/docs/resources/products/");

$TWater = "/admin/watermark/";

echo "<a href=\"/admin/setup.php\">Настройки</a> | ";
if(!isset($_GET["tvmact"]) || $_GET["tvmact"] == "") {
	$TovMAct = "";
	echo "Добавление водяных знаков";
}
else {
	$TovMAct = $_GET["tvmact"];
	echo '<a href="'.$TWater.'">Добавление водяных знаков</a> | ';
}

switch($TovMAct) {
	case "":
		$r=mysqli_query($folder, "SELECT COUNT(*) FROM `tbl_tovar_pic_watermark`
		WHERE `IsWater` = '0';") or die('Get count of work ');
		$WorkCount = mysqli_fetch_row($r);

		echo '<ol>
		<li><a href="'.$TWater.'?tvmact=loadwmark">Загрузить водяной знак</a></li>
		<li><a href="'.$TWater.'?tvmact=updatelist">Обновить список картинок, которые нужно обрабатывать</a></li>
		<li><a href="'.$TWater.'?tvmact=addwmarks">Добавить водяные знаки</a> (осталось обработать: '.$WorkCount[0].')</li>
		<li><a href="'.$TWater.'?tvmact=clearflag">Сбросить флаг и добавить водяные знаки заново</a></li>
		</ol>';
	break;

	case "loadwmark":
		echo "Файл водяного знака";
		
		if (file_exists("tovwater.png")) {
			echo "<p><a href=\"tovwater.png\" target=\"_blank\">Файл с водяными знаками</a>. Или его <a href=\"".$TWater."?tvmact=delwatermark\" ONCLICK=\"javascript:if(confirm('Действительно хотите удалить файл?')) {return true;} else{return false;}\">удалить</a>?";
		}
		else {
			echo "
			<form name=\"svtov\" method=post action=\"".$TWater."?tvmact=uplwmark\" enctype=\"multipart/form-data\">
			<p><table border=1 cellspacing=0 cellpadding=4>
			<tr>
				<td>File:</td>
				<td><input name=\"strctimp\" type=\"file\" size=\"50\"></td>
				<td><input name=\"wmupl\" type=\"submit\" value=\"Загрузить\"></td>
			</tr>
			</table></form>";
		}
	break;

	case "uplwmark":
		echo "Uploded";
		$Text = "Нет файла для загрузки";
		if (isset($_FILES['strctimp']['name']) && $_FILES['strctimp']['name'] != "") {
			move_uploaded_file($_FILES['strctimp']['tmp_name'], 'tovwater.png');
			$Text = "Файл загружен";
		}
		echo "<H2>".$Text."!</H2><SCRIPT>\n\nvar i = setTimeout(\"window.location.href='" . $TWater . "'\", 1000);\n</SCRIPT>";

	break;

	case "delwatermark":
		echo "Удаление файла с водяными знаками";

		if (file_exists("tovwater.png")) {
			unlink("tovwater.png");
		}
		echo "<H2>Удалено!</H2><SCRIPT>\n\nvar i = setTimeout(\"window.location.href='" . $TWater . "'\", 1000);\n</SCRIPT>";
	break;

	case "updatelist":
		set_time_limit(1000);
		echo "Обновление списка файлов для обработки";

		$i = 0;
		function glob_recursive($dir, $mask){
			global $i, $folder;
			foreach(glob($dir.'/*') as $filename){
				if(strtolower(substr($filename, strlen($filename)-strlen($mask), strlen($mask)))==strtolower($mask)) {
					$forDB = str_replace('/home/armma/armma.ru/docs/resources/products/', '', $filename);
					$r=mysqli_query($folder, "INSERT IGNORE INTO `tbl_tovar_pic_watermark`
					SET `image_large` = '".mysqli_real_escape_string($folder, $forDB)."';") or die('Error adding image: '.$filename.' error message: '.mysqli_error($folder));

					//echo $filename."<br>";
					$i++;
				}
				if(is_dir($filename)) glob_recursive($filename, $mask);
			}
		}
		glob_recursive(UPLOAD_DIR, "large.jpg");
		echo "<H2>Было найдено на диске файлов: ".$i.", они добавлены/обновлены в БД!</H2><SCRIPT>\n\nvar i = setTimeout(\"window.location.href='" . $TWater . "'\", 1000);\n</SCRIPT>";
	break;

	case "clearflag":
		$r=mysqli_query($folder, "UPDATE `tbl_tovar_pic_watermark`
		SET `IsWater` = '0';") or die('Set flag to null: '.mysqli_error($folder));

		echo "<H2>Флаги обновлены!</H2><SCRIPT>\n\nvar i = setTimeout(\"window.location.href='" . $TWater . "'\", 1000);\n</SCRIPT>";
	break;

	case "addwmarks":
		$r = mysqli_query( $folder, "SELECT COUNT(*) FROM `tbl_tovar_pic_watermark` WHERE `IsWater`='0' AND `image_large` != '';") or die ("Count images table :(");

		$Wmklv = mysqli_fetch_row($r);

		echo 'Добавление водяных знаков<p></p>
		<link rel="stylesheet" href="../js/theme/jquery.ui.theme.css">
		<script src="../js/jquery-1.8.2.min.js"></script>
		<script src="../js/jquery-ui-1.7.3.custom.min.js"></script>
		<div style="width: 90%">
		<h3>Обработка файлов (было всего <span id="filetotal">0</span> осталось <span id="filefrparse">0</span>)</div></h3>
			<div id="fileprogressbar"></div>
		</div>
		 <style>
		 .ui-progressbar .ui-progressbar-value { background-image: url(../images/pbar-ani.gif); height: 22px; width: 400px}
		 .ui-progressbar ui-widget ui-widget-content ui-corner-all {height: 22px;}
		 </style>
		<script>
			var filestot = '.$Wmklv[0].';
			var filesbar = 0;
			var fileslast = '.$Wmklv[0].';
			var fti = 0;

			$("#filetotal").html(filestot);
			$("#filefrparse").html(filestot);

			$("#fileprogressbar" ).progressbar({value: 0});

			function UpdateFileKlv(i) {
				$("#fileprogressbar").progressbar("option", "value", i);
			}

			function SucssFunct(curobrab) {
				filesbar = filesbar + ((curobrab/filestot)*100);
				fileslast = fileslast - curobrab;
				fti = fti + curobrab;

				$("#filefrparse").html(fileslast);

				UpdateFileKlv(filesbar);
				if (fileslast > 0) {
					MakeWMark();
				}
				
			}

			function MakeWMark() {
				$.ajax({
					url: "watermark-to-file.php",
					cache: false,
					type: "POST",
					success: function(data){
						$("#movebar").prepend(data);
					}
				});
			}

			MakeWMark();
		</script>
		<p>&nbsp;</p>
		<div id="movebar"></div>';
	break;
		// tbl_tovar_pic_watermark
		/*
image_large	varchar(255)	Нет 	 	 	 
IsWater	enum('0', '1')
		*/
}

?>
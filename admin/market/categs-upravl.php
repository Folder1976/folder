<?php
ini_set('display_errors','Off');

$MFSubUrl = $FSubUrl."&tvmact=categs";

if(!isset($_GET["subact"]) || $_GET["subact"] == "") {
	$MFSubAct = "";
	echo "Настройка базовых категорий";
}
else {
	$MFSubAct = $_GET["subact"];
	echo '<a href="'.$MFSubUrl.'">Настройка базовых категорий</a> | ';
}

function readTree($parent,$folder){
   $sql = "SELECT parent_inet_id AS product_type_id, parent_inet_parent AS product_parent_id, parent_inet_1 AS product_type_name
			FROM  tbl_parent_inet  WHERE parent_inet_parent = '$parent' ORDER BY product_type_name ASC;";
    $rs1 = $folder->query($sql) or die ("Get product type list".$sql);

    $body = "";

     while ($Type = mysqli_fetch_assoc($rs1)) {
		$body .=  "\n<li><span id=\"span_".$Type['product_type_id']."\"><a class=\"tree\" href=\"#\" onclick=\"SaveCat(".$Type['product_type_id'].");return false;\" id=\"".$Type['product_type_id']."\">".$Type['product_type_name']."</a></span>".readTree($Type['product_type_id'],$folder);
	    $body .= "</li>\n";
    }
    if($body != "") {$body = "<ul>".$body."</ul>\n\n";}
    return $body;

}

switch ($MFSubAct) {
	case "":
		$CategSpis = array();

		$r = mysqli_query( $folder, "SELECT parent_inet_id AS id,
					parent_inet_parent AS parent,
					parent_inet_1 AS name
			FROM tbl_parent_inet
			WHERE 1;") or die ("GetMagID:(");
                
		while ($tCatS = mysqli_fetch_assoc($r)) {
                   
			$CategSpis[$tCatS["id"]] = $tCatS["name"];
		}
		
		echo "
		<p><table border=1 cellspacing=0 cellpadding=3 align=center>
		<tr bgcolor=silver align=center>
		<td><b>#</b></td>
		<td><b>Название категории</b></td>
		<td><b>Категория Магазина</b></td>
		<td><b>Путь в Маркете</b></td>
		<td><b>Ставка</b>**</td>
		<td><b>VDT</b>*</td>
		<td><b>Del</b></td>
		</tr>
		<tr><td colspan=8 align=center bgcolor=yellow><a href=\"".$MFSubUrl."&subact=addnewcateg\">Добавить новую категорию</a></td></tr>\n";

		$r = mysqli_query( $folder, "SELECT MarketCategID, MarketCategNazv, ShopCategoryID, MarketPath, CategViewTovDisabled, CategClickPrice
		FROM ".$ppt."yandex_market_setup
		ORDER BY BINARY (MarketCategNazv);") or die ("GetCategs :(");
		$i = 1;
		if (mysqli_num_rows($r) > 0) {
			echo "<form name=\"adnewc\" method=\"post\" action=\"".$MFSubUrl."&subact=saveupd\">";
			while ($CitSp = mysqli_fetch_array($r)) {
				echo '<tr><td>'.$CitSp["MarketCategID"].'</td>
				<td><input type=text name="prmname'.$i.'" value="'.$CitSp["MarketCategNazv"].'" size=25></td>
				<td>';
				if (isset($CategSpis[$CitSp["ShopCategoryID"]])) {
					echo $CategSpis[$CitSp["ShopCategoryID"]];
				}
				else {
					echo "<font color=red>Category not found!</font>";
				}
				echo ' (ID: '.$CitSp["ShopCategoryID"].')</td>
				<td><input type=text name="prmlable'.$i.'" value="'.$CitSp["MarketPath"].'" size=40></td>
				<td><input type=text name="prclick'.$i.'" value="'.$CitSp["CategClickPrice"].'" size=2></td>
				<td align=center><input type=checkbox name="viewdisable'.$i.'" value=1';
				if ($CitSp["CategViewTovDisabled"] == 1) {echo ' checked';}
				echo '></td>

				<td align=center><a href="'.$MFSubUrl.'&subact=delprm&categ='.$CitSp["MarketCategID"].'" ONCLICK="javascript:if(confirm(\'Удаление категории повлечет автоматическое удаление товаров этой категории из файла Яндекс.Маркет\')) {return true;} else{return false;}">Del</a></td>
				<input type=hidden name="categ'.$i.'" value="'.$CitSp["MarketCategID"].'">
				</tr>';
				$i += 1;
			}
			echo "<tr><td colspan=8 align=center><input type=hidden name=\"prmklv\" value=\"".$i."\">
			<input type=submit value=\"Обновить\"></td></tr>";
		}
		echo "</table>
		<p>Категории из которых будут собираться товары для Яндекс.Маркета
		<p>* Показывать ли в категории товары, котрых &quot;нет в наличии&quot;
		<p>** Ставка клика в Маркете";
	break;

	case "addnewcateg":
		$AlrdSV = array();
		$r = mysqli_query( $folder, "SELECT ShopCategoryID FROM ".$ppt."yandex_market_setup;") or die ("GetUsedCategs :(");
		while($UsdCat = mysqli_fetch_assoc($r)) {
			$AlrdSV[$UsdCat["ShopCategoryID"]] = 1;
		}
		
		echo "Добавление новой категории
		<p>
		<form name='svc' method='post' action='".$MFSubUrl."&subact=savenew'>
		<table border=1 cellspacing=0 cellpadding=3>
		<tr><td bgcolor=silver><b>Название категории</b></td><td><input type=text size=70 name=\"categnazv\"></td></tr>
		<tr><td bgcolor=silver><b>Путь категории в Маркете</b></td><td><input type=text size=70 name=\"marketpath\"></td></tr>
		<tr><td bgcolor=silver><b>Цена клика для товаров в категории</b></td><td><input type=text size=70 name=\"priceclick\"></td></tr>
		<tr><td bgcolor=silver><b>Показывать товары, которых нет в наличии</b></td><td><input type=checkbox name=\"viewdisable\" value=1></td></tr>
		
		<tr><td bgcolor=silver><b>Категория магазина</b></td><td>
			<div id=\"container\" class = \"product-type-tree\">
				<input type='hidden' id='categsv' name='catofshop' value=''>
				<ul  id=\"celebTree\"><li><span id=\"span_0\"><a class = \"tree\" href=\"javascript:\"
				id=\"0\">Категории</a></span><ul>";

				$rs = mysqli_query( $folder, "SELECT parent_inet_id AS product_type_id,
					parent_inet_parent AS product_parent_id, parent_inet_1 AS product_type_name
					FROM  tbl_parent_inet  WHERE parent_inet_parent = '0' ORDER BY parent_inet_1 ASC;") or die ("GetMagID:(");
					
					while ($Type = mysqli_fetch_assoc($rs)) {
						if($Type['product_parent_id'] == 0){
							echo  "<li>
							<span id=\"span_".$Type['product_type_id']."\"> <a class=\"tree\" href=\"#\" onclick=\"SaveCat(".$Type['product_type_id'].");return false;\" id=\"".$Type['product_type_id']."\">".$Type['product_type_name']."</a>
							</span>".readTree($Type['product_type_id'],$folder)."</li>\n";
						}
					}
					$body .= "</ul>
						</li></ul></div>";
				
				
//		echo "<span id=\"span_0\"> <a class = \"tree\" href=\"javascript:\" id=\"0\">Категории</a>
//		</span>".readTree($Type['product_type_id'],$folder);
		/*
		echo "<table border=1 cellspacing=0 cellpadding=3>\n";
               
		$r = mysqli_query( $folder, "SELECT parent_inet_id AS id,
					parent_inet_parent AS parent,
					parent_inet_1 AS name
			FROM tbl_parent_inet
			WHERE parent_inet_parent = '0'") or die ("GetMagID:(");
		while ($TpCat = mysqli_fetch_assoc($r)) {
			if (isset($AlrdSV[$TpCat["categoryID"]])) {continue;}
			echo "<tr><td><input type=radio name=\"catofshop\" value=\"".$TpCat["id"]."\"></td>
			<td>".$TpCat["name"]."</td></tr>\n";
		}
		echo "</table>";
		*/
		echo "</td></tr>
		<tr><td colspan=2 align=center><input type=submit value=\"  Добавить  \"></td></tr>
		</table></form>
		<style>
			.tree{
				margin-left: 15px;
			}
			.tree_ul{
				margin-left: 0px;
			}
			.handle {
				background: transparent url(images/tree-handle.png) no-repeat left top;
				display:block;
				float:left;
				width:15px;
				height:17px;
				cursor:pointer;
			}
			#container ul {
				margin-top: 15px;
				margin-left: 20px; /* Отступ слева в браузере IE и Opera */
				padding-left: 0; /* Отступ слева в браузере Firefox, Safari, Chrome */
			}
			li {
				padding-top: 3px;
				padding-bottom: 4px;
				list-style-type: none; 
			}
			#container  li {
				list-style-type: none; 
			}
			.closed { background-position: left 2px; }
			.opened { background-position: left -13px; }
		</style>
		<script>
			$(document).ready(function(){
				
				//Скрипт дерева ========================

				$('#celebTree ul')
					.hide()
					.prev('span')
					.before('<span></span>')
					.prev()
					.addClass('handle closed')
					.click(function() {
					  // plus/minus handle click
							 $(this).toggleClass('closed opened').nextAll('ul').toggle();
					});

				$('#celebTree ul')
					.prev('span')
					.children('a')
					.toggleClass('tree tree_ul')
					.click(function() {
					  // plus/minus handle click
							$(this).toggleClass('closed opened').nextAll('ul').toggle();
					});
					
				  //Развернем первый уровень
				$('#0').parent('span').parent('li').children('span').first().toggleClass('closed opened').nextAll('ul').toggle();

			});
			function SaveCat(CatID) {
				console.log(CatID);
				$('#categsv').val(CatID);
				$('.tree').css('background', 'none');
				$('.tree_ul').css('background', 'none');
				$('#'+CatID).css('background', '#73ff73');
				
			}
		</script>";
	break;
	
	case "savenew":
		if (!isset($_POST["categnazv"]) || $_POST["categnazv"] == "") {die("<h2>Ошибка: не введено название категории</h2>");}

		if (!isset($_POST["marketpath"]) || $_POST["marketpath"] == "") {die("<h2>Ошибка: не введен путь категории в Маркете</h2>");}

		if (!isset($_POST["catofshop"]) || !is_numeric($_POST["catofshop"])) {die("<h2>Ошибка: категория магазина</h2>");}
		
		$ViewDisable = 0;
		if (isset($_POST["viewdisable"]) && is_numeric($_POST["viewdisable"])) {
			$ViewDisable = 1;
		}

		$PrClick = str_replace(array(' ',','), array('','.'), $_POST["priceclick"]);
		if(!is_numeric($PrClick)) {$PrClick = 0;}

		$CategName = str_replace(array("'", '"'), array('', '&quot;'),  $_POST["categnazv"]);

		$MarketPath = str_replace(array("'", '"'), array('', '&quot;'),  $_POST["marketpath"]);

		$MagCat = $_POST["catofshop"];

		$r = mysqli_query( $folder, "INSERT INTO ".$ppt."yandex_market_setup SET MarketCategID='', MarketCategNazv='".$CategName."', ShopCategoryID='".$MagCat."', MarketPath='".$MarketPath."', CategViewTovDisabled='".$ViewDisable ."', CategClickPrice='".$PrClick."';") or die ("SaveNew:(");

		echo "<H2>Добавлено</H2><SCRIPT>\nvar i=setTimeout(\"window.location.href='".$MFSubUrl."'\", 500);\n</SCRIPT>";
	break;


	case "saveupd":
		if(isset($_POST["prmklv"]) && $_POST["prmklv"] > 1) {
			for($i=1; $i<$_POST["prmklv"]; $i++) {
				if (isset($_POST["categ".$i]) && is_numeric($_POST["categ".$i])) {
					$CatID = $_POST["categ".$i];

					$CategName = str_replace(array('&', "'", '"', '>', '<'), array('&amp;', '&apos;', '&quot;', '&gt;', '&lt;'),  $_POST["prmname".$i]);

					$MarketPath = str_replace(array('&', "'", '"', '>', '<'), array('&amp;', '&apos;', '&quot;', '&gt;', '&lt;'),  $_POST["prmlable".$i]);

					$ViewDisable = 0;
					if (isset($_POST["viewdisable".$i]) && is_numeric($_POST["viewdisable".$i])) {
						$ViewDisable = 1;
					}

					$PrClick = str_replace(array(' ',','), array('','.'), $_POST["prclick".$i]);
					if(!is_numeric($PrClick)) {$PrClick = 0;}

					$r = mysqli_query( $folder, "UPDATE ".$ppt."yandex_market_setup SET MarketCategNazv='".$CategName."', MarketPath='".$MarketPath."', CategViewTovDisabled='".$ViewDisable."', CategClickPrice='".$PrClick."' WHERE MarketCategID='".$CatID."';") or die ("Upd market setup:(");
				}
			}
		}

		$r=mysqli_query( $folder, "OPTIMIZE TABLE ".$ppt."yandex_market_setup;") or die ("Optim");
		echo "<H2>Обновлено</H2><SCRIPT>\nvar i=setTimeout(\"window.location.href='".$MFSubUrl."'\", 500);\n</SCRIPT>";
	break;

	case "delprm":
		if (!isset($_GET["categ"]) || !is_numeric($_GET["categ"])) {die("<SCRIPT>history.back();</SCRIPT>");}
		$CatID = $_GET["categ"];

		$r=mysqli_query( $folder, "DELETE FROM ".$ppt."yandex_market_setup WHERE MarketCategID='".$CatID."';") or die ("DEL 1");
		$r=mysqli_query( $folder, "OPTIMIZE TABLE ".$ppt."yandex_market_setup;") or die ("Opt1");

		echo "<H2>Удалено</H2><SCRIPT>\n\nvar i= setTimeout(\"window.location.href='".$MFSubUrl."'\", 500);\n</SCRIPT>";
	break;
}
?>
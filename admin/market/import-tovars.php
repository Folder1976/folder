<?php
$MFSubUrl = $FSubUrl."&tvmact=importtovars";

$SCat = "";

$Catgs = array();
$r = mysqli_query( $folder, "SELECT MarketCategID FROM ".$ppt."yandex_market_setup;") or die ("Count categ table :(");
while($CtLst = mysqli_fetch_assoc($r)) {
	$Catgs[] = $CtLst["MarketCategID"];
}
$CtK = mysqli_num_rows($r);


//Процедура AJAX обновления
echo 'Создание YML<p></p>
<link rel="stylesheet" href="js/theme/jquery.ui.theme.css">
<script src="js/jquery-1.8.2.min.js"></script>
<script src="js/jquery-ui-1.7.3.custom.min.js"></script>
<div style="width: 90%">
';
echo '<h3>Категория  (было всего <span id="ymcattotal">0</span> осталось <span id="ymcatparse">0</span>)</div></h3>
<div id="ymprogressbar"></div>';

echo '<h3>Обработка товаров в категории (было всего <span id="tovtotal">0</span> осталось <span id="tovfrparse">0</span>)</div></h3>
	<div id="tovarsprogressbar"></div>
</div>


 <style>
 .ui-progressbar .ui-progressbar-value { background-image: url(images/pbar-ani.gif); height: 22px; width: 400px}
 .ui-progressbar ui-widget ui-widget-content ui-corner-all {height: 22px;}
 </style>
<script>
	var categtot = '.$CtK.';
	var categtoobr = '.$CtK.';
	var catbar = 0;
	var cati = 0;
	var tovincat = 0;
	var tovfixincat = 0;
	var tovbar = 0;
	var tovi = 0;
	var CategLst = ["'.implode('","',$Catgs).'"];
	var TovLst = [];

	$("#ymcattotal").html(categtot);
	$("#ymcatparse").html(categtot);
	$("#tovarsprogressbar" ).progressbar({value: 0});
	$("#ymprogressbar" ).progressbar({value: 0});

	function UpdateYMLCategory(i) {
		$("#ymprogressbar").progressbar("option", "value", i);
	}

	function UpdateYMLTovar(i) {
		$("#tovarsprogressbar").progressbar("option", "value", i);
	}

	function MakeHeadYML() {
		$.ajax({
			url: "market/import-tovars-ajax-head.php",
			type: "GET",
			cache: false,
			success: function(data){
				$("#movebar").prepend(data);
			}
		});
	}

	function MakeFinalYML() {
		$.ajax({
			url: "market/import-tovars-ajax-final.php",
			type: "GET",
			cache: false,
			success: function(data){
				$("#movebar").prepend(data);
			}
		});
	}

	function ResetTovarsCateg(Tov) {
		$("#tovtotal").html(Tov);
		$("#tovfrparse").html(Tov);
		tovincat = Tov;
		tovfixincat = Tov;
		tovi = 0;
		tovbar = 0;
		UpdateYMLTovar(0);
	}

	function GetNextCateg(ItemID) {
		$.ajax({
			url: "market/import-tovars-ajax-getcateg.php",
			type: "GET",
			cache: false,
			type: "POST",
			data: {categ: CategLst[ItemID]},
			success: function(data){
				$("#movebar").prepend(data);
			}
		});
	}

	function MakeYML(catID, tovID) {
		$.ajax({
			url: "market/import-tovars-ajax-genyml.php",
			cache: false,
			type: "POST",
			data: {categ: catID, tovid: TovLst[tovID]},
			success: function(data){
				$("#movebar").prepend(data);
			}
		});
	}


	function SucssFunct(catID, curobrab) {
		tovbar = tovbar + ((curobrab/tovfixincat)*100);
		tovincat = tovincat - curobrab;
		tovi = tovi + 1;

		$("#tovfrparse").html(tovincat);

		UpdateYMLTovar(tovbar);
		if (tovincat > 0 && tovi <= tovfixincat) {
			MakeYML(catID, tovi);
		}
		else {
			SucssCategFunct();
		}
		
	}

	function SucssCategFunct() {
		catbar = catbar + ((1/'.$CtK.')*100);
		categtoobr = categtoobr - 1;
		cati = cati + 1;

		UpdateYMLCategory(catbar);
		if (categtoobr > 0 && cati <= categtot) {
			GetNextCateg(cati);
		}
		else {
			MakeFinalYML();
		}
	}

	MakeHeadYML();
</script>
<p>&nbsp;</p>
<div id="movebar"></div>
';
?>
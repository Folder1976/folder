<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Оформление заказа '; ?>
        <?php $description = 'Оформление заказа'; ?>
    
        <?php include_once SKIN_PATH.'header_main.php'; ?>    
        <?php include_once SKIN_PATH.'header_includes.php'; ?>
        <?php
        //header("Content-Type: text/html; charset=UTF-8");
        //echo "<pre>";  print_r(var_dump( $user )); echo "</pre>";
        
        ?>
</head>
<body>
<!--[if lt IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="page">
    <header class="page__header js-slider-bg">
    <div class="header">
        <?php include SKIN_PATH . 'header_info.php'; ?>
        <?php include SKIN_PATH . 'header_menu.php'; ?>
        <?php include SKIN_PATH . 'header_search.php'; ?>
    </div>
    
</header>


    <div class="row">
    <div class="small-24 columns">
        <div class="breadcrumbs">
            
            <div class="breadcrumb">
                <a href="index.html" class="breadcrumb__name"><span class="fa fa-home"></span></a>
            </div>
            
            <div class="breadcrumb">
                <a href="<?php echo HOST_URL;?>/account_cart.html" class="breadcrumb__name">Корзина</a>
            </div>
            
            <div class="breadcrumb breadcrumb_last">
                <span class="breadcrumb__name">Оформление заказа</span>
            </div>
            
        </div>
    </div>
</div>

<div class="section">
    <div class="row">
        <div class="small-24 columns section__header">
            <h1 class="section__title">Оформление заказа</h1>
        </div>
    </div>

    <div class="row cart-final">
        <div class="large-12 medium-16 medium-centered small-24 columns">
            <h3 class="section__sub-title">Спасибо за ваш заказ!</h3>
            <p>Спасибо за заказ! Вашему заказу присвоен номер <strong style="font-size: 18px;">№<?php echo $order['id']; ?></strong>.<br>
                В ближайшее время наш менеджер свяжется с Вами для уточнения деталей.<br>
                Спасибо за покупку!</p>
            <p>Сумма заказа <span style="font-size: 24px;"><?php echo $order['sum']; ?>  ₽</span>.</p>
<?php
if (isset($_GET["kredit"])) {
	// Заказ клиента
	$order = array(
		// Состав заказа
		'items' => $order['krprod'],
		// Информация о покупателе
		'details' => $order['kruser'],
		'partnerId' => 'a06b0000023kIQTAA2', // ID Партнера в системе Банка (выдается Банком)
		'partnerOrderId' => 'armma-'.$order['id'], // Уникальный номер заказа в системе Партнера
	);

	// JSON-представление заказа
	$json = json_encode($order);

	// Base64-кодирование JSON-представления заказа
	$base64 = base64_encode($json);

	// Секретная строка для формирования подписи (выдается Банком)
	$secret = 'armma-secret-3kIQT93e';

	/**
	 * Функция формирования подписи заказа
	 * @param $message Base64-представление заказа
	 * @param $secretPhrase Секретная строка
	 * @return string
	 */
	function signMessage($message, $secretPhrase) {
		$message = $message.$secretPhrase;
		$result = md5($message).sha1($message);
		for ($i = 0; $i < 1102; $i++) {
			$result = md5($result);
		}
		return $result;
	}

	// Формирование подписи
	$sign = signMessage($base64, $secret);
?>
	<script src="https://form.kupivkredit.ru/sdk/v1/sdk.js?onload=myOnLoadFunction" type="text/javascript" async></script>
	<script type="text/javascript">
		window.callbacks = [];

		window.onload = function() {
			for (var i = 0; i < this.callbacks.length; i++) {
				this.callbacks[i].call();
			}
		};

		window.myOnLoadFunction = function(KVK) {
			var button, form;
			form = KVK.ui("form", {
				order:"<?php echo $base64; ?>",
				sign: "<?php echo $sign; ?>",
				type: "full"
			});

			window.callbacks.push(function() {
				button = document.getElementById("openbutton");
				button.removeAttribute("disabled");
				button.onclick = function() {
					// Открытие формы по нажатию кнопки
					form.open();
				};
			});
		}

	</script>
	<button type="button" id="openbutton" name="open" class="btn btn_text-large btn_img"></button>
<?php
}
?>
            <div class="cart-final__footer"><a href="<?php echo HOST_URL;?>" class="btn btn_text-large">Вернуться в каталог</a></div>
        </div>
    </div>
</div>


<?php include SKIN_PATH . 'footer.php'; ?>

</div>
<?php include SKIN_PATH . 'footer_includes.php';?>
<script>
$( document ).ready(function() {
	setTimeout(function(){
	  $('#openbutton').trigger('click');
	}, 1000);
});
</script>
</body>
</html>

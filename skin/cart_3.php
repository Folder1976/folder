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
            <div class="cart-final__footer"><a href="<?php echo HOST_URL;?>" class="btn btn_text-large">Вернуться в каталог</a></div>
        </div>
    </div>
</div>


<?php include SKIN_PATH . 'footer.php'; ?>

</div>
<?php include SKIN_PATH . 'footer_includes.php';?>
</body>
</html>

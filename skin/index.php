<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Магазин военного снаряжения ARMMA'; ?>
        <?php $description = 'В нашем магазине вы можете купить снаряжение с доставкой по России'; ?>
   
        <?php include_once SKIN_PATH.'header_main.php'; ?>    
        <?php include_once SKIN_PATH.'header_includes.php'; ?>    
</head>
<body>
<!--[if lt IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="page">
    <header class="page__header js-slider-bg">
    <div class="header header_home">
   
        <?php include SKIN_PATH . 'header_info.php'; ?>
        <?php include SKIN_PATH . 'header_menu.php'; ?>
        <?php include SKIN_PATH . 'header_search.php'; ?>

 
        </div>
    </div>

<?php
        $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
        $palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
        $berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
        $ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
        $mobile = strpos($_SERVER['HTTP_USER_AGENT'],"Mobile");
        $symb = strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");
        $operam = strpos($_SERVER['HTTP_USER_AGENT'],"Opera M");
        $htc = strpos($_SERVER['HTTP_USER_AGENT'],"HTC_");
        $fennec = strpos($_SERVER['HTTP_USER_AGENT'],"Fennec/");
        $winphone = strpos($_SERVER['HTTP_USER_AGENT'],"WindowsPhone");
        $wp7 = strpos($_SERVER['HTTP_USER_AGENT'],"WP7");
        $wp8 = strpos($_SERVER['HTTP_USER_AGENT'],"WP8");
        if ($ipad ||$ipod || $iphone || $android || $palmpre || $berry || $mobile || $symb || $operam || $htc || $fennec || $winphone || $wp7 || $wp8 === true) {
           // echo '111111111111';// <!--header('Location: http://m.site.ru');--> 
        }else{
?>
    <div class="h-slider js-slider-bg-small">
    <div class="row">
        <div class="medium-23 small-24 columns medium-centered">
            <div class="h-slider__list _fix" id="header-slider">
                <?php if(isset($large_banners)){ ?>
                <?php foreach($large_banners as $banner){ ?>
                        <div class="h-slider__item" data-img="<?php echo $banner['img']; ?>">
                            <h3 class="h-slider__title"><?php echo $banner['header']; ?></h3>
                            <div class="h-slider__summary">
                                <span class="h-slider__summary-text">
                                    <?php echo $banner['title']; ?>
                                </span>
                            </div>
        
                            <div class="row h-slider__footer">
                                <?php if($banner['price'] != ''){ ?>
                                <div class="medium-12 columns">
                                        <?php if($banner['url'] == ''){ ?>
                                                <div class="h-slider__price"><?php echo $banner['price']; ?></div>
                                        <?php }else{ ?>
                                                <a href="<?php echo $banner['url']; ?>" target="_blank"><div class="h-slider__price"><?php echo $banner['price']; ?></div></a>
                                        <?php }?>
                                </div>
                                <?php } ?>
                                <div class="medium-12 columns text-right small-only-text-left">
                                    <div class="h-slider__hint"><?php echo $banner['slogan']; ?></div>
                                </div>
                        </div>
                </div>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
    <?php } ?>

</header>

    <?php include SKIN_PATH . 'body_home_homecat.php'; ?>
    <?php include SKIN_PATH . 'body_brands.php'; ?>
    <?php include SKIN_PATH . 'body_sections.php'; ?>
    <?php include SKIN_PATH . 'footer.php'; ?>
 
</div>

<?php include SKIN_PATH . 'footer_includes.php';?>
</body>
</html>

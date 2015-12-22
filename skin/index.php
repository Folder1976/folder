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
    <div class="h-slider js-slider-bg-small">
    <div class="row">
        <div class="medium-23 small-24 columns medium-centered">
            <div class="h-slider__list _fix" id="header-slider">
                <div class="h-slider__item" data-img="<?php echo SKIN_URL; ?>img/banners/banner_1920_1200_1.jpg">
                    <h3 class="h-slider__title">Качество.<br>Надежность.<br>Точность.</h3>
                    <div class="h-slider__summary">
                        <span class="h-slider__summary-text">
                            Тактическое снаряжение и&nbsp;оружие
                        </span>
                    </div>

                    <div class="row h-slider__footer">
                        <div class="medium-12 columns">
                            <div class="h-slider__price"><small>от</small> 15 800 ₽</div>
                        </div>

                        <div class="medium-12 columns text-right small-only-text-left">
                            <div class="h-slider__hint">*ЭКСКЛЮЗИВНАЯ КОЛЛЕКЦИЯ ОТ ARMA.RU</div>
                        </div>
                    </div>
                </div>

                <div class="h-slider__item" data-img="<?php echo SKIN_URL; ?>img/banners/banner_1920_1200_2.jpg">
                    <h3 class="h-slider__title">Зимняя<br>распродажа</h3>
                    <div class="h-slider__summary">
                        <span class="h-slider__summary-text">
                            Скидки на все виды оптики до&nbsp;50%
                        </span>
                    </div>

                    <div class="row h-slider__footer">
                        <div class="medium-12 columns">
                            <div class="h-slider__price"><small>от</small> 15 800 ₽</div>
                        </div>

                        <div class="medium-12 columns text-right small-only-text-left">
                            <div class="h-slider__hint">*ЭКСКЛЮЗИВНАЯ КОЛЛЕКЦИЯ ОТ ARMA.RU</div>
                        </div>
                    </div>
                </div>

                <div class="h-slider__item" data-img="<?php echo SKIN_URL; ?>img/banners/banner_1920_1200_1.jpg">
                    <h3 class="h-slider__title">Третий слайд.<br>Надежность.<br>Точность.</h3>
                    <div class="h-slider__summary">
                        <span class="h-slider__summary-text">
                            Тактическое снаряжение и&nbsp;оружие
                        </span>
                    </div>

                    <div class="row h-slider__footer">
                        <div class="medium-12 columns">
                            <div class="h-slider__price"><small>от</small> 15 800 ₽</div>
                        </div>

                        <div class="medium-12 columns text-right small-only-text-left">
                            <div class="h-slider__hint">*ЭКСКЛЮЗИВНАЯ КОЛЛЕКЦИЯ ОТ ARMA.RU</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</header>

    <?php include SKIN_PATH . 'body_home_homecat.php'; ?>
    <?php include SKIN_PATH . 'body_brands.php'; ?>
    <?php include SKIN_PATH . 'body_sections.php'; ?>
    <?php include SKIN_PATH . 'footer.php'; ?>
 
</div>

<?php include SKIN_PATH . 'footer_includes.php';?>
</body>
</html>

<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Мой гардероб'; ?>
        <?php $description = 'В этом разделе вы можете сохранить свои мерки'; ?>
    
        <?php include_once SKIN_PATH.'header_main.php'; ?>    
        <?php include_once SKIN_PATH.'header_includes.php'; ?>
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
            
            <div class="breadcrumb breadcrumb_last">
                <span class="breadcrumb__name">Личный кабинет</span>
            </div>
            
        </div>
    </div>
</div>

<div class="section">
    <div class="row">
        <div class="small-24 columns section__header">
            <h2 class="section__title">Личный кабинет</h2>
        </div>
    </div>
    <div class="row">
    <div class="large-20 medium-22 medium-centered columns">
        <div class="a-menu">
            
            
            <a href="account_personal.html" class="a-menu__link">Личные данные</a>
            
            
            
            <a href="account_cart.html" class="a-menu__link">Корзина</a>
            
            
            
            <a href="account_orders.html" class="a-menu__link">Список заказов</a>
            
            
            
            <a href="account_password.html" class="a-menu__link">Изменить пароль</a>
            
            
            
            <span class="a-menu__link a-menu__link_active">Мой гардероб</span>
            
            
        </div>
    </div>
</div>

    <div class="row">
        <div class="large-20 medium-22 medium-centered columns">
            <h3 class="section__sub-title">Мой гардероб <img width="70" src="<?php echo SKIN_URL?>img/reconstruction.png"><font color=red>(Данный раздел находится в разработке)</font></h3>

            <form action="" class="form wardrobe">
                <div class="row">
                    <div class="large-9 medium-10 columns wardrobe__col">
                        <div class="wardrobe__title">Ваши данные</div>
                        <div class="row form__row">
                            <label for="wardrobe-head" class="small-12 columns form__label"><span class="form__label-name">Объём головы:</span></label>

                            <div class="medium-7 small-8 columns">
                                <input type="text" class="form__input" id="wardrobe-head" value="32">
                            </div>

                            <div class="medium-5 small-4 columns">
                                <span class="form__label"><span class="form__label-name">см</span></span>
                            </div>
                        </div>

                        <div class="row form__row">
                            <label for="wardrobe-height" class="small-12 columns form__label"><span class="form__label-name">Рост:</span></label>

                            <div class="medium-7 small-8 columns">
                                <input type="text" class="form__input" id="wardrobe-height" value="188">
                            </div>

                            <div class="medium-5 small-4 columns">
                                <span class="form__label"><span class="form__label-name">см</span></span>
                            </div>
                        </div>

                        <div class="row form__row">
                            <label for="wardrobe-chest" class="small-12 columns form__label"><span class="form__label-name">Объём груди:</span></label>

                            <div class="medium-7 small-8 columns">
                                <input type="text" class="form__input" id="wardrobe-chest" value="92">
                            </div>

                            <div class="medium-5 small-4 columns">
                                <span class="form__label"><span class="form__label-name">см</span></span>
                            </div>
                        </div>

                        <div class="row form__row">
                            <label for="wardrobe-waist" class="small-12 columns form__label"><span class="form__label-name">Объём талии:</span></label>

                            <div class="medium-7 small-8 columns">
                                <input type="text" class="form__input" id="wardrobe-waist" value="63">
                            </div>

                            <div class="medium-5 small-4 columns">
                                <span class="form__label"><span class="form__label-name">см</span></span>
                            </div>
                        </div>

                        <div class="row form__row">
                            <label for="wardrobe-hips" class="small-12 columns form__label"><span class="form__label-name">Объём бедер:</span></label>

                            <div class="medium-7 small-8 columns">
                                <input type="text" class="form__input" id="wardrobe-hips" value="95">
                            </div>

                            <div class="medium-5 small-4 columns">
                                <span class="form__label"><span class="form__label-name">см</span></span>
                            </div>
                        </div>

                        <div class="row form__row">
                            <label for="wardrobe-trouser-leg" class="small-12 columns form__label"><span class="form__label-name">Длина внутр. шва штанины:</span></label>

                            <div class="medium-7 small-8 columns">
                                <input type="text" class="form__input" id="wardrobe-trouser-leg" value="175">
                            </div>

                            <div class="medium-5 small-4 columns">
                                <span class="form__label"><span class="form__label-name">см</span></span>
                            </div>
                        </div>

                        <div class="row form__row">
                            <label for="wardrobe-leg" class="small-12 columns form__label"><span class="form__label-name">Размер обуви:</span></label>

                            <div class="medium-7 small-8 columns">
                                <input type="text" class="form__input" id="wardrobe-leg" value="40">
                            </div>

                            <div class="medium-5 small-4 columns">
                                <span class="form__label"><span class="form__label-name"></span></span>
                            </div>
                        </div>

                        <div class="row form__row">
                            <label for="wardrobe-leg-fullness" class="small-12 columns form__label"><span class="form__label-name">Полнота ноги:</span></label>

                            <div class="medium-7 small-8 columns">
                                <input type="text" class="form__input" id="wardrobe-leg-fullness" value="42">
                            </div>

                            <div class="medium-5 small-4 columns">
                                <span class="form__label"><span class="form__label-name">см</span></span>
                            </div>
                        </div>

                        <div class="form__row">
                            <button type="button" class="btn btn_light">Подобрать размер</button>
                        </div>
                    </div>

                    <div class="large-9 medium-10 columns wardrobe__col text-center">
                        <div class="wardrobe__title">Подходящие размеры</div>
                        <div class="form__row">
                            <div class="wardrobe__sub-title">Головной убор</div>
                            <div class="row">
                                <div class="small-6 columns"><span class="wardrobe__name">RU</span><span class="wardrobe__value">58</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">GER</span><span class="wardrobe__value">2</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">EU</span><span class="wardrobe__value">VL</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">US</span><span class="wardrobe__value">XXL</span></div>
                            </div>
                        </div>

                        <div class="form__row">
                            <div class="wardrobe__sub-title">Куртки, кителя</div>
                            <div class="row">
                                <div class="small-6 columns"><span class="wardrobe__name">RU</span><span class="wardrobe__value">58</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">GER</span><span class="wardrobe__value">2</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">EU</span><span class="wardrobe__value">VL</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">US</span><span class="wardrobe__value">XXL</span></div>
                            </div>
                        </div>

                        <div class="form__row">
                            <div class="wardrobe__sub-title">Брюки</div>
                            <div class="row">
                                <div class="small-6 columns"><span class="wardrobe__name">RU</span><span class="wardrobe__value">58</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">GER</span><span class="wardrobe__value">2</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">EU</span><span class="wardrobe__value">VL</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">US</span><span class="wardrobe__value">XXL</span></div>
                            </div>
                        </div>

                        <div class="form__row">
                            <div class="wardrobe__sub-title">Обувь</div>
                            <div class="row">
                                <div class="small-6 columns"><span class="wardrobe__name">RU</span><span class="wardrobe__value">58</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">GER</span><span class="wardrobe__value">2</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">EU</span><span class="wardrobe__value">VL</span></div>
                                <div class="small-6 columns"><span class="wardrobe__name">US</span><span class="wardrobe__value">XXL</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="form__row">
                <button type="submit" class="btn btn_text-large">Созранить изменения</button>
            </div>
        </div>
    </div>
</div>


  <?php include SKIN_PATH . 'footer.php'; ?> 
</div>
<?php include SKIN_PATH . 'footer_includes.php';?></body>
</html>

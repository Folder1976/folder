<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Список заказов'; ?>
        <?php $description = 'Список заказов'; ?>
    
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
            <span class="a-menu__link a-menu__link_active">Список заказов</span>
            <a href="account_password.html" class="a-menu__link">Изменить пароль</a>
            <a href="account_wardrobe.html" class="a-menu__link">Мой гардероб</a>
        </div>
    </div>
</div>

    <div class="row">
        <div class="large-20 medium-22 medium-centered columns">
            <h3 class="section__sub-title">Список заказов <img width="70" src="<?php echo SKIN_URL?>img/reconstruction.png"><font color=red>(Данный раздел находится в разработке)</font></h3>

            <div class="orders">
                <div class="orders__header">
                    <div class="order__col order__col_action"></div>
                    <div class="order__col">Номер заказа</div>
                    <div class="order__col">Дата заказа</div>
                    <div class="order__col">Кол-во товаров</div>
                    <div class="order__col">Сумма заказа</div>
                    <div class="order__col">Статус заказа</div>
                </div>

                <div class="order">
                    <div class="order__header">
                        <div class="order__col order__col_action">
                            <div class="order__action order__action_show"><span class="fa fa-eye"></span></div>
                            <div class="order__action order__action_close"><span class="fa fa-times"></span></div>
                        </div>
                        <div class="order__col">№23244343</div>
                        <div class="order__col">15.09.2015</div>
                        <div class="order__col">2<span class="order__small-i-show"> тов.</span></div>
                        <div class="order__col">258 369 ₽</div>
                        <div class="order__col"><span class="order__status order__status_inwork">В обработке</span></div>
                    </div>

                    <div class="order__content">
                        <div class="cart">
                            <header class="cart__header">
                                <div class="cart__col small-3">
                                    Артикул
                                </div>

                                <div class="cart__col small-7">
                                    Товар
                                </div>

                                <div class="cart__col small-3">
                                    Производитель
                                </div>

                                <div class="cart__col small-4">
                                    Цена за ед.
                                </div>

                                <div class="cart__col small-3">
                                    Кол-во
                                </div>

                                <div class="cart__col small-4">
                                    Сумма
                                </div>
                            </header>

                            <div class="cart__row cart__row_small">
                                <div class="cart__col small-3 cart__col_small-block">
                                    <span><span class="show-for-small-only">Артикул: </span>5645891</span>
                                </div>

                                <div class="cart__col cart__col_small-block small-7">
                                    <div class="cart__prod">
                                        <div class="cart__prod-col small-8">
                                            <div class="img-fix"><img src="img/catalog/products/thumb_300_300_10.jpg" alt=""></div>
                                        </div>
                                        <div class="cart__prod-col small-16">
                                            <a href="#">Condor Drop Leg Dump Pouch Multicam</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart__col small-3 cart__col_small-hide">
                                    <div class="img-fix"><img src="img/brends/brend2.jpg" alt=""></div>
                                </div>

                                <div class="cart__col small-4 cart__col_small-hide">
                                    <div class="cart__price">10 300 ₽</div>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_counter small-4">
                                    <span>10<span class="show-for-small-only"> шт.</span></span>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_total small-4">
                                    <div class="cart__price">103 000 ₽</div>
                                </div>
                            </div>

                            <div class="cart__row cart__row_small">
                                <div class="cart__col small-3 cart__col_small-block">
                                    <span><span class="show-for-small-only">Артикул: </span>5645891</span>
                                </div>

                                <div class="cart__col cart__col_small-block small-7">
                                    <div class="cart__prod">
                                        <div class="cart__prod-col small-8">
                                            <div class="img-fix"><img src="img/catalog/products/thumb_300_300_7.jpg" alt=""></div>
                                        </div>
                                        <div class="cart__prod-col small-16">
                                            <a href="#">Condor Drop Leg Dump Pouch Multicam</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart__col small-3 cart__col_small-hide">
                                    <div class="img-fix"><img src="img/brends/brend2.jpg" alt=""></div>
                                </div>

                                <div class="cart__col small-4 cart__col_small-hide">
                                    <div class="cart__price">11 000 ₽</div>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_counter small-4">
                                    <span>4<span class="show-for-small-only"> шт.</span></span>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_total small-4">
                                    <div class="cart__price">24 000 ₽</div>
                                </div>
                            </div>

                            <div class="cart__row">
                                <div class="cart__col cart__col_small-hide small-8 text-left" style="vertical-align: top;">
                                    Данные покупателя:
                                </div>

                                <div class="cart__col cart__col_small-block small-16 text-left">
                                    Александр Петрович, г.Таганрог, ул. Морозова 27, кв. 195<br>
                                    +7 (805) 022 54 646
                                </div>
                            </div>
                        </div>

                        <div class="row cart__footer">
                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn">Повторить заказ</button>
                            </div>

                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn">Положить в корзину</button>
                            </div>

                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn btn_link"><span class="fa fa-print"></span><span class="btn__name">Распечатать</span></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order">
                    <div class="order__header">
                        <div class="order__col order__col_action">
                            <div class="order__action order__action_show"><span class="fa fa-eye"></span></div>
                            <div class="order__action order__action_close"><span class="fa fa-times"></span></div>
                        </div>
                        <div class="order__col">№23244343</div>
                        <div class="order__col">15.09.2015</div>
                        <div class="order__col">2</div>
                        <div class="order__col">258 369 ₽</div>
                        <div class="order__col"><span class="order__status order__status_done">Выполнен</span></div>
                    </div>

                    <div class="order__content">
                        <div class="cart">
                            <header class="cart__header">
                                <div class="cart__col small-3">
                                    Артикул
                                </div>

                                <div class="cart__col small-7">
                                    Товар
                                </div>

                                <div class="cart__col small-3">
                                    Производитель
                                </div>

                                <div class="cart__col small-4">
                                    Цена за ед.
                                </div>

                                <div class="cart__col small-3">
                                    Кол-во
                                </div>

                                <div class="cart__col small-4">
                                    Сумма
                                </div>
                            </header>

                            <div class="cart__row cart__row_small">
                                <div class="cart__col small-3 cart__col_small-block">
                                    <span><span class="show-for-small-only">Артикул: </span>5645891</span>
                                </div>

                                <div class="cart__col cart__col_small-block small-7">
                                    <div class="cart__prod">
                                        <div class="cart__prod-col small-8">
                                            <div class="img-fix"><img src="img/catalog/products/thumb_300_300_10.jpg" alt=""></div>
                                        </div>
                                        <div class="cart__prod-col small-16">
                                            <a href="#">Condor Drop Leg Dump Pouch Multicam</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart__col small-3 cart__col_small-hide">
                                    <div class="img-fix"><img src="img/brends/brend2.jpg" alt=""></div>
                                </div>

                                <div class="cart__col small-4 cart__col_small-hide">
                                    <div class="cart__price">10 300 ₽</div>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_counter small-4">
                                    <span>10<span class="show-for-small-only"> шт.</span></span>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_total small-4">
                                    <div class="cart__price">103 000 ₽</div>
                                </div>
                            </div>

                            <div class="cart__row cart__row_small">
                                <div class="cart__col small-3 cart__col_small-block">
                                    <span><span class="show-for-small-only">Артикул: </span>5645891</span>
                                </div>

                                <div class="cart__col cart__col_small-block small-7">
                                    <div class="cart__prod">
                                        <div class="cart__prod-col small-8">
                                            <div class="img-fix"><img src="img/catalog/products/thumb_300_300_7.jpg" alt=""></div>
                                        </div>
                                        <div class="cart__prod-col small-16">
                                            <a href="#">Condor Drop Leg Dump Pouch Multicam</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart__col small-3 cart__col_small-hide">
                                    <div class="img-fix"><img src="img/brends/brend2.jpg" alt=""></div>
                                </div>

                                <div class="cart__col small-4 cart__col_small-hide">
                                    <div class="cart__price">11 000 ₽</div>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_counter small-4">
                                    <span>4<span class="show-for-small-only"> шт.</span></span>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_total small-4">
                                    <div class="cart__price">24 000 ₽</div>
                                </div>
                            </div>

                            <div class="cart__row">
                                <div class="cart__col cart__col_small-hide small-8 text-left" style="vertical-align: top;">
                                    Данные покупателя:
                                </div>

                                <div class="cart__col cart__col_small-block small-16 text-left">
                                    Александр Петрович, г.Таганрог, ул. Морозова 27, кв. 195<br>
                                    +7 (805) 022 54 646
                                </div>
                            </div>
                        </div>

                        <div class="row cart__footer">
                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn">Повторить заказ</button>
                            </div>

                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn">Положить в корзину</button>
                            </div>

                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn btn_link"><span class="fa fa-print"></span><span class="btn__name">Распечатать</span></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order">
                    <div class="order__header">
                        <div class="order__col order__col_action">
                            <div class="order__action order__action_show"><span class="fa fa-eye"></span></div>
                            <div class="order__action order__action_close"><span class="fa fa-times"></span></div>
                        </div>
                        <div class="order__col">№23244343</div>
                        <div class="order__col">15.09.2015</div>
                        <div class="order__col">2</div>
                        <div class="order__col">258 369 ₽</div>
                        <div class="order__col"><span class="order__status order__status_fail">Отменен</span></div>
                    </div>

                    <div class="order__content">
                        <div class="cart">
                            <header class="cart__header">
                                <div class="cart__col small-3">
                                    Артикул
                                </div>

                                <div class="cart__col small-7">
                                    Товар
                                </div>

                                <div class="cart__col small-3">
                                    Производитель
                                </div>

                                <div class="cart__col small-4">
                                    Цена за ед.
                                </div>

                                <div class="cart__col small-3">
                                    Кол-во
                                </div>

                                <div class="cart__col small-4">
                                    Сумма
                                </div>
                            </header>

                            <div class="cart__row cart__row_small">
                                <div class="cart__col small-3 cart__col_small-block">
                                    <span><span class="show-for-small-only">Артикул: </span>5645891</span>
                                </div>

                                <div class="cart__col cart__col_small-block small-7">
                                    <div class="cart__prod">
                                        <div class="cart__prod-col small-8">
                                            <div class="img-fix"><img src="img/catalog/products/thumb_300_300_10.jpg" alt=""></div>
                                        </div>
                                        <div class="cart__prod-col small-16">
                                            <a href="#">Condor Drop Leg Dump Pouch Multicam</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart__col small-3 cart__col_small-hide">
                                    <div class="img-fix"><img src="img/brends/brend2.jpg" alt=""></div>
                                </div>

                                <div class="cart__col small-4 cart__col_small-hide">
                                    <div class="cart__price">10 300 ₽</div>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_counter small-4">
                                    <span>10<span class="show-for-small-only"> шт.</span></span>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_total small-4">
                                    <div class="cart__price">103 000 ₽</div>
                                </div>
                            </div>

                            <div class="cart__row cart__row_small">
                                <div class="cart__col small-3 cart__col_small-block">
                                    <span><span class="show-for-small-only">Артикул: </span>5645891</span>
                                </div>

                                <div class="cart__col cart__col_small-block small-7">
                                    <div class="cart__prod">
                                        <div class="cart__prod-col small-8">
                                            <div class="img-fix"><img src="img/catalog/products/thumb_300_300_7.jpg" alt=""></div>
                                        </div>
                                        <div class="cart__prod-col small-16">
                                            <a href="#">Condor Drop Leg Dump Pouch Multicam</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="cart__col small-3 cart__col_small-hide">
                                    <div class="img-fix"><img src="img/brends/brend2.jpg" alt=""></div>
                                </div>

                                <div class="cart__col small-4 cart__col_small-hide">
                                    <div class="cart__price">11 000 ₽</div>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_counter small-4">
                                    <span>4<span class="show-for-small-only"> шт.</span></span>
                                </div>

                                <div class="cart__col cart__col_small-ib cart__col_total small-4">
                                    <div class="cart__price">24 000 ₽</div>
                                </div>
                            </div>

                            <div class="cart__row">
                                <div class="cart__col cart__col_small-hide small-8 text-left" style="vertical-align: top;">
                                    Данные покупателя:
                                </div>

                                <div class="cart__col cart__col_small-block small-16 text-left">
                                    Александр Петрович, г.Таганрог, ул. Морозова 27, кв. 195<br>
                                    +7 (805) 022 54 646
                                </div>
                            </div>
                        </div>

                        <div class="row cart__footer">
                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn">Повторить заказ</button>
                            </div>

                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn">Положить в корзину</button>
                            </div>

                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn btn_link"><span class="fa fa-print"></span><span class="btn__name">Распечатать</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


  <?php include SKIN_PATH . 'footer.php'; ?> 
    <span class="btn-to-top"><img src="img/totop.png" alt=""></span>
</div>
<?php include SKIN_PATH . 'footer_includes.php';?>
</body>
</html>

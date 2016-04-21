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
<?php

//echo "<pre>";  print_r(var_dump( $orders )); echo "</pre>";

//Цвета статусов
$color_table = array(
                     '1' => '#000000',
                     '2' => '#FFD700',
                     '3' => '#7FFF00',
                     '4' => '#008000',
                     '5' => '#008000',
                     '6' => '#008000',
                     '7' => '#008000',
                     '8' => '#008000',
                     '9' => '#00BFFF',
                     '10' => '#008000',
                     '11' => '#008000',
                     '12' => '#008000',
                     '13' => '#008000',
                     '14' => '#008000',
                     '15' => '#F0E68C',
                     '16' => '#008000',
                     '17' => '#FF1493',
                     '18' => '#FF1493',
                     '19' => '#FF1493',
                     
                     );

?>
    <div class="row">
        <div class="large-20 medium-22 medium-centered columns">
            <h3 class="section__sub-title">Список заказов</h3>

            <div class="orders">
                <div class="orders__header">
                    <div class="order__col order__col_action"></div>
                    <div class="order__col">Номер заказа</div>
                    <div class="order__col">Дата заказа</div>
                    <div class="order__col">Кол-во товаров</div>
                    <div class="order__col">Сумма заказа</div>
                    <div class="order__col">Статус заказа</div>
                </div>

            <?php foreach($orders as $id => $order){ $main = $order[0]?>   
                <div class="order">
                    <?php
                    //echo "<pre>";  print_r(var_dump( $order )); echo "</pre>";
                    ?>
                    <div class="order__header">
                        <div class="order__col order__col_action">
                            <?php if($main['operation_status_id'] != 9){ ?>
                            <div class="order__action order__action_show"><span class="fa fa-eye"></span></div>
                            <div class="order__action order__action_close"><span class="fa fa-times"></span></div>
                            <?php } ?>
                        </div>  
                        <div class="order__col">№<?php echo $id; ?></div>
                        <div class="order__col"><?php echo $main['operation_data'];?></div>
                        <div class="order__col">
                            <?php if($main['operation_status_id'] != 9){ ?>
                                <?php echo $order['items'];?><span class="order111__small-i-show"> тов.</span>
                            <?php }else{ ?>
                                <span class="order111__small-i-show">Ваша оплата:</span>
                            <?php } ?>
                        </div>
                        <div class="order__col">
                            <?php if($main['operation_status_id'] != 9){ ?>
                                <?php echo number_format($order['summ'],0,'',' ');?> ₽
                            <?php }else{ ?>
                                <?php echo number_format($main['operation_summ'],0,'',' ');?> ₽
                            <?php } ?>
                        </div>
                        <div class="order__col"><span class="order__status" style="color:<?php echo $color_table[$main['operation_status_id']];?>;"><?php echo $main['operation_status_name'];?></span></div>
                    </div>
                
                    <?php if($main['operation_status_id'] != 9){ ?>
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
                        <?php foreach($order as $index => $product){ ?>
                            <?php if(is_numeric($index)) {?>
                                <div class="cart__row cart__row_small">
                                    <div class="cart__col small-3 cart__col_small-block">
                                        <span><span class="show-for-small-only">Артикул: </span><?php echo $product['tovar_artkl']; ?></span>
                                    </div>
    
                                    <div class="cart__col cart__col_small-block small-7">
                                        <div class="cart__prod">
                                            <div class="cart__prod-col small-8">
                                                <div class="img-fix"><img src="<?php echo $product['pic']; ?>" alt="Картинка <?php echo $product['tovar_name_1']; ?>"></div>
                                            </div>
                                            <div class="cart__prod-col small-16">
                                                <a href="#"><?php echo $product['tovar_name_1']; ?></a>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="cart__col small-3 cart__col_small-hide">
                                        <a href="/brend/<?php echo $product['brand_code']; ?>">
                                            <div class="img-fix"><img src="/resources/brends/<?php echo $product['brand_code']; ?>.png" alt=""></div>
                                        </a>
                                    </div>
    
                                    <div class="cart__col small-4 cart__col_small-hide">
                                        <div class="cart__price" style="text-align: right;"><?php echo $product['price']; ?> ₽</div>
                                    </div>
    
                                    <div class="cart__col cart__col_small-ib cart__col_counter small-4">
                                        <span><?php echo $product['item']; ?><span class="show-for-small-only"> шт.</span></span>
                                    </div>
    
                                    <div class="cart__col cart__col_small-ib cart__col_total small-4">
                                        <div class="cart__price" style="text-align: right;"><?php echo $product['summ']; ?> ₽</div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                   
                    
                    
                                <div class="cart__row">
                                    <div class="cart__col cart__col_small-hide small-8 text-left" style="vertical-align: top;">
                                        Данные покупателя:
                                    </div>
    
                                    <div class="cart__col cart__col_small-block small-16 text-left">
                                        <?php echo str_replace('*', '<br>',$main['operation_customer_memo']);?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                        <!--div class="row cart__footer">
                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn">Повторить заказ</button>
                            </div>

                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn">Положить в корзину</button>
                            </div>

                            <div class="medium-8 columns cart__footer-col">
                                <button type="button" class="btn btn_link"><span class="fa fa-print"></span><span class="btn__name">Распечатать</span></button>
                            </div>
                        </div-->
                    
                </div>
            <?php } ?>
                <!--div class="order__col"><span class="order__status order__status_done">Выполнен</span></div>
                <div class="order__col"><span class="order__status order__status_fail">Отменен</span></div-->

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

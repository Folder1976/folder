<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Корзина'; ?>
        <?php $description = 'Корзина'; ?>
    
        <?php include_once SKIN_PATH.'header_main.php'; ?>    
        <?php include_once SKIN_PATH.'header_includes.php'; ?>
        
        <?php
                //header("Content-Type: text/html; charset=UTF-8");
                //echo "<pre>";  print_r(var_dump( $cart )); echo "</pre>";
        
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
            <span class="a-menu__link a-menu__link_active">Корзина</span>
            <a href="account_orders.html" class="a-menu__link">Список заказов</a>
            <a href="account_password.html" class="a-menu__link">Изменить пароль</a>
            <a href="account_wardrobe.html" class="a-menu__link">Мой гардероб</a>
        </div>
    </div>
</div>

    <div class="row">
        <div class="large-20 medium-22 medium-centered columns">
            <h3 class="section__sub-title">Корзина</h3>
            <div class="cart">
                <header class="cart__header">
                    <div class="cart__col small-2">

                    </div>

                    <div class="cart__col small-10">
                        Товар
                    </div>

                    <div class="cart__col small-4">
                        Цена за ед.
                    </div>

                    <div class="cart__col small-4">
                        Количество
                    </div>

                    <div class="cart__col small-4">
                        Сумма
                    </div>
                </header>

                <?php $summ = 0; ?>
                <?php if(isset($cart) AND $cart){ ?>
                <?php foreach($cart as $product){ ?>
                <?php $total= (int)$product['order_item'] * (float)$product['product_price'];; ?>
                <?php $summ += $total;?>
                <div class="cart__row" id="row<?php echo $product['line_id'];?>">
                    <div class="cart__col small-2 cart__col_small-hide">
                        <div class="cart__action cart__action_del"><span class="fa fa-times dell_product" id="<?php echo $product['line_id'];?>"></span></div>
                    </div>

                    <div class="cart__col cart__col_small-block small-10">
                        <div class="cart__prod">
                            <div class="cart__prod-col small-8">
                                <div class="img-fix"><img src="<?php echo $product['img'];?>" alt="Картинка <?php echo $product['name'];?>"></div>
                            </div>
                            <div class="cart__prod-col small-16">
                                <a href="<?php echo HOST_URL.'/'.$product['seo_alias'];?>"><?php echo $product['tovar_artkl'];?> (Доставка <?php echo $product['delivery_days'];?> дн.)<br>
                                                                                        <?php echo $product['name'];?></a>
                            </div>
                        </div>
                    </div>

                    <div class="cart__col small-4 cart__col_small-hide">
                        <div class="cart__price"><?php echo $product['product_price'];?> ₽</div>
                    </div>

                    <div class="cart__col cart__col_small-ib cart__col_counter small-4">
                        <div class="counter" id="<?php echo $product['line_id'];?>">
                            <button type="button" class="counter__minus " >&minus;</button>
                            <input type="text" class="counter__input" id="items<?php echo $product['line_id'];?>" min="0" max="999" value="<?php echo $product['order_item'];?>">
                            <button type="button" class="counter__plus " >&plus;</button>
                        </div>
                    </div>

                    <div class="cart__col cart__col_small-ib cart__col_total small-4">
                        <div class="cart__price"><span class="total" id="total<?php echo $product['line_id'];?>"><?php echo $total;?></span> ₽</div>
                    </div>

                    <div class="cart__small-col">
                        <div class="cart__action cart__action_del"><span class="fa fa-times"></span></div>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>

             <div class="row cart__footer">
                <div class="medium-12 columns cart__footer-col">
                    <a href="<?php echo HOST_URL;?>" class="btn btn_light">Вернуться в каталог</a>
                </div>

                <div class="medium-12 columns cart__footer-col text-right small-only-text-left">
                    <span class="cart__total-price"><span class="cart__total_all"><?php echo $summ;?></span> ₽</span>
                    <?php if(isset($cart) AND $cart){ ?>
					<a href="<?php echo HOST_URL;?>/cart.html?kredit"><button type="button" class="btn btn_text-large btn_img">&nbsp;</button></a><br\>&nbsp;<br\>
                    <a href="<?php echo HOST_URL;?>/cart.html"><button type="button" class="btn btn_text-large">Оформить</button></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>


 <?php include SKIN_PATH . 'footer.php'; ?> 
    <span class="btn-to-top"><img src="skin/img/totop.png" alt=""></span>
</div>
<?php include SKIN_PATH . 'footer_includes.php';?>
</body>
</html>
<script>
        
        $(document).on('click', '.dell_product', function(){
                var line = $(this).attr('id');
               
                $('#items'+line).val('0');
                $('#items'+line).trigger('change');
                
                $('#row'+line).remove();
                
        });
        
        $('.counter').bind('click change', function(){
                
                var line = $(this).attr('id');
                var val = $('#items'+line).val();
            
                $.ajax({
                        type: "POST",
                        url: "ajax/update_cart.php",
                        dataType: "json",
                        data: "line="+line+"&val="+val,
                        success: function(msg){
                                if (msg.err == false) {
                                        $('#total'+line).html(msg.total);
                                        $('.cart__total_all').html(msg.summ);
                                        $('.num-cart__total').html(msg.summ);
                                }else{
                                        alert(msg.msg);
                                }
                                //console.log(msg );
                        }
                });
                
        });
</script>
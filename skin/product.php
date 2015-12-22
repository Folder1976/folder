<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Купить '. $product['name']; ?>
        <?php $description = 'В этом разделе вы можете купить '. $product['name'] . ' с доставкой по России'; ?>
    
        <?php include_once SKIN_PATH.'header_main.php'; ?>    
        <?php include_once SKIN_PATH.'header_includes.php'; ?>
        <?php
                global $Month_r;
                //header("Content-Type: text/html; charset=UTF-8");
                //echo "<pre>";  print_r(var_dump( $product )); echo "</pre>";
                $breadcrumbs    = $product['breadcrumb'];
                $photos         = $product['photos'];
                if(isset($product['tovar_video_url']))
                        $video          = $product['tovar_video_url'];
                
                if(isset($product['memo']))
                        $memo           = $product['memo'];
                
                if(isset($product['tovar_size_table']))
                        $tovar_size_table = $product['tovar_size_table'];
                
                if(isset($product['comments']))
                        $comments   = $product['comments'];
                
                if(isset($product['size']))
                        $size       = $product['size'];
                
                if(isset($product['min_price']))
                        $min_price  = $product['min_price'];
                
                $old_price = '';
                
                
                unset($product['min_price']);
                unset($product['size']);
                unset($product['comments']);
                unset($product['tovar_size_table']);
                unset($product['tovar_video_url']);
                unset($product['memo']);
                unset($product['photos']);
                unset($product['breadcrumb']);
                //echo "<pre>";  print_r(var_dump( $product['size'] )); echo "</pre>";
        
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
                <a href="<?php echo HOST_URL;?>/index.php" class="breadcrumb__name"><span class="fa fa-home"></span></a>
            </div>
             <?php if(isset($breadcrumbs)){ ?>
                <?php foreach($breadcrumbs as $id => $breadcrumb){ ?>
                        
                        <?php if($id != 0){ ?>
                                <div class="breadcrumb">
                                        <a href="<?php echo HOST_URL.'/'.$breadcrumb['url']; ?>.html" class="breadcrumb__name"><?php echo $breadcrumb['name']; ?></a>
                                </div>
                        <?php } ?>                
                <?php } ?>
                <div class="breadcrumb breadcrumb_last">
                        <span class="breadcrumb__name"><?php echo $product['name']; ?></span>
                </div>
                               
            <?php } ?>
        </div>
    </div>
</div>

<div class="row section product">
    <div class="medium-12 columns">
        <header class="row section__header show-for-small-only">
            <div class="medium-19 columns">
                <h2 class="section__sub-title"><?php echo $product['name']; ?></h2>
            </div>
            <div class="medium-5 columns text-right small-only-text-left">
                <img src="<?php echo HOST_URL;?>/resources/brends/<?php echo $product['brand_code']; ?>.jpg" alt="Картинка <?php echo $product['brand_name']; ?>">
            </div>
        </header>

        <div class="row gallery">
            <div class="small-19 columns gallery__col">
                <div class="gallery__stage gallery__stage_fix" id="gallery-stage">
                        <?php $count=0; ?>
                        <?php foreach($photos as $photo){ ?>
                                <a href="<?php echo $photo;?>" class="gallery__stage-link gallery__stage-link_sale">
                                    <img src="<?php echo str_replace('large','medium',$photo);?>" alt="Картинка <?php echo $product['name']; if($count > 0 ){ echo $count;}else{echo '';}?>">
                                </a>
                        <?php $count++; ?>
                        <?php } ?>
                </div>
            </div>

            <div class="small-5 columns gallery__col" id="gallery-thumbs">
                <?php $count=0; ?>
                <?php foreach($photos as $photo){ ?>
                        <a data-slide-index="<?php echo $count;?>" href="" class="gallery__thumb" style="background-image: url(<?php echo str_replace('large','small',$photo);?>);"></a>
                 <?php $count++; ?>
                <?php } ?>
            </div>
        </div>

        <div class="likes">
            <div class="likes__inner">
                <span class='like st_facebook_hcount' displayText='Facebook'></span>
                <span class='like st_twitter_hcount' displayText='Tweet'></span>
                <span class='like st_vkontakte_hcount' displayText='Vkontakte'></span>
                <span class='like st_googleplus_hcount' displayText='Google +'></span>
            </div>
        </div>
    </div>

    <div class="medium-12 columns section__col-content">
        <header class="row section__header hide-for-small-only">
            <div class="medium-19 columns">
                <h1 class="section__sub-title"><?php echo $product['name']; ?></h1>
            </div>
                
            <div class="medium-5 columns text-right small-only-text-left">
                <img src="<?php echo HOST_URL;?>/resources/brends/<?php echo $product['brand_code']; ?>.jpg" alt="Купить <?php echo $product['brand_name']; ?>">
            </div>
        </header>

        <!--form class="product__summary form"-->
                <?php if(isset($_SESSION[BASE.'usersetup']) AND strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
                        <span class="c-product__availability">
                                <a href="<?php echo HOST_URL;?>/admin/edit_tovar.php?tovar_id=<?php echo $product['id'];?>" target="_blank"><font color=red>редактировать</font></a>
                        </span>
                <?php }?>
                <style>
                        .size__item{
                                display: inline-block;
                                vertical-align: middle;
                                margin: 0.3125rem;
                                min-width: 32px;
                                height: 32px;
                                font-weight: normal;
                                font-size: 1.25rem;
                                line-height: 30px;
                                border: 1px solid #fff;
                                text-decoration: none;
                                text-align: center;
                                background-color: transparent;
                        }
                        .size__item:hover{
                                border: 1px solid #FF0000;
                        }
                        
                </style>
                
            <div class="product__row">
                <?php if(isset($product['size'])){ ?>
                        <?php foreach($product['size'] as $size){ ?>
                                <?php if($size['size'] != 'none'){ ?>
                                        <a href="javascript:" class="size__item" id="<?php echo $size['id']; ?>"><?php echo $size['size']; ?></a>
                                        <!--span class="pager__item pager__item_current">2</span-->
                                <?php } ?>
                        <?php } ?>
                        
                <?php } ?>
                <!--span class="product__availability product__availability_true">в наличии</span-->
            </div>
            

            <div class="product__row product__article">
                Код товара: <?php echo $product['artkl'];?>
            </div>

        <?php if(isset($product['attributes'])){ ?>
            <?php foreach($product['attributes'] as $attributes){ ?>
                <div class="row product__row">
                    <div class="large-8 small-10 columns"><?php echo $attributes['name'];?>:</div>
                    <div class="large-16 small-14 columns"><?php echo $attributes['value'];?></div>
                </div>
            <?php } ?>
        <?php } ?>
       
            <!--div class="product__row">
                Цвет
                <div class="product__colors">
                    <span class="product__color product__color_active" style="background-image: url(<?php echo SKIN_URL;?>img/colors/color_40_40_1.png);"></span>
                    <span class="product__color" style="background-image: url(<?php echo SKIN_URL;?>img/colors/color_40_40_2.png);"></span>
                    <span class="product__color" style="background-image: url(<?php echo SKIN_URL;?>img/colors/color_40_40_3.png);"></span>
                    <span class="product__color" style="background-image: url(<?php echo SKIN_URL;?>img/colors/color_40_40_4.png);"></span>
                    <span class="product__color" style="background-image: url(<?php echo SKIN_URL;?>img/colors/color_40_40_5.png);"></span>
                    <span class="product__color" style="background-image: url(<?php echo SKIN_URL;?>img/colors/color_40_40_6.png);"></span>
                    <span class="product__color" style="background-image: url(<?php echo SKIN_URL;?>img/colors/color_40_40_7.png);"></span>
                    <span class="product__color" style="background-image: url(<?php echo SKIN_URL;?>img/colors/color_40_40_8.png);"></span>
                    <span class="product__color" style="background-image: url(<?php echo SKIN_URL;?>img/colors/color_40_40_9.png);"></span>
                </div>
            </div-->

            <!--div class="row product__row">
                <div class="large-8 small-10 columns">
                    <label class="form__label"><span class="form__label-name">Объем рюкзака:</span></label>
                </div>
                <div class="large-5 small-7 columns">
                    <select name="" id="" class="form__select">
                        <option value="32">32</option>
                        <option value="40">40</option>
                        <option value="45">45</option>
                        <option value="50">50</option>
                        <option value="60">60</option>
                        <option value="70">70</option>
                        <option value="90">90</option>
                        <option value="120">120</option>
                    </select>
                </div>
                <div class="large-11 small-7 columns">
                    <label class="form__label"><span class="form__label-name">литров</span></label>
                </div>
            </div-->
          
                
            <?php if(!isset($min_price['delive_days'])) $min_price['delive_days'] = 'НЕТ'; ?>
            <div class="row product__row product__row_footer">
                <div class="small-11 columns">
                    <div class="product__old-price"><?php echo $old_price; ?></div>
                    <div class="product__price"><span id="min_price_price"><?php echo $min_price['price']; ?></span> ₽ </div>
                    <div class="columns">(Доставка <span id="min_delive_days"><?php echo $min_price['delive_days']; ?></span> дн.)
                    </div>
                </div>

                <div class="small-4 columns">
                        <?php if($min_price['delive_days'] == 'НЕТ'){ ?>
                                <input type="text" class="form__input text-center" id="items" required value="0" disabled>
                        <?php }else{ ?>
                                <input type="text" class="form__input text-center" id="items" required value="1">
                        <?php } ?>
                </div>

                <div class="small-9 columns">
                        <input type="hidden" id="product_id" value="<?php echo $product['id'];?>">
                        <input type="hidden" id="postav_id" value="<?php echo $min_price['postav_id'];?>">
                    <button class="form__submit form__submit_text-large to_cart">В корзину</button>
                </div>
            </div>
            
            <script>
                $(document).ready(function(){
                       
                       $('.icheck_deliv').on('change', function(){     
                       var params = '';
                           $("input:radio:checked").each(function(){
                                
                                params = params + this.id+'['+$(this).val()+']&';
                           
                                $('#min_delive_days').html($('#'+this.id).data('deliv'));
                                $('#min_price_price').html($('#'+this.id).data('price'));
                                $('#product_id').val($('#'+this.id).data('product'));
                                $('#postav_id').val($('#'+this.id).data('postav'));
                                //console.log($('#'+this.id).data('product'));
                           });
              
                        
                        });
                });
                
                $(document).on('click', '.to_cart', function(){
                        var price = $('#min_price_price').html();          
                        var delive_days = $('#min_delive_days').html();          
                        var items = $('#items').val();          
                        var prod_id = $('#product_id').val();          
                        var postav_id = $('#postav_id').val();          
                        
                        if(items != '' && items > 0){                                        
                                $.ajax({
                                        type: "POST",
                                        url: "<?php echo HOST_URL; ?>/ajax/add_to_cart.php",
                                        dataType: "json",
                                        data: "price="+price+"&delive_days="+delive_days+"&items="+items+"&prod_id="+prod_id+"&postav_id="+postav_id,
                                        success: function(msg){
                                                
                                                if (msg.err == 'true') {
                                                    alert(msg.msg);
                                                }else{
                                                    $('.num-cart__total').html(msg.summ);
                                                }
                                                //console.log( msg );
                                        }
                                });
                        }
                });
            </script>
            
            
            <div class="row product__row">
                        <h4 class="filters__sub-title">Выбрать цену и срок доставки:</h4>
                        <?php foreach($size as $size_name => $size_){ ?>
                                
                                <div class="filters form">
                                  <?php if($size_name != 'none'){ ?>
                                        <h3 class="filters__sub-title">Размер <?php echo $size_name; ?></h3>
                                  <?php } ?>      
                                <table style="width: 100%;">
                                        <tr>
                                                <th style="width: 30%;text-align: left;">Цена</th>
                                                <th style="text-align: left;">Cрок</th>
                                        </tr>
                                        <?php if(isset($size_['deliv']) AND $size_['deliv']){ ?>
                                        <?php foreach($size_['deliv'] as $deliv){ ?>
                                                <tr>
                                                        <td><?php echo $deliv['price_1']; ?> ₽</td>
                                                        <td>
                                                        <div class="filters__row">
                                                                <input type="radio" class="icheck icheck_deliv"
                                                                       name="product_size"
                                                                       id="product-<?php echo $size_name?>-<?php echo $deliv['postav_id'];?>-<?php echo $deliv['delivery_days'];?>"
                                                                        data-radioClass="form__radio"
                                                                        data-postav="<?php echo $deliv['postav_id'];?>"
                                                                        data-deliv="<?php echo $deliv['delivery_days'];?>"
                                                                        data-price="<?php echo $deliv['price_1'];?>"
                                                                        data-product="<?php echo $size_['id'];?>"
                                                                        <?php if($min_price['delive_days'] == $deliv['delivery_days'] AND $deliv['price_1'] == $min_price['price']) echo ' checked '; ?>
                                                                        
                                                                        >
                                                                        <div class="form__check-label-wrapper">
                                                                    <label for="product-<?php echo $size_name?>-<?php echo $deliv['postav_id'];?>-<?php echo $deliv['delivery_days'];?>" class="form__check-label"><?php echo $deliv['delivery_days']; ?> дн.</label>
                                                        </div>
                                                      </td>
                                                </tr>
                                        <?php } ?>                                                                
                                        <?php } ?>                
                                </table>
                                </div>
                        <?php } ?>
            </div>
            
        <!--/form-->

        <div class="product__hint">
            <h3 class="product__sub-title">Доставка товаров</h3>
            <p>Заказывая доставку товара сторонними курьерскими службами (Новая Почта, Мист Экспресс ) - при получении
                обязательно проверяйте наличие всего товара в заказе, а так же его внешний вид.</p>
        </div>
    </div>
</div>

<div class="row section">
    <div class="small-24 columns">
        <ul class="tabs" data-tab>
            <li class="tab-title"><a href="#panel1">Характеристики</a></li>
            <li class="tab-title <?php if(!isset($_GET['reviewer-comment'])) echo 'active'; ?>"><a href="#panel2">Обзор</a></li>
            <li class="tab-title"><a href="#panel3">Таблица размеров</a></li>
            <li class="tab-title"><a href="#panel4">Видео</a></li>
            <li class="tab-title <?php if(isset($_GET['reviewer-comment'])) echo 'active'; ?>"><a href="#panel5">Отзывы</a></li>
        </ul>
        <div class="tabs-content">
            <div class="tab-content" id="panel1">
                <p>Характеристики</p>
                <?php if(isset($product['attributes'])){ ?>
                        <?php foreach($product['attributes'] as $attributes){ ?>
                            <div class="row product__row">
                                <div class="large-8 small-10 columns"><?php echo $attributes['name'];?>:</div>
                                <div class="large-16 small-14 columns"><?php echo $attributes['value'];?></div>
                            </div>
                        <?php } ?>
                <?php } ?>
            </div>
            <div class="tab-content <?php if(!isset($_GET['reviewer-comment'])) echo 'active'; ?>" id="panel2">
                <p>Обзор</p>
                <?php if(isset($memo)){
                     echo $memo;                   
                } ?>
            </div>
            <div class="tab-content" id="panel3">
                <p>Таблица размеров</p>
                <?php if(isset($tovar_size_table)){
                     echo $tovar_size_table.'<br>';                   
                } ?>
            </div>
            <div class="tab-content" id="panel4">
                <p>Видео</p>
                <?php if(isset($video)){
                     echo $video.'<br>';                   
                } ?>
            </div>
            <div class="tab-content <?php if(isset($_GET['reviewer-comment'])) echo 'active'; ?>" id="panel5">
                <div class="reviews">
                        <?php if(isset($comments) AND $comments){ ?>
                        <?php foreach($comments as $comment){ ?>
                        
                        <article class="review">
                            <header class="review__header">
                                <h2 class="review__title"><?php echo $comment['comments_name']; ?></h2>
                                <time class="review__date" datetime="<?php echo date('Y-m-d', strtotime($comment['comments_date'])); ?>">
                                    <?php echo $Month_r[date('m', strtotime($comment['comments_date']))] . ' ' . date('d, Y', strtotime($comment['comments_date'])); ?></time>
                            </header>
    
                            <div class="review__content">
                                <p><?php echo $comment['comments_memo']; ?></p>
                            </div>
                        </article>
                        <?php } ?>
                        <?php } ?>

<!-- Пагинация для коментов - пока не нужно! -->
<!--div class="pager">
    <a href="#" class="pager__item pager__item_prev"><span class="fa fa-angle-left"></span></a>
    <a href="#" class="pager__item">1</a>
    <span class="pager__item pager__item_current">2</span>
    <a href="#" class="pager__item">3</a>
    <a href="#" class="pager__item">4</a>
    <a href="#" class="pager__item pager__item_next"><span class="fa fa-angle-right"></span></a>
</div-->

                    <form class="reviews__form form" METHOD=POST>
                        <h3 class="reviews__title">Написать отзыв</h3>
                        <div class="row form__row">
                            <div class="large-4 medium-6 columns">
                                <label for="reviewer-name" class="form__label"><span class="form__label-name">Ваше имя</span></label>
                            </div>

                            <div class="large-6 medium-8 columns end">
                                <input type="text" class="form__input" id="reviewer-name" name="reviewer-name">
                                <input type="hidden"  id="reviewer-product" name="reviewer-product" value="<?php echo $product['artkl']; ?>">
                            </div>
                        </div>

                        <div class="row form__row">
                            <div class="large-4 medium-6 columns">
                                <label for="reviewer-email" class="form__label"><span class="form__label-name">Email</span></label>
                            </div>

                            <div class="large-6 medium-8 columns end">
                                <input type="email" class="form__input" id="reviewer-email" name="reviewer-email">
                            </div>
                        </div>

                        <div class="row form__row">
                            <div class="large-4 medium-6 columns">
                                <label for="reviewer-comment" class="form__label"><span class="form__label-name">Комментарий</span></label>
                            </div>

                            <div class="large-8 medium-10 columns end">
                                <textarea id="reviewer-comment" name="reviewer-comment" class="form__textarea" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="row form__row">
                            <div class="large-8 medium-10 large-offset-4 medium-offset-6 columns end">
                                <button class="btn btn_light">Написать отзыв</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--div class="row section">
    <div class="small-24 columns">
        <h3 class="section__title">Товары из этой категории</h3>

        <div class="c-products c-products_carousel">
            <div class="c-products__carousel c-products__carousel_fix">
                <div class="c-product">
                    <a href="#" class="c-product__img c-product__img_sale" style="background-image: url(img/catalog/products/thumb_300_300_1.jpg);"></a>

                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_1.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_3.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_4.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_6.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_7.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_9.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_true">в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="c-product">
                    <a href="#" class="c-product__img c-product__img_hit" style="background-image: url(img/catalog/products/thumb_300_300_2.jpg);"></a>

                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_2.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_3.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_4.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_5.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_8.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_9.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_false">нет в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="c-product">
                    <a href="#" class="c-product__img" style="background-image: url(img/catalog/products/thumb_300_300_3.jpg);"></a>

                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_1.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_2.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_3.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_4.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_5.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_6.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_true">в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="c-product">
                    <a href="#" class="c-product__img c-product__img_action" style="background-image: url(img/catalog/products/thumb_300_300_4.jpg);"></a>

                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_3.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_4.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_5.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_6.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_7.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_9.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_true">в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="c-product">
                    <a href="#" class="c-product__img" style="background-image: url(img/catalog/products/thumb_300_300_5.jpg);"></a>

                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_1.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_2.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_3.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_7.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_8.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_9.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_true">в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="c-product">
                    <a href="#" class="c-product__img" style="background-image: url(img/catalog/products/thumb_300_300_6.jpg);"></a>
                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_1.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_2.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_4.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_5.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_6.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_8.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_true">в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div-->
<!--div class="row section">
    <div class="small-24 columns">
        <h3 class="section__title">Последние просмотренные</h3>

        <div class="c-products c-products_carousel">
            <div class="c-products__carousel c-products__carousel_fix">
                <div class="c-product">
                    <a href="#" class="c-product__img c-product__img_sale" style="background-image: url(img/catalog/products/thumb_300_300_1.jpg);"></a>

                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_1.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_3.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_4.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_6.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_7.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_9.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_true">в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="c-product">
                    <a href="#" class="c-product__img c-product__img_hit" style="background-image: url(<?php echo SKIN_URL;?>img/catalog/products/thumb_300_300_2.jpg);"></a>

                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_2.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_3.png);"></span>
                        <span class="c-product__color" style="background-image: url(<?php echo SKIN_URL;?>img/colors/color_40_40_4.png);"></span>
                        <span class="c-product__color" style="background-image: url(<?php echo SKIN_URL;?>img/colors/color_40_40_5.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_8.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_9.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_false">нет в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="c-product">
                    <a href="#" class="c-product__img" style="background-image: url(img/catalog/products/thumb_300_300_3.jpg);"></a>

                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_1.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_2.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_3.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_4.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_5.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_6.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_true">в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="c-product">
                    <a href="#" class="c-product__img c-product__img_action" style="background-image: url(img/catalog/products/thumb_300_300_4.jpg);"></a>

                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_3.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_4.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_5.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_6.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_7.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_9.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_true">в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="c-product">
                    <a href="#" class="c-product__img" style="background-image: url(img/catalog/products/thumb_300_300_5.jpg);"></a>

                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_1.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_2.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_3.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_7.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_8.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_9.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_true">в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="c-product">
                    <a href="#" class="c-product__img" style="background-image: url(img/catalog/products/thumb_300_300_6.jpg);"></a>
                    <div class="c-product__row c-product__colors">
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_1.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_2.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_4.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_5.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_6.png);"></span>
                        <span class="c-product__color" style="background-image: url(img/colors/color_40_40_8.png);"></span>
                    </div>

                    <div class="c-product__row">
                        <span class="c-product__availability c-product__availability_true">в наличии</span>
                    </div>

                    <div class="c-product__row c-product__row_title">
                        <a href="#" class="c-product__title">MFH США рюкзак штурмовой большой Vegetato Desert</a>
                    </div>

                    <div class="c-product__row c-product__row_price">
                        <div class="row">
                            <div class="small-8 columns">
                                <span class="c-product__old-price">6 750</span>
                                <span class="c-product__price">5 300 ₽</span>
                            </div>

                            <div class="small-16 columns text-right">
                                <button class="btn" type="button">В корзину</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div-->


    <!--?php include SKIN_PATH . 'body_brands.php'; ?-->
    <!--?php include SKIN_PATH . 'body_sections.php'; ?-->
    <?php include SKIN_PATH . 'footer.php'; ?>
</div>
<?php include SKIN_PATH . 'footer_includes.php';?>
</body>
</html>
  
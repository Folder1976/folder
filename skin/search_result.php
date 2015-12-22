<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Результаты поиска на Arrma.ru '; ?>
        <?php $description = 'Результаты поиска по запросу '; ?>
    
        <?php include_once SKIN_PATH.'header_main.php'; ?>    
        <?php include_once SKIN_PATH.'header_includes.php'; ?>
        <?php
        
                $index = 0;
                
                if(isset($data)){
                        $brands = $data['brands'];
                        unset($data['brands']);
                        
                        $country = $data['country'];
                        unset($data['country']);
                        
                        $attribute_filter = $data['attribute_filter'];
                        unset($data['attribute_filter']);
                }
                //Подчищаем ссылку        
                if(strpos($_SERVER['REDIRECT_URL'], '?') !== false){
                        $tmp = explode('?', $_SERVER['REDIRECT_URL']);
                        $_SERVER['REDIRECT_URL'] = $tmp[0];
                }
        
                //$categories
                //$categ_selected
                //$category_children
                //header("Content-Type: text/html; charset=UTF-8");
                //echo "<pre>";  print_r(var_dump( $data['products_info'] )); echo "</pre>";
                //echo "<pre>";  print_r(var_dump( $data['category_info'] )); echo "</pre>";
        
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
                <div class="breadcrumb breadcrumb_last">
                        <span class="breadcrumb__name">Результаты поиска</span>
                </div>
        </div>
    </div>
</div>

<div class="row section">
    <div class="medium-7 columns">
         <?php if(isset($data['products_info']) AND count($data['products_info']['parent']) > 1){ ?>
        <ul class="l-menu">
                <li class="l-menu__item">
                        <a href="#" class="l-menu__link l-menu__link_current">Категории</a>
                        <ul class="l-menu__sub">
                                <?php foreach($data['products_info']['parent'] as $index => $parent){ ?>
                                        <?php if($index != $data['category_id']) { ?>
                                                <li class="l-menu__sub-item">
                                                        <a href="<?php echo HOST_URL.'/'.$parent['url']; ?>.html" class="l-menu__sub-link">
                                                                <?php echo $parent['name']; ?> (<?php echo $data['products_parent_items'][$index];?>)
                                                        </a>
                                                        <?php if(isset($_SESSION[BASE.'usersetup']) AND strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
                                                                &nbsp;&nbsp;<a href="<?php echo HOST_URL;?>/admin/edit_parent_inet.php?parent_inet_id=<?php echo $index;?>"><font color=red><b>ред.</b></font></a>
                                                        <?php }?>
                                                </li>
                                        <?php } ?>
                                <?php } ?>
                         </ul>
                </li>
        </ul>
        <br>
        <?php } ?>

<form class="filters form" action="#" method="post">
    <h3 class="filters__title">Фильтр товаров</h3>
    <button class="filters__reset" type="button"><span class="fa fa-times"></span><span class="filters__reset-name">Сбросить фильтры</span></button>
    <div class="filters__group">
        <h4 class="filters__sub-title">Цена</h4>
        <div class="row">
            <div class="large-12 medium-24 small-12 columns filter">
                <div class="row collapse">
                    <div class="small-5 columns">
                        <label for="min-price" class="form__label form__label_v"><span class="form__label_name">от</span></label>
                    </div>

                    <div class="small-16 columns end">
                        <input id="min-price" type="text" class="form__input" min="<?php echo  $data['min_price']; ?>" value="<?php echo  $data['min_price']; ?>">
                    </div>
                </div>
            </div>

            <div class="large-12 medium-24 small-12 columns filter">
                <div class="row collapse">
                    <div class="small-5 columns">
                        <label for="max-price" class="form__label form__label_v"><span class="form__label_name">до</span></label>
                    </div>

                    <div class="small-16 columns">
                        <input id="max-price" type="text" class="form__input" max="<?php echo $data['max_price']; ?>" value="<?php echo  $data['max_price']; ?>">
                    </div>

                    <div class="small-3 columns">
                        <label class="form__label form__label_v"><span class="form__label_name">₽</span></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form__row">
            <input type="text" id="price-range" data-min="0" data-max="<?php echo  $data['max_price']; ?>" data-from="<?php echo  $data['min_price']; ?>" data-to="<?php echo  $data['max_price']; ?>">
        </div>
    </div>

<!-- Бренды -->  
        <?php if(isset($brands) AND count($brands) > 0 ){ ?>
                <div class="filters__group ">
                    <h4 class="filters__sub-title">Производитель</h4>
                    
                <?php foreach($brands as $i => $item){ ?>
                    <div class="filters__row">
                        <input type="checkbox" class="icheck icheck_brand" id="brand-<?php echo $index?>" data-checkboxClass="form__checkbox" value="<?php echo $item;?>"
                                <?php if(isset($_GET['brand-'.$index][$item])) echo ' checked '; ?> >
                        <div class="form__check-label-wrapper">
                            <label for="brand-<?php echo $index?>" class="form__check-label"><?php echo $item;?></label>
                        </div>
                    </div>
                <?php } ?>
                </div>
        <?php } ?>

<!-- Страны -->         
        <?php if(isset($country) AND count($country) > 0 ){ ?>
                <div class="filters__group ">
                    <h4 class="filters__sub-title">Страна</h4>
                    
                <?php foreach($country as $i => $item){ ?>
                    <div class="filters__row">
                        <input type="checkbox" class="icheck icheck_country" id="country-<?php echo $index?>" data-checkboxClass="form__checkbox" value="<?php echo $item;?>"
                                <?php if(isset($_GET['country-'.$index][$item])) echo ' checked '; ?> >
                        <div class="form__check-label-wrapper">
                            <label for="brand-<?php echo $index?>" class="form__check-label"><?php echo $item;?></label>
                        </div>
                    </div>
                <?php } ?>
                </div>
        <?php } ?>

<!-- Атрибуты -->  
            <?php if(isset($attribute_filter)){ ?>
                <?php foreach($attribute_filter as $index => $value){ ?>
                <div class="filters__group ">
                    <h4 class="filters__sub-title"><?php echo $value['title']; ?></h4>
                        <?php foreach($value['value'] as $i => $item){ ?>
                            <div class="filters__row">
                                <input type="checkbox" class="icheck icheck_filter" id="filter-<?php echo $index?>" data-checkboxClass="form__checkbox" value="<?php echo $item;?>"
                                        <?php if(isset($_GET['filter-'.$index][$item])) echo ' checked '; ?> >
                                <div class="form__check-label-wrapper">
                                    <label for="brand-<?php echo $index?>" class="form__check-label"><?php echo $item;?></label>
                                </div>
                            </div>
                        <?php } ?>
                </div>
                <?php } ?>
            <?php } ?>
            
    <!--div class="filters__group">
        <h4 class="filters__sub-title">Цвет</h4>
        <div class="filters__row">
            <input type="checkbox" class="icheck" id="color-1" data-checkboxClass="form__checkbox" checked>
            <div class="form__check-label-wrapper form__check-label-wrapper_iconized">
                <label for="color-1" class="form__check-label"><span class="filter__color" style="background-image: url(img/colors/color_40_40_1.png);"></span> RainySkin 038</label>
            </div>
        </div>

        <div class="filters__row">
            <input type="checkbox" class="icheck" id="color-2" data-checkboxClass="form__checkbox" checked>
            <div class="form__check-label-wrapper form__check-label-wrapper_iconized">
                <label for="color-2" class="form__check-label"><span class="filter__color" style="background-image: url(img/colors/color_40_40_2.png);"></span>Desert Eagle 211</label>
            </div>
        </div>

        <div class="filters__row">
            <input type="checkbox" class="icheck" id="color-3" data-checkboxClass="form__checkbox">
            <div class="form__check-label-wrapper form__check-label-wrapper_iconized">
                <label for="color-3" class="form__check-label"><span class="filter__color" style="background-image: url(img/colors/color_40_40_3.png);"></span>Dust MT-A1</label>
            </div>
        </div>

        <div class="filters__row">
            <input type="checkbox" class="icheck" id="color-4" data-checkboxClass="form__checkbox">
            <div class="form__check-label-wrapper form__check-label-wrapper_iconized">
                <label for="color-4" class="form__check-label"><span class="filter__color" style="background-image: url(img/colors/color_40_40_4.png);"></span>Jungle Forest B44</label>
            </div>
        </div>
    </div>

    <div class="filters__group filters__group_last">
        <h4 class="filters__sub-title">Размер</h4>
        <div class="filters__row">
            <input type="checkbox" class="icheck" id="size-s" data-checkboxClass="form__checkbox" checked>
            <div class="form__check-label-wrapper">
                <label for="size-s" class="form__check-label">S</label>
            </div>
        </div>

        <div class="filters__row">
            <input type="checkbox" class="icheck" id="size-m" data-checkboxClass="form__checkbox" checked>
            <div class="form__check-label-wrapper">
                <label for="size-m" class="form__check-label">M</label>
            </div>
        </div>

        <div class="filters__row">
            <input type="checkbox" class="icheck" id="size-l" data-checkboxClass="form__checkbox">
            <div class="form__check-label-wrapper">
                <label for="size-l" class="form__check-label">L</label>
            </div>
        </div>

        <div class="filters__row">
            <input type="checkbox" class="icheck" id="size-xl" data-checkboxClass="form__checkbox">
            <div class="form__check-label-wrapper">
                <label for="size-xl" class="form__check-label">XL</label>
            </div>
        </div>

        <div class="filters__row">
            <input type="checkbox" class="icheck" id="size-xxl" data-checkboxClass="form__checkbox">
            <div class="form__check-label-wrapper">
                <label for="size-xxl" class="form__check-label">XXL</label>
            </div>
        </div>
    </div-->

    <div class="filters__row text-center">
        <button class="btn btn_light">Подобрать</button>
    </div>
</form>
    </div>

    <div class="medium-17 columns section__col-content">
        <h1 class="section__title">Результаты поиска: <?php echo $search; ?></h1>
        <?php if(!isset($data['products'])){?>
                <h2>Поиск не дал результата :(</h2>
                
                <?php if(strlen($search) < 5){ ?>
                        <h3><font color="red">Слово слишком короткое. Меньше 5 букв.</font></h3>
                <?php }else{ ?>
                        <h3>Попробуйте снова</h3>
                <?php } ?>
                
        <?php } ?>
        
        <div class="row sort-by">
            <div class="medium-14 columns sort-by__col">
                <span class="sort-by__title">Сортировать по:</span>
                <span class="sort-by__link">Популярные</span>
                <span class="sort-by__link sort-by__link_active">Цена</span>
                <span class="sort-by__link">Акции</span>
                <span class="sort-by__link">Скидка</span>
            </div>

                <?php
                $step = 15;
                if(isset($_GET['step'])) $step = $_GET['step'];
                ?>
                
            <div class="medium-10 columns sort-by__col text-right">
                <span class="sort-by__title">Отображать по:</span>
                <a href="<?php echo $_SERVER['REDIRECT_URL'].'?search='.$search.'&step=15';?>"><span class="sort-by__link <?php if($step == 15) echo 'sort-by__link_active';?>">15</span></a>
                <a href="<?php echo $_SERVER['REDIRECT_URL'].'?search='.$search.'&step=30';?>"><span class="sort-by__link <?php if($step == 30) echo 'sort-by__link_active';?>">30</span></a>
                <a href="<?php echo $_SERVER['REDIRECT_URL'].'?search='.$search.'&step=45';?>"><span class="sort-by__link <?php if($step == 45) echo 'sort-by__link_active';?>">45</span></a>
                <a href="<?php echo $_SERVER['REDIRECT_URL'].'?search='.$search.'&step=1000';?>"><span class="sort-by__link <?php if($step == 1000) echo 'sort-by__link_active';?>">Все</span></a>
            </div>
        </div>

        <div class="c-products">
                <?php if(isset($data['products'])) { ?>
                        <?php foreach($data['products'] as $artkl => $product){ ?>
                                
                                <div class="c-product">
                                    <a href="<?php echo $product['url']; ?>.html" class="c-product__img c-product__img" style="background-image: url(<?php echo str_replace('small', 'medium', $product['img']); ?>);"></a>
                                        <!--a href="#" class="c-product__img c-product__img_action" style="background-image: url(img/catalog/products/thumb_300_300_11.jpg);"></a-->    
                                        <!--a href="#" class="c-product__img c-product__img_hit" style="background-image: url(img/catalog/products/thumb_300_300_12.jpg);"></a-->   
                                        <!--a href="#" class="c-product__img" style="background-image: url(img/catalog/products/thumb_300_300_13.jpg);"></a-->
                                        <!--a href="#" class="c-product__img_sale" style="background-image: url(img/catalog/products/thumb_300_300_13.jpg);"></a-->
                                    
                                    <div class="c-product__row">
                                        <?php if($product['total'] == 0){ ?>
                                                <span class="c-product__availability c-product__availability_false">нет в наличии</span>
                                        <?php }else{ ?>
                                                <span class="c-product__availability c-product__availability_true">в наличии</span>
                                        <?php } ?>
                                                
                                                <?php if(isset($_SESSION[BASE.'usersetup']) AND strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
                                                        <span class="c-product__availability">
                                                                <a href="<?php echo HOST_URL;?>/admin/edit_tovar.php?tovar_id=<?php echo $product['id'];?>" target="_blank"><font color=red>редактировать</font></a>
                                                        </span>
                                                <?php }?>
                                    </div>
                    
                                    <div class="c-product__row c-product__row_title">
                                        <a href="<?php echo $product['url']; ?>.html" class="c-product__title"><?php echo $artkl . ' - ' . $product['name'];?></a>
                                    </div>
                    
                                    <div class="c-product__row c-product__row_price">
                                        <div class="row">
                                            <div class="small-8 columns">
                                                <!--span class="c-product__old-price">6 750</span-->
                                                <span class="c-product__price"><?php echo $product['price'];?> ₽</span>
                                            </div>
                    
                                            <div class="small-16 columns text-right">
                                                <a href="<?php echo $product['url']; ?>.html">
                                                        <button class="btn" type="button">В корзину</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php } ?>
                <?php } ?>

        </div>

        <!--div class="c-products-footer">
            <button class="btn btn_light">Показать еще</button>
        </div-->

        <div class="pager">
                <?php $page = 1;
                        $end = round($data['products_count'] / $step); 
                        if(isset($_GET['page'])) $page = $_GET['page'];?>
                        
                <?php if($page > 1) {?>
                        <a href="<?php echo $_SERVER['REDIRECT_URL'].'?search='.$search.'&page=1&step='.$step;?>" class="pager__item pager__item_prev"><span class="fa fa-angle-left"></span></a>
                <?php } ?>
                
                <?php for($x = 1;$x <= $end; $x++){ ?>
                        <?php if($x == $page){ ?>
                                <a href="<?php echo $_SERVER['REDIRECT_URL'].'?search='.$search.'&page='.$x.'&step='.$step;?>" class="pager__item pager__item_current"><?php echo $x; ?></a>
                        <?php }else{ ?>
                                <a href="<?php echo $_SERVER['REDIRECT_URL'].'?search='.$search.'&page='.$x.'&step='.$step;?>" class="pager__item"><?php echo $x; ?></a>
                        <?php } ?>
                <?php } ?>
        
                <?php if($page < $end) {?>
                    <a href="<?php echo $_SERVER['REDIRECT_URL'].'?search='.$search.'&page='.$end.'&step='.$step;?>" class="pager__item pager__item_next"><span class="fa fa-angle-right"></span></a>
                <?php } ?>
        </div>

        <div class="row">
            <div class="medium-12 columns catalog-banner">
                <a href="#"><img src="<?php echo SKIN_URL; ?>img/catalog/banners/banner_1.jpg" alt=""></a>
            </div>

            <div class="medium-12 columns catalog-banner">
                <a href="#"><img src="<?php echo SKIN_URL; ?>img/catalog/banners/banner_2.jpg" alt=""></a>
            </div>
        </div>
    </div>
</div>

        <?php include SKIN_PATH . 'body_brands.php'; ?>
        <?php include SKIN_PATH . 'body_sections.php'; ?>
        <?php include SKIN_PATH . 'footer.php'; ?>
</div>
        <?php include SKIN_PATH . 'footer_includes.php';?>
</body>
</html>
<script>
    $(document).ready(function(){
        
        $('.icheck_filter').on('change', function(){     
        var params = 'search=<?php echo $search;?>&';
            $("input:checkbox:checked").each(function(){
                 params = params + this.id+'['+$(this).val()+']&';                               
            });

        var url = window.location.href    
            
        window.location.replace(window.location.pathname + "?"+params);
        });
      
        $('.icheck_brand').on('change', function(){     
        var params = 'search=<?php echo $search;?>&';
            $("input:checkbox:checked").each(function(){
                 params = params + this.id+'['+$(this).val()+']&';                               
            });

        var url = window.location.href    
            
        window.location.replace(window.location.pathname + "?"+params);
        });
      
        $('.icheck_country').on('change', function(){     
        var params = 'search=<?php echo $search;?>&';
            $("input:checkbox:checked").each(function(){
                 params = params + this.id+'['+$(this).val()+']&';                               
            });

        var url = window.location.href    
            
        window.location.replace(window.location.pathname + "?"+params);
        });
        
    });
    
</script>
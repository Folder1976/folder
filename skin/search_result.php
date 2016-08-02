<!doctype html>
<?php
        //Строка ГЕТ 
        $_get = '?step=15';
        if(isset($_GET['step'])) $_get = '?step='.$_GET['step'];
        
        if(strpos($_SERVER['REQUEST_URI'],'?')){
                $t = explode('?',$_SERVER['REQUEST_URI']);
                $find = array('page=', 'step=15', 'step=30', 'step=45', 'step=1000', '&&', '&&');
                $rep = array('','','','','','&','&');
                $t = str_replace($find,$rep,$t[1]);
                $_get .= '&'.trim($t,'&');
        }
 ?>
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

<div class="row row_large section">
    <div class="large-6 medium-7 columns">
        
        <ul class="l-menu">
                <li class="l-menu__item">
                        <a href="#" class="l-menu__link l-menu__link_current">Категории</a>
                        <ul class="l-menu__sub sub_categories">
                            <li>Загружаю...</li>
                            <!-- Тут подкатегории товаров -->
                        </ul>
                </li>
        </ul>
        <br>
        
        
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

<form class="filters form" id="filter_form" action="#" method="GET">
    <h3 class="filters__title">Фильтр товаров</h3>
    <?php if(isset($_GET) AND count($_GET) > 2){ ?>
    <div class="filters__row text-center">
        <a href="<?php echo $_SERVER['REDIRECT_URL']; ?>"><button class="filters__reset" type="button"><span class="fa fa-times"></span><span class="filters__reset-name">Сбросить фильтры</span></button></a>
    </div>
    <br>
    <?php } ?>
    <div class="filters__row text-center">
        <button class="btn btn_light" onclick="submit();">Подобрать</button>
    </div>

   <!--div class="filters__group">
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
    </div-->
<!--style>
        .filters__row{
                width: 49%;
                float: left;
        }
        
</style-->
<!-- Пользователи -->
        <?php if(isset($_SESSION[BASE.'usersetup']) AND strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
        <?php if(isset($users) AND count($users) > 0 ){?>
                <div class="filters__group ">
                    <h4 class="filters__sub-title">Редакторы по товарам <font color="red"><b>*</b></font></h4>
                    
                <?php foreach($users as $index => $item){ ?>
                    <div class="filters__row">
                        <input type="checkbox" class="icheck icheck_user" id="user-<?php echo $index?>" data-name="user" data-id="<?php echo $index?>" data-checkboxClass="form__checkbox" value="<?php echo $item;?>"
                                <?php if(isset($_GET['user'][$index])) echo ' checked '; ?> >
                        <div class="form__check-label-wrapper">
                            <label for="user-<?php echo $index?>" class="form__check-label"><?php echo $item;?></label>
                        </div>
                    </div>
                    
                <?php } ?>
                <div style="clear: both"></div>
                </div>
                
        <?php } ?>
        <?php } ?>
        
<!-- Цвета -->         
                <div class="filters__group filter_colors">
                    <h4 class="filters__sub-title">Цвет</h4>
                    <div class="filters__row">
                        <div class="form__check-label-wrapper">
                            <label class="form__check-label">Загружаю...</label>
                        </div>
                    </div>
                 <div style="clear: both"></div>
                 
                </div>
                <div class="filters__more" data-id="filters__row__color">
                    <span class="filters__more-link">Еще...</span><span class="fa fa-angle-up"></span><span class="fa fa-angle-down"></span>
                </div>

<!-- Бренды -->  
                <div class="filters__group filter_brands">
                    <h4 class="filters__sub-title ">Производитель</h4>
                    <div class="filters__row">
                        <div class="form__check-label-wrapper">
                            <label class="form__check-label">Загружаю...</label>
                        </div>
                    </div>
                    
                <div style="clear: both"></div>
                </div>
                <div class="filters__more" data-id="filters__row__brand">
                    <span class="filters__more-link">Еще...</span><span class="fa fa-angle-up"></span><span class="fa fa-angle-down"></span>
                </div>

<!-- Страны -->         
                <div class="filters__group filter_countries">
                    <h4 class="filters__sub-title">Страна</h4>
                    <div class="filters__row">
                        <div class="form__check-label-wrapper">
                            <label class="form__check-label">Загружаю...</label>
                        </div>
                    </div>
                 <div style="clear: both"></div>
                </div>
                <div class="filters__more" data-id="filters__row__country">
                    <span class="filters__more-link">Еще...</span><span class="fa fa-angle-up"></span><span class="fa fa-angle-down"></span>
                </div>

<script>
    $(document).on('click', '.filters__more', function(){
        var id = $(this).data('id');
        console.log(id);
        $('.'+id).css('max-height', '100%');
        $('.'+id).css('overflow-y', 'auto');
        
        $(this).remove();
        $('.'+id).css('border-bottom','1px solid gray');
    });
    
    $(document).on('click', '.filters__row div', function(){
        //$("#filter_form").submit();
        console.log('id');
    });
    
</script>
<style>
    .filters__more-link{
        font-size: 18px;
    }
    .fff{
        overflow-y: scroll
    }
</style>
<!-- Атрибуты -->
<!--
            <?php if(isset($attribute_filter)){ ?>
                <?php foreach($attribute_filter as $index => $value){ ?>
                <div class="filters__group ">
                    <h4 class="filters__sub-title"><?php echo $value['title']; ?></h4>
                        <?php foreach($value['value'] as $i => $item){ ?>
                            <div class="filters__row">
                                <input type="checkbox" class="icheck icheck_filter" id="filter-<?php echo $index?>" data-name="filter-<?php echo $index?>" data-checkboxClass="form__checkbox" value="<?php echo $item;?>"
                                        <?php if(isset($_GET['filter-'.$index][$item])) echo ' checked '; ?> >
                                <div class="form__check-label-wrapper">
                                    <label for="brand-<?php echo $index?>" class="form__check-label"><?php echo $item;?></label>
                                </div>
                            </div>
                        <?php } ?>
                <div style="clear: both"></div>
                </div>
                <?php } ?>
            <?php } ?>
            <div style="clear: both"></div>
-->
    <div class="filters__row text-center">
        <button class="btn btn_light" onclick="submit();">Подобрать</button>
    </div>
<div style="clear: both"></div>    
</form>


    </div>

    <div class="large-18 medium-17 columns section__col-content">
        <h1 class="section__title">Результаты поиска: <?php echo $search; ?></h1>
        <?php if(!isset($data['products'])){?>
                <h2>Поиск не дал результата :(</h2>
                
                <?php if(strlen($search) < 3){ ?>
                        <h3><font color="red">Слово слишком короткое. Меньше 3 букв.</font></h3>
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
                <a href="<?php echo $_SERVER['REDIRECT_URL'].$_get.'&step=15';?>"><span class="sort-by__link <?php if($step == 15) echo 'sort-by__link_active';?>">15</span></a>
                <a href="<?php echo $_SERVER['REDIRECT_URL'].$_get.'&step=30';?>"><span class="sort-by__link <?php if($step == 30) echo 'sort-by__link_active';?>">30</span></a>
                <a href="<?php echo $_SERVER['REDIRECT_URL'].$_get.'&step=45';?>"><span class="sort-by__link <?php if($step == 45) echo 'sort-by__link_active';?>">45</span></a>
                <a href="<?php echo $_SERVER['REDIRECT_URL'].$_get.'&step=1000';?>"><span class="sort-by__link <?php if($step == 100) echo 'sort-by__link_active';?>">100</span></a>
            </div>
        </div>

        <div class="c-products">
                <?php if(isset($data['products'])) { $count=1;?>
                        <?php foreach($data['products'] as $artkl => $product){ ?>
                                
                                <?php if($count++ > 1) echo '-->'; ?><div class="c-product">
                                    <a href="<?php echo $product['url']; ?>.html" class="c-product__img">
                                        <img src="<?php echo str_replace('small', 'medium', $product['img']); ?>"
                                        id="image_<?php echo $artkl; ?>"
                                        title =  title="Картинка <?php echo str_replace('"',"'",$product['name']); ?>"
                                        <?php if($product['total'] == 0){ ?>
                                            class="no_product"   
                                        <?php } ?>
                                        >
                                    </a>
                                     <!--a href="#" class="c-product__img c-product__img_action" style="background-image: url(img/catalog/products/thumb_300_300_11.jpg);"></a-->    
                                        <!--a href="#" class="c-product__img c-product__img_hit" style="background-image: url(img/catalog/products/thumb_300_300_12.jpg);"></a-->   
                                        <!--a href="#" class="c-product__img" style="background-image: url(img/catalog/products/thumb_300_300_13.jpg);"></a-->
                                        <!--a href="#" class="c-product__img_sale" style="background-image: url(img/catalog/products/thumb_300_300_13.jpg);"></a-->
                                    
                                    <div class="c-product__row">
                                        <?php if(isset($product['color_variants']) AND $product['color'] /* AND count($product['color_variants'])>1*/){ ?>
                                        <?php $colors = $product['color_variants']; $limit = 4;?> 
                                        <div class="product__colors">
                                            <a href="<?php echo $product['url']; ?>.html"><span class="product__color product__color_active" title="<?php echo $colors[$product['color']]['color_name'];?>" style="margin-right: 0px;background-image: url(/resources/colors/<?php echo $colors[$product['color']]['color_id'];?>.png);"></span></a>
                                            <?php foreach($colors as $color => $color_val){ ?>
                                                <?php if($color != $product['color']){ ?>
                                                    <?php if($limit-- < 1) continue; ?>
                                                    <a href="/<?php echo $color_val['seo_alias'];?>.html"><span class="product__color" title="<?php echo $color;?>" style="margin-right: 0px;background-image: url(/resources/colors/<?php echo $color_val['color_id'];?>.png);"></span></a>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                        <?php } ?>
                                    </div>

                                    
                                    <div class="c-product__row">
                                        <?php if($product['total'] == 0){ ?>
                                                <span class="c-product__availability c-product__availability_false">нет в наличии</span>
                                        <?php }else{ ?>
                                                <span class="c-product__availability c-product__availability_true">в наличии</span>
                                        <?php } ?>
                                                 <?php if(isset($_SESSION[BASE.'usersetup']) AND strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
                                                        <span class="c-product__availability">
                                                                <!-- is_social -->  
                                                                <?php if(strpos($product['social'], 'facebook') !== false){echo '<img src="'.HOST_URL.'/resources/img/facebook.png" style="height:15px">';} ?>
                                                                <?php if(strpos($product['social'], 'vkontakte') !== false){echo '<img src="'.HOST_URL.'/resources/img/vkontakte.png" style="height:15px">';} ?>
                                                                <!-- edit key -->
                                                                <a href="<?php echo HOST_URL;?>/admin/edit_tovar.php?tovar_id=<?php echo $product['id'];?>" target="_blank"><font color=red>редактировать</font></a>
                                                                <a href="javascript:" class="glav_photo" data-id="<?php echo $product['id'];?>" target="_blank"><font color=red>главфото</font></a>
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
                                                <span class="c-product__price"><?php echo isset($product['price']) ? $product['price'] : '0.00';?> ₽</span>
                                            </div>
                    
                                            <div class="small-16 columns text-right">
                                                <?php if($product['total'] == 0){ ?>
                                                    <a href="<?php echo $product['url']; ?>.html?report">
                                                        <button class="btn" type="button" style="background-color: rgb(89, 96, 113); border-color: rgb(167, 177, 198); font-size:12px;">Следить за наличием</button>
                                                    </a>
                                                <?php }else{ ?>
                                                    <a href="<?php echo $product['url']; ?>.html">
                                                        <button class="btn" type="button">В корзину</button>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--
                        <?php } ?>
                <?php } ?>
                                -->
        </div>

        <!--div class="c-products-footer">
            <button class="btn btn_light">Показать еще</button>
        </div-->

<!-- Пагинация pagination -->
        <div class="pager">
                <?php $page = 1;
                        $end = round($data['products_count'] / $step);
                        
                        //echo $data['products_count'] . ' / ' . $step . ' = ' . $end;
                        if(isset($_GET['page'])) $page = $_GET['page'];?>
                        
                 <?php if($page > 1) {?>
                        <a href="<?php echo $_SERVER['REDIRECT_URL'].$_get.'&page=1';?>" class="pager__item pager__item_prev"><span class="fa fa-angle-left"></span></a>
                <?php } ?>
                <?php if($page == 1){ ?>
                        <a href="<?php echo $_SERVER['REDIRECT_URL'].$_get.'&page=1';?>" class="pager__item pager__item_current">1</a>
                <?php }else{ ?>
                        <a href="<?php echo $_SERVER['REDIRECT_URL'].$_get.'&page=1';?>" class="pager__item">1</a>
                <?php } ?>
                        
                <?php if($page > 4) echo ' . . . '?>
                  
                
                
                <?php
                        $xx = $page - 2;
                        if($xx < 2) $xx = 2;
                        
                        $end_m = $page + 2;
                        if($end_m > $end) $end_m = $end;
                                         
                        for($x = $xx;$x <= $end_m; $x++){ ?>
                        <?php if($x == $page){ ?>
                                <a href="<?php echo $_SERVER['REDIRECT_URL'].$_get.'&page='.$x;?>" class="pager__item pager__item_current"><?php echo $x; ?></a>
                        <?php }else{ ?>
                                <a href="<?php echo $_SERVER['REDIRECT_URL'].$_get.'&page='.$x;?>" class="pager__item"><?php echo $x; ?></a>
                        <?php } ?>
                <?php } ?>
        
                <?php if($page < ($end - 3)) echo ' . . . '?>

                <?php if($page < $end - 2) {?>
                    <a href="<?php echo $_SERVER['REDIRECT_URL'].$_get.'&page='.$end;?>" class="pager__item"><?php echo $end; ?></a>
                <?php } ?>
    
                <?php if($page < $end) {?>
                    <a href="<?php echo $_SERVER['REDIRECT_URL'].$_get.'&page='.$end;?>" class="pager__item pager__item_next"><span class="fa fa-angle-right"></span></a>
                <?php } ?>
        </div>

       <div class="row">
            <?php if(isset($banners) AND count($banners) > 0){ ?>
            <?php foreach($banners as $banner){ ?>
                <div class="medium-12 columns catalog-banner">
                        <?php if($banner['url'] == ''){ ?>
                                <img src="<?php echo $banner['img']; ?>" alt="<?php echo $banner['title']; ?>" title="Картинка <?php echo str_replace('"',"'",$banner['title']); ?>">
                        <?php }else{ ?>
                                <a href="<?php echo $banner['url']; ?>"><img src="<?php echo $banner['img']; ?>" alt="<?php echo str_replace('"',"'",$banner['title']); ?>" title="Картинка <?php echo str_replace('"',"'",$banner['title']); ?>"></a>
                        <?php } ?>
                </div>
            <?php } ?>
            <?php } ?>
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
        //console.log(window.location.pathname + "?"+params);
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

<script>
    $(document).ready(function(){
       
        <?php foreach($data['timer1'] as $time){?>
            console.log("<?php echo $time; ?>");
        <?php } ?>
        <?php foreach($data['timer'] as $time){ ?>
            console.log("<?php echo $time; ?>");
        <?php } ?>

		function RebuildFilters() {
			var params = '';
            var params = 'search=<?php echo $search;?>&';
				$("input:checkbox:checked").each(function(){
					//Для брендов и стран отдельный фильтр нах!
					if($(this).data('name') == 'user' || $(this).data('name') == 'brand' || $(this).data('name') == 'country') {
						params = params + $(this).data('name')+'['+$(this).data('id')+']='+$(this).val()+'&';                               
					}else{
						params = params + $(this).data('name')+'['+$(this).val()+']='+$(this).val()+'&';
					}
				});

			var url = window.location.href    
				
			window.location.replace(window.location.pathname + "?"+params);
		}
        
        $(document).on('change', '.icheck_filter', RebuildFilters);
        $(document).on('change', '.icheck_brand', RebuildFilters);
        $('.icheck_user').on('change', RebuildFilters);
		$(document).on('change', '.icheck_country', RebuildFilters);
        
        //Начинаем подгрузки всего всего
        //Категории
        var brand = "<?php echo (isset($_POST['brand_id'])) ? $_POST['brand_id'] : 0; ?>";
        $.ajax({
            type: "POST",
            url: "/ajax/get_sub_categories.php",
            dataType: "json",
            data: "parent=<?php echo $data['category_id'] ;?>&brand="+brand,
            beforeSend: function(){
            },
            success: function(msg){
                
                //console.log(msg);
                var html = '';
                $.each(msg, function( index, value ) {
                    
                    if (index != '<?php echo $data['category_id'] ;?>') {
                        html = html + '<li class="l-menu__sub-item">';
                        html = html + '<a href="<?php echo HOST_URL; ?>/'+value.url+'.html" class="l-menu__sub-link">';
                        html = html + ''+value.name+' ('+value.tovar_count+')';
                        html = html + '</a>';
                        <?php if(isset($_SESSION[BASE.'usersetup']) AND strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
                        html = html + '&nbsp;&nbsp;<a href="<?php echo HOST_URL;?>/admin/edit_parent_inet.php?parent_inet_id='+index+'" target="_blank"><font color=red><b>ред.</b></font></a>';
                        <?php }?>
                        html = html + '</li>';
                    }                    
                });
                
                $('.sub_categories').html(html);
            }
        });
        
        
        //Цвета у продуктов
        $('.product_colors').each(function( index, value ) {
        
            console.log($(this).data('id'));
            element = $(this);
            
            $.ajax({
                type: "POST",
                url: "/ajax/get_colors_prod.php",
                dataType: "json",
                data: "artkl="+element.data('id')+"",
                beforeSend: function(){
                },
                success: function(msg){
                    //console.log(msg);
                    var html = '<div class="product__colors">';
                    var count = 1;
                    
                    $.each(msg.colors, function( index, value ) {
                    
                        if (count < 6) {
                         
                            if (index < 10) {
                                index_color = '00'+index;
                            }else if (index < 100) {
                                index_color = '0'+index;
                            }else{
                                index_color = index;
                            }
                            html = html + '<a href="/'+value.alias+'.html"><span class="product__color" title="'+value.name+'" style="margin-right: 0px;background-image: url(/resources/colors/'+index_color+'.png);"></span></a>';
                        
                            count = count+1;
                        }
                    });
                    //console.log('#colors_'+msg.id);
                    $('#colors_'+msg.id).html(html+'</div>');
                
                }
            });
        });
        
        //Цвета
        $.ajax({
            type: "POST",
            url: "/ajax/get_colors.php",
            dataType: "json",
            data: "parent=<?php echo $data['category_id'] ;?>&brand="+brand,
            beforeSend: function(){
            },
            success: function(msg){
                //console.log(msg);
                var html = '<h4 class="filters__sub-title ">Цвет</h4><div class="filters__row__color filters__group_small">';
                var filter_2 = "<?php if(isset($_GET['filter-2'])){echo '*'.implode('*',$_GET['filter-2']).'*';} ?>";

                $.each(msg, function( index, value ) {
                    
                        check = '';
                        if (filter_2.indexOf('*'+value+'*') >= 0) {
                            check = ' checked ';
                            
                        }
                     console.log(value);

                        html = html + '<div class="filters__row">';
                        html = html + '<div class="form__checkbox icheck-item'+check+'">';
                        html = html + '<input type="checkbox" '+check+' class="icheck icheck_filter" name="filter-2[]" id="filter-2" data-name="filter-2" data-id="2" data-checkboxClass="form__checkbox" value="'+value+'"';
                        html = html + '>';
                        html = html + '</div>';
                        html = html + '<div class="form__check-label-wrapper">';
                        html = html + '<span class="product__color" style="width:32px; height:22px; margin: 0px 10px 0px 0px;background-image: url(/resources/colors/'+index+'.png);"></span>';
                        html = html + '<label for="filter-2-'+value+'"" class="form__check-label">'+value+'</label>';
                        html = html + '</div>';
                        html = html + '</div>';
                  
                });
                $('.filter_colors').html(html+'</div><div style="clear: both"></div>');
                 
            }
        });
       
        $(document).on('click', '.icheck-item', function(){
            
            if ($(this).hasClass('checked')) {
                $(this).removeClass('checked');
                $(this).children('input').attr('checked', false);
               }else{
                $(this).addClass('checked');
                $(this).children('input').attr('checked', true);
            }
			RebuildFilters();
        }); 

        //Бренды и страны
        $.ajax({
            type: "POST",
            url: "/ajax/get_brands.php",
            dataType: "json",
            data: "parent=<?php echo $data['category_id'] ;?>&brand="+brand,
            beforeSend: function(){
            },
            success: function(msg){
                var brands = "<?php if(isset($_GET['brand'])){echo '*'.implode('*',$_GET['brand']).'*';} ?>";
                var country = "<?php if(isset($_GET['country'])){echo '*'.implode('*',$_GET['country']).'*';} ?>";
                var check = '';
                
                var html = '<h4 class="filters__sub-title ">Производитель</h4><div class="filters__row__brand filters__group_small">';
                var html1 = '<h4 class="filters__sub-title">Страна</h4><div class="filters__row__country filters__group_small">';
                $.each(msg.brands, function( index, value ) {
                    
                        check = '';
                        if (brands.indexOf('*'+index+'*') >= 0) {
                            check = ' checked ';
                        }
                     
                        html = html + '<div class="filters__row">';
                        html = html + '<div class="form__checkbox icheck-item'+check+'">';
                        html = html + '<input type="checkbox" '+check+' class="icheck icheck_brand" name="brand[]"  id="brand-'+index+'" data-name="brand" data-id="'+index+'" data-checkboxClass="form__checkbox" value="'+index+'"';
                     
                        html = html + '>';
                        html = html + '</div>';
                        html = html + '<div class="form__check-label-wrapper">';
                        html = html + '<label for="brand-'+index+'"" class="form__check-label">'+value.name+'</label>';
                        html = html + '</div>';
                        html = html + '</div>';
                  
                });
                $.each(msg.countries, function( index, value ) {
                   //html1 = '<h4 class="filters__sub-title">Страна</h4>';
                        check = '';
                        if (country.indexOf('*'+index+'*') >= 0) {
                            check = ' checked ';
                        }
                     
                        html1 = html1 + '<div class="filters__row">';
                        html1 = html1 + '<div class="form__checkbox icheck-item'+check+'"">';
                        html1 = html1 + '<input type="checkbox" '+check+' class="icheck icheck_country" name="country[]" id="country-'+index+'" data-name="country" data-id="'+index+'" data-checkboxClass="form__checkbox" value="'+index+'"';
                      
                        html1 = html1 + '>';
                        html1 = html1 + '</div>';
                        html1 = html1 + '<div class="form__check-label-wrapper">';
                        html1 = html1 + '<label for="country-'+index+'"" class="form__check-label">'+value+'</label>';
                        html1 = html1 + '</div>';
                        html1 = html1 + '</div>';
                        html1 = html1 + '</div>';
                  
                });
                
                $('.filter_brands').html(html+'</div><div style="clear: both"></div>');
                $('.filter_countries').html(html1+'</div><div style="clear: both"></div>');
                 
            }
        });
        
    });
    
</script>
<style>
    .filters__row{
        padding-bottom: 2px;
        padding-top: 2px;
    }
    .form__checkbox{
            position:relative;
            float:left;
            width:22px;
            height:22px;
            background:#fff;
            cursor:pointer;
            -moz-transition:border-color 0.2s ease;
            -o-transition:border-color 0.2s ease;
            -webkit-transition:border-color 0.2s ease;
            transition:border-color 0.2s ease;
            }
    .filters__group_small{
        max-height: 200px;
        overflow:  hidden;
    }
    
</style>
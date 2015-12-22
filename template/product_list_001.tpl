<link rel="stylesheet" medis="screen" type="text/css" href="<?php echo HOST_URL;?>/css/product_list.css">
<div class="product_main">
<!-- Left column -->
    <div class="left_col">
        <!-- filter menu -->
        <div class="left_menu">
            <?php if(isset($products_info['parent'])){ ?>
            <label class="left_menu_title">Категории</label>
                <ul class="attribute_filter_main">
                    <?php foreach($products_info['parent'] as $index => $value){ ?>
                        <li class="attribute_filter" >
                            <a href="<?php echo HOST_URL.'/'.$value['alias'];?>" title="<?php echo $value['name'];?>">
                                <?php echo $value['name']. ' ('.$products_parent_items[$index].')';?>
                            </a>
                            <?php if(isset($_SESSION[BASE.'usersetup']) AND strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
                                &nbsp;&nbsp;<a href="<?php echo HOST_URL;?>/admin/edit_parent_inet.php?parent_inet_id=<?php echo $value['id'];?>"><font color=red><b>ред.</b></font></a>
                            <?php }?>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>

<!-- Бренды -->  
            <?php if(isset($brands) AND count($brands) > 0 ){ ?>
                    <label class="left_menu_title">Производитель</label>
                    <ul class="attribute_filter_main">
                        <?php foreach($brands as $i => $item){ ?>
                            <li class="attribute_filter">
                                <input type="checkbox" class="brand_filter_check" id="brand-<?php echo $index?>" value="<?php echo $item;?>"
                                <?php if(isset($_GET['brand-'.$index][$item])) echo ' checked '; ?>
                                value="<?php echo $i; ?>"><?php echo $item;?>
                            </li>
                        <?php } ?>
                    </ul>
             <?php } ?>

<!-- Страны -->  
            <?php if(isset($country) AND count($country) > 0 ){ ?>
                    <label class="left_menu_title">Страна</label>
                    <ul class="attribute_filter_main">
                        <?php foreach($country as $i => $item){ ?>
                            <li class="attribute_filter">
                                <input type="checkbox" class="country_filter_check" id="country-<?php echo $index?>" value="<?php echo $item;?>"
                                <?php if(isset($_GET['country-'.$index][$item])) echo ' checked '; ?>
                                value="<?php echo $i; ?>"><?php echo $item;?>
                            </li>
                        <?php } ?>
                    </ul>
            <?php } ?>

<!-- Атрибуты -->  
            <?php if(isset($attribute_filter)){ ?>
                <?php foreach($attribute_filter as $index => $value){ ?>
                    <label class="left_menu_title"><?php echo $value['title']; ?></label>
                    <ul class="attribute_filter_main">
                        <?php foreach($value['value'] as $i => $item){ ?>
                            <li class="attribute_filter">
                                <input type="checkbox" class="attribute_filter_check" id="filter-<?php echo $index?>" value="<?php echo $item;?>"
                                <?php if(isset($_GET['filter-'.$index][$item])) echo ' checked '; ?>
                                value="<?php echo $i; ?>"><?php echo $item;?>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            <?php } ?>
        </div>
    </div>

<!-- Main right col -->
<div class="right_col">
    <!-- product list -->
        <div class="product_list">
            <?php if(isset($products) && count($products) > 0){ ?>
                <?php foreach($products as $artkl => $product){ ?>
                        <a href="<?php echo $product['alias']; ?>" target="_blank">
                            <div class="product">
                            <!-- Product title-->               
                                <div class="product_title">
                                    <?php echo '('.$artkl.') '.$product['name']?>
                                </div> 
                            
                            <!-- Product picture-->               
                                <div class="product_pic">
                                    <img src="<?php echo $product['img']?>" title="<?php echo $product['name']?>" wigth="200" height="200">
                                    
                                    <?php if(isset($_SESSION[BASE.'usersetup']) AND strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
                                        <div style="display: block; top:10px;">
                                            &nbsp;&nbsp;<a href="<?php echo HOST_URL;?>/admin/edit_tovar.php?tovar_id=<?php echo $product['id'];?>" target="_blank"><font color=red><b>редактировать</b></font></a>
                                        </div>
                                    <?php }?>
                                
                                </div>
                                
                            <!-- Product brand country-->               
                                <div class="product_brand_country">
                                    <?php if(isset($product['brand_country'])){ ?>
                                        <li class="product_brand_country_li"><?php echo $product['brand_country'];?></li>
                                    <?php } ?>
                                </div>
                      
                           <!-- Product characteristic-->               
                                <div class="product_characteristic">
                                    <?php if(isset($product['attributes'])){ ?>
                                        <ul>
                                            <?php foreach($product['attributes'] as $char_value){ ?>
                                                <li><?php echo $char_value['name'];?> - <b><?php echo $char_value['value'];?></b></li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </div>
                      
                            <!-- Product memo-->
                            <a href="<?php echo $product['alias']; ?>" target="_blank">
                                <div class="product_memo">
                                    <?php if(isset($product['memo']) AND $product['memo'] != ''){ ?>
                                        <?php echo $product['memo'];?>&nbsp;
                                    <?php } ?>
                                </div> 
                            </a>
                            <!-- Product Price-->
                            <!--a href="<?php echo $product['alias']; ?>" target="_blank"-->
                                <div class="product_price">
                                    <?php echo ceil($product['price']).' '.$currency_name[$setup['price default lang']];?>
                                </div> 
                            <!--/a-->
                            <!-- Product KEY-->
                            <a href="<?php echo $product['alias']; ?>" target="_blank">
                                <div class="product_key">
                                    КУПИТЬ
                                </div>
                            </a>
                      
                            </div>
                        </a>
                <?php } ?>
            <?php }else{ ?>
                <h3>Не найдено товаров</h3>
            <?php } ?>
        </div>
    </div>
    
</div>
<div style="clear: both;"></div>

<script>
    $(document).ready(function(){
        
        $('.attribute_filter_check').on('change', function(){     
        var params = '';
            $("input:checkbox:checked").each(function(){
                 params = params + this.id+'['+$(this).val()+']&';                               
            });

        var url = window.location.href    
            
        window.location.replace(window.location.pathname + "?"+params);
        });
      
        $('.brand_filter_check').on('change', function(){     
        var params = '';
            $("input:checkbox:checked").each(function(){
                 params = params + this.id+'['+$(this).val()+']&';                               
            });

        var url = window.location.href    
            
        window.location.replace(window.location.pathname + "?"+params);
        });
      
        $('.country_filter_check').on('change', function(){     
        var params = '';
            $("input:checkbox:checked").each(function(){
                 params = params + this.id+'['+$(this).val()+']&';                               
            });

        var url = window.location.href    
            
        window.location.replace(window.location.pathname + "?"+params);
        });
        
    });
    
</script>
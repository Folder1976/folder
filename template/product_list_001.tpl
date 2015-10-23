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
                        <a href="<?php echo $product['alias']; ?>">
                            <div class="product">
                            <!-- Product title-->               
                                <div class="product_title">
                                    <?php echo '('.$artkl.') '.$product['name']?>
                                </div> 
                            
                            <!-- Product picture-->               
                                <div class="product_pic">
                                    <img src="<?php echo $product['img']?>" title="<?php echo $product['name']?>" wigth="200" height="200">
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
                                <div class="product_memo">
                                    <?php echo $product['memo'];?>&nbsp;
                                </div> 
                            
                            <!-- Product Price-->               
                                <div class="product_price">
                                    <?php echo $product['price'].' '.$currency_name[$setup['price default lang']];?>
                                </div> 
                            
                            <!-- Product KEY-->               
                                <div class="product_key">
                                    КУПИТЬ
                                </div> 
                      
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
        
    });
    
</script>
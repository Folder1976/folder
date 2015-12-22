<link rel="stylesheet" media="screen" type="text/css" href="<?php echo HOST_URL; ?>/css/product_form.css">
<script src="<?php echo HOST_URL; ?>/lightbox2-master/dist/js/lightbox-plus-jquery.min.js"></script>
 <link rel="stylesheet" href="<?php echo HOST_URL; ?>/lightbox2-master/dist/css/lightbox.css">
<!-- Main right col -->
<div class="product_main">
    <div class="product_title">
        <h1><?php echo $product['artkl'].' - '.$product['name'];?></h1>
    </div>
    <div class="product_info_wrap">
        <div class="product_photos" id="gallery">
            <!--img title="" src="" class="main_photo"-->
                <?php echo $product['photos'] ?>
        </div>
        <div class="product_info">
            <div class="card">
                <table class="product_size">
                    <tr>
                        <!--th>Артикл</th-->
                        <th>Размер</th>
                        <th>Цена</th>
                        <th>Наличие</th>
                        <th></th>
                        <th>Заказать</th>
                    </tr>
                    <?php foreach($product['size'] as $value){?>
                        <tr>
                            <!--td align=left>
                                <?php echo $product['artkl'];?>
                                <?php if($value['size'] != 'нет') echo $separator.$value['size'];?>
                            </td-->
                            <td align=center>    
                                <?php echo $value['size']; ?>
                                <?php if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
                                    &nbsp;&nbsp;
                                        <a href="/admin/edit_tovar.php?tovar_id=<?php echo $value['id']; ?>" class="admin" target="_blank">
                                        <b><font color=red>ред.</font></b></a>
                                <?php }?>
                            </td>
                            <td align=center class="price">
                                <?php echo ceil($value['price']).' '.$currency_name[$setup['price default lang']]; ?>
                            </td>
                          
                            <td align=center>
                                <?php if($value['items'] == 0){ ?>
                                    <label class="no_item">нет в наличии</label>
                                <?php }else{ ?>
                                    <label class="is_item">есть</label>
                                <?php } ?>
                            </td>
                            <td align=center>
                                <input type="text" class="order_in"  id="<?php echo $value['id']; ?>" value="1">
                            </td>
                            <td align=center>
                                <button class='add_to_cart' id='add*<?php echo $value['id']; ?>' OnClick='addtovar(this.id);'>
                                <!--img src='resources/cart.png'>&nbsp;&nbsp;-->В КОРЗИНУ</button>
	        
                            </td>
                        </tr>
               
                    <?php }?>
                </table>
            </div>
            
            <div class="product_attribute">
                <?php if(isset($product['attributes'])){?>
                    <label class=product_attribute_title>Характеристики</label><br>
                    <table class="attribute_list">
                        <?php foreach($product['attributes'] as $value){ ?>
                            <tr>
                                <td width="200px"><?php echo $value['name'].'</td><td><b>'.$value['value']; ?></b></td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
            </div>
            
	    <?php if(isset($product['tovar_size_table']) AND $product['tovar_size_table'] != ''){ ?>
		<div class="product_memo_main">
		    <label class=product_attribute_title>Таблица размеров</label>
		    <div class="product_size_table">
			<?php echo $product['tovar_size_table'];?>
			<?php //echo '<pre>'; print_r(var_dump($product['size']));?>
		    </div>
		</div>
	    <?php } ?>
	    
	    <?php if(isset($product['memo']) AND $product['memo'] != ''){ ?>
		<div class="product_memo_main">
		    <label class=product_attribute_title>Описание</label>
		    <div class="product_memo">
			<?php echo $product['memo'];?>
			<?php //echo '<pre>'; print_r(var_dump($product['size']));?>
		    </div>
		</div>
	    <?php } ?>
	    
	    <?php if(isset($product['tovar_video_url']) AND $product['tovar_video_url'] != ''){ ?>
		<div class="product_memo_main">
		    <label class=product_attribute_title>Видео</label>
		    <div class="product_youtube">
			<?php echo $product['tovar_video_url'];?>
			<?php //echo '<pre>'; print_r(var_dump($product['size']));?>
		    </div>
		</div>
	    <?php } ?>


        </div>
    </div>
    
    
</div>

<div style="clear: both;"></div>

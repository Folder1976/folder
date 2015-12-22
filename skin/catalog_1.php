<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Купить '.$categ_selected['name']; ?>
        <?php $description = 'В этом разделе вы можете купить '.$categ_selected['name'] . ' с доставкой по России'; ?>
   
        <?php include_once SKIN_PATH.'header_main.php'; ?>    
        <?php include_once SKIN_PATH.'header_includes.php'; ?>
        <?php
                //$categories
                //$categ_selected
                //$category_children
        
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
                <a href="<?php echo HOST_URL; ?>" class="breadcrumb__name"><span class="fa fa-home"></span></a>
            </div>
            
            <div class="breadcrumb breadcrumb_last">
                <span class="breadcrumb__name"><?php echo $categ_selected['name']; ?></span>
            </div>
            
        </div>
    </div>
</div>

<div class="row section">
    <div class="medium-17 columns section__col-content right">
        <h1 class="section__title"><?php echo $categ_selected['name']; ?> - Armma.ru</h1>
        <div class="catalog">
            <?php if($category_children){ ?> 
                <?php foreach($category_children as $children){ ?>
                      <?php if($children['id'] != $categ_selected['id']){ ?>
                                <a href="<?php echo HOST_URL.'/'.$children['url']; ?>.html" class="catalog__item">
                                    <span class="catalog__img" style="background-image: url(<?php echo HOST_URL; ?>/resources/products/!category/<?php echo $children['id']; ?>.medium.jpg);"></span>
                                    <span class="catalog__title"><?php echo $children['name']; ?>
                                    </span>
                                </a>
                        <?php } ?>
                <?php } ?>
            <?php } ?>
        </div>

        <div class="row">
            <div class="medium-12 columns catalog-banner">
                <a href="#"><img src="<?php echo SKIN_URL; ?>/img/catalog/banners/banner_1.jpg" alt=""></a>
            </div>

            <div class="medium-12 columns catalog-banner">
                <a href="#"><img src="<?php echo SKIN_URL; ?>/img/catalog/banners/banner_2.jpg" alt=""></a>
            </div>
        </div>
    </div>

    <div class="medium-7 columns left hide-for-small-only">
        <ul class="l-menu">
        <?php if($categories){ ?>
            <?php foreach($categories as $ind => $categ){ ?>
                    <li class="l-menu__item">
                        <?php if($ind == $categ_selected['id']){ ?>
                        <a href="<?php echo HOST_URL.'/'.$categ['url']; ?>.html" class="l-menu__link l-menu__link_current"><?php echo $categ['name']; ?></a>
                        <ul class="l-menu__sub">
                            <?php foreach($category_children as $children) { ?>
                                <?php if($children['id'] != $categ_selected['id']){ ?>
                                        <li class="l-menu__sub-item"><a href="<?php echo HOST_URL.'/'.$children['url']; ?>.html" class="l-menu__sub-link"><?php echo $children['name']; ?></a>
                                        <?php if(isset($_SESSION[BASE.'usersetup']) AND strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
                                                &nbsp;&nbsp;<a href="<?php echo HOST_URL;?>/admin/edit_parent_inet.php?parent_inet_id=<?php echo $children['id'];?>"><font color=red><b>ред.</b></font></a>
                                        <?php }?>
                               
                                        </li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    <?php }else{ ?>
                        <a href="<?php echo HOST_URL.'/'.$categ['url']; ?>.html" class="l-menu__link"><?php echo $categ['name']; ?></a>
                    <?php } ?>
                    </li>
            <?php } ?>
        <?php } ?>
        </ul>
    </div>
</div>

    <?php include SKIN_PATH . 'body_brands.php'; ?>
    <?php include SKIN_PATH . 'body_sections.php'; ?>
    <?php include SKIN_PATH . 'footer.php'; ?>
</div>
    <?php include SKIN_PATH . 'footer_includes.php';?></body>
</html>

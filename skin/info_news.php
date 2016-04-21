<!doctype html>
<html class="no-js" lang="">
<head>
        <?php //$title = 'Новости'; ?>
        <?php //$description = 'Новости.'; ?>
    
        <?php include_once SKIN_PATH.'header_main.php'; ?>    
        <?php include_once SKIN_PATH.'header_includes.php'; ?>
        <?php
        //header("Content-Type: text/html; charset=UTF-8");
        //echo "<pre>";  print_r(var_dump( $user )); echo "</pre>";
        
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
                <span class="breadcrumb__name"><?php echo $breadcrumb;?></span>
            </div>
            
        </div>
    </div>
</div>

<div class="section">
    <div class="row">
        <div class="small-24 columns section__header">
            <h1 class="section__title">Статьи и Обзоры</h1>
        </div>
    </div>
<?php

//echo "<pre>";  print_r(var_dump( $news )); echo "</pre>";
?>
<?php foreach($news as $index => $new){ ?>
    <div class="row">
        <div class="medium-18 columns">
            <h3 style="text-align: left;"><?php echo $new['date'] . ' : ' . $new['h1']; ?></h3>
        </div>
        <div class="medium-6 columns text-right small-only-text-left">
            <a href="javascript:" class="btn btn_light brands_key news_key" data-id="body_<?php echo $index; ?>">Читать полностью</a>
        </div>    
        <div style="clear: both;"></div>
        <div class="medium-18 columns text" id="body_<?php echo $index; ?>">
            <?php echo $new['text']; ?>
            
        </div>
        
        <div class="cart__header" style="margin-bottom: 35px; margin-top: 15px;"></div>
    </div>
    
<?php } ?>    
    
    
</div>
<style>
    .text{
        height: 60px;
        overflow: hidden;
    }
</style>
<script>
        $(document).on('click', '.news_key', function(){
            var target = $(this).data('id');
            $('#'+target).css('height','auto');
        });
        
    $(document).on('click', '.text', function(){
        $('.brends').css('height','auto');   
    });
</script>

<?php include SKIN_PATH . 'footer.php'; ?>

</div>
<?php include SKIN_PATH . 'footer_includes.php';?>
</body>
</html>

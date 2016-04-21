<!doctype html>
<html class="no-js" lang="">
<head>
        <?php //$title = 'О нас'; ?>
        <?php //$description = 'Компания  ARMMA основана в 2009 году.'; ?>
    
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
                <span class="breadcrumb__name"><?php echo $title; ?></span>
            </div>
            
        </div>
    </div>
</div>

<div class="section">
    <div class="row">
        <div class="small-24 columns section__header">
            <h1 class="section__title"><?php echo $h1; ?></h1>
        </div>
    </div>
<style>
        
</style>
    <div class="row">
        <?php echo $text; ?>
    </div>
</div>


<?php include SKIN_PATH . 'footer.php'; ?>

</div>
<?php include SKIN_PATH . 'footer_includes.php';?>
</body>
</html>

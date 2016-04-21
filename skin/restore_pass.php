<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Востановление пароля'; ?>
        <?php $description = 'В этом разделе мы поможем Вам востановить пароль к Вашему аккаунту'; ?>
    
        <?php include_once SKIN_PATH.'header_main.php'; ?>    
        <?php include_once SKIN_PATH.'header_includes.php'; ?>
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
                <a href="<?php echo HOST_URL;?>" class="breadcrumb__name"><span class="fa fa-home"></span></a>
            </div>
            
            <div class="breadcrumb breadcrumb_last">
                <span class="breadcrumb__name">Востановление пароля</span>
            </div>
            
        </div>
    </div>
</div>

<div class="section">
    <div class="row">
        <div class="small-24 columns section__header">
            <h2 class="section__title">Востановление пароля</h2>
        </div>
    </div>
    <div class="row enter">
        <div class="large-20 medium-22 medium-centered columns">
            <!--h3 class="section__sub-title text-center">Тут можно востановить забытый пароль или войти с помощью соцсетей</h3-->
            <div class="enter__body">
                <div class="row">
                        <div class="row form__row">
                                <label for="login-email" class="medium-8 columns form__label msg"></label>
                        </div>
               
                    <form class="small-11 columns form enter__col" data-abide="ajax">
                        <div class="row form__row">
                            <label for="login-email" class="medium-8 columns form__label">Введи Ваш Email</label>
                            <div class="medium-16 columns">
                                <span class="form__error error">Нужен email</span>
                                <input type="email" class="form__input" id="login-email" required>
                            </div>
                        </div>
                        
                        <div class="row form__row">
                            <div class="medium-16 medium-offset-8 columns">
                                <button type="submit" class="btn btn_text-large" id="submit">Выслать новый пароль</button>
                            </div>
                        </div>
                     </form>

                    <div class="small-11 columns enter__col">
                        
                        <div class="form__row">
                            <div class="form__label">Войти через соц.сети</div>
                        </div>

                        <?php global $adapters; foreach ($adapters as $title => $adapter) { ?>
                                <div class="form__row">
                                    <a href="<?php echo $adapter->getAuthUrl(); ?>">Аутентификация через <?php echo ucfirst($title); ?></a>
                                </div>
                        <?php  } ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <?php include SKIN_PATH . 'footer.php'; ?> 
</div>
    <?php include SKIN_PATH . 'footer_includes.php';?>
</body>
</html>

<style>
        .enter__soc_facebook:hover{
                cursor: pointer;
        }
</style>
<script>
        
    $(document).on('click', '#submit', function(){
        
        var email   = $('#login-email').val();
        
        if ($('#login-email').attr('data-invalid') == null ) {
            if(email != ''){   
            
                $.ajax({
                    type: "POST",
                    url: "ajax/user_restore_pass.php",
                    dataType: "json",
                    data: "email="+email,
                    beforeSend: function(){
                        $('.msg').html('Проверяем, отсылаем...');
                        $('#submit').css('display','none');
                    },
                    success: function(msg){
                       //console.log(  msg ); 
                        $('.msg').html(msg.msg);
                        $('#submit').css('display','block');
                       
                    }
                });
                        
 
            }
        }
    });
</script>
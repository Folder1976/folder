<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Регистрация нового пользователя'; ?>
        <?php $description = 'В этом разделе вы можете создать свой аккаунт'; ?>
    
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
                <a href="index.php" class="breadcrumb__name"><span class="fa fa-home"></span></a>
            </div>
            
            <div class="breadcrumb breadcrumb_last">
                <span class="breadcrumb__name">Вход на сайт / Регистрация</span>
            </div>
            
        </div>
    </div>
</div>

<div class="section">
    <div class="row">
        <div class="small-24 columns section__header">
            <h2 class="section__title">Регистрация</h2>
        </div>
    </div>
    <div class="row enter">
        <div class="large-20 medium-22 medium-centered columns">
            <h3 class="section__sub-title text-center">Регистрация нового пользователя</h3>
            <div class="enter__body">
                <div class="row">
                    <form class="small-11 columns form enter__col" data-abide="ajax">
                        <div class="row form__row">
                            <label for="login-name" class="medium-8 columns form__label">Имя</label>
                            <div class="medium-16 columns">
                                <span class="form__error error">Вы не представились</span>
                                <input type="text" class="form__input" id="login-name" required>
                            </div>
                        </div>

                        <div class="row form__row">
                            <label for="login-email" class="medium-8 columns form__label">Email</label>
                            <div class="medium-16 columns">
                                <span class="form__error error">Некорректный email</span>
                                <input type="email" class="form__input" id="login-email" required>
                            </div>
                        </div>
                        
                        <div class="row form__row">
                            <label for="login-name" class="medium-8 columns form__label">Телефон</label>
                            <div class="medium-16 columns">
                                <!--span class="form__error error">Вы не представились</span-->
                                <input type="text" class="form__input" id="login-phone">
                            </div>
                        </div>

                        <div class="row form__row">
                            <label for="login-password" class="medium-8 columns form__label">Пароль</label>
                            <div class="medium-16 columns">
                                <span class="form__error error">Введите пароль</span>
                                <input type="text" class="form__input" id="login-password" required>
                            </div>
                        </div>
                        
                        <div class="row form__row">
                            <div class="medium-16 medium-offset-8 columns">
                                <button type="submit" class="btn btn_text-large" id="submit">Регистрация</button>
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
    <span class="btn-to-top"><img src="img/totop.png" alt=""></span>
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
        $(document).ready(function(){
                console.log('1111');
        $('#vk_login').html('<a href="" class="enter__soc enter__soc_vk"><span class="fa fa-vk"></span><span class="enter__soc-name">ВКонтакте</span></a>');
                console.log('1111');
                });
        
        
    $(document).on('click', '#submit', function(){
        
        var login   = $('#login-name').val();
        var email   = $('#login-email').val();
        var pass    = $('#login-password').val();
        var phone   = $('#login-phone').val();
        
        if ($('#login-name').attr('data-invalid') == null &&
                $('#login-email').attr('data-invalid') == null &&
                    $('#login-password').attr('data-invalid') == null) {
            if(login != '' && email != '' && pass != ''){   
            
                $.ajax({
                    type: "POST",
                    url: "ajax/user.php?registration",
                    dataType: "json",
                    data: "name="+login+"&email="+email+"&pass="+pass+"&phone="+phone,
                    success: function(msg){
                        
                        if (msg['err'] == 'false') {
                                alert(msg['msg']);
                                window.location.href = '<?php echo HOST_URL; ?>';
                        }else{
                                alert(msg['msg']);
                        }
                        
                        console.log(  msg['msg'] );
                    }
                });
                        
 
            }
        }
    });
</script>
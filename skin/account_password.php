<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Персональные данные пользователя'; ?>
        <?php $description = 'В этом разделе вы можете сменить данные своего аккаунта'; ?>
    
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
                <span class="breadcrumb__name">Личный кабинет</span>
            </div>
            
        </div>
    </div>
</div>

<div class="section">
    <div class="row">
        <div class="small-24 columns section__header">
            <h2 class="section__title">Личный кабинет</h2>
        </div>
    </div>
    <div class="row">
    <div class="large-20 medium-22 medium-centered columns">
        <div class="a-menu">
            <a href="account_personal.html" class="a-menu__link">Личные данные</a>
            <a href="account_cart.html" class="a-menu__link">Корзина</a>
            <a href="account_orders.html" class="a-menu__link">Список заказов</a>
            <span class="a-menu__link a-menu__link_active">Изменить пароль</span>
            <a href="account_wardrobe.html" class="a-menu__link">Мой гардероб</a>
        </div>
    </div>
</div>

    <div class="row">
        <form class="large-12 medium-16 medium-offset-2 columns form" data-abide="ajax">
            <h3 class="section__sub-title">Личные данные</h3>
            <fieldset class="form__fieldset">
                <div class="row form__row">
                    <label class="medium-8 columns form__label"><span class="form__label-name">Логин/email</span></label>
                    <div class="medium-14 end columns">
                            <input type="hidden" id="account-id" value="<?php echo $user['klienti_id'];?>">
                            <input type="hidden" id="account-email" value="<?php echo $user['klienti_email'];?>">
                            <div class="form__label"><span class="form__label-name"><?php echo $user['klienti_email'];?></span></div>
                    </div>
                </div>

                <div class="row form__row">
                    <label for="account-name" class="medium-8 columns form__label">Новый пароль</label>
                    <div class="medium-14 end columns">
                        <span class="form__error error">Нельзя оставлять пустым</span>
                        <input type="text" class="form__input" id="account-pass" required value="">
                    </div>
                </div>
            </fieldset>
            <div class="form__row">
                <button type="submit" class="btn btn_text-large submit">Созранить изменения</button>
            </div>
        </form>
    </div>
</div>


 <?php include SKIN_PATH . 'footer.php'; ?> 
    <span class="btn-to-top"><img src="img/totop.png" alt=""></span>
</div>
<?php include SKIN_PATH . 'footer_includes.php';?>
</body>
</html>

<script>
    $(document).on('click', '.submit', function(){
            var pass    = $('#account-pass').val();
            var id      = $('#account-id').val();
        
            $.ajax({
                type: "POST",
                url: "ajax/user_pass.php",
                dataType: "json",
                data: "pass="+pass+"&id="+id,
                success: function(msg){
                    console.log( msg );
                    alert(msg['msg']);
                }
            });
            
        
        });
    
    
</script>

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
             <span class="a-menu__link a-menu__link_active">Личные данные</span>
            <a href="account_cart.html" class="a-menu__link">Корзина</a>
            <a href="account_orders.html" class="a-menu__link">Список заказов</a>
            <a href="account_password.html" class="a-menu__link">Изменить пароль</a>
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
                        <?php if($user['klienti_email'] != ''){ ?>
                            <input type="hidden" id="account-email" value="<?php echo $user['klienti_email'];?>">
                            <div class="form__label"><span class="form__label-name"><?php echo $user['klienti_email'];?></span></div>
                        <?php }else{ ?>
                            <span class="form__error error">Укажите Ваш емаил для связи</span>
                            <input type="text" class="form__input" id="account-email" required value="">
                        <?php } ?>
                    </div>
                </div>

                <div class="row form__row">
                    <label for="account-name" class="medium-8 columns form__label">Имя</label>
                    <div class="medium-14 end columns">
                        <span class="form__error error">Вы не представились</span>
                        <input type="text" class="form__input" id="account-name" required value="<?php echo $user['klienti_name_1'];?>">
                    </div>
                </div>

                <div class="row form__row">
                    <label for="account-phone" class="medium-8 columns form__label">Контактный телефон</label>
                    <div class="medium-14 small-20 columns">
                        <span class="form__error error">Укажите ваш контактный телефон</span>
                        <input type="tel" class="form__input" id="account-phone" required value="<?php echo $user['klienti_phone_1'];?>">
                    </div>
                    <div class="medium-2 small-4 columns text-center"><span class="form__label form__action form__action_del"><!--span class="fa fa-times"></span--></span></div>
                </div>

                <div class="row form__row">
                    <div class="medium-offset-8 medium-14 end columns">
                        <!--button type="button" class="btn btn_link-normal">Добавить еще один</button-->
                    </div>
                </div>

                <div class="row form__row">
                    <label class="medium-8 columns form__label">Адрес</label>
                    <div class="medium-14 small-20 columns">
                        <span class="form__label"><!--span class="form__label-name form__label-name_link">Екатеринбург</span--></span>
                    </div>
                    <div class="medium-2 small-4 columns text-center"><span class="form__label form__action form__action_del"><!--span class="fa fa-times"></span--></span></div>
                </div>

                <div class="row form__row">
                    <label for="account-address-city" class="medium-8 columns form__label">Город</label>
                    <div class="medium-14 end columns">
                        <!--span class="form__error error">Обязательное поле</span-->
                        <input type="text" class="form__input" id="account-address-city" value="<?php echo $user['klienti_sity'];?>">
                    </div>
                </div>

                <div class="row form__row">
                    <label for="account-address-street" class="medium-8 columns form__label">Улица</label>
                    <div class="medium-14 end columns">
                        <!--span class="form__error error">Обязательное поле</span-->
                        <input type="text" class="form__input" id="account-address-street" value="<?php echo $user['klienti_adress'];?>" placeholder="ул. Куйбышева, дом 55, корпус 1, кв.45">
                    </div>
                </div>

                <!--div class="row form__row">
                    <label for="account-address-building" class="medium-8 columns form__label">Номер дома</label>
                    <div class="medium-10 small-12 end columns">
                        <span class="form__error error">Обязательное поле</span>
                        <input type="text" class="form__input" id="account-address-building" required value="55, корпус 1">
                    </div>
                </div-->

                <!--div class="row form__row">
                    <label for="account-apart" class="medium-8 columns form__label">Квартира/офис</label>
                    <div class="medium-10 small-12 end columns">
                        <input type="text" class="form__input" id="account-apart" value="45">
                    </div>
                </div-->

                <!--div class="row form__row">
                    <div class="medium-offset-8 medium-14 end columns">
                        <button type="button" class="btn btn_link-normal">Сохранить</button>
                    </div>
                </div-->

                <!--div class="row form__row">
                    <div class="medium-offset-8 medium-14 end columns">
                        <button type="button" class="btn btn_link-normal">Добавить еще адрес</button>
                    </div>
                </div-->
            </fieldset>

            <!--fieldset class="form__fieldset">
                <div class="row">
                    <div class="medium-11 columns form__row">
                        <a href="" class="enter__soc enter__soc_vk"><span class="fa fa-vk"></span><span class="enter__soc-name">ВКонтакте</span></a>
                    </div>

                    <div class="medium-11 columns form__row">
                        <a href="" class="enter__soc enter__soc_facebook"><span class="fa fa-facebook"></span><span class="enter__soc-name">Facebook</span></a>
                    </div>
                </div>
            </fieldset-->

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
            var email   = $('#account-email').val();
            var name    = $('#account-name').val();
            var phone   = $('#account-phone').val();
            var sity    = $('#account-address-city').val();
            var address = $('#account-address-street').val();
            var id      = $('#account-id').val();
        
            $.ajax({
                type: "POST",
                url: "ajax/user_personal.php",
                dataType: "json",
                data: "name="+name+"&email="+email+"&phone="+phone+"&sity="+sity+"&address="+address+"&id="+id,
                success: function(msg){
                    console.log( msg );
                    alert(msg['msg']);
                }
            });
            
        
        });
    
    
</script>

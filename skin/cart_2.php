<!doctype html>
<html class="no-js" lang="">
<head>
        <?php $title = 'Оформление заказа '; ?>
        <?php $description = 'Оформление заказа'; ?>
    
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
            
            <div class="breadcrumb">
                <a href="<?php echo HOST_URL;?>/account_cart.html" class="breadcrumb__name">Корзина</a>
            </div>
            
            <div class="breadcrumb breadcrumb_last">
                <span class="breadcrumb__name">Оформление заказа</span>
            </div>
            
        </div>
    </div>
</div>

<div class="section">
    <div class="row">
        <div class="small-24 columns section__header">
            <h2 class="section__title">Оформление заказа</h2>
        </div>
    </div>

    <div class="row">
        <div class="large-16 medium-18 columns medium-centered">
            <form action="<?php echo HOST_URL; ?>/order.html" METHOD="POST" class="form" data-abide="ajax">
                <fieldset class="form__fieldset">
                    <div class="row form__row">
                        <label for="buyer-name" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Ваше Фамилия Имя</span></label>
                        <div class="large-10 medium-16 end columns">
                            <span class="form__error error">Вы не представились</span>
                            <input type="text" class="form__input" name="buyer-name"  id="buyer-name" required value="<?php echo isset($user['klienti_name_1']) ? $user['klienti_name_1'] : ''; ?>">
                        </div>
                    </div>

                    <div class="row form__row">
                        <label for="buyer-email" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Email</span></label>
                        <div class="large-10 medium-16 end columns">
                            <span class="form__error error">Некорректный email</span>
                            <input type="email" class="form__input" name="buyer-email" id="buyer-email" required value="<?php echo isset($user['klienti_email']) ? $user['klienti_email'] : ''; ?>">
                        </div>
                    </div>

                    <div class="row form__row">
                        <label for="buyer-phone" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Контактный телефон</span></label>
                        <div class="large-10 medium-16 end columns">
                            <span class="form__error error">Укажите ваш контактный телефон</span>
                            <input type="tel" class="form__input" name="buyer-phone" id="buyer-phone" required value="<?php echo isset($user['klienti_phone_1']) ? $user['klienti_phone_1'] : ''; ?>">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="form__fieldset">
                    <div class="row form__row">
                        <label class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Способ доставки</span></label>
                        <div class="large-14 medium-16 end columns">
                            <div class="form__check-row">
                                <input type="radio" name="delivery" class="icheck" id="delivery-courier" value="delivery-courier" data-radioClass="form__radio" checked>
                                <div class="form__check-label-wrapper">
                                    <label for="delivery-courier" class="form__check-label">Курьерская доставка</label>
                                </div>
                            </div>

                            <!--div class="form__check-row">
                                <input type="radio" name="delivery" class="icheck" id="delivery-pickup" value="delivery-pickup" data-radioClass="form__radio">
                                <div class="form__check-label-wrapper">
                                    <label for="delivery-pickup" class="form__check-label">Самовывоз из авторизированной точки</label>
                                </div>
                            </div-->

                            <div class="form__check-row">
                                <input type="radio" name="delivery" class="icheck" id="delivery-post" value="delivery-post" data-radioClass="form__radio">
                                <div class="form__check-label-wrapper">
                                    <label for="delivery-post" class="form__check-label">Служба доставки</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form__row">
                        <label for="buyer-city" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Город</span></label>
                        <div class="large-10 medium-16 end columns">
                            <input type="text" class="form__input" name="buyer-city" id="buyer-city" value="<?php echo isset($user['klienti_sity']) ? $user['klienti_sity'] : ''; ?>">
                            <!--select name="" id="buyer-city" class="form__select">
                                <option value="">Москва</option>
                                <option value="">С-Петербург</option>
                                <option value="">Екатеринбург</option>
                                <option value="">Иркутск</option>
                                <option value="">Казань</option>
                                <option value="">Краснодар</option>
                                <option value="">Нижний Новгород</option>
                                <option value="">Новосибирск</option>
                                <option value="">Ростов-на-Дону</option>
                                <option value="">Самара</option>
                                <option value="">Владикавказ</option>
                                <option value="">Волгоград</option>
                                <option value="">Воронеж</option>
                                <option value="">Ижевск</option>
                                <option value="">Калининград</option>
                                <option value="">Красноярск</option>
                                <option value="">Мурманск</option>
                                <option value="">Омск</option>
                                <option value="">Оренбург</option>
                                <option value="">Пермь</option>
                                <option value="">Сургут</option>
                                <option value="">Астрахань</option>
                                <option value="">Сыктывкар</option>
                                <option value="">Барнаул</option>
                                <option value="">Махачкала</option>
                                <option value="">Тамбов</option>
                                <option value="">Белгород</option>
                                <option value="">Мурманск</option>
                                <option value="">Тверь</option>
                                <option value="">Брянск</option>
                                <option value="">Нижний Новгород</option>
                                <option value="">Тольятти</option>
                                <option value="">Великий Новгород</option>
                                <option value="">Новокузнецк</option>
                                <option value="">Томск</option>
                            </select-->
                        </div>
                    </div>

                    <div class="row form__row">
                        <label for="buyer-city" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Адрес</span></label>
                        <div class="large-10 medium-16 end columns">
                            <input type="text" class="form__input" name="buyer-address" id="buyer-address" value="<?php echo isset($user['klienti_adress']) ? $user['klienti_adress'] : ''; ?>">
                        </div>
                    </div>

                    <div class="row form__row">
                        <label for="buyer-delivery-system" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Служба доставки</span></label>
                        <div class="large-10 medium-16 end columns">
                            <select name="buyer-delivery-system" id="buyer-delivery-system" class="form__select">
                                <option value="0">Выбрать...</option>
                                <?php if(isset($transport['city'])){ ?>
                                <?php foreach($transport['city'] as $city){ ?>
                                        
                                        <option value="<?php echo $city['TranspID'];?>"><?php echo $city['TranspSmNazv']; ?> </option>
                                
                                <?php } ?>
                                <?php } ?>
                              </select>
                        </div>
                    </div>
                </fieldset>
                
                <fieldset class="form__fieldset">
                    <div class="row form__row">
                        <label class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Способ оплаты</span></label>
                        <div class="large-14 medium-16 end columns">
                            <div class="form__check-row">
                                <input type="radio" name="payments" class="icheck" value="payment-courier" id="payment-courier" data-radioClass="form__radio" checked>
                                <div class="form__check-label-wrapper">
                                    <label for="payment-courier" class="form__check-label">Наличными курьеру</label>
                                </div>
                            </div>

                            <div class="form__check-row">
                                <input type="radio" name="payments" class="icheck" value="payment-pickup" id="payment-pickup" data-radioClass="form__radio">
                                <div class="form__check-label-wrapper">
                                    <label for="payment-pickup" class="form__check-label">Наличными при самовывозе</label>
                                </div>
                            </div>

                            <div class="form__check-row">
                                <input type="radio" name="payments" class="icheck" value="payment-post" id="payment-post" data-radioClass="form__radio">
                                <div class="form__check-label-wrapper">
                                    <label for="payment-post" class="form__check-label">Электронные системы оплаты</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                
                <fieldset class="form__fieldset">
                    <div class="row form__row">
                        <label for="buyer-city" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Ваш комментарий к заказу</span></label>
                        <div class="large-10 medium-16 end columns">
                            <input type="text" class="form__input" name="buyer-komment" id="buyer-komment">
                        </div>
                    </div>
                </fieldset>
              

                    <!--div class="row form__row">
                        <label for="buyer-delivery-point" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Служба доставки</span></label>
                        <div class="large-10 medium-16 end columns">
                            <select name="" id="buyer-delivery-point" class="form__select">
                                <option value="">ул. Куйбышева, 85</option>
                                <option value="">ул. 50-ти летия РККА, 112, строение 2</option>
                            </select>
                        </div>
                    </div-->

                    <!--div class="form__map form__map_outside">
                        <div class="form__map-inner" id="form-map" data-address="Бродников пер., 3,Москва"></div>
                    </div-->
               

             
                <!--fieldset class="form__fieldset">
                    <div class="row form__row">
                        <label for="buyer-additional-city" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Город</span></label>
                        <div class="large-10 medium-16 end columns">
                            <input type="text" class="form__input" id="buyer-additional-city">
                        </div>
                    </div>

                    <div class="row form__row">
                        <label for="buyer-additional-street" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Улица</span></label>
                        <div class="large-10 medium-16 end columns">
                            <input type="text" class="form__input" id="buyer-additional-street">
                        </div>
                    </div>

                    <div class="row form__row">
                        <label for="buyer-additional-building" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Номер дома</span></label>
                        <div class="large-10 medium-16 end columns">
                            <input type="text" class="form__input" id="buyer-additional-building">
                        </div>
                    </div>

                    <div class="row form__row">
                        <label for="buyer-apart" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Квартира/офис</span></label>
                        <div class="large-10 medium-16 end columns">
                            <input type="text" class="form__input" id="buyer-apart">
                        </div>
                    </div>

                    <div class="row form__row">
                        <label for="buyer-floor" class="large-6 large-offset-4 medium-8 columns form__label"><span class="form__label-name">Этаж</span></label>
                        <div class="large-10 medium-16 end columns">
                            <input type="text" class="form__input" id="buyer-floor">
                        </div>
                    </div>

                    <div class="row form__row">
                        <div class="large-offset-10 medium-offset-8 large-10 medium-16 end columns">
                            <button type="button" class="form__simple-btn">Сохранить как дополнительный адрес</button>
                        </div>
                    </div>
                </fieldset-->

                <div class="row">
                    <div class="medium-12 columns form__row">
                        <a href="<?php echo HOST_URL; ?>/account_cart.html" class="btn btn_light">Редактировать заказ</a>
                    </div>

                    <div class="medium-12 columns form__row text-right small-only-text-left">
                        <button class="btn btn_text-large" id="but1" onClick="submit();" style="display:none;">Оформить</button>
                         <button class="btn btn_text-large" id="but2">Заполните одно поле для связи</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include SKIN_PATH . 'footer.php'; ?>
</div>

<?php include SKIN_PATH . 'footer_includes.php';?>

</body>
</html>
<script>
        
        $(document).ready(function(){
                if($('#buyer-email').val() != '' ||  $('#buyer-phone').val() != ''){
                        $('#but2').hide();
                        $('#but1').show();
                }
        });
        
        $(document).on('change','#buyer-email', function(){
                
                if($('#buyer-email').val() != '' ||  $('#buyer-phone').val() != ''){
                        $('#but2').hide();
                        $('#but1').show();
                }else{
                        $('#but2_mes').show();
                        $('#but2').show();
                        $('#but1').hide();
                }
                
        });
        
        $(document).on('change','#buyer-phone', function(){
                
                if($('#buyer-email').val() != '' ||  $('#buyer-phone').val() != ''){
                        $('#but2').hide();
                        $('#but1').show();
                }else{
                        $('#but2').show();
                        $('#but1').hide();
                }
                
        });
        
</script>

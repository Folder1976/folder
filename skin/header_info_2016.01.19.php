        <?php $order_sum = $Order->getOrderSumm(); ?>
        <?php $order_items = $Order->getOrderItems(); ?>
        
        <div class="row header__info">
            <div class="medium-13 small-14 columns">
                <div class="header__table">
                    <a href="<?php echo HOST_URL;?>">
                        <div class="header__table-cell"><img src="<?php echo SKIN_URL; ?>img/logo.png" alt="Armma.ru"></div>
                    </a>
                    <div class="header__table-cell">
                        <ul class="h-menu">
                            
                            <li class="h-menu__item">
                                <a href="articles.html" class="h-menu__link">Обзоры</a></li>
                            
                            <li class="h-menu__item">
                                <a href="select-size.html" class="h-menu__link">Как выбрать размер</a></li>
                            
                            <li class="h-menu__item">
                                <a href="payments-delivery.html" class="h-menu__link">Оплата и доставка</a></li>
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="small-10 columns show-for-small-only">
                <div class="h-mob-actions">
                    <span class="h-mob-action" data-dropdown="mobile-head-menu" aria-controls="mobile-head-menu" aria-expanded="false"><span class="h-mob-action__ico fa fa-bars"></span><span class="h-mob-action__ico h-mob-action__ico_inactive fa fa-times"></span></span>
                    <span class="h-mob-action" data-dropdown="mobile-drop-login" aria-controls="mobile-drop-login" aria-expanded="false"><span class="h-mob-action__ico fa fa-user"></span><span class="h-mob-action__ico h-mob-action__ico_inactive fa fa-times"></span></span>
                    <a href="<?php echo HOST_URL;?>/account_cart.html" class="h-mob-action"><span class="fa fa-shopping-cart"></span></a>
                </div>
            </div>
            <div class="small-24 columns show-for-small-only">
                <ul class="h-menu h-menu_mobile">
                    
                    <li class="h-menu__item">
                        <a href="<?php echo HOST_URL;?>/news.html" class="h-menu__link">Обзоры</a></li>
                    
                    <li class="h-menu__item">
                        <a href="<?php echo HOST_URL;?>/help.html" class="h-menu__link">Как выбрать размер</a></li>
                    
                    <li class="h-menu__item">
                        <a href="<?php echo HOST_URL;?>/dostavka.html" class="h-menu__link">Оплата и доставка</a></li>
                    
                </ul>
            </div>
            <div class="small-24 columns show-for-small-only">
                <div class="socials">
    <a href="#" class="social"><span class="fa fa-youtube"></span></a>
    <a href="http://vk.com/armma_ru" class="social"><span class="fa fa-vk"></span></a>
    <a href="#" class="social"><span class="fa fa-facebook"></span></a>
    <a href="#" class="social"><span class="fa fa-instagram"></span></a>
    <a href="#" class="social h-mob-action" data-dropdown="mobile-drop-search" aria-controls="mobile-drop-search" aria-expanded="false"><span class="h-mob-action__ico fa fa-search"></span><span class="h-mob-action__ico h-mob-action__ico_inactive fa fa-times"></a>
</div>

            </div>
            <div class="medium-11 columns hide-for-small-only">
                <div class="header__table">
                    <div class="header__table-cell small-14">
                        <?php if(!isset($_SESSION[BASE.'username'])){ ?> 
                        
                            <form action="<?php echo HOST_URL;?>/index.php" METHOD="POST" class="h-enter">
                                <div class="row">
                                    <div class="small-12 columns h-enter__col" style="width: 40%;">
                                        <input type="email" name="email" class="h-enter__input" required placeholder="Email">
                                    </div>
                                    <div class="small-12 columns h-enter__col" style="width: 40%;">
                                        <input type="password" name="pass" class="h-enter__input" required placeholder="Пароль">
                                        <input type="hidden" name="login" value="true">
                                    </div>
                                    <div class="small-12 columns h-enter__col" style="width: 20%; height: 15px;">
                                            <button class="form__submit" onclick="Submit();" style="padding: 2px;
                                                                                                    height: 25px;"><<</button>
                                    </div>
                                 </div>
                                
    
                                <div class="row">
                                    <div class="small-12 columns">
                                        <a href="<?php echo HOST_URL;?>/registration.html" class="h-enter__helper">Регистрация</a>
                                    </div>
                                         
                                    <?php global $adapters; foreach ($adapters as $title => $adapter) { ?>
                                           <!--a href="<?php echo $adapter->getAuthUrl(); ?>"><?php echo substr(ucfirst($title),0,2); ?></a-->
                                    <?php  } ?>
                                  
                                    <div class="small-12 columns">
                                        <a href="<?php echo HOST_URL;?>/restore_pass" class="h-enter__helper">Напомнить пароль</a>
                                    </div>
                                </div>
                            </form>
                            <?php }else{ ?>
                               <div class="row">
                                    <div class="small-12 columns" style="width: 100%;">
                                        Пользователь: <?php echo $_SESSION[BASE.'username']; ?>
                                    </div>
                                </div>
       
                                <div class="row">
                                     <?php if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'].'can_shop')>0){ ?>
                                        <div class="small-12 columns">
                                            <a href="<?php echo HOST_URL;?>/shop.php" class="h-enter__helper">Магазин</a>
                                        </div>
                                    <?php } ?>
                                    <?php if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
                                        <div class="small-12 columns">
                                            <a href="<?php echo HOST_URL;?>/admin/index.php" class="h-enter__helper">Админ панель</a>
                                        </div>
                                    <?php } ?>
                                    <div class="small-12 columns">
                                        <a href="<?php echo HOST_URL;?>/account_personal.html" class="h-enter__helper">Мой кабинет</a>
                                    </div>
                                    <div class="small-12 columns">
                                        <a href="<?php echo HOST_URL;?>/logout.html" class="h-enter__helper">Выйти</a>
                                    </div>
                                </div>
                             
                            <?php } ?>
                    </div>

                    
                    <div class="header__table-cell medium-10">
                        <div class="h-cart">
                               <div class="h-cart__row">
                                    <a href="<?php echo HOST_URL;?>/account_cart.html">
                                        <span class="h-cart__ico fa fa-shopping-cart"></span><span class="h-cart__total"><span class="num-cart__items"><?php if($order_items > 0){ echo '('.$order_items.')'; } ?></span> <span class="num-cart__total"><?php if($order_sum > 0){ echo $order_sum; } ?></span> ₽</span>
                                    </a>
                                </div>
                                <div class="h-cart__row">
                                    <a href="<?php echo HOST_URL;?>/account_cart.html" class="h-cart__checkout">Оформить заказ</a>
                                </div>
                         
                        </div>
                    </div>
                    
                </div>
            </div>
            <nav id="mobile-head-menu" data-dropdown-content class="f-dropdown hm-menu" aria-hidden="true" tabindex="-1" aria-autoclose="false">
    <ul class="hm-menu__list">
       
        <?php foreach($categories as $Ind => $category){ ?> 
        
            <li class="hm-menu__item">
                
                <a href="<?php echo HOST_URL.'/'.$category['url'];?>.html" class="hm-menu__link"><span class="hm-menu__name"><?php echo $category['name']; ?></span></a>
                
            </li>
        
        <?php } ?>
        
    </ul>
</nav>
            <div id="mobile-drop-login" data-dropdown-content class="f-dropdown small dropdown" aria-hidden="true" tabindex="-1" aria-autoclose="false">
    <?php
        $Msg = 'Вход';
        if(isset($_SESSION[BASE.'username'])){
            $Msg = 'Пользовательское меню';
        }
    ?> 
    <h3 class="dropdown__subtitle"><?php echo $Msg; ?></h3>
    <?php if(!isset($_SESSION[BASE.'username'])){ ?> 
        <form action="" class="form" data-abide="ajax">
            <div class="form__row">
                <span class="form__error error">некорректный email</span>
                <input type="email" name="email" class="form__input" required placeholder="Email">
            </div>
    
            <div class="form__row">
                <span class="form__error error">введите пароль</span>
                <input type="password" name="pass" class="form__input" required placeholder="Пароль">
                <input type="hidden" name="login" value="true">
            </div>
            <div class="form__row text-center">
                <button class="form__submit" onclick="Submit();">Войти</button>
            </div>
    
            <div class="form__row row">
                <div class="small-12 columns"><a href="<?php echo HOST_URL;?>/registration.html" class="h-enter__helper">Регистрация</a></div>
                <div class="small-12 columns text-right"><a href="<?php echo HOST_URL;?>/restore_pass" class="h-enter__helper">Напомнить пароль</a></div>
            </div>
            
            <?php global $adapters; foreach ($adapters as $title => $adapter) { ?>
                    <div class="form__row">
                        <a href="<?php echo $adapter->getAuthUrl(); ?>">Аутентификация через <?php echo ucfirst($title); ?></a>
                    </div>
            <?php  } ?>

        </form>
    <?php }else{ ?>
            <div class="row" style="padding-top: 20px;">
                <div class="small-12 columns" style="width: 100%;">
                    Пользователь: <?php echo $_SESSION[BASE.'username']; ?>
                </div>
            </div>
            
            <div class="row" style="padding-top: 20px;padding-bottom: 20px;">
                 <?php if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'].'can_shop')>0){ ?>
                    <div class="small-12 columns" style="padding-bottom: 20px;">
                        <a href="<?php echo HOST_URL;?>/shop.php" class="h-enter__helper" style="font-size: 20px;">Магазин</a>
                    </div>
                <?php } ?>
                <?php if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
                    <div class="small-12 columns" style="padding-bottom: 20px;">
                        <a href="<?php echo HOST_URL;?>/admin/index.php" class="h-enter__helper" style="font-size: 20px;">Админ панель</a>
                    </div>
                <?php } ?>
                <div class="small-12 columns">
                    <a href="<?php echo HOST_URL;?>/account_personal.html" class="h-enter__helper" style="font-size: 20px;">Мой кабинет</a>
                </div>
                <div class="small-12 columns">
                    <a href="<?php echo HOST_URL;?>/logout.html" class="h-enter__helper" style="font-size: 20px;">Выйти</a>
                </div>
            </div>
    <?php } ?>
        
</div>
        </div>
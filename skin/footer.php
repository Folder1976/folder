   <footer class="page__footer">
    <div class="row footer">
        <div class="large-5 medium-6 columns">
            <div class="row">
                <div class="medium-24 small-14 columns">
                    <img src="<?php echo SKIN_URL; ?>img/logo_small.png" alt="Armma.ru">
                </div>

                <div class="small-10 columns show-for-small-only">
                    <div class="f-mob-actions">
                        <span class="f-mob-action" data-dropdown="mobile-drop-login" aria-controls="mobile-drop-login" aria-expanded="false"><span class="f-mob-action__ico fa fa-user"></span><span class="f-mob-action__ico f-mob-action__ico_inactive fa fa-times"></span></span>
                        <a href="#" class="f-mob-action"><span class="fa fa-shopping-cart"></span></a>
                    </div>
                </div>
            </div>

            <div class="footer__copyright">
                © 2015 интернет-магазин arma.ru.<br>
                Все права защищены
            </div>

            <div class="footer__sitemap">
                <a href="#">Карта сайта</a>
            </div>
        </div>

        <div class="large-14 medium-12 columns">
            <div class="row">
                <div class="medium-8 small-12 columns f-menu">
                    <h4 class="f-menu__title">Интернет-магазин</h4>
                    <ul class="f-menu__list">
                      
                        <?php foreach($categories as $Ind => $category){ ?> 
                         <li class="f-menu__item"><a href="<?php echo HOST_URL.'/'.$category['url'];?>.html" class="f-menu__link"><?php echo $category['name']; ?></a></li>
                        <?php } ?>
                        <li class="f-menu__item"><a href="lates_products.html" class="f-menu__link">Новинки</a></li>
                    </ul>
                </div>

                <div class="medium-8 small-12 columns f-menu">
                    <h4 class="f-menu__title">Информация</h4>
                    <ul class="f-menu__list">
                        <li class="f-menu__item"><a href="<?php echo HOST_URL;?>/news.html" class="f-menu__link">Обзоры</a></li>
                        <li class="f-menu__item"><a href="<?php echo HOST_URL;?>/news.html" class="f-menu__link">Статьи и новости</a></li>
                        <li class="f-menu__item"><a href="<?php echo HOST_URL;?>/help.html" class="f-menu__link">Как выбрать</a></li>
                        <li class="f-menu__item"><a href="<?php echo HOST_URL;?>/dostavka.html" class="f-menu__link">Доставка и оплата</a></li>
                        <li class="f-menu__item"><a href="<?php echo HOST_URL;?>/contact.html" class="f-menu__link">Контакты</a></li>
                    </ul>
                </div>

                <div class="medium-8 columns f-menu hide-for-small-only">
                    <h4 class="f-menu__title">Личная информация</h4>
                    <ul class="f-menu__list">
                        <li class="f-menu__item"><a href="#" class="f-menu__link">Вход на сайт</a></li>
                        <li class="f-menu__item"><a href="<?php echo HOST_URL;?>/myaccount.html" class="f-menu__link">Личный кабинет</a></li>
                        <li class="f-menu__item"><a href="<?php echo HOST_URL;?>/user_order_view.html" class="f-menu__link">Корзина</a></li>
                    </ul>
                    <br><br>
                <div class="f-menu">
                <h4 class="f-menu__title">Подписаться на рассылку</h4>
                <!--form action="#" class="f-subscribe form"-->
                    <div class="row collapse">
                        <div class="small-14 columns">
                            <input type="email" class="form__input" id="resent_email" placeholder="Ваш email">
                        </div>

                        <div class="small-10 columns">
                            <button class="form__submit form__submit_small rassilka">Подписаться</button>
                        </div>
                    </div>
                
                </div>
                
                <!--/form-->
                </div>
            </div>
        </div>

        <div class="large-5 medium-6 columns">
            <div class="f-menu">
                <h4 class="f-menu__title">Мы в социальных сетях</h4>
                <div class="socials">
    <a href="#" class="social"><span class="fa fa-youtube"></span></a>
    <a href="#" class="social"><span class="fa fa-vk"></span></a>
    <a href="#" class="social"><span class="fa fa-facebook"></span></a>
    <a href="#" class="social"><span class="fa fa-instagram"></span></a>
    
</div>

            </div>

            
        </div>
    </div>
</footer>
    <span class="btn-to-top"><img src="<?php echo SKIN_URL; ?>img/totop.png" alt=""></span>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="<?php echo HOST_URL; ?>/js/jquery.msgBox.js" type="text/javascript"></script>
	<link href="<?php echo HOST_URL; ?>/css/msgBoxLight.css" rel="stylesheet" type="text/css">
    <script>
     $(document).on('click', '.rassilka', function(){
       
       $.ajax({
				type: "POST",
				url: "<?php echo HOST_URL; ?>/ajax/podpiska.php?email=" + $('#resent_email').val(),
				dataType: "json",
				success: function(msg){
					
					//console.log(msg);
					
                    alert(msg['msg']);
                   /* 
					$.msgBox({
							title: msg['title'],
							content: msg['msg'],
							type: "info"
					});*/
                   	
				}
        });
    });
     
    </script>
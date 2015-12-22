        <nav class="row hide-for-small-only">
            <div class="small-24 columns">
                <div class="hc-menu">
                
                <?php $count = 1; ?>
                <?php foreach($categories as $Ind => $category){?>                           
                <a href="<?php echo HOST_URL.'/'.$category['url']; ?>.html"
                        class="hc-menu__link"
                        data-dropdown="catalog-menu-catalog_<?php echo $count; ?>"
                        aria-controls="catalog-menu-catalog_<?php echo $count; ?>"
                        aria-expanded="false"
                        data-options="is_hover:true; hover_timeout:200">
                        <span class="hc-menu__name"><?php echo $category['name']; ?></span>
                </a>
                        
                    <div id="catalog-menu-catalog_<?php echo $count; ?>" data-dropdown-content class="f-dropdown dropdown hc-menu__sub" aria-hidden="true" tabindex="-1" aria-autoclose="false">
                        <div class="row">
                            <div class="large-6 medium-4 columns">
                                <img src="<?php echo SKIN_URL; ?>img/menu-icons/<?php echo $category['url']; ?>.jpg" alt="Картинка <?php echo $category['name']; ?>">
                            </div>
                            
                            <?php $sub_menus = $Category->getCategoryChildrenFull($Ind); ?>
                            <div class="large-9 medium-10 columns">
                                <?php if($sub_menus){ ?>
                                    <?php $row = (int)($sub_menus['count'] / 2); ?>
                           
                                    <ul class="hc-menu__sub-list">
                                        <?php foreach($sub_menus as $sub_menu){ ?>
                                            <li class="hc-menu__sub-item">
                                                <a href="<?php echo HOST_URL.'/'.$sub_menu['url'];?>.html" class="hc-menu__sub-link"><?php echo $sub_menu['name']; ?></a>
                                                <?php if($sub_menu['children']){ ?>
                                                    <ul class="hc-menu__sub-list">
                                                        <?php foreach($sub_menu['children'] as $sub_childr){ ?>
                                                            <li class="hc-menu__sub-item"><a href="<?php echo HOST_URL.'/'.$sub_childr['url']; ?>.html" class="hc-menu__sub-link hc-menu__sub-link_inner"><?php echo $sub_childr['name']; ?></a></li>
                                                            <?php $row--; ?>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                            </li>
                                            <?php $row--; ?>
                                            
                                            <!--Если перевалили за половину счетчика - начнем новую колонку -->
                                            <?php if($row < 0) { ?>
                                                </ul></div>
                                                <div class="large-9 medium-10 columns">
                                                     <ul class="hc-menu__sub-list">
                                                         <ul class="hc-menu__sub-list">
                                            <?php } ?>
                                            
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php $count++; ?>
                <?php } ?>
                    
                    <a href="<?php echo HOST_URL; ?>/lates_products.html" class="hc-menu__link" data-dropdown="catalog-menu-catalog_<?php echo $count; ?>" aria-controls="catalog-menu-catalog_<?php echo $count; ?>" aria-expanded="false" data-options="is_hover:true; hover_timeout:200"><span class="hc-menu__name">Новинки</span></a>
                        
                    <!--div id="catalog-menu-catalog_<?php echo $count; ?>" data-dropdown-content class="f-dropdown dropdown hc-menu__sub" aria-hidden="true" tabindex="-1" aria-autoclose="false">
                        <div class="row">
                            <div class="large-6 medium-4 columns">
                                <img src="<?php echo SKIN_URL; ?>img/menu-icons/menu_prev_320_320_1.jpg" alt="Новинки">
                            </div>
                        </div>
                    </div-->
                        
                                    
                    <!--a href="catalog.html" class="hc-menu__link" data-dropdown="catalog-menu-catalog_6" aria-controls="catalog-menu-catalog_6" aria-expanded="false" data-options="is_hover:true; hover_timeout:200"><span class="hc-menu__name">Новинки</span></a>
                        
                    <div id="catalog-menu-catalog_6" data-dropdown-content class="f-dropdown dropdown hc-menu__sub" aria-hidden="true" tabindex="-1" aria-autoclose="false">
                        <div class="row">
                            <div class="large-6 medium-4 columns">
                                <img src="<?php echo SKIN_URL; ?>img/menu-icons/menu_prev_320_320_1.jpg" alt="Новинки">
                            </div>

                            <div class="large-9 medium-10 columns">
                                <ul class="hc-menu__sub-list">
                                    <li class="hc-menu__sub-item">
                                        <a href="#" class="hc-menu__sub-link">Подсумки</a>
                                        <ul class="hc-menu__sub-list">
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Поясные сумки</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Баулы, мешки</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Кошельки, чехлы для документов</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Однолямочные рюкзаки</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Рюкзаки, сумки</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Сумки EDC</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Сумки для туалетных принадлежностей</a></li>
                                        </ul>
                                    </li>
                                    <li class="hc-menu__sub-item">
                                        <a href="#" class="hc-menu__sub-link">Чехлы для оружия</a>
                                    </li>
                                    <li class="hc-menu__sub-item">
                                        <a href="#" class="hc-menu__sub-link">Компасы</a>
                                    </li>
                                    <li class="hc-menu__sub-item">
                                        <a href="#" class="hc-menu__sub-link">Нашивки</a>
                                        <ul class="hc-menu__sub-list">
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Жетоны</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Значки</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Медали</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Шевроны</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            <div class="large-9 medium-10 columns">
                                <ul class="hc-menu__sub-list">
                                    <li class="hc-menu__sub-item">
                                        <a href="#" class="hc-menu__sub-link">Защитная амуниция</a>
                                        <ul class="hc-menu__sub-list">
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Защитные маски</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Бронежилеты</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Каски</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Наушники</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Наколенники, налокотники</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Щиты</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Ограждения</a></li>
                                        </ul>
                                    </li>
                                    <li class="hc-menu__sub-item">
                                        <a href="#" class="hc-menu__sub-link">Ножи и томагавки</a>
                                        <ul class="hc-menu__sub-list">
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Ножи</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Мультитулы</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Мачете</a></li>
                                            <li class="hc-menu__sub-item"><a href="#" class="hc-menu__sub-link hc-menu__sub-link_inner">Аксессуары</a></li>
                                        </ul>
                                    </li>
                                    <li class="hc-menu__sub-item">
                                        <a href="#" class="hc-menu__sub-link">Средства маскировки</a>
                                    </li>
                                    <li class="hc-menu__sub-item">
                                        <a href="#" class="hc-menu__sub-link">Сувениры</a>
                                    </li>
                                    <li class="hc-menu__sub-item">
                                        <a href="#" class="hc-menu__sub-link">Оптика</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div-->
                    
                    <span class="hc-menu__link" data-dropdown="mobile-drop-search" aria-controls="mobile-drop-search" aria-expanded="false" data-options="is_hover:true; hover_timeout:200"><span class="fa fa-search"></span></span>
                </div>
            </div>
        </nav>
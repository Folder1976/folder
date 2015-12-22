<article class="section">
    <header class="row section__header">
        <div class="medium-18 columns">
            <h2 class="section__title">Производители</h2>
        </div>

        <div class="medium-6 columns text-right small-only-text-left">
            <a href="#" class="btn btn_light">Все бренды</a>
        </div>
    </header>
    <?php
        $brands = $Brand->getBrands();
    ?>
    <div class="row">
        <div class="small-24 columns">
            <div class="brends">
                <?php if(isset($brands) AND $brands){ ?>
                <?php foreach($brands as $brand){ ?>
                    <!--div class="brend"><img src="<?php echo HOST_URL; ?>/resources/brends/<?php echo $brand['brand_code']; ?>.jpg" alt=""></div>
                <?php } ?>
                <?php } ?>
                
                --><div class="brend"><img src="<?php echo SKIN_URL; ?>img/brends/crosman.png" alt=""></div><!--
                --><div class="brend"><img src="<?php echo SKIN_URL; ?>img/brends/crosman.png" alt=""></div><!--
                --><div class="brend"><img src="<?php echo SKIN_URL; ?>img/brends/danner.png" alt=""></div><!--
                --><div class="brend"><img src="<?php echo SKIN_URL; ?>img/brends/caa.png" alt=""></div><!--
                --><div class="brend"><img src="<?php echo SKIN_URL; ?>img/brends/stirling.png" alt=""></div><!--
                --><div class="brend"><img src="<?php echo SKIN_URL; ?>img/brends/umarex.png" alt=""></div><!--
                --><div class="brend"><img src="<?php echo SKIN_URL; ?>img/brends/propper.png" alt=""></div><!--
                --><div class="brend"><img src="<?php echo SKIN_URL; ?>img/brends/mcnett.png" alt=""></div><!--
                --><div class="brend"><img src="<?php echo SKIN_URL; ?>img/brends/revision.png" alt=""></div><!--
                --><div class="brend"><img src="<?php echo SKIN_URL; ?>img/brends/snugpak.png" alt=""></div>
            </div>
        </div>
    </div>
</article>
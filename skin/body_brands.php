<article class="section">
    <header class="row section__header">
        <div class="medium-18 columns">
            <h2 class="section__title">Производители</h2>
        </div>

        <div class="medium-6 columns text-right small-only-text-left">
            <a href="/brands" class="btn btn_light brands_key">Все бренды</a>
        </div>
    </header>
    <?php
        if(!isset($brand_limit)) $brand_limit = 8;
        $brands = $Brand->getBrands($brand_limit);
    ?>
    <div class="row">
        <div class="small-24 columns">
            <div class="brends">
                <?php if(isset($brands) AND $brands){ ?>
                <?php foreach($brands as $brand){ ?>
                    <div class="brend">
                        <a href="brend/<?php echo $brand['brand_code']; ?>">
                            <img src="<?php echo HOST_URL; ?>/resources/brends/<?php echo $brand['brand_code']; ?>.png" title="Картинка <?php echo $brand['brand_name']; ?>">
                        </a>
                    </div>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</article>
<!--style>
    .brends{
        height: 220px;
        overflow: hidden;
    }
</style-->
<!--script>
    $(document).on('click', '.brands_key', function(){
        $('.brends').css('height','auto');   
    });
</script-->
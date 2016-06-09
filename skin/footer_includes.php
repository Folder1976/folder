<script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.11.3.min.js"><\/script>')</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3"></script>
<script src="<?php echo SKIN_URL; ?>js/vendor/foundation.min.js"></script>
<script src="<?php echo SKIN_URL; ?>js/vendor/placeholder.js"></script>
<script src="<?php echo SKIN_URL; ?>js/vendor/jquery.bxslider.min.js"></script>
<script src="<?php echo SKIN_URL; ?>js/vendor/icheck.min.js"></script>
<script src="<?php echo SKIN_URL; ?>js/vendor/ion.rangeSlider.min.js"></script>
<script src="<?php echo SKIN_URL; ?>js/vendor/jquery.magnific-popup.min.js"></script>
<script src="<?php echo SKIN_URL; ?>js/vendor/jquery.mCustomScrollbar.min.js"></script>
<script src="<?php echo SKIN_URL; ?>js/plugins.js"></script>
<script src="<?php echo SKIN_URL; ?>js/main.js"></script>
<script src="<?php echo SKIN_URL; ?>js/vendor/jquery.tooltipster.min.js"></script>

<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "f114b3e9-4a3d-4760-90ce-ef46295e2064", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>

<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="<?php echo HOST_URL; ?>/js/jquery.msgBox.js" type="text/javascript"></script>
<link href="<?php echo HOST_URL; ?>/css/msgBoxLight.css" rel="stylesheet" type="text/css"-->



<?php
include_once(".assets/analyticstracking.php");
include_once(".assets/analytics_yandex.php");
?>


<?php if(isset($_SESSION[BASE.'usersetup']) AND strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'])>0){ ?>
<div class="msg_back"></div>
<input type="hidden" id="edited_product" value="">
<div class="msg">загрузка...</div>

<style>
    .msg_back{width: 100%;height: 100%;opacity: 0.7;display: none;position: fixed;background-color: gray;top:0;left:0;z-index: 998;}
    .msg{
        display: none;
        position: fixed;
        top: 15%;
        left: 50%;
        margin-left: -150px;
        padding: 50px;
        width: 600px;
        height: 500px;
        text-align: center;
        border: 2px solid gray;
        background-color: #FFC87C;
        z-index: 999;
        overflow-x: auto;
    }
    .glav_photo_links{
        float: left;
        margin: 10px;
    }
    </style>
    

<script>
    
    $(document).on('click', '.glav_photo_links', function(){
        var image = $(this).data('img');
         var id = $('#edited_product').val();
        
        $.ajax({
            type: "POST",
            url: "/ajax/get_photos.php",
            dataType: "json",
            data: "id="+id+'&image='+image+'&key=set',
            beforeSend: function(){
            },
            success: function(msg){
                console.log( msg );

                var image = '#image_'+msg.target;
                console.log(image);
                $(image).attr('src','/resources/products/'+msg.image.replace('small','medium'));
                
                $('.msg_back').hide();
                $('.msg').hide();
                
                $('.msg').html('загрузка...');
            }
        });
        
        
        
    });
    
    $(document).on('click', '.glav_photo', function(){
        var id = $(this).data('id');
        $('#edited_product').val(id);
        
        $('.msg_back').show();
        $('.msg').show();
        
        $.ajax({
            type: "POST",
            url: "/ajax/get_photos.php",
            dataType: "text",
            data: "id="+id+'&key=get',
            beforeSend: function(){
            },
            success: function(msg){
                //console.log( msg );
                $('.msg').html(msg);
            }
        });
        
        
        
    });
    $(document).on('click', '.msg_back', function(){
        $('.msg_back').hide();
        $('.msg').hide();
    });
</script>
<?php } ?>
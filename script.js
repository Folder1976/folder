$(document).ready(function(){
    $("#ddmenu li").hover(function () {
         clearTimeout($.data(this,"timer"));
         $("ul",this).stop(true,true).slideDown(200);
    }, function () {
         $.data(this,"timer", setTimeout($.proxy(function() {
      $("ul",this).stop(true,true).slideUp(200);
    }, this), 100));
  });
});

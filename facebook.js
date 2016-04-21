/*
<div class="form__row">
        <div id="status"></div>
        
        <a show-faces="true" scope="public_profile,email" class="enter__soc enter__soc_facebook"><span class="fa fa-facebook"></span>
        <span class="enter__soc-name">Facebook</span></a>
</div>
           
<style>
        .enter__soc_facebook:hover{
                cursor: pointer;
        }
</style>
*/
    //===================================FACEBOOK==================================
        //474370436076360  - http://armmatest.ru/
        //474531189393618  - http://armmatest.ru/
        //474370436076360  - http://armmatest.ru/
        
        //a51789032bd76697061c7ab66496d5dd
        /*
        (function(d, s, id){
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) {return;}
           js = d.createElement(s); js.id = id;
           js.src = "//connect.facebook.net/ru_RU/sdk.js";
           fjs.parentNode.insertBefore(js, fjs);
         }(document, 'script', 'facebook-jssdk'));
        
        
          (function ($) {
              $(function () {
                  $(".enter__soc_facebook").on("click", function () {
                      FB.login(function(response) {
                          if (response.authResponse) {
                              statusChangeCallback(response);
                          }
                      });
                  });
              });
      })(jQuery);
      
      
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '474854576027946',
            xfbml      : true,
            version    : 'v2.5'
          });
        };

        function checkLoginState() {
                FB.getLoginStatus(function(response) {
                        //console.log(response);
                  statusChangeCallback(response);
                });
        }
        
        function statusChangeCallback(response) {
                //console.log('statusChangeCallback');
                //console.log(response);
                // The response object is returned with a status field that lets the
                // app know the current login status of the person.
                // Full docs on the response object can be found in the documentation
                // for FB.getLoginStatus().
                if (response.status === 'connected') {
                  // Logged into your app and Facebook.
                  testAPI();
                } else if (response.status === 'not_authorized') {
                  // The person is logged into Facebook, but not your app.
                  document.getElementById('status').innerHTML = 'Please log ' +
                    'into this app.';
                } else {
                  // The person is not logged into Facebook, so we're not sure if
                  // they are logged into this app or not.
                  document.getElementById('status').innerHTML = 'Пожалуйста залогиньтесь на ' +
                    'Facebook.';
                }
        }
        
         // Here we run a very simple test of the Graph API after login is
                // successful.  See statusChangeCallback() for when this call is made.
        function testAPI() {
                  FB.api('/me?fields=name,email', function(response) {
                        console.log(response);
                        console.log(response.email);
                        $.ajax({
                                type: "POST",
                                url: "ajax/user_facebook.php",
                                dataType: "json",
                                data: "name="+response.name+"&id="+response.id,
                                success: function(msg){
                                        //console.log(msg);
                                        if (msg['err'] == 'false') {
                                                alert(msg['msg']);
                                                //window.location.href = ''+msg['url'];
                                        }else{
                                                alert(msg['msg']);
                                                //window.location.href = ''+msg['url'];
                                        }
                                        
                                        //console.log(  msg['msg'] );
                                }
                        });
                        
                        console.log('Successful login for: ' + response.name);
                  });
        }
        
         FB.login(function (response) {
                if (response.session) {
                    var url = '/me?fields=name,email';
                    FB.api(url, function (response) {
                        alert(response.name);
                        alert(response.email);
                    });
                }
                else {
                    alert("User did not login successfully");
                }
            }, { scope: 'email' }); /* perms changed to scope */
        */
        /*
        FB.login(function(response) {
                if (response.status === 'connected') {
                  // Logged into your app and Facebook.
                } else if (response.status === 'not_authorized') {
                  // The person is logged into Facebook, but not your app.
                } else {
                  // The person is not logged into Facebook, so we're not sure if
                  // they are logged into this app or not.
                }
        });
        
        FB.login(function(response) {
                // handle the response
        }, {scope: 'public_profile,email'});
        */
        //==============END FACEBOOK =============================
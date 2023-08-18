    $(document).ready(function(){
    /*List Inside Panel Manager*/
    $(".panel-list .main-content").hover(function(){
        $(".list-hidden-content").slideUp('normal');
        $(".list-hidden-content").children(".hidden-icon-set").css({"right":"55px","opacity":0});
        if($(this).siblings(".list-hidden-content").css('display')=='none'){
            $(this).siblings(".list-hidden-content").slideDown('normal');
            $(this).siblings(".list-hidden-content").children(".hidden-icon-set").css({"right":"5px","opacity":1});
        }else{
            $(this).siblings(".list-hidden-content").slideUp('normal');
            $(this).siblings(".list-hidden-content").children(".hidden-icon-set").css({"right":"55px","opacity":1});
        }
        });
    
    /* Hidden menu navigation bar slideToggle */
        $('.hidden-nav').click(function(){
            $('.hidden-menu').slideToggle('fast');
        });
    
    /* 10s Pop up */
    var alreadyShow = 0;
    $(window).scroll(function(){        
        if($(window).scrollTop()>350&&$(window).scrollTop()<1000 && alreadyShow === 0){
            setTimeout(showTinyPopup,3000);
            alreadyShow = 1;
        }
    });
    
    
    function showTinyPopup(){
        $(".tiny-popup").css({'opacity':'1','bottom':'45px'});
    }
    $('.tiny-popup .tiny-close-btn').click(function(){
            $(".tiny-popup").css({'opacity':'1','bottom':'-135px'});
        });
    
    
    $('.panel-job').click(function(){
        $(this).children('.panel-body-hidden').slideToggle('fast');
        });
    
    /* Scroll Along Ads Background */
    var scrollPosPre = $(window).scrollTop();
                var scrollDirection;
                var maxDistanceUp = 80;
                var maxDistanceDown = 0;
                var BGcurrentPos = -50;
                $(window).scroll(function(){
                    if($(window).scrollTop() > scrollPosPre){
                        /* User Scroll Down  */
                        scrollDirection = 'down';
                        scrollPosPre = $(window).scrollTop();
                        if(BGcurrentPos >= maxDistanceDown){
                            BGcurrentPos = maxDistanceDown;
                            }else{
                            BGcurrentPos = BGcurrentPos + 0.5;
                        }
                        $('.adds').css('background-position','center '+ BGcurrentPos+'px');
                    }
                        /* User Scroll Up */
                    else{
                        scrollDirection = 'up';
                        scrollPosPre = $(window).scrollTop();
                        if(BGcurrentPos <= - maxDistanceUp){
                            BGcurrentPos = -maxDistanceUp;
                            }else{
                            BGcurrentPos = BGcurrentPos - 0.5;
                        }
                        $('.adds').css('background-position','center '+ BGcurrentPos+'px');
                    }
                    
                });
        /* End of Background Scrolling */
                  
        /*Smooth Scrolling */
            $(function() {
            $('a[href*="#"]:not([href="#"])').click(function() {
              if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                if (target.length) {
                  $('html, body').animate({
                    scrollTop: target.offset().top
                  }, 500);
                  return false;
                }
              }
            });
          });

});
    function showArticle(){
    $('.hidden-article').css('visibility', 'visible');
    $('.hidden-article .article-content').css({'transition':'all 400ms 50ms ease-out'});
    $('.hidden-article .article-content').css({'top':0,'opacity':1});
    }
    function showIMGArticle(){
        $('.article-img').css('visibility', 'visible');
        $('.article-img .article-content').css({'transition':'all 400ms 50ms ease-out'});
        $('.article-img .article-content').css({'top':0,'opacity':1});
    }
    function closeArticle(){
         $('.hidden-article, .article-img').css('visibility','hidden');
         $('.hidden-article .article-content, .article-img .article-content').css({'top':"300px",'opacity':0});
    }
    function showModal(){
        $('.modal-container').css('visibility', 'visible');
        $('.modal-container .modal').css({'transition':'all 400ms 50ms ease-out'});
        $('.modal-container .modal').css({'top':0,'opacity':1});
    }
    function closeModal(){
         $('.modal-container').css('visibility','hidden');
         $('.modal-container .modal').css({'top':"300px",'opacity':0});
    }

    /*Footer to top button */
    $(document).ready(function(){
        $(".floating-button").click(function() {
        $('html, body').animate({
            scrollTop: $("#navigation-bar").offset().top
        }, 500);
        });
    });
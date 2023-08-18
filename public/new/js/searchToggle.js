$(document).ready(function(){
        $('.adds .searchform .close-btn').click(function(){
             $('.adds .searchform').css({'width':'0','height':'0','bottom': '5px','right':'15px','opacity':'0'});
             setTimeout(function(){
                $('.adds .searchform').css({'display':'none'});
                $('.adds .seemore-btn').css({'display':'block'});
                },200);
            });
        $('.adds .seemore-btn').click(function(){
             $('.adds .searchform').css('display','block');
             setTimeout(function(){
                $('.adds .seemore-btn').css({'display':'none'});
                $('.adds .searchform').css({'width':'400px','height':'190px','bottom': '40px','right':'100px','opacity':'1'});
                },10);
            });
    });
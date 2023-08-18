            
            $(window).bind('resize', function()
            {
                $('.dashboard-drawer .hide-btn, .setting-button').click(function(){
                        if(drawerSize=='small' && $(window).width()>992){
                                $('.dashboard-drawer').css('left','0');
                                $('.dashboard-content').css('margin-left','180px');
                                $('.dashboard-header').css('margin-left','0px');
                                $('.drawer-menu ul li a').css('opacity','1');
                                $('.drawer-menu ul li span.icon1').css('left','15px');
                                drawerSize = 'normal';
                            }else{
                            if(drawerSize=='normal' && $(window).width()>992){
                                $('.dashboard-drawer').css('left','-130px');
                                $('.dashboard-content').css('margin-left','50px');
                                $('.dashboard-header').css('margin-left','0px');
                                $('.drawer-menu ul li a').css('opacity','0');
                                $('.drawer-menu ul li span.icon1').css('left','145px');
                                $('.drawer-menu>ul>li').children('.level2').slideUp('fast');
                                drawerSize = 'small';
                                $('.dim_menu').hide();
                            }else{
                                if(drawerSize=='small' && $(window).width()<992&&$(window).width()>480){
                                    $('.dashboard-drawer').css('left','0');
                                    $('.drawer-menu ul li a').css('opacity','1');
                                    $('.drawer-menu ul li span.icon1').css('left','15px');
                                    drawerSize = 'normal';
                                }else {
                                    if(drawerSize=='normal' && $(window).width()<992&&$(window).width()>480){
                                        $('.dashboard-drawer').css('left','-130px');
                                        $('.drawer-menu ul li a').css('opacity','0');
                                        $('.drawer-menu ul li span.icon1').css('left','145px');
                                        $('.drawer-menu>ul>li').children('.level2').slideUp('fast');
                                        drawerSize = 'small';
                                        $('.dim_menu').hide();
                                    }else{
                                        if(drawerSize=='small' &&$(window).width()<480){
                                            $('.dashboard-drawer').css('left','0');
                                            $('.drawer-menu ul li a').css('opacity','1');
                                            $('.drawer-menu ul li span.icon1').css('left','15px');
                                            drawerSize = 'normal';
                                            $('.dim_menu').show();
                                        }else {
                                                if(drawerSize=='normal' &&$(window).width()<480){
                                                    $('.dashboard-drawer').css('left','-180px');
                                                    $('.drawer-menu ul li a').css('opacity','0');
                                                    $('.drawer-menu ul li span.icon1').css('left','140px');
                                                    $('.drawer-menu>ul>li').children('.level2').slideUp('fast');
                                                    drawerSize = 'small';
                                                    $('.dim_menu').hide();
                                                }
                                        }
                                    }
                                }
                            }
                        }
                    });
            });


            $(document).ready(function(){
                
                /* Drawer Toggle */
                    var drawerSize;
                    if($(window).width()<992){
                        drawerSize = 'small';
                    }else{
                        drawerSize = 'normal';
                    }
                    
                    $('.dashboard-drawer .hide-btn, .setting-button').click(function(){
                        if(drawerSize=='small' && $(window).width()>992){
                                $('.dashboard-drawer').css('left','0');
                                $('.dashboard-content').css('margin-left','180px');
                                $('.dashboard-header').css('margin-left','0px');
                                $('.drawer-menu ul li a').css('opacity','1');
                                $('.drawer-menu ul li span.icon1').css('left','15px');
                                drawerSize = 'normal';
                            }else{
                            if(drawerSize=='normal' && $(window).width()>992){
                                $('.dashboard-drawer').css('left','-130px');
                                $('.dashboard-content').css('margin-left','50px');
                                $('.dashboard-header').css('margin-left','0px');
                                $('.drawer-menu ul li a').css('opacity','0');
                                $('.drawer-menu ul li span.icon1').css('left','145px');
                                $('.drawer-menu>ul>li').children('.level2').slideUp('fast');
                                drawerSize = 'small';
                                $('.dim_menu').hide();
                            }else{
                                if(drawerSize=='small' && $(window).width()<992&&$(window).width()>480){
                                    $('.dashboard-drawer').css('left','0');
                                    $('.drawer-menu ul li a').css('opacity','1');
                                    $('.drawer-menu ul li span.icon1').css('left','15px');
                                    drawerSize = 'normal';
                                }else {
                                    if(drawerSize=='normal' && $(window).width()<992&&$(window).width()>480){
                                        $('.dashboard-drawer').css('left','-130px');
                                        $('.drawer-menu ul li a').css('opacity','0');
                                        $('.drawer-menu ul li span.icon1').css('left','145px');
                                        $('.drawer-menu>ul>li').children('.level2').slideUp('fast');
                                        drawerSize = 'small';
                                        $('.dim_menu').hide();
                                    }else{
                                        if(drawerSize=='small' &&$(window).width()<480){
                                            $('.dashboard-drawer').css('left','0');
                                            $('.drawer-menu ul li a').css('opacity','1');
                                            $('.drawer-menu ul li span.icon1').css('left','15px');
                                            drawerSize = 'normal';
                                            $('.dim_menu').show();
                                        }else {
                                                if(drawerSize=='normal' &&$(window).width()<480){
                                                    $('.dashboard-drawer').css('left','-180px');
                                                    $('.drawer-menu ul li a').css('opacity','0');
                                                    $('.drawer-menu ul li span.icon1').css('left','140px');
                                                    $('.drawer-menu>ul>li').children('.level2').slideUp('fast');
                                                    drawerSize = 'small';
                                                    $('.dim_menu').hide();
                                                }
                                        }
                                    }
                                }
                            }
                        }
                    });

                    $('.dim_menu').click(function(){
                        $('.dashboard-drawer').css('left','-180px');
                        $('.drawer-menu ul li a').css('opacity','0');
                        $('.drawer-menu ul li span.icon1').css('left','140px');
                        $('.drawer-menu>ul>li').children('.level2').slideUp('fast');
                        drawerSize = 'small';
                        $(this).hide();
                    });

                    function setListenerForDrawer(){
                        $('.dashboard-drawer .hide-btn, .setting-button').click(function(){
                        if(drawerSize=='small' && $(window).width()>992){
                                $('.dashboard-drawer').css('left','0');
                                $('.dashboard-content').css('margin-left','180px');
                                $('.dashboard-header').css('margin-left','0px');
                                $('.drawer-menu ul li a').css('opacity','1');
                                $('.drawer-menu ul li span.icon1').css('left','15px');
                                drawerSize = 'normal';
                            }else{
                            if(drawerSize=='normal' && $(window).width()>992){
                                $('.dashboard-drawer').css('left','-130px');
                                $('.dashboard-content').css('margin-left','50px');
                                $('.dashboard-header').css('margin-left','0px');
                                $('.drawer-menu ul li a').css('opacity','0');
                                $('.drawer-menu ul li span.icon1').css('left','145px');
                                $('.drawer-menu>ul>li').children('.level2').slideUp('fast');
                                drawerSize = 'small';
                            }else{
                                if(drawerSize=='small' && $(window).width()<992&&$(window).width()>480){
                                    $('.dashboard-drawer').css('left','0');
                                    $('.drawer-menu ul li a').css('opacity','1');
                                    $('.drawer-menu ul li span.icon1').css('left','15px');
                                    drawerSize = 'normal';
                                }else {
                                    if(drawerSize=='normal' && $(window).width()<992&&$(window).width()>480){
                                        $('.dashboard-drawer').css('left','-130px');
                                        $('.drawer-menu ul li a').css('opacity','0');
                                        $('.drawer-menu ul li span.icon1').css('left','145px');
                                        $('.drawer-menu>ul>li').children('.level2').slideUp('fast');
                                        drawerSize = 'small';
                                    }else{
                                        if(drawerSize=='small' &&$(window).width()<480){
                                            $('.dashboard-drawer').css('left','0');
                                            $('.drawer-menu ul li a').css('opacity','1');
                                            $('.drawer-menu ul li span.icon1').css('left','15px');
                                            drawerSize = 'normal';
                                        }else {
                                                if(drawerSize=='normal' &&$(window).width()<480){
                                                    $('.dashboard-drawer').css('left','-180px');
                                                    $('.drawer-menu ul li a').css('opacity','0');
                                                    $('.drawer-menu ul li span.icon1').css('left','140px');
                                                    $('.drawer-menu>ul>li').children('.level2').slideUp('fast');
                                                    drawerSize = 'small';
                                                }
                                        }
                                    }
                                }
                            }
                        }
                    });
                    }
                    
                    $(window).resize(function(){
                        if($(window).width()<480){
                            $('.dashboard-drawer').css('left','-180px');
                            $('.dashboard-content').css('margin-left','0');
                            $('.dashboard-header').css('margin-left','0');
                            drawerSize = 'small';
                            setListenerForDrawer();
                        }else{
                            if($(window).width()>480&&$(window).width()<992){
                                $('.dashboard-drawer').css('left','-130px');
                                    $('.drawer-menu ul li a').css('opacity','0');
                                    $('.dashboard-content').css('margin-left','50px');
                                    $('.dashboard-header').css('margin-left','0px');
                                    $('.drawer-menu ul li span.icon1').css('left','145px');
                                    $('.drawer-menu>ul>li').children('.level2').slideUp('fast');
                                    drawerSize = 'small';
                                    setListenerForDrawer();
                            }else{
                                $('.dashboard-drawer').css('left','0');
                                $('.dashboard-content').css('margin-left','180px');
                                $('.dashboard-header').css('margin-left','0px');
                                $('.drawer-menu ul li a').css('opacity','1');
                                $('.drawer-menu ul li span.icon1').css('left','15px');
                                drawerSize = 'normal';
                                setListenerForDrawer()
                            }
                        }
                    });
                /*End of Drawer Toggle*/
                
                /*Sub Menu Toggle */
                
                    $('.drawer-menu>ul>li').click(function(){
                        if(drawerSize=='normal'){
                            $(this).siblings().children('.level2').slideUp('fast');
                            $(this).children('.level2').slideToggle('fast');
                        }else{
                                /* New function updated 26/9/2016 */
                                if(drawerSize=='small'){
                                        $('.dashboard-drawer').css('left','0');
                                        if($(window).width()>992){
                                                $('.dashboard-content').css('margin-left','180px');
                                                $('.dashboard-header').css('margin-left','0px');
                                        }
                                        $('.drawer-menu ul li a').css('opacity','1');
                                        $('.drawer-menu ul li span.icon1').css('left','15px');
                                        $(this).siblings().children('.level2').slideUp('fast');
                                        $(this).children('.level2').slideToggle('fast');
                                        drawerSize = 'normal';
                                }
                        }
                    });

                /*End of Sub Menu Toggle */
                
                /* User Config Toggle */
                $('.user-config .user-name').click(function(){
                    $('.dashboard-header .hidden-menu').toggle();
                });
                $('.dashboard-content, .dashboard-drawer').click(function(){
                     $('.dashboard-header .hidden-menu').hide();
                });
                $('.multi-lang').click(function(){
                        $('.lang-selector').slideToggle('fast');
                });$('.multi-lang').mouseleave(function(){
                        $('.lang-selector').slideUp('fast');
                });
            });
            $(document).ready(function(){
                //hide menu when load trang tinh nang menu moi cua anh Thoai
                //lc Danh 16/4/2020



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
                                setListenerForDrawer()
                            }
                        }
                    });
					
                /*End of Drawer Toggle*/
                
                /*Sub Menu Toggle */
                
                    $('span.icon-dropdown').click(function(){

                    if(drawerSize=='normal'){

                            $(this).parent().siblings().children('.level2').slideUp('fast');
                            $(this).parent().children('.level2').slideToggle('fast');
                        }else{
                                /* New function updated 26/9/2016 */
                                if(drawerSize=='normal'){
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
	 function showModal(){
                $('.modal-container').css('visibility', 'visible');
                $('.modal-container .modal').css({'transition':'all 400ms 50ms ease-out'});
                $('.modal-container .modal').css({'top':'40px','opacity':1});
                $('.waiting-overlay').css({'visibility':'hidden','opacity':'0'});
                $('.waiting-notification').css({'top':'-10%','opacity':'0'});
            }
            function closeModal(){
                 $('.modal-container').css('visibility','hidden');
                 $('.modal-container .modal').css({'top':"300px",'opacity':0});
            }
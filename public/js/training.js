$(document).ready(function(){

        $(document).on('click', ".nav_menu", function() {
            $(this).addClass('nav_menu2');
    		$(this).removeClass('nav_menu');
            $(".left-page").addClass('left_page');
            $(".left-page").removeClass('left_page2');
            
            $(".right-page").addClass('right_page');
            $(".right-page").removeClass('right_page2');
            
            //Current page
            $('.left_page').find('#cssmenu ul ul').hide();
            $('.left_page').find('#cssmenu > ul > li > a').css('text-indent','-9999px');
                
    	});
    
    	$(document).on('click', ".nav_menu2", function() {
    		$(this).addClass('nav_menu');
    		$(this).removeClass('nav_menu2');
            $(".left-page").addClass('left_page2');
            $(".left-page").removeClass('left_page');
            
            $(".right-page").addClass('right_page2');
            $(".right-page").removeClass('right_page');
            //Current 
            $('#cssmenu .current_class').closest('.cat0').find('ul').first().show();
            $('#cssmenu .current_class').closest('.cat1').find('ul').first().show();
            
            $('.left_page2').find('#cssmenu > ul > li > a').css('text-indent','0px');

    	});
            
        //Submenu
		$('#cssmenu .current_class').closest('.cat0').find('ul').first().show();
        $('#cssmenu .current_class').closest('.cat1').find('ul').first().show();
        
        
        $('#cssmenu li.active').addClass('open').children('ul').show();
		$('#cssmenu li.has-sub>a').on('click', function(){
			$(this).removeAttr('href');
			var element = $(this).parent('li');
			if (element.hasClass('open')) {
				element.removeClass('open');
				element.find('li').removeClass('open');
				element.find('ul').slideUp(200);
			}
			else {
				element.addClass('open');
				element.children('ul').slideDown(200);
				element.siblings('li').children('ul').slideUp(200);
				element.siblings('li').removeClass('open');
				element.siblings('li').find('li').removeClass('open');
				element.siblings('li').find('ul').slideUp(200);
			}
		});
        
		//Add question
		$('.btn_addques').on('click', function(){
            var count = $(".ques_wrap").children().length;
            count++;
			var div = document.createElement('div');
			div.className="form_ques";
		    div.innerHTML = "<div class='col-sm-3'>\
                            <label for='inputPassword3' class='control-label cauhoi'>Câu hỏi "+count+":</label>\
                          </div>\
                          <div class='col-sm-9 form_ques_wrap'>\
                            <div class='form-group'>\
            				<div class='col-sm-9'>\
            					<div class='input-group'>\
            					  <span class='input-group-text' id='basic-addon1'>Câu hỏi:</span>\
            					  <input type='text' class='form-control' placeholder='Câu hỏi' name='question_choice[]'/>\
                            </div>\
            				</div>\
                            <div class='col-sm-3'>\
            					<div class='input-group'>\
            					  <span class='input-group-text' id='basic-addon1'>Điểm:</span>\
            					  <input type='text' class='form-control scores' name='scores[]' placeholder='Điểm'/>\
            					</div>\
            				</div>\
            			  </div>\
                          <div class='form_answer'>\
    					  <div class='form-group'>\
    						<div class='col-sm-9'>\
    							<div class='input-group'>\
    							  <span class='input-group-text'id='basic-addon1'><input type='checkbox' class='check' name='check_ans'/> Đáp án 1</span>\
    							  <input type='text' class='form-control answer' placeholder='Đáp án'>\
    							</div>\
    						</div>\
    					  </div>\
    					  <div class='form-group'>\
    						<div class='col-sm-9'>\
    							<div class='input-group'>\
    							  <span class='input-group-text'id='basic-addon1'><input type='checkbox' class='check' name='check_ans'/> Đáp án 2</span>\
    							  <input type='text' class='form-control answer' placeholder='Đáp án'>\
    							</div>\
    						</div>\
    					  </div>\
    					  <div class='form-group'>\
    						<div class='col-sm-9'>\
    							<div class='input-group'>\
    							  <span class='input-group-text'id='basic-addon1'><input type='checkbox' class='check' name='check_ans'/> Đáp án 3</span>\
    							  <input type='text' class='form-control answer' placeholder='Đáp án'>\
    							</div>\
    						</div>\
    					  </div>\
    					  <div class='form-group'>\
    						<div class='col-sm-9'>\
    							<div class='input-group'>\
    							  <span class='input-group-text'id='basic-addon1'><input type='checkbox' class='check' name='check_ans'/> Đáp án 4</span>\
    							  <input type='text' class='form-control answer' placeholder='Đáp án'>\
    							</div>\
    						</div>\
    					  </div>\
    				  </div>\
                      <div class='form-group'>\
        				<div class='col-sm-4'>\
        					<div class='input-group'>\
        					  <button type='button' class='btn btn-info navbar-btn btn_addanswer'>+</button>\
        					</div>\
        				</div>\
        			  </div>\
                      <div class='remove_quess'>X</div>\
                      <input type='hidden' class='data_answer' name='data_answer[]'/>\
                      <input type='hidden' class='data_check' name='data_check[]'/>\
                    </div>\
                </div>";
	        $('.ques_wrap')[0].appendChild(div);
		});
        
        
        //Add answer
        $(document).on('click', ".btn_addanswer", function() {
            var count = $(this).closest('.form_ques').find(".form_answer").children().length;
            count++;
			var div = document.createElement('div');
			div.className="form-group";
		    div.innerHTML = "<div class='col-sm-9'>\
    							<div class='input-group'>\
    							  <span class='input-group-text'id='basic-addon1'><input type='checkbox' class='check' name='check_ans'/> Đáp án "+count+"</span>\
    							  <input type='text' class='form-control answer' placeholder='Câu trả lời'>\
    							</div>";
	        $(this).closest('.form_ques').find('.form_answer')[0].appendChild(div);
		});
        
        //Add câu hỏi tự luận
        $(document).on('click', ".btn_addques2", function() {
            var count = $(this).closest('.content_page').find(".ques_wrap2").children().length;
            count++;
			var div = document.createElement('div');
			div.className="form-group";
		    div.innerHTML = "<label for='inputPassword3' class='col-sm-3 control-label'></label>\
            				<div class='col-sm-6'>\
            					<div class='input-group'>\
            					  <span class='input-group-text' id='basic-addon1'>Câu hỏi tự luận "+count+":</span>\
            					  <input type='text' class='form-control' name='question[]' placeholder='Câu hỏi tự luận'>\
            					</div>\
            				</div>";
	        $(this).closest('.content_page').find('.ques_wrap2')[0].appendChild(div);
		});

		//Remove question
        $(document).on('click', ".remove_quess", function() {
            if (confirm('Có chắc xóa câu hỏi này?')) {
                $(this).closest('.form_ques').remove();
            }
		});
        
        //Remove comment
        $(document).on('click', ".del_comment", function() {
            if (confirm('Có chắc xóa bình luận này này?')) {
                $(this).closest('.comment').remove();
            }
		});
        
        
        $(document).on('click', ".show_box_comment", function() {  
            var check = $('.box_comment').attr('block');
            if(check == 0){
                $('.box_comment').html("");
                $.ajax({
        			  url: "/training/show-box-comment",
        		      type: "POST",
        		      dataType: "text",
        		      context: this,
        		      beforeSend: function() {				
        				},
        		      success: function(result){
        					if(result)
        					{
                                $('.box_comment').append(result);
                                $('.box_comment').show();
                                $('.box_comment').attr('block',1);
        					}
        					else
        					{	
        						alert("Có lỗi 1 !!");
        					}						
        		      },
        		      error:function(){
        		    	  alert("Có lỗi 2!!");
        		      }
        		});
            }
            else{
                $('.box_comment').hide();
                $('.box_comment').attr('block',0);
            }
        });
       
		
});

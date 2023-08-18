    /*[ Back to top ]
    ===========================================================*/
    var windowH = $(window).height()/2;

    $(window).on('scroll',function(){
        if ($(this).scrollTop() > windowH) {
            $("#myBtn").css('display','flex');
        } else {
            $("#myBtn").css('display','none');
        }
    });

    $('#myBtn').on("click", function(){
        $('html, body').animate({scrollTop: 0}, 300);
    });
    // ------------------------------------------------------
//Create
function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

$("#check-category").change(function () {
    if ($("#check-category option:selected").val() == 1) {
        $("#channel").prop('disabled', false);
    }else{
        $("#channel").prop('disabled', true);
        $("#channel").val("");
    }
});
temp = 0;
$("#add-more").click(function () {
    temp++;
    text ="        <div class='payment_form_child_" + temp + "''> <div class=\"my-span11\">\n" +
        "                                        <div class=\"span3 div-payment-date\">\n" +
        "                                            <div class=\"form-group\">\n" +
        "                                                <h5 for=\"\">Ngày thanh toán:</h5>\n" +
        "                                                <input autocomplete=\"off\" required type=\"\" class=\"date form-control\"name=\"payment_date[" + temp + "]\" value=\"\">\n" +
        "                                            </div>\n" +
        "                                        </div>\n" +
        "                                        <div class=\"span3 div-payment\">\n" +
        "                                            <div class=\"form-group\">\n" +
        "                                                <h5 for=\"\">Số tiền thanh toán:</h5>\n" +
        "                                                <input autocomplete=\"off\" required type=\"text\" class=\"number form-control\"name=\"payment_cost[" + temp + "]\">\n" +
        "                                            </div>\n" +
        "                                        </div>\n" +
        "                                       <div class=\"span3 div-payment\">\n" +
        "                                            <div class=\"form-group\">\n" +
        "                                                <h5 for=\"\">Số hóa đơn:</h5>\n" +
        "                                                <input autocomplete=\"off\" required type=\"text\" class=\"form-control\"name=\"bill_number[" + temp + "]\">\n" +
        "                                            </div>\n" +
        "                                        </div>\n" +
        "                                    </div></div>";
    $('.payment_form').append(text);
    $('.date').datepicker({dateFormat: "yy-mm-dd"});
    format_Number();
});
$('.date').datepicker({dateFormat: "yy-mm-dd"});
$("#sub-more").click(function () {
    $(".payment_form_child_" + temp).remove();
    temp--;
});

pr_payment_temp = 0;
$("#add-more-pr-payment").click(function () {
    pr_payment_temp++;
    text ="        <div class='pr_payment_form_child_" + pr_payment_temp + "'> <div class=\"my-span11\">\n" +
        "                                        <div class=\"span3 div-payment-deparment\">\n" +
        "                                                        <h5 for=\"\">Chi trả cho phòng ban:</h5>\n" +
        "                                        </div>\n" +
        "                                        <div class=\"span3 div-payment\">\n" +
        "                                            <div class=\"form-group\">\n" +
        "                                                <h5 for=\"\">Số tiền thanh toán:</h5>\n" +
        "                                                <input autocomplete=\"off\" required type=\"text\" class=\"number form-control\"name=\"pr_payment_cost[" + pr_payment_temp + "]\">\n" +
        "                                            </div>\n" +
        "                                        </div>\n" +
        "                                        </div>"+
        "                                        <div class=\"span3 div-payment\">\n" +
        "                                            <div class=\"form-group\">\n" +
        "                                                <h5 for=\"\">Số lượng:</h5>\n" +
        "                                                <input autocomplete=\"off\"  type=\"number\" class=\"form-control\"name=\"pr_payment_amount[" + pr_payment_temp + "]\">\n" +
        "                                            </div>\n" +
        "                                        </div>\n"+
        "</div>";
    $('.pr_payment_form').append(text);
    // var $pr_department=$("#department_id").clone().addClass("span3").appendTo('.pr_payment_form_child_'+pr_payment_temp+' .my-span11');
    var $pr_department=$("#department_id").clone().addClass("span3");
    // $pr_department.attr('name').replace('department_id','pr_payment_department_form_child_' + pr_payment_temp);
    $pr_department.attr('name','pr_payment_department[' + pr_payment_temp + ']');
    $pr_department.removeAttr( "id" );
    $pr_department.attr('disabled',false);

    $pr_department.appendTo('.pr_payment_form_child_'+pr_payment_temp+' .my-span11 .div-payment-deparment');

    $('.date').datepicker({dateFormat: "yy-mm-dd"});
    format_Number();
});
$('.date').datepicker({dateFormat: "yy-mm-dd"});
$("#sub-more-pr-payment").click(function () {
    $(".pr_payment_form_child_" + pr_payment_temp).remove();
    pr_payment_temp--;
});




//User
//temp = 0;
$("#add-more-user").click(function () {
    temp++;
    text ="        <div class='payment_form_child_" + temp + "''> <div class=\"my-span11\">\n" +
        "                                        <div class=\"span2 div-payment-date\">\n" +
        "                                            <div class=\"form-group\">\n" +
        "                                                <h5 for=\"\">Ngày đề nghị thanh toán:</h5>\n" +
        "                                                <input autocomplete=\"off\" type=\"\" class=\"span2 date form-control\"name=\"recommended_date[" + temp + "]\" value=\"\">\n" +
        "                                            </div>\n" +
        "                                        </div>\n" +
        "                                        <div class=\"span2 div-payment\">\n" +
        "                                            <div class=\"form-group\">\n" +
        "                                                <h5 for=\"\">Số tiền thanh toán:</h5>\n" +
        "                                                <input autocomplete=\"off\" required type=\"text\" class=\"span2 number form-control\"name=\"payment_cost[" + temp + "]\">\n" +
        "                                            </div>\n" +
        "                                        </div>\n" +
        "                                       <div class=\"span2 div-payment\">\n" +
        "                                            <div class=\"form-group\">\n" +
        "                                                <h5 for=\"\">Số hóa đơn:</h5>\n" +
        "                                                <input autocomplete=\"off\" type=\"text\" class=\"span2 form-control\"name=\"bill_number[" + temp + "]\">\n" +
        "                                            </div>\n" +
        "                                        </div>\n" +
        "                                        <div class=\"span2 div-payment\">\n" +
        "                                            <div class=\"form-group\">\n" +
        "                                                <h5 for=\"\">Ngày hóa đơn:</h5>\n" +
        "                                                <input autocomplete=\"off\" type=\"text\" class=\"span2 date form-control\"name=\"bill_date[" + temp + "]\">\n" +
        "                                            </div>\n" +
        "                                        </div>\n" +
        "                                    </div></div>";
    $('.payment_form').append(text);
    $('.date').datepicker({dateFormat: "yy-mm-dd"});
    format_Number();
});
$('.date').datepicker({dateFormat: "yy-mm-dd"});
$("#sub-more-user").click(function () {
    $(".payment_form_child_" + temp).remove();
    temp--;
});
//End user update








function format_Number(){
    $('input.number').keyup(function(event) {

        // skip for arrow keys
        if(event.which >= 37 && event.which <= 40) return;

        // format number
        $(this).val(function(index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                ;
        });
    });
}



    $('input.number').keyup(function(event) {

        // skip for arrow keys
        if(event.which >= 37 && event.which <= 40) return;

        // format number
        $(this).val(function(index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                ;
        });
    });

function formatMoney2(n, currency) {
    return n.toFixed(0).replace(/./g, function (c, i, a) {
        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
    }) + " " + currency;
}

$(document).ready(function () {
    $('.date').datepicker({dateFormat: "yy-mm-dd"});
});


//edit


function readURL(input) {
    $(".image-preview").append("<div class='container'><div class='row'>");
    for (i = 0; i < input.files.length; i++)
        if (input.files && input.files[i]) {
            const acceptedImageTypes = ['image/gif', 'image/jpg', 'image/jpeg', 'image/png'];
            var is_img = acceptedImageTypes.includes(input.files[i]["type"]);
            var reader = new FileReader();
            if (!is_img) {
                $(".image-preview").append("<div class='col-lg-3 col-md-5 col-sm-8 image-preview-child'><i class='icon-file' style='font-size: 200px';></i></div>");
            } else {
                reader.onload = function (e) {
                    $(".image-preview").append("<div class='col-lg-3 col-md-5 col-sm-8 image-preview-child'>\n" +
                        "<img class=\"img-fluid img-thumbnail staff_img \" src=\"\n" + e.target.result + "\"\n" +
                        "alt=\"FILE\">\n" +
                        "</div>");
                };
                reader.readAsDataURL(input.files[i]);
            }
        }
    $(".image-preview").append(" </div></div>");
}

// $("#check-category").change(function () {
//     if ($("#check-category option:selected").val() == 2) {
//         $("#channel").prop('disabled', false);
//     }
// });

$(".staff_img").click(function () {
    if ($(this).attr("src") != '') {
        $("#full-size-image").prop("src", $(this).attr("src"));
        $("#lightbox").modal();
    }
});

function myFunction() {
    var x = document.getElementById("price");
    var valuex = x.value;
    valuex = valuex.replace(new RegExp(',', 'g'), '');
    valuex = valuex.replace(" ", "");
    a = parseInt(valuex);
    x.value = formatMoney2(a, '').trim();
}

//edit

$("#file").change(function () {
    $(".image-preview-child").remove();
    readURL(this);
});


function goBack() {
  window.history.back();
}







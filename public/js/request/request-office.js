
$(document).on('change', '#department_id', function () {
    $('.loading').remove();
    $(this).after('<span class="loading"></span>');
    var _self = $(this);
    var department_id = $(this).val();
    //$('form').bind('submit', function (e) {
        //e.preventDefault();
    //});
    // $('.reload-by-department').prop('disabled', true);
    $.get("/ajax/load-team",
        {department_id: [department_id]}
        , function (data, status) {
            var obj = $.parseJSON(data);
            $('#team,.team').find('option:not(:first)').remove();
            for (var i = 0; i < obj.length; i++) {
                $('#team,.team').append('<option value="' + obj[i]['id'] + '">' + obj[i]['name'] + '</option>');
            }

            // $('button, .btn, input, select').prop('disabled', false);
            // $('.reload-by-department').prop('disabled', false);
            // $('form').unbind('submit');
            $('.loading').remove();
            $('#team,.team').change();
        });
});
$(document).on('change', '#pr_department_id', function () {
    $('.loading').remove();
    $(this).after('<span class="loading"></span>');
    var _self = $(this);
    var department_id = $(this).val();
    $('form').bind('submit', function (e) {
        e.preventDefault();
    });
    // $('.reload-by-department').prop('disabled', true);
    $.get("/ajax/load-team",
        {department_id: [department_id]}
        , function (data, status) {
            var obj = $.parseJSON(data);
            $('#pr_team_id,.team').find('option:not(:first)').remove();
            for (var i = 0; i < obj.length; i++) {
                $('#pr_team_id,.team').append('<option value="' + obj[i]['id'] + '">' + obj[i]['name'] + '</option>');
            }

            // $('button, .btn, input, select').prop('disabled', false);
            // $('.reload-by-department').prop('disabled', false);
            // $('form').unbind('submit');
            $('.loading').remove();
            $('#pr_team_id,.team').change();
        });
});

$("#has_purchasing_request").change(function () {
    if ($(this).is(':checked')) {
        $('#purchasing_request').show("fast");
        // $('.purchasing_request_form').prop('disabled', false);
        $('#has_payment').prop('checked', true);

        $('#id_pr').prop('disabled', false);
        $('#name_pr').prop('disabled', false);
        $('#urgent_date').prop('disabled', false);
        $('#purchasing_type').prop('disabled', false);
        $('#shipping_address').prop('disabled', false);


        $('#has_payment').prop('disabled', true);

    } else {
        $('#purchasing_request').hide("fast");
        // $('.purchasing_request_form').prop('disabled', true);
        $('#has_payment').prop('checked', false);
        $('#has_payment').prop('disabled', false);
        $('#id_pr').prop('disabled', true);
        $('#name_pr').prop('disabled', true);
        $('#urgent_date').prop('disabled', true);
        $('#purchasing_type').prop('disabled', true);
        $('#shipping_address').prop('disabled', true);

    }
});


$("#id_pr").on("change",function(){
    var $this_data=$("#id_pr option:selected");
    // alert($("#id_pr option:selected").data("type"));
            $("#name_pr").val($this_data.data("name"));
            $("#purchasing_type").val($this_data.data("type"));
            $("#project_id").val($this_data.data("project-id"));


            // $("#type_pr").val($("#id_pr option:selected").data("type"));
            $("#shipping_address").val($this_data.data("shipping-address"));
            $("#urgent_date").val($this_data.data("urgent-date"));
            $("#pr_department_id").val($this_data.data("department-id"));

            $value_team_id=$this_data.data("team-id");
            // $("#pr_team_id").append(new Option(123,$value_team_id));
            $("#pr_team_id").val($value_team_id);

            $("#pr_area_id").val($this_data.data("area-id"));

})
$("#check-category").change(function () {
    if ($("#check-category option:selected").val() == 3) {
        $(".advanced_payment").show();
        $("#advanced_payment").prop('disabled', false);
         $("#advanced_payment").select2();

    }else{
        $(".advanced_payment").hide();
        $("#advanced_payment").prop('disabled', true);


        $(".cost_advanced_payment").hide();
        $(".cost_advanced_payment").prop('disabled', true);
    }
});


// Load cost that was make advanced payment

$(".advanced_payment").change(async function () {
    var $this_data=$("#advanced_payment option:selected");
    $(".cost_advanced_payment").show();
    $("#cost_temp_title").html("Số tiền đã dùng:");
    $("#cost_advanced_payment").val($this_data.data("cost_advanced"));

     request_id=$this_data.val();

    
});
   
$('#cost_temp').bind('input', function() { // get the current value of the input field.
    $cost_temp=$("#cost_temp").val().replace(",","");
    $cost_advanced_payment=$("#cost_advanced_payment").val().replace(",","");

    $calculation_money= $cost_advanced_payment-$cost_temp;
    
    $("#calculation_money").val($calculation_money);
    format_Number();
});

function goBack() {
  window.history.back();
}

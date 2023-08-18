
$(document).ready(function () {
    var prize = Math.floor(Math.random() * 9) + 1;
    var prizeArray_1 = [
        false,
        'Đế điện thoại',
        'Tượng phi hành gia',
        'Bàn chải điện OCLEAN ENDURANCE',
        'Bàn chải điện OCLEAN F5002',
        'Quạt để bàn',
        'Tai nghe JBL',
        'Đèn để bàn',
    ];
    var prizeArray_2 = [
        false,
        'Đế điện thoại',
        'Tượng phi hành gia',
        'Bàn chải điện OCLEAN ENDURANCE',
        'Oppo Watch Free',
        'Quạt để bàn',
        'Tai nghe JBL',
        'Đèn để bàn',
    ];
    var prizeArray_3 = [
        false,
        'Đế điện thoại',
        'Tượng phi hành gia',
        'Bàn chải điện OCLEAN ENDURANCE',
        'Tai nghe Enco Air 2',
        'Quạt để bàn',
        'Tai nghe JBL',
        'Đèn để bàn',
    ];
    var prizeArray_4 = [
        false,
        'Đế điện thoại',
        'Tượng phi hành gia',
        'Bàn chải điện OCLEAN ENDURANCE',
        'Máy lọc không khí LV-H128',
        'Quạt để bàn',
        'Tai nghe JBL',
        'Loa OPPO',
        'Đèn để bàn',
        'Tai nghe Enco Bud',
    ];
    var prizeArray = [];
    var getPrize = false;
    $(' .spin-btn').click(function () {
        $(this).css('visibility', 'hidden');
        var round_number = $("#round_number").val();
        var const_id = 0;
        if (round_number == 1) {
            prizeArray = prizeArray_1;
            const_id = 0;
        }
        if (round_number == 2) {
            prizeArray = prizeArray_2;
            const_id = 8;
        }
        if (round_number == 3) {
            prizeArray = prizeArray_3;
            const_id = 16;
        }
        if (round_number == 4) {
            prizeArray = prizeArray_4;
            const_id = 24;
        }
        $.ajax({
            method: "POST",
            url: "/culture-game/rotation",
            data: {
                'round_number': round_number
            }
        }).done(function (result) {
            var data = JSON.parse(result);
            if (data.status == 1) {
                prize_number = parseInt(data.item_id);
                prize = prize_number - const_id;

                if (getPrize == false) {
                    getPrize = true;
                    $('.wheel-container .wheel').addClass('prize' + prize);
                }

                setTimeout(function () {
                    if (prizeArray[prize - 1] == false) {
                        $('.congratulation p').html('MAY MẮN CHƯA MỈM CƯỜI RỒI. CÙNG CỐ GẮNG Ở TRẠM SAU NHÉ BẠN MÌNH ƠI');
                    } else {
                        $('.congratulation .prize-name').html(data.name);
                    }
                    $('.congratulation').addClass('active');

                    setTimeout(function() {
                        $("#xmas-popup").css('display', 'block');
                    }, 2000);
                    
                }, 15000);

            } else {
                alert(data.message)
            }
        });



    });

});

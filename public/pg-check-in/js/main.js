/*
 *  Copyright (c) 2015 The WebRTC project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a BSD-style license
 *  that can be found in the LICENSE file in the root of the source
 *  tree.
 */

//'use strict';
// Put variables in global scope to make them available to the browser console.
var video = document.querySelector('video');
var canvas1 = window.canvas = document.getElementById('canvas1');
var videoSelect = document.querySelector('#choose-cam');
var selectors = [videoSelect];
var canvas2 = window.canva2 = document.getElementById('canvas2');
var canvas3 = window.canva3 = document.getElementById('canvas3');
canvas1.width = 480;
canvas1.height = 360;
canvas2.width = 480;
canvas2.height = 360;
canvas3.width = 480;
canvas3.height = 360;
var click_button1 = 0;
var click_button2 = 0;
var click_button3 = 0;
var button1 = document.getElementById('btn1');
var button2 = document.getElementById('btn2');
var button3 = document.getElementById('btn3');

$(document).ready(function () {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        //	if (/android/i.test(userAgent)) {
        $('.button-row2').show();
        $('.button-row').hide();
        $('.pic-stack').hide();
        $('.main-canvas').hide();
    } else {
        $('.button-row2').hide();

    }
    init_uploader();
    init_changeShift();
    init_changeStore();
    init_changeCategory();
    getLocation();
    setInterval(function () {
        getLocation();
    }, 100000);

});
button1.onclick = function () {
    mime_type = "image/jpeg";
    canvas1.width = 800;
    canvas1.height = Math.round(video.videoHeight/(video.videoWidth/800)*100)/100;
    canvas1.getContext('2d').
            drawImage(video, 0, 0, canvas1.width, canvas1.height);
    canvas1 = canvas1.toDataURL(mime_type, 20 / 100);
    click_button1 = 1;
};
button2.onclick = function () {
    mime_type = "image/jpeg";
    canvas2.width = 800;
    canvas2.height = Math.round(video.videoHeight/(video.videoWidth/800)*100)/100;
    canvas2.getContext('2d').
            drawImage(video, 0, 0, canvas2.width, canvas2.height);
    canvas2 = canvas2.toDataURL(mime_type, 20 / 100);
    click_button2 = 1;
};
button3.onclick = function () {
    mime_type = "image/jpeg";
    canvas3.width = 800;
    canvas3.height = Math.round(video.videoHeight/(video.videoWidth/800)*100)/100;
    canvas3.getContext('2d').
            drawImage(video, 0, 0, canvas3.width, canvas3.height);
    canvas3 = canvas3.toDataURL(mime_type, 20 / 100);
    click_button3 = 1;
};

var constraints = {
    audio: false,
    video: true
};

function handleSuccess(stream) {
    window.stream = stream; // make stream available to browser console
    video.srcObject = stream;
}
function init_changeShift() {
    $('#shift').change(function () {
        var _shift = $('#shift').val();
        $('#shift1').val(_shift);
    });
}

function init_changeStore() {
    $('#store_id').change(function () {
        var store_id = $('#store_id').val();
        $('#store_id_ios').val(store_id);
    });
}

function init_changeCategory() {
    
    $('.pgcheck_category').change(function () {
        var pgcheck_category = '';
        $('input[name^="pgcheck_category"]:checked').each(function() {
            x = ($(this).val());
            pgcheck_category += x + ',';
        });
        $('#pgcheck_category_ios').val(pgcheck_category);
    });
}

function init_uploader() {
    $('#check-in').click(function () {
        var data = new FormData($("#form")[0]);
        var shift = $('#shift1').val();
        var latitude = $('#latitude1').val();
        var longitude = $('#longitude1').val();
        $('#check-in').submit();

        var link = '?latitude=' + latitude + '&longitude=' + longitude + '&shift=' + shift;
        /*
         $.ajax({
         url: 'https://center-dev.opposhop.vn/time/time-upload'+link, // Url to which the request is send
         type: "POST",             // Type of request to be send, called as method
         data:  data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
         contentType: false,       // The content type used when sending data to the server.
         cache: false,             // To unable request pages to be cached
         processData:false,        // To send DOMDocument or non processed data file it is set to false
         success: function(data)   // A function to be called if request succeeds
         {
         //					var data = JSON.parse(xhttp.responseText);
         //					if(data.status==1){
         //						$('#iframe').text(data.message);
         //					}else{
         //						$('#iframe').text(data.message);
         //					}
         
         }
         });
         */



    });
}

navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia
navigator.getUserMedia(constraints, handleSuccess, handleError);
//  then(handleSuccess).catch(handleError);
function save() {
    document.getElementById("fail-message").innerHTML = "";
    /*
     if (click_button1 == 0 || click_button2 == 0) {
     // return 
     //  alert(1);
     document.getElementById("fail-message").innerHTML = "Vui lòng chụp đủ 2 hình";
     
     return false;
     }
     */
    var latitude = document.getElementById("latitude").value;
    var longitude = document.getElementById("longitude").value;
    var store_id = document.getElementById("store_id").value;
    var pgcheck_note = document.getElementById("pgcheck_note").value;

    var city_input1 = document.getElementById("city_input1").value;
    var city_input2 = document.getElementById("city_input2").value;
    var city_input3 = document.getElementById("city_input3").value;
    var city_input7 = document.getElementById("city_input7").value;
    
    var pgcheck_category = '';
    $('input[name^="pgcheck_category"]:checked').each(function() {
        x = ($(this).val());
        pgcheck_category += '&pgcheck_category[]=' + x
    });

    var address_input = document.getElementById("address_input").value;
//    var canvas1 = document.getElementById("canvas1");
//    var canvas2 = document.getElementById("canvas2");
//    var canvasData1 = canvas1.toDataURL("image/png");
//    var canvasData2 = canvas2.toDataURL("image/png");
    var shift = document.getElementById("shift").value;
    var xhttp = new XMLHttpRequest();
    var params = 'latitude=' + latitude + '&longitude=' + longitude
            + '&city_input1=' + city_input1
            + '&city_input2=' + city_input2
            + '&city_input3=' + city_input3
            + '&city_input7=' + city_input7
            + '&store_id=' + store_id
            + '&pgcheck_note=' + pgcheck_note
            + '&address_input=' + address_input
            + pgcheck_category
            + '&shift=' + shift + '&isimg1=' + click_button1 + '&isimg2=' + click_button2 + '&isimg3=' + click_button3 + '&imagedata1=' + canvas1 + '&imagedata2=' + canvas2+ '&imagedata3=' + canvas3;
    xhttp.onreadystatechange = function () {
        if (this.readyState != 4 && (longitude != '' || latitude != '')) {
            $('.waiting-overlay').css({'visibility': 'visible', 'opacity': '1'});
            $('.waiting-notification').css({'top': '40%', 'opacity': '1'});
        } else {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(xhttp.responseText);
                if (data.status == 1) {
                    // document.getElementById("fail-message").innerHTML = data.message;
                    showModal();
//                    setTimeout(function () {
//                        window.location.href = "/staff-time/staff-view";
//                    }, 3000);
                } else {
                    document.getElementById("fail-message").innerHTML = data.message;
                    $('.waiting-overlay').css('visibility', 'hidden');
                    $('.waiting-notification').css({'top': "300px", 'opacity': 0});
                }
            }
        }

    };
    xhttp.open("POST", "/time/pg-time-save", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(params);
}
function saveOut() {
    document.getElementById("fail-message").innerHTML = "";

    var shift = document.getElementById("shift").value;
    var latitude = document.getElementById("latitude").value;
    var longitude = document.getElementById("longitude").value;
    var xhttp = new XMLHttpRequest();
    var params = "latitude=" + latitude + '&longitude=' + longitude + '&shift=' + shift;
    xhttp.onreadystatechange = function () {
        if (this.readyState != 4 && (longitude != '' || latitude != '')) {
            $('.waiting-overlay').css({'visibility': 'visible', 'opacity': '1'});
            $('.waiting-notification').css({'top': '40%', 'opacity': '1'});
        } else {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(xhttp.responseText);
                if (data.status == 1) {
                    //document.getElementById("fail-message").innerHTML = data.message;
                    showModal();
                } else {
                    document.getElementById("fail-message").innerHTML = data.message;
                    $('.waiting-overlay').css('visibility', 'hidden');
                    $('.waiting-notification').css({'top': "300px", 'opacity': 0});
                }
            }
        }

    };
    xhttp.open("POST", "/time/pg-time-save-out", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(params);
}
function getLocation() {
    //alert(111);
    if (navigator.geolocation) {
        //navigator.geolocation.getCurrentPosition(showPosition, showError);

        navigator.geolocation.getCurrentPosition(function (position, showError) {
            window.latitude = position.coords.latitude;
            window.longitude = position.coords.longitude;


            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('latitude1').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            document.getElementById('longitude1').value = position.coords.longitude;
             // var GEOCODING = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + position.coords.latitude + '%2C' + position.coords.longitude + '&language=en';
             // var GEOCODING = 'https://api.mapbox.com/geocoding/v5/mapbox.places/peets.json?proximity=' + position.coords.longitude + ',' + position.coords.latitude + '&access_token=pk.eyJ1IjoidG9hbm1hcGJveCIsImEiOiJjazY5MG9vMzgwMXZtM2ZxYzN1eWM5dWkyIn0.yzW89GABcKLg_iy2AItoCA';
             // var GEOCODING = 'https://api.opencagedata.com/geocode/v1/json?key=8d519df99f764cb58d00b126104c265d&q=' + position.coords.latitude +'%2C+'+ position.coords.longitude + '&pretty=1&no_annotations=1';
            // var GEOCODING ='https://www.mapquestapi.com/geocoding/v1/reverse?key=KEY&location=' + position.coords.latitude +'%2C'+ position.coords.longitude + '&outFormat=json&thumbMaps=false';
            var GEOCODING ='https://eu1.locationiq.com/v1/reverse.php?key=09ea137c61fa53&lat=' + position.coords.latitude +'&lon='+ position.coords.longitude + '&format=json';
             
             // $.getJSON(GEOCODING).done(function(location) {
             //    console.log(location);
             //  document.getElementById('city_input1').value  = location.address.state;
             // document.getElementById('city_input4').value  = location.address.state;
             
             // document.getElementById('city_input2').value  = location.address.country;
             // document.getElementById('city_input5').value  = location.address.state;
             
             // document.getElementById('city_input3').value  = location.address.state;
             // document.getElementById('city_input6').value  = location.address.state;
             
             // document.getElementById('city_input7').value  = location.address.state;
             // document.getElementById('city_input8').value  = location.address.state;

             // document.getElementById('city_input1').value  = location.results[0].address_components[4].long_name;
             // document.getElementById('city_input4').value  = location.results[0].address_components[4].long_name;
             
             // document.getElementById('city_input2').value  = location.results[0].address_components[5].long_name;
             // document.getElementById('city_input5').value  = location.results[0].address_components[5].long_name;
             
             // document.getElementById('city_input3').value  = location.results[0].address_components[6].long_name;
             // document.getElementById('city_input6').value  = location.results[0].address_components[6].long_name;
             
             // document.getElementById('city_input7').value  = location.results[0].address_components[3].long_name;
             // document.getElementById('city_input8').value  = location.results[0].address_components[3].long_name;
             
             // })
             
        });

    } else {
        document.getElementById("fail-message").innerHTML = "'Geolocation is not supported by this browser";
    }
}
function showPosition(position) {
    console.log("Latitude: " + position.coords.latitude + ", Longitude: " + position.coords.longitude);
    x.innerHTML = "Latitude: " + position.coords.latitude +
            "<br>Longitude: " + position.coords.longitude;

    window.latitude = position.coords.latitude;
    window.longitude = position.coords.longitude;

    document.getElementById('latitude').value = position.coords.latitude;
    document.getElementById('latitude1').value = position.coords.latitude;
    document.getElementById('longitude').value = position.coords.longitude;
    document.getElementById('longitude1').value = position.coords.longitude;
}

function showError(error) {
    x = document.getElementById("fail-message");
    switch (error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}


function gotDevices(deviceInfos) {
    // Handles being called several times to update labels. Preserve values.
    var values = selectors.map(function (select) {
        return select.value;
    });
    selectors.forEach(function (select) {
        while (select.firstChild) {
            select.removeChild(select.firstChild);
        }
    });
    for (var i = 0; i !== deviceInfos.length; ++i) {
        var deviceInfo = deviceInfos[i];
        var option = document.createElement('option');
        option.value = deviceInfo.deviceId;
        if (deviceInfo.kind === 'videoinput') {
            option.text = deviceInfo.label || 'camera ' + (videoSelect.length + 1);
            videoSelect.appendChild(option);
        } else {
            console.log('Some other kind of source/device: ', deviceInfo);
        }
    }
    selectors.forEach(function (select, selectorIndex) {
        if (Array.prototype.slice.call(select.childNodes).some(function (n) {
            return n.value === values[selectorIndex];
        })) {
            select.value = values[selectorIndex];
        }
    });
}


function gotStream(stream) {
    window.stream = stream; // make stream available to console
    videoElement.srcObject = stream;
    // Refresh button list in case labels have become available
    return navigator.mediaDevices.enumerateDevices();
}

function start() {
    if (window.stream) {
        window.stream.getTracks().forEach(function (track) {
            track.stop();
        });
    }
    var videoSource = videoSelect.value;
    var constraints = {
        //audio: {deviceId: audioSource ? {exact: audioSource} : undefined},
        video: {deviceId: videoSource ? {exact: videoSource} : undefined}
    };

    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia
    navigator.getUserMedia(constraints, handleSuccess, handleError);


    // navigator.mediaDevices.getUserMedia(constraints).
    //    then(gotStream).then(gotDevices).catch(handleError);
}
function handleError(error) {
    alert('navigator.getUserMedia error: ', error);
}
navigator.mediaDevices.enumerateDevices().then(gotDevices).catch(handleError);
$('#choose-cam').change(function () {
    start();
});
//start();
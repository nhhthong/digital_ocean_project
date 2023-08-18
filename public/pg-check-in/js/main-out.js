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
var videoSelect = document.querySelector('#choose-cam');
var selectors = [videoSelect];
var click_button1=0;
var click_button2=0;

$(document).ready(function(){
	
	getLocation();
	setInterval(function(){getLocation();},7000);
	
});


var constraints = {
    audio: false,
    video: true
};

function handleSuccess(stream) {
    window.stream = stream; // make stream available to browser console
}

navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia
navigator.getUserMedia(constraints,handleSuccess,handleError);
        //  then(handleSuccess).catch(handleError);

function saveOut() {
    document.getElementById("fail-message").innerHTML = "";
 
    var latitude = document.getElementById("latitude").value;
    var longitude = document.getElementById("longitude").value;
    var xhttp = new XMLHttpRequest();
    var params = "latitude="+latitude+'&longitude='+longitude;
    xhttp.onreadystatechange = function () {
    	if ( this.readyState != 4 && (longitude != '' || latitude !='')){
        	$('.waiting-overlay').css({'visibility':'visible','opacity':'1'});
            $('.waiting-notification').css({'top':'40%','opacity':'1'});
        }else{
        	 if (this.readyState == 4 && this.status == 200) {
                 var data = JSON.parse(xhttp.responseText);
                 if(data.status==1){
                     //document.getElementById("fail-message").innerHTML = data.message;
                 	showModal();  
//					setTimeout(function() { 
//                	    window.location.href = "/staff-time/staff-view"; 
//                	 }, 3000);
                 }else{
                     document.getElementById("fail-message").innerHTML = data.message;
					 $('.waiting-overlay').css('visibility','hidden');
                     $('.waiting-notification').css({'top':"300px",'opacity':0});
                 }
         }
        }
       
    };
    xhttp.open("POST", "/time/pg-time-save-out", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(params);
}
function getLocation() {

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        document.getElementById("fail-message").innerHTML = "'Geolocation is not supported by this browser";
    }
}
function showPosition(position) {

    window.latitude = position.coords.latitude;
    window.longitude = position.coords.longitude;
    document.getElementById('latitude').value = position.coords.latitude;
    document.getElementById('longitude').value = position.coords.longitude;
}

function showError(error) {
    x=  document.getElementById("fail-message");
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
  var values = selectors.map(function(select) {
    return select.value;
  });
  selectors.forEach(function(select) {
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
  selectors.forEach(function(select, selectorIndex) {
    if (Array.prototype.slice.call(select.childNodes).some(function(n) {
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
	    window.stream.getTracks().forEach(function(track) {
	      track.stop();
	    });
	  }
	  var videoSource = videoSelect.value;
	  var constraints = {
	    //audio: {deviceId: audioSource ? {exact: audioSource} : undefined},
	    video: {deviceId: videoSource ? {exact: videoSource} : undefined}
	  };
	  
	  navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia
	  navigator.getUserMedia(constraints,handleSuccess,handleError);
	  
	  
	 // navigator.mediaDevices.getUserMedia(constraints).
	  //    then(gotStream).then(gotDevices).catch(handleError);
	}
function handleError(error) {
	//  alert('navigator.getUserMedia error: ', error);
	}
navigator.mediaDevices.enumerateDevices().then(gotDevices).catch(handleError);
$('#choose-cam').change(function(){
	start();
});
//start();
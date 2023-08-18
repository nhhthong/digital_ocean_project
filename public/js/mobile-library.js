var handler = new Handler();
var storage = window.localStorage;

// START COMMUNICATION CORE
function Work(id, onSuccess, onError) {
    this.id = id;
    this.onSuccess = onSuccess;
    this.onError = onError;
}

function Request(functionName, options = {}) {
    this.id = new Date().getTime();
    this.functionName = functionName;
    this.options = options;
}

function Response(data) {
    this.id = data.id;
    this.status = data.status;
    this.data = data.data;
    this.watching = data.watching;
}

/**
 * Send Request
 * @param request: Request
 * @param onSuccess: function(data)
 * @param onError: function(data)
 */
function sendMessage(request, onSuccess = null, onError = null) {
    let work = new Work(request.id, onSuccess, onError);
    handler.push(work);
    window.parent.postMessage(request, '*');
}

/**
 * Receive Response API
 */
$(document).ready(function () {
    $(window).on("message", function (e) {
        let data = e.originalEvent.data;
        if (typeof data === 'object' && data.id !== undefined) {
            let response = new Response(data);
            let work = handler.pop(response.id);
            if (work !== null) {
                if (response.status === ApiStatus.SUCCESS && work.onSuccess !== null) {
                    work.onSuccess(response.data);
                }
                if (response.status === ApiStatus.ERROR && work.onError !== null) {
                    work.onError(response.data);
                }
            } else if (response.watching) {
                if (response.data.coords !== undefined) { // WatchPosition
                    let watchingId = _Geolocation._getWatchingId();
                    if (watchingId === response.data.watchId) {
                        _Geolocation._removeWatchingId();
                    }
                    _Geolocation.clearWatch(response.data.watchId);
                }
            }
        }
    });

    /**
     * Get current device
     */
    if (storage.getItem("DEVICE_PLATFORM") === null) {
        _Device.requestPlatform();
        setTimeout(function () {
            if (storage.getItem("DEVICE_PLATFORM") === null) {
                storage.setItem("DEVICE_PLATFORM", "WEB");
            }
        }, 500);
    }
});
// END COMMUNICATION CORE

// START API
var _Geolocation = {
    /**
     * [TESTED] Returns the device's current position
     * @param onSuccess: function(position)
     * @param onError: function(PositionError)
     * @param options: object
     *      enableHighAccuracy: bool,
     *      timeout: int (milliseconds),
     *      maximumAge: int (milliseconds allow get position in cache),
     */
    getCurrentPosition: function (onSuccess, onError, options = {}) {
        if (options['enableHighAccuracy'] === undefined) {
            options['enableHighAccuracy'] = true;
        }
        if (options['timeout'] === undefined) {
            options['timeout'] = 30 * 1000; // 30 seconds
        }
        if (options['maximumAge'] === undefined) {
            options['maximumAge'] = 20 * 1000; // 20 seconds
        }
        sendMessage(new Request('_Geolocation.getCurrentPosition', options), onSuccess, onError);
    },

    /**
     * [TESTED] Returns the device's current position when a change in position is detected
     * @param onSuccess: function(position)
     * @param onError: function(error)
     * @param options: position {coords: Object, watchId: string}
     *      enableHighAccuracy: bool,
     *      timeout: int (milliseconds),
     *      maximumAge: int (milliseconds allow get position in cache),
     */
    watchPosition: function (onSuccess, onError, options = {}) {
        let watchingId = Geolocation._getWatchingId();
        if (watchingId) {
            Geolocation.clearWatch(watchingId);
            Geolocation._removeWatchingId();
        }
        if (options['enableHighAccuracy'] === undefined) {
            options['enableHighAccuracy'] = true;
        }
        if (options['timeout'] === undefined) {
            options['timeout'] = 30 * 1000; // 30 seconds
        }
        if (options['maximumAge'] === undefined) {
            options['maximumAge'] = 20 * 1000; // 20 seconds
        }
        let preSuccess = function (position) {
            let watchingId = Geolocation._getWatchingId();
            if (watchingId !== position.watchId) {
                Geolocation.clearWatch(watchingId);
                Geolocation._removeWatchingId();
                Geolocation._setWatchingId(position.watchId);
            }
            onSuccess(position);
        };
        sendMessage(new Request('_Geolocation.watchPosition', options), preSuccess, onError);
    },

    /**
     * [TESTED] Stop watching for changes to the device's location referenced
     * @param watchId: string
     */
    clearWatch: function (watchId) {
        sendMessage(new Request('_Geolocation.clearWatch', watchId), null, null);
    },

    /**
     * Get watching position id in storage
     * @returns {string}
     * @private
     */
    _getWatchingId: function () {
        return storage.getItem('WATCHING_POSITION_ID');
    },

    /**
     * Set watching position id in storage
     * @param watchId: string
     * @private
     */
    _setWatchingId: function (watchId) {
        storage.setItem('WATCHING_POSITION_ID', watchId);
    },

    /**
     * Set watching position id in storage
     * @private
     */
    _removeWatchingId: function () {
        storage.removeItem('WATCHING_POSITION_ID');
    }
};

var _Camera = {
    /**
     * [TESTED] Takes a photo using the camera, or retrieves a photo from the device's image gallery
     * @param onSuccess: function(ImageFileBase64)
     * @param onError: function(message)
     * @param options Read more at: https://cordova.apache.org/docs/en/latest/reference/cordova-plugin-camera/index.html#module_camera.Camera
     */
    getPicture: function (onSuccess, onError, options = {}) {
        if (options['quality'] === undefined) {
            options['quality'] = 90;
        }
        if (options['destinationType'] === undefined) {
            options['destinationType'] = CameraOptions.DestinationType.FILE_URI;
        }
        if (options['sourceType'] === undefined) {
            options['sourceType'] = CameraOptions.PictureSourceType.CAMERA;
        }
        if (options['allowEdit'] === undefined) {
            options['allowEdit'] = false;
        }
        if (options['encodingType'] === undefined) {
            options['encodingType'] = CameraOptions.EncodingType.JPEG;
        }
        if (options['targetWidth'] === undefined) {
            options['targetWidth'] = 2560; // Max 2MP
        }
        if (options['targetHeight'] === undefined) {
            options['targetHeight'] = 2560; // Max 2MP
        }
        if (options['correctOrientation'] === undefined) {
            options['correctOrientation'] = true;
        }
        if (options['saveToPhotoAlbum'] === undefined) {
            options['saveToPhotoAlbum'] = false;
        }
        if (options['cameraDirection'] === undefined) {
            options['cameraDirection'] = CameraOptions.Direction.BACK;
        }
        sendMessage(new Request('_Camera.getPicture', options), onSuccess, onError);
    }
};

var _Scanner = {
    /**
     * [TESTED] QRScanner's native camera preview is rendered behind the Cordova app's webview
     * @param onSuccess: function(content)
     * @param onError: function(code)
     */
    scan: function (onSuccess, onError) {
        sendMessage(new Request('_Scanner.scan', {}), onSuccess, onError);
    }
};

var _ImagePicker = {
    getPictures: function(onSuccess, onError, options = {}) {
        if(options['maxImages'] === undefined) {
            options['maxImages'] = 10;
        }
        if(options['width'] === undefined) {
            options['width'] = 2560; // Max 2MP
        }
        if(options['height'] === undefined) {
            options['height'] = 2560; // Max 2MP
        }
        if(options['quality'] === undefined) {
            options['quality'] = 50;
        }
        sendMessage(new Request('_ImagePicker.getPictures', options), onSuccess, onError);
    }
};

var _Device = {
    requestPlatform: function () {
        sendMessage(new Request('_Device.requestPlatform'), function () {
            storage.setItem("DEVICE_PLATFORM", "APP_MOBILE");
        })
    }
};
// END API

// START CONSTANT API
const PositionError = {
    PERMISSION_DENIED: 1,
    POSITION_UNAVAILABLE: 2,
    TIMEOUT: 3
};

const CameraOptions = {
    DestinationType: {
        DATA_URL: 0, // Return base64 encoded string. DATA_URL can be very memory intensive and cause app crashes or out of memory errors
        FILE_URI: 1, // Return file uri (content://media/external/images/media/2 for Android)
        NATIVE_URI: 2 // Return native uri (eg. asset-library://... for iOS)
    },
    EncodingType: {
        JPEG: 0, // Return JPEG encoded image
        PNG: 1 // Return PNG encoded image
    },
    MediaType: {
        PICTURE: 0, // Allow selection of still pictures only. DEFAULT. Will return format specified via DestinationType
        VIDEO: 1, // Allow selection of video only, ONLY RETURNS URL
        ALLMEDIA: 2 // Allow selection from all media types
    },
    PictureSourceType: {
        PHOTOLIBRARY: 0, // Choose image from the device's photo library
        CAMERA: 1, // Take picture from camera
        SAVEDPHOTOALBUM: 2 // Choose image only from the device's Camera Roll album (same as PHOTOLIBRARY for Android)
    },
    Direction: {
        BACK: 0, // Use the back-facing camera
        FRONT: 1 // Use the front-facing camera
    }
};

const ScannerError = {
    UNEXPECTED_ERROR: 0,
    CAMERA_ACCESS_DENIED: 1,
    CAMERA_ACCESS_RESTRICTED: 2,
    BACK_CAMERA_UNAVAILABLE: 3,
    FRONT_CAMERA_UNAVAILABLE: 4,
    CAMERA_UNAVAILABLE: 5,
    SCAN_CANCELED: 6,
    LIGHT_UNAVAILABLE: 7,
    OPEN_SETTINGS_UNAVAILABLE: 8
};

const ApiStatus = {
    SUCCESS: 1,
    ERROR: 0
};
// END CONSTANT API

// START FUNCTION
/**
 * Check current platform is mobile app
 * @returns {boolean}
 */
function isMobilePlatform() {
    return storage.getItem("DEVICE_PLATFORM") === "APP_MOBILE";
}

function isWebPlatform() {
    return storage.getItem("DEVICE_PLATFORM") !== "APP_MOBILE";
}

function convertFileToBase64(file, callback) {
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function () {
        callback(reader.result);
    };
}

function convertBase64ToFile(base64, filename) {
    var arr = base64.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
    while(n--){
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new File([u8arr], filename + '.' + mime.split('/')[1], {type:mime});
}

// Create thumbnail from base64 from Bart Kalisz - WunderBart
function createThumbnail(base64Image, targetWidth, callback) {
    var img = new Image();
    img.onload = function() {
        var width = img.width,
            height = img.height,
            targetHeight = Math.floor(targetWidth / width * height),
            canvas = document.createElement('canvas'),
            ctx = canvas.getContext("2d");

        canvas.width = targetWidth;
        canvas.height = targetHeight;

        ctx.drawImage(img, 0, 0, targetWidth, targetHeight);
        callback(canvas.toDataURL());
    };
    img.src = base64Image;
}

function getDataUrl(url, callback) {
    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        let reader = new FileReader();
        reader.onloadend = function() {
            callback(reader.result);
        };
        reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}

function showLoadingPage() {
    if(isMobilePlatform()) {
        window.parent.postMessage('page-loading', '*');
    }
}

function hideLoadingPage() {
    if(isMobilePlatform()) {
        window.parent.postMessage('page-loaded', '*');
    }
}

$.cachedScript = function(url, callbackSuccess, callbackError) {
    let options = {
        dataType: "script",
        cache: true,
        url: url
    };
    $.ajax(options).done(callbackSuccess).fail(callbackError);
};


// END FUNCTION
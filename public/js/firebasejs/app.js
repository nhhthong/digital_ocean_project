var isPushEnabled = false;
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });

    $('#btnSubmit').on('click', function() {
        if ($('#delayTime').val() > 0) {
            $.ajax({
                url : "addJob",
                type : "post",
                dateType: "JSON",
                data : $('#formPushNofitication').serialize(),
                success: function() {
                    alert('Your nofitication has been added to queues !');
                },
                error: function() {
                    alert('0');
                }
            });
        } else {
            $.ajax({
                url : "push",
                type : "post",
                dateType: "JSON",
                data : $('#formPushNofitication').serialize(),
                success: function() {
                    alert('Push Done !');
                },
                error: function() {
                    alert('0');
                }
            });
        }

    });
});


$(window).on('load', function() {
    $('.js-push-button').on('click', function() {
        if (isPushEnabled) {
            unsubscribe();
        } else {
            subscribe();
        }
    });

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/js/firebasejs/service-worker.js')
        .then(initialiseState);
    } else {
        console.warn('Service workers aren\'t supported in this browser.');
    }
})


function initialiseState() {
    if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
        console.warn('Notifications aren\'t supported.');
        return;
    }

    if (Notification.permission === 'denied') {
        console.warn('The user has blocked notifications.');
        return;
    }

    if (!('PushManager' in window)) {
        console.warn('Push messaging isn\'t supported.');
        return;
    }

    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
        serviceWorkerRegistration.pushManager.getSubscription()
        .then(function(subscription) {
            var pushButton = document.querySelector('.js-push-button');
            pushButton.disabled = false;

            if (!subscription) {
                return;
            }

            sendSubscriptionToServer(subscription);

            $('.switch-label').text('Disable Push Notifications');
            pushButton.checked = true;
            isPushEnabled = true;
            $('#formPushNofitication').show();
        })
        .catch(function(err) {
            console.warn('Error during getSubscription()', err);
        });
    });
}

function subscribe() {
    var pushButton = document.querySelector('.js-push-button');
    pushButton.disabled = true;
    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
        serviceWorkerRegistration.pushManager.subscribe({userVisibleOnly: true})
        .then(function(subscription) {
            isPushEnabled = true;
            $('.switch-label').text('Disable Push Notifications');
            pushButton.checked = true;
            pushButton.disabled = false;
            $('#formPushNofitication').show();

            return sendSubscriptionToServer(subscription);
        })
        .catch(function(e) {
            if (Notification.permission === 'denied') {
                console.warn('Permission for Notifications was denied');
                pushButton.disabled = true;
            } else {
                console.error('Unable to subscribe to push.', e);
                pushButton.disabled = false;
                $('.switch-label').text('Enable Push Notifications');
                pushButton.checked = false;
                $('#formPushNofitication').hide();
            }
        });
    });
}

function unsubscribe() {
    var pushButton = document.querySelector('.js-push-button');
    pushButton.disabled = true;
    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
        serviceWorkerRegistration.pushManager.getSubscription().then(
            function(pushSubscription) {
                if (!pushSubscription) {
                    isPushEnabled = false;
                    pushButton.disabled = false;

                    $('.switch-label').text('Enable Push Notifications');
                    $('#formPushNofitication').hide();
                    pushButton.checked = false;
                    return;
                }

                var subscriptionId = pushSubscription.subscriptionId;
                pushSubscription.unsubscribe().then(function(successful) {
                    pushButton.disabled = false;
                    $('.switch-label').text('Enable Push Notifications');
                    $('#formPushNofitication').hide();
                    pushButton.checked = false;
                    isPushEnabled = false;
                }).catch(function(e) {
                    console.log('Unsubscription error: ', e);
                    pushButton.disabled = false;
                    $('.switch-label').text('Enable Push Notifications');
                    pushButton.checked = false;
                });
            }).catch(function(e) {
                console.error('Error thrown while unsubscribing from push messaging.', e);
            });
        });
}
function sendSubscriptionToServer(subscription) {
    // $.ajax({
    //     url : "save_endpoint",
    //     type : "post",
    //     dateType:"text",
    //     data : {
    //         endpoint : subscription.endpoint
    //     }
    // });

    $('#endPoint').val(subscription.endpoint);
}

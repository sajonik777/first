"use strict";

(function () {
    // ======== private vars ========
    var socket;
    var xhttp;
    var srvaddress = '/ws/';
    var startserveraddress = srvaddress + 'echowsstart.php';
    var request_uri = location.pathname + location.search;
    var req = (/user=/.test(request_uri));

    ////////////////////////////////////////////////////////////////////////////
    var init = function () {

        wsserverrun();
        socket = new WebSocket('ws://' + document.domain + ':8889/pr');

        socket.onopen = connectionOpen;
        socket.onmessage = messageReceived;
        //socket.onerror = errorOccurred;
        //socket.onopen = connectionClosed;
        if (request_uri == '/chat/privates' || req == true) {
            document.getElementById("sock-send-butt").onclick = function () {
                if (document.getElementById("sock-msg").value != '') {
                    var sid = get_cookie("PHPSESSID");
                    socket.send(sid + '|' + document.getElementById("sock-usr").value + '|' + document.getElementById("sock-msg").value);
                    document.getElementById("sock-msg").value = '';
                }
            };
            var input = document.getElementById("sock-msg");
            input.onkeydown = keydown;
        }
        setTimeout(loopGetChats, 500);
    };


    function get_cookie(cookie_name) {
        var results = document.cookie.match('(^|;) ?' + cookie_name + '=([^;]*)(;|$)');

        if (results)
            return ( decodeURI(results[2]) );
        else
            return null;
    }

    function connectionOpen() {
        if (request_uri == '/chat/privates' || req == true) {
            document.getElementById("sock-info").innerHTML += "<i class='icon-square text-green'></i> Соединение открыто<br>";
        }
    }

    function keydown(event) {
        var keypressed = event.keyCode || event.which;
        if (keypressed == 13) {
            if (document.getElementById("sock-msg").value != '') {
                var sid = get_cookie("PHPSESSID");
                socket.send(sid + '|' + document.getElementById("sock-usr").value + '|' + document.getElementById("sock-msg").value);
                document.getElementById("sock-msg").value = '';
            }
        }
    }

    var loopGetChats = function () {
        var sid = get_cookie("PHPSESSID");
        if (request_uri == '/chat/privates' || req == true) {
            var user = document.getElementById("sock-usr").value;
            socket.send(sid + '|' + user);

            var users_id = $('ul#priv_msg li a');
            var all_id = $('ul#all_msg li a');
            users_id.each(function(){
                var reader = $(this).text().trim();
                $.ajax({
                    type: "POST",
                    url: "/site/getprivcount",
                    data: {'reader': reader, 'user': user},
                    dataType: "json",
                    success: function (event) {
                       if (event !== null && event.count > 0) {
                            var ids = '#'+event.name+'_msg';
                            var ids_count = '#'+event.name+'_msg_count';
                            playNotification();
                            blinkNotify(ids);
                            $(ids_count).html(event.count);
                       }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });
            all_id.each(function(){
                var reader = $(this).text().trim();
                $.ajax({
                    type: "POST",
                    url: "/site/getprivcount",
                    data: {'reader': reader, 'user': user},
                    dataType: "json",
                    success: function (event) {
                        if (event !== null && event.count > 0) {
                            var ids = '#'+event.name+'_msg';
                            var ids_count = '#'+event.name+'_msg_count';
                            playNotification();
                            $(ids_count).html(event.count);
                            blinkNotify(ids);
                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });
        }
        $.ajax({
            type: "POST",
            url: "/site/getmsgcount",
            dataType: "text",
            success: function (result) {
                if (result > 0) {
                    if (request_uri == '/chat/privates' || req == true) {
                        //playNotification();
                        document.getElementById('msg_count').innerHTML = result;
                        blinkNotify('#msg_count');
                    } else {
                        playNotification();
                        document.getElementById('msg_count').innerHTML = result;
                        blinkNotify('#msg_count');
                    }
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
        setTimeout(loopGetChats, 4000);
    };

    function messageReceived(e) {
        var json = eval('(' + e.data + ')');
        var chats = '';
        if (request_uri == '/chat/privates' || req == true) {
            for (var i = json.length - 1; i >= 0; i--) {
                var content = json[i].message;
                var result = '';
                var nice = '';
                /* Составляем небольшое регулярное выражение: */
                content = content.replace(/((https?\:\/\/|ftp\:\/\/)|(www\.))(\S+)(\w{2,4})(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi, function(url) {
                    nice = url;
                    if( url.match('^https?:\/\/') )
                        nice = nice.replace(/^https?:\/\//i,'')
                    else
                        url = 'http://' + url;
                    /* Вовзращаем кликабельный урл, завернутый в nofollow и яндексовый noindex: */
                    return '<noindex><a target="_blank" rel="nofollow" href="'+ url +'">'+ nice.replace(/^www./i,'') +'</a></noindex>';
                });
                result = content;

                if (i % 2 == 0) {
                    var chat = '<div class="direct-chat-msg right"><div class="direct-chat-info clearfix">'
                        + '<span class="direct-chat-name pull-right">' + json[i].name + '</span>'
                        + '<span class="direct-chat-timestamp pull-left">' + json[i].created + '</span></div>'
                        + '<img class="direct-chat-img" src="/images/profle.png">'
                        + '<div class="direct-chat-text">' + result + '</div></div>';

                } else {
                    var chat = '<div class="direct-chat-msg"><div class="direct-chat-info clearfix">'
                        + '<span class="direct-chat-name pull-left">' + json[i].name + '</span>'
                        + '<span class="direct-chat-timestamp pull-right">' + json[i].created + '</span></div>'
                        + '<img class="direct-chat-img" src="/images/profle.png">'
                        + '<div class="direct-chat-text">' + result + '</div></div>';

                }
                chats = chat + chats;
            }
        }
        if (request_uri == '/chat/privates' || req == true) {
            document.getElementById("sock-messages").innerHTML = (chats);
        }
    }

    function connectionClose() {
        socket.close();
        if (request_uri == '/chat/privates' || req == true) {
            document.getElementById("sock-info").innerHTML += "<i class='icon-square text-red'></i> Соединение закрыто<br>";
        }
    }

    function playNotification() {
        var audio = document.getElementsByTagName("audio")[0];
        audio.play();
    }

    function blinkNotify(ids){
        var freqSecs = 0.2;
        setInterval(blink, freqSecs * 1000);
        function blink() {
            var inout = (freqSecs * 1000) / 0.5;
            $(ids).fadeIn(inout).fadeOut(inout);
        }
    }

    var wsserverrun = function () {

        xhttp = new XMLHttpRequest();
        xhttp.open('GET', startserveraddress, true);
        xhttp.send();
        xhttp.onreadystatechange = function () {
            if (xhttp.readyState == 4) {
                //Принятое содержимое файла должно быть опубликовано
                //console.log(xhttp.responseText);
                //Принятое содержимое json файла должно быть вначале обработано функцией eval
                var json = eval('(' + xhttp.responseText + ')');

                if (json.run == 1) return;
                else if (json.run == 2) {
                    sleep(500);
                    return;
                }
            }
        }
    };

    function sleep(ms) {
        ms += new Date().getTime();
        while (new Date().getTime() < ms) {
        }
    };

    return {
        ////////////////////////////////////////////////////////////////////////////
        // ---- onload event ----
        load: function () {
            window.addEventListener('load', function () {
                init();
            }, false);
        }
    }
})().load();

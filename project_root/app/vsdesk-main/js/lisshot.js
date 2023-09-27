/* 
 ======================
 ==== LisShot 1.0 =====
 Screenshot library
 == Lisnes (c) 2014 ==
 */
$.fn.LisShot = function (opts) {
    var self = LisShot;
    self.target = $(this);

    var options = $.extend({
        uploadUrl: false,
        columns: 2,
        lang: "ru",
        click: "increase",
        success: function () {
        }
    }, opts);

    self.columns = options.columns;

    self.injectCss();
    self.moz();

    $("body")[0].addEventListener('paste', function (e) {
            LisShot.paste(e);
        },
        false);

    self.uploadUrl = options.uploadUrl;
    self.lang = self.language[options.lang];
    self.success = options.success;
    self.click = options.click;
};

var LisShot = {
    /*
     Mozilla's ficha
     */
    moz: function () {
        if (!this.isMoz()) {
            return false;
        }

        this.target.prepend('<div id="LisShot-temp" contenteditable="true" style="width:1px;height:1px;color:#fff;"></div>');
        $("#LisShot-temp").focus();
    },

    /*
     Paste of screenshots
     */
    paste: function (e) {
        if (this.isMoz()) {
            $("#LisShot-temp").focus();

            setTimeout(function () {
                LisShot.loadImg($("#LisShot-temp img").attr("src"));
                $("#LisShot-temp img").remove();
            }, 1);
        }
        else if (e.clipboardData) {
            for (var i = 0, l = e.clipboardData.items.length; i < l; i++) {
                var item = e.clipboardData.items[i];
                if (item.kind == 'file' && item.type == 'image/png') {
                    this.loadImg(item.getAsFile());
                    e.preventDefault();
                    break;
                }
            }
        }
        else this.error(this.lang.html5Error, function () {
                var close = LisShot.close;
                LisShot = {};
                LisShot.close = close;
            });
    },

    /*
     Check if browser is Mozilla Firefox
     */
    isMoz: function () {
        return (/firefox/i.test(navigator.userAgent.toLowerCase()));
    },

    /*
     Show preview of image
     */
    loadImg: function (file) {
        if (!this.tmpl) {
            this.tmpl = '<input type="hidden" name="bufers[]" value="$srcI"><div class="LisShot-wrap"' + (this.click == 'increase' ? ' onclick="LisShot.increase(this);"' : '') + ' id="LisShot-screen$id">' + (this.click == 'newtab' ? '<a href="$src" target="_blank" class="LisShot-link">' : '') + '<img src="$src" class="LisShot-screen"><div class="LisShot-title">$screen №$n</div>' + (this.click == 'newtab' ? '</a>' : '') + '</div>';
        }

        if (this.isMoz()) {
            var sourceSplit = file.split("base64,");
            var sourceString = sourceSplit[1];
            this.images[this.id] = {};
            this.target.prepend(this.tmpl.replace('$id', this.id).replace(/\$srcI/g, sourceString).replace(/\$src/g, file).replace('$screen', this.lang.screen).replace('$n', (this.id + 1)));
            this.images[this.id].url = file;
            file = this.dataURLtoBlob(file);
            this.images[this.id].file = file;
        }

        if (!this.isMoz()) {
            this.images[this.id] = {
                file: file
            };
            var fr = new FileReader();
            fr.sid = this.id;
            fr.onload = function () {
                var sourceSplit = this.result.split("base64,");
                var sourceString = sourceSplit[1];
                var id = this.sid;
                LisShot.target.prepend(LisShot.tmpl.replace('$id', id).replace(/\$srcI/g, sourceString).replace(/\$src/g, this.result).replace('$screen', LisShot.lang.screen).replace('$n', (id + 1)));
                LisShot.images[id].url = this.result;
            };
            fr.readAsDataURL(file);
        }

        if (this.uploadUrl) {
            var fr2 = new FileReader();
            fr2.sid = this.id;
            fr2.onload = function () {
                LisShot.upload(this.sid, this.result);
                LisShot.images[this.sid].binaryStr = this.result;
            };
            fr2.readAsBinaryString(file);
        }

        this.id++;
    },

    /*
     Inject the css styles into html page
     */
    injectCss: function () {
        if ($("#LisShot-css").length > 0)
            return false;

        this.target.append('<div class="LisShot-clear" id="LisShot-main"></div>');
        this.target = $("#LisShot-main");
        this.maxW = $("body").width() - 100;
        this.maxH = $(window).height() - 30;
        this.left = ($('body').width() - this.maxW) / 2;

        this.thumbW = this.target.width() / this.columns - 20;
        this.thumbH = parseFloat(((screen.height * this.thumbW) / screen.width).toFixed(0));

        $("head").append('<style id="LisShot-css">\
		  .LisShot-clear{display:block;background:#cdcdcd;}.LisShot-clear:after{display:block;visibility:hidden;height:0px;content:" ";clear:both;}\
          .LisShot-wrap{box-shadow: 0 .5px 3px rgba(0,0,0,.37);border-radius:5px;padding:5px;background:#fff;width:' + this.thumbW + 'px;height:' + (this.thumbH + 33) + 'px;margin:4px;float:left;cursor:pointer;}\
		  .LisShot-wrap{-webkit-transition: all 0.6s ease;}\
          .LisShot-screen{width:' + this.thumbW + 'px;height:' + this.thumbH + 'px;margin:0px;display:block;}\
          .LisShot-title{width:' + (this.thumbW - 10) + 'px;margin-top:5px;border-radius:5px;padding:7px 5px;font-weight:bold;color:#80c241;background:#fff;white-space: nowrap;text-overflow: ellipsis;overflow:hidden;}\
		  .LisShot-large{width:' + this.maxW + 'px;height:' + this.maxH + 'px;position:fixed;top:5px;left:45px;z-index:10000;}\
		  .LisShot-large .LisShot-screen{width:' + this.maxW + 'px;height:' + (this.maxH - 33) + 'px}\
		  .LisShot-large .LisShot-title, .LisShot-large .LisShot-progress-bar{width:' + (this.maxW - 10) + 'px;}\
		  .LisShot-uploaded .LisShot-title{background:#80c241 !important;color:#fff !important;}\
		  .LisShot-error{background:#bb2d2d;border-bottom:1px solid #000;padding:8px 15px;width:' + (this.maxW + 70) + 'px;height:18px;top:0px;left:0px;position:fixed;}\
		  .LisShot-error-text{color:#fff;font-weight:bold;float:left;}\
		  .LisShot-error-close{float:right;color:#fff;font-weight:bold;text-decoration:underline;margin:0px 10px;cursor:pointer;}\
		  #LisShot-temp img{width:1px;height:1px;}\
		  .LisShot-progress-bar-wrap{padding:5px;}\
		  .LisShot-progress-bar{height:18px;background:#191d21;padding:1px;border-radius:10px;}\
		  .LisShot-progress{height:18px;border-radius:10px;width:0%;background: url(./images/prg_bar.png) repeat-x;transition:width 0.4s ease;-moz-transition:width 0.4s ease;-webkit-transition:width 0.4s ease;-o-transition:width 0.4s ease;}\
		  .LisShot-link{text-decoration:none;}\
		</style>');
    },

    /*
     Switch between fullsize and thumb
     */
    increase: function (el) {
        el = $(el);
        var
            clone = el.clone(),
            of = el.offset();

        el.attr("id", "LisShot-hidden");

        $("body").append(clone);
        clone.css({"position": "fixed", "top": (of.top - 3) + "px", "left": (of.left - 3) + "px"})
            .attr({"data-offset": JSON.stringify(of)})
            .animate({
                "top": "5px",
                "left": "45px",
                "width": this.maxW + "px",
                "height": this.maxH + "px"
            }, 100, function () {
                $(this).addClass("LisShot-large").attr("onclick", "LisShot.back(this);");
            });


        clone.find("img").animate({"width": (this.maxW - 10) + "px", "height": (this.maxH - 33) + "px"}, 400);
    },
    back: function (el) {
        el = $(el);

        el.removeClass("LisShot-large");

        var of = $.parseJSON(el.attr("data-offset"));

        el.animate({
            "width": this.thumbW + "px",
            "height": (this.thumbH + 33) + "px",
            "left": of.left + "px",
            "top": of.top + "px"
        }, 300, function () {
            $("#LisShot-hidden").attr("id", $(this).attr("id"));
            $(this).remove();
        });
        el.find("img").animate({"width": this.thumbW + "px", "height": this.thumbH + "px"});
    },

    /*
     File upload

     id - id of file
     file - file binarystring
     */
    upload: function (id, file) {
        if (!this.uploadUrl) {
            return;
        }

        var xhr = new XMLHttpRequest();

        if (xhr.upload && xhr.upload.addEventListener) {
            xhr.upload.photoId = id;
            xhr.upload.addEventListener('progress', function (e) {
                if (e.lengthComputable) {
                    if ($("#LisShot-screen" + this.photoId + " .LisShot-progress-bar-wrap").length == 0) {
                        $("#LisShot-screen" + this.photoId + " .LisShot-title").addClass("LisShot-progress-bar-wrap").html('<div class="LisShot-progress-bar"><div class="LisShot-progress"></div></div>')
                    }
                    else {
                        var p = (e.loaded / e.total) * 100;
                        $("#LisShot-screen" + this.photoId + " .LisShot-progress").css("width", p + "%");
                    }
                }
            }, false);

            xhr.upload.addEventListener('error', function (e) {
                LisShot.error(e);
            }, false);

            xhr.onreadystatechange = function () {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        var result = LisShot.parse(this.response);
                    }
                    else {
                        LisShot.error(this);
                    }
                }
            };

            xhr.onerror = function (e) {
                LisShot.error(e);
            };

            xhr.open("POST", this.uploadUrl);

            var boundary = "xxxxxxxxx";
            xhr.setRequestHeader('Content-type', 'multipart/form-data; boundary="' + boundary + '"');
            xhr.setRequestHeader('Cache-Control', 'no-cache');

            var body = "--" + boundary + "\r\n";
            body += "Content-Disposition: form-data; name='file'; filename='" + this.title(id) + ".png'\r\n";
            body += "Content-Type: application/octet-stream\r\n\r\n";
            body += file + "\r\n";
            body += "--" + boundary + "--";

            if (!XMLHttpRequest.prototype.sendAsBinary) {
                XMLHttpRequest.prototype.sendAsBinary = function (datastr) {
                    function byteValue(x) {
                        return x.charCodeAt(0) & 0xff;
                    }

                    var ords = Array.prototype.map.call(datastr, byteValue);
                    var ui8a = new Uint8Array(ords);
                    this.send(ui8a.buffer);
                }
            }

            if (xhr.sendAsBinary) {
                xhr.sendAsBinary(body);
            } else {
                xhr.send(body);
            }
        }
    },

    /*
     Generating a name for the file
     */
    title: function (id) {
        var d = new Date();
        return 'upload_' + id + '_' + d.getDate() + '-' + (d.getMonth() + 1) + '-' + d.getFullYear() + '_' + d.getHours().fix() + '-' + d.getMinutes().fix() + '-' + d.getSeconds().fix();
    },

    /*
     Parsing of response
     response{JSON} - the server response
     */
    parse: function (response) {
        var resp = $.parseJSON(response);

        if (resp.error) {
            this.error(resp.error);
            return;
        }

        var arr = resp.url.split('/'),
            name = arr[arr.length - 1],
            id = name.split('_')[1];

        var p = $("#LisShot-screen" + id);

        p.addClass("LisShot-uploaded");
        p.find("img").attr("src", resp.url);
        p.find("a").attr("href", resp.url);
        p.find(".LisShot-title").removeClass("LisShot-progress-bar-wrap").html(name);
        this.success(resp.url, id);
    },

    /*
     Calls the error
     e - error message
     cb - callback
     */
    error: function (e, cb) {
        var err_txt;
        if (e.toString() == '[object XMLHttpRequest]') {
            var url = e.responseURL,
                id = e.upload.photoId;

            switch (e.status) {
                case 404:
                    err_txt = this.lang.notFound;
                    break;
                case 403:
                    err_txt = this.lang.forbidden;
                    break;
            }

            err_txt = err_txt.replace('%url', url) + ' ' + this.lang.previewOnly;
            this.uploadUrl = false;

            $("#LisShot-screen" + id + " .LisShot-title").removeClass("LisShot-progress-bar-wrap").html(this.lang.screen + " №" + (id + 1));
        }
        else if (typeof e == "string") {
            err_txt = e;
        }
        else {
            err_txt = e.message;
        }

        $("body").prepend('<div class="LisShot-error"><a class="LisShot-error-close" onclick="LisShot.close();">' + this.lang.close + '</a><span class="LisShot-error-text">' + err_txt + '</span></div>');
        if ($.isFunction(cb)) {
            cb();
        }
    },
    close: function () {
        $(".LisShot-error").fadeOut(300);
    },

    /*
     filer.js - https://github.com/ebidel/filer.js/blob/master/src/filer.js#L137

     * Creates and returns a blob from a data URL (either base64 encoded or not).
     *
     * @param {string} dataURL The data URL to convert.
     * @return {Blob} A blob representing the array buffer data.
     */
    dataURLtoBlob: function (dataURL) {
        var BASE64_MARKER = ';base64,';
        if (dataURL.indexOf(BASE64_MARKER) == -1) {
            var parts = dataURL.split(',');
            var contentType = parts[0].split(':')[1];
            var raw = decodeURIComponent(parts[1]);

            return new Blob([raw], {type: contentType});
        }

        var parts = dataURL.split(BASE64_MARKER);
        var contentType = parts[0].split(':')[1];
        var raw = window.atob(parts[1]);
        var rawLength = raw.length;

        var uInt8Array = new Uint8Array(rawLength);

        for (var i = 0; i < rawLength; ++i) {
            uInt8Array[i] = raw.charCodeAt(i);
        }

        return new Blob([uInt8Array], {type: contentType});
    },
    id: 0,
    images: [],
    left: false
};

LisShot.language = {
    /*
     Language module
     Russian - is a default language
     */
    "ru": {
        screen: "Скриншот",
        close: "Закрыть",
        notFound: "Файл \"%url\" не найден.",
        forbidden: "Недостаточно прав для просмотра файла \"%url\".",
        previewOnly: "Доступен только предпросмотр скриншотов",
        html5Error: "Ваш браузер не поддерживает HTML5"
    },

    "en": {
        screen: "Screenshot",
        close: "Close",
        notFound: "The file \"%url\" is not found.",
        forbidden: "The file \"%url\" is forbidden",
        previewOnly: "You can see screenshot preview only",
        html5Error: "Your browser doesn't support HTML5"
    }
};

Number.prototype.fix = function () {
    return (this < 10) ? '0' + this : this;
};
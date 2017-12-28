var Changes = {};


; (function ($) {

    Changes.dialog = Dialog;


    function Dialog(options) {
        this.default = {
            title: "",
            message: "",
            color: "white",
            buttons: { "确定": function () { }, "取消": function () { } },
            showclose: true,
            close: function () { },
            delay: 0,
            locations: null,
            type: "default",
            animate: "show",
            model: false
        };
        this.options = $.extend(this.default, options);
        this.html = '';
        this.html += '<div class="dialog"><div class="content">';
        if (this.options.title) {
            this.html += '<h1>' + this.options.title + '</h1>';
        }
        if (this.options.showclose) {
            this.html += '<a href="javascript:;" class="close">×</a>';
        }
        if (this.options.message) {
            this.html += '<p>' + this.options.message + '</p>';
        }
        this.html += ' </div>';
        if (this.options.delay > 0) {
            this.html += '<div class="warn"><span class="warntime">' + this.options.delay + '</span>秒后关闭...</div>';
        }
        if (this.options.buttons) {
            this.html += '<div class="actions">';
            for (var a in this.options.buttons) {
                this.html += '<button class="btn_event">' + a + '</button>';
            }
            this.html += '</div>';
        }
        this.html += '</div>';

        //this.html = '<div class="dialog"><div class="content"><h1>Title</h1><a href="javascript:;" class="close">×</a><p>Just another dialog</p> </div><div class="actions"><button class="cancel">Cancel</button><button class="button">Ok</button> </div> </div>';
        this.self = $(this.html);
        return this;
    }

    Dialog.prototype.show = function () {
        var _this = this, _d = $(document);
        if (this.options.model) {
            if (!$(".model").length) {
                var model = '<div class="model"></div>';
                $(model).appendTo('body');
            }
        }

        if (!arguments.length || (arguments.length && arguments[0])) {
            $(".dialog").remove();
        }

        this.self.appendTo('body');

        this.event();
        var _left, _top;
        if (!_this.options.locations) {
            _left = (_d.width() - this.self.width()) / 2,
               _top = (_d.height() - this.self.height()) / 2;
        } else {
            _left = _this.self.locations.left;
            _top = _this.self.locations.top;
        }

        this.self.css({ top: _top, left: _left })[this.options.animate]();
        return this;
    }

    Dialog.prototype.event = function () {
        var _this = this;
        $(".close", this.self).click(function () {
            _this.close();
        });

        $(".btn_event", this.self).click(function () {
            _this.close();
            var handler = $(this).html();
            if (_this.options.buttons[handler])
                _this.options.buttons[handler]();

        });

        if (_this.options.delay > 0) {
            //关闭时间
            var time = $(".warntime", this.self);
            var timer = setInterval(function () {
                _this.options.delay--;
                time.html(_this.options.delay);
                if (_this.options.delay <= 0) {
                    _this.close();
                    clearInterval(timer);
                }
            }, 1000);
            //setTimeout(function () { _this.close(); }, _this.options.delay);
        }
    }

    Dialog.prototype.close = function () {
        this.self.remove();
        if (this.options.model) {
            $(".model").remove();
        }
        if (this.options.close) {
            this.options.close();
        }
    }




    function Tip(options) {
        this.default = {
            subject: "",
            message: "",
            color: "white",
            showclose: true,
            close: function () { },
            delay: 0,
            type: "default",
            animate: "show",
            showEvent: "hover"
        };
        this.options = $.extend(this.default, options);

        this.html = '';
        this.html += '<div class="tip">';
        this.html += '<div class="tips_arrow lt"><em>◆</em><span>◆</span></div>';
        this.html += '<div class="content">';
        this.options.subject = this.options.subject ? this.options.subject : this.options.self.attr("subject");
        this.options.message = this.options.message ? this.options.message : this.options.self.attr("message");
        if (this.options.title) {
            this.html += '<h1>' + this.options.title + '</h1>';
        }
        if (this.options.showclose) {
            this.html += '<a href="javascript:;" class="close">×</a>';
        }
        if (this.options.message) {
            this.html += '<p>' + this.options.message + '</p>';
        }
        this.html += '</div></div>';

        this.self = $(this.html);
        return this;
    }


    Tip.prototype.show = function () {
        var _this = this, _d = $(document);

        if (!arguments.length || (arguments.length && arguments[0])) {
            $(".tip").remove();
        }

        this.self.appendTo('body');

        //缺少一个位置的计算
        //……

        _this.event();
        return this;
    }

    Tip.prototype.event = function () {
        var _this = this;
        $(".close", this.self).click(function () {
            _this.close();
        });

        
    }

    Tip.prototype.close = function () {
        this.self.remove();

        if (this.options.close) {
            this.options.close();
        }
    }

    $.fn.Tip = function (options) {
        //options = $.extend({ self: this }, options);
        this.bind("mouseenter", function () {
            var _this = $(this);
            options = $.extend({ self: _this }, options);
             new Tip(options).show();
        });

        //new Tip({subject:"xxx",message:"sss"}).show();

        //this.mouseenter(function () {
        //    options = $.extend({ self: $(this) }, options);
        //    t = new Tip(options).show();
        //}).mouseleave(function () {
        //    t.close();
        //});

        return this;
    };
})(jQuery);
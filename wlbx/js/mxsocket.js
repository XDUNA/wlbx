function mxsocket(opts) {
    var defaultOpts = {
        domain: "",
        security: false,
        appid: "",
        title: '扫码登录',
        qrcode_id: 'qrcode',
        qrcode_width: 256,
        qrcode_height: 256,
        debug: false,
        url: "",
        open: null,
        close: null,
        error: null,
        qrcode: null
    };
    this.opts = Object.assign({}, defaultOpts, opts);
   
    this.qrcode = new QRCode(document.getElementById(this.opts.qrcode_id), {
        width: this.opts.qrcode_width,
        height: this.opts.qrcode_height
    });
    
    this.opts.url = (this.opts.security ? "wss" : "ws") + "://" + this.opts.domain + "/sock/qrcode?appid=" + this.opts.appid + '&title=' + this.opts.title,
    this.init();
}


mxsocket.prototype = {
    constructor: mxsocket,
    sock: null,
    qrcode: null,
    init: function () {
        this.sock = new WebSocket(this.opts.url);
        var self = this;
        this.sock.onopen = function (evt) {
            self.log("socket conneted");
            if (self.opts.open) {
                self.opts.open.call(self, evt);
            }
        };
        this.sock.onclose = function (evt) {
            self.log("socket disconnected");
            if (self.opts.close) {
                self.opts.close.call(self, evt);
            }
            self.socket = null;
        };
        this.sock.onerror = function (evt) {
            self.log("socket error:");
            self.log(evt);
            if (self.opts.error) {
                self.opts.error(evt, 2);
                self.opts.error.call(self, evt);
            }
            self.socket = null;
        };
        this.sock.onmessage = function (message) {
            self.log("message:" + message.data);
            var data = JSON.parse(message.data);
            var evt = data.event;
            if(evt == 'ticket'){
                return self.ticket.call(self, data.data);
            }
            else if (typeof self.opts[evt] == "undefined") {
                alert("未定义事件：" + evt);
                return;
            }
            self.opts[evt].call(self, data.data);
        }
    },

    callError: function (msg) {
        if (this.opts.error) {
            this.opts.error(msg, 1);
        } else {
            console.error(msg);
        }
    },
    ticket: function (rs) {
        //请求ticket成功后会触发该事件,无需复写
        this.qrcode.makeCode((this.opts.security ? "https" : "http") + "://" + this.opts.domain + "/wap/scan/index?ticket=" + rs.ticket);
        var self = this;

        if (rs.expire) {
            setTimeout(function () {
                self.send('ticket');
            }, rs.expire * 1000);
        }
    },
    send: function (data, error) {
        if (!this.sock) {
            console.error("socket closed");
            return false;
        }
        if (this.sock.readyState != 1) {
            console.error("connection is establishing or closed");
            return false;
        }
        return this.sock.send(data);
    },

    close: function () {
        if (this.sock) {
            this.sock.close();
        }
    },

    log: function (msg) {
        if (typeof this.opts.log == "function") {
            this.opts.log(msg);
        } else if (this.opts.debug) {
            console.log(msg);
        }
    }
}// JavaScript Document
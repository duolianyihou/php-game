var WebSoket = function() {
    this.ws = {};
    
    this.init = function() {
        //使用原生WebSocket
        if (window.WebSocket || window.MozWebSocket) {
            this.ws = new WebSocket(config.server);
            
        //使用flash websocket
        } else if (config.flash_websocket) {
            WEB_SOCKET_SWF_LOCATION = "./websoket/flash-websocket/WebSocketMain.swf";
            $.getScript("./websoket/flash-websocket/swfobject.js", function () {
                $.getScript("./websoket/flash-websocket/web_socket.js", function () {
                    this.ws = new WebSocket(config.server);
                });
            });
            
        //使用http xhr长轮循
        } else {
            this.ws = new Comet(config.server);
        }
    }
    
    this.run = function() {
        this.init();
        var soket = this;
        
        /**
         * 连接服务器
         * @param {type} e
         * @returns {undefined}
         */
        this.ws.onopen = function(e) {
            //连接成功
            console.log("与服务器建立连接成功.");

            // 绑定连接
            msg = new Object();
            msg.cmd = 'bindUser';
            msg.uid = uid;
            console.log(msg);
            soket.ws.send($.toJSON(msg));
        }
        
        /**
         * 接收消息
         * @param {type} e
         * @returns {undefined}
         */
        this.ws.onmessage = function(e) {
            console.log($.evalJSON(e.data));
            var message = $.evalJSON(e.data);
            var cmd = message.cmd;
            
            switch(cmd) {
                // 绑定用户
                case 'bindUser':
                    // 初始化在线列表
                    soket.ws.send($.toJSON({cmd : 'initUserList'}));
                    console.log('initUserList');
                    // 初始化房间列表
                    soket.ws.send($.toJSON({cmd : 'initRoomList'}));
                    console.log('initRoomList');
                    // 请求房间状态
                    soket.ws.send($.toJSON({cmd : 'getRoomInfo',roomid : roomid}));
                    console.log('getRoomInfo');
                    break;
                // 退出房间操作
                case 'closeRoom':
                    soket.closeRoom(message);
                    break;
                case 'getRoomInfo':
                    soket.getRoomInfo(message);
                    break;
            }
        }
        
        /**
         * 更新页面房间信息
         * @param {type} message
         * @returns {undefined}
         */
        this.getRoomInfo = function(message) {
            var room = message;
            var red_name = room.red_name == '' ? '等待中' : '等待中('+ room.red_name +')';
            var black_name = room.black_name == '' ? '等待中' : '等待中('+ room.black_name +')';
            
            if (room.owner_uid > 0 && room.guest_uid > 0) {
                $('#red_Info').attr('value', room.red_name);
                $('#black_info').attr('value', room.black_name);
                
                play.isPlay=true ;	
                com.get("chessRight").style.display = "none";
                com.get("moveInfo").style.display = "block";
                com.get("moveInfo").innerHTML="";
                play.depth = 4;
                play.init();
            } else {
                $('#red_Info').attr('value', red_name);
                $('#black_info').attr('value', black_name);
            }
        }
        
        /**
         * 退出房间操作
         * @param {type} message
         * @returns {undefined}
         */
        this.closeRoom = function(message) {
            alert(message.msg)
            
            var red_name = '准备中(' + message.red_name + ')';
            var black_name = message.guest_uid > 0 ? message.black_name : '等待加入';
            
            $('#red_Info').val(red_name);
            $('#black_info').val(black_name);
        }
        
        /**
         * 监控服务器连接关闭状态
         * @param {type} e
         * @returns {undefined}
         */
        this.ws.onclose = function (e) {
            
            $.post(online)
            $.post(deleteRoom)
            
            if (confirm("服务器连接失败,请重新登录!")) {
                console.log('服务器连接失败,请重新登录!');
//                location.href = louout;
            }
        }
        
        /**
         * 异常处理
         * @param {type} e
         * @returns {undefined}
         */
        this.ws.onerror = function (e) {
            console.log("异常:" + e.data);
        };
    };
    
    /**
     * 退出房间
     * @returns {undefined}
     */
    this.deleteRoom = function() {
        msg = new Object();
        msg.cmd = 'outRoom';
        msg.uid = uid;
        msg.username = username;
        console.log(msg);
        this.ws.send($.toJSON(msg));
    }
}

var client = new WebSoket();
client.run();


$(function(){
    $('#return').on('click', function() {
        if (confirm('确定要返回大厅吗?')) {
            client.deleteRoom();
            
            location.href = siteIndex;
        }
        return false;
    });
})
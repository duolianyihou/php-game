var WebSoket = function() {
    /**
     * websoket对象
     */
    this.ws = {};
    
    /**
     * 初始化
     * @returns {undefined}
     */
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
    
    /**
     * 运行
     * @returns {undefined}
     */
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

                    break;
                // 用户上线通知
                case 'userOnline':
                    soket.newInfo(message);
                    break;
                // 用户下线通知
                case 'offline':
                    soket.offline(message);
                    break;
                // 初始化用户列表
                case 'initUserList':
                    soket.initUserList(message);
                    
                    break;
                // 初始化房间列表
                case 'initRoomList':
                    soket.initRoomList(message);
                    break;
                // 广播消息
                case 'newInfo':
                    soket.newInfo(message);
                    break;
                case 'roomInfo':
                    soket.roomInfo(message);
                    break;
            }
        }
        
        /**
         * 监控服务器连接关闭状态
         * @param {type} e
         * @returns {undefined}
         */
        this.ws.onclose = function (e) {
            $.post(online, {'uid':uid})
            
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
        
        this.createRoom();
    };
    
    /**
     * 初始化用户列表
     * @param {type} message
     * @returns {undefined}     */
    this.initUserList = function(message) {
        var html = '';
        var online = message.online;
        
        for(var i = 0; i < online.length; i++) {
            var user = $.evalJSON(online[i].data);

            html += '<tr id="'+ user.uid +'">';
            html += '<td>'+ user.username +'</td>';
            html += '<td>'+ user.win +'</td>';
            html += '<td>'+ user.lose +'</td>';
            html += '</tr>';
        }

        $('#zaixian tbody').html(html);
        $('#online_num').html(online.length+ '人');
        $('#lobby_num').html(online.length+ '人');
    };
    
    /**
     * 初始化房间列表 
     * @param {type} message
     * @returns {undefined}     */
    this.initRoomList = function(message) {
        var html = '';
        var room = message.room;

        for(var i = 0; i < room.length; i++) {
            var list = room[i];

            html += '<tr id="'+ list.id +'">';
            html += '<td>'+ list.id +'</td>';
            html += '<td>'+ list.red_name +'</td>';
            html += '<td>'+ list.black_name +'</td>';
            html += '<td>'+ list.status_info +'</td>';
            
            if (list.status == 1) {
                html += '<td><a href="index.php?c=site&a=play&room_id='+ list.id +'"><span class="label label-info">加入游戏</span></a></td>';
            } else {
                html += '<td><a href="javascript:;"><span class="label label-info">观战</span></a></td>';
            }
            
            html += '</tr>';
        }
        $('#room').html(html);
    }
    
    /**
     * 下线通知 
     * @param {type} message
     * @returns {undefined}     */
    this.offline = function(message) {
        $('#'+ message.uid).remove();

        $('#system_danger').html(message.msg);
        $('#system_danger').fadeIn("slow");
        
        setTimeout(function(){
            $('#system_danger').fadeOut();
        }, 3000)
    }
    
    /**
     * 用户上线通知
     * @param {type} message
     * @returns {undefined}     */
    this.userOnline = function(message) {
        $('#system_success').html(message.msg);
        $('#system_success').fadeIn("slow");

        setTimeout(function(){
            $('#system_success').fadeOut();
        }, 3000)
    }
    
    /**
     * 广播消息
     * @param {type} message
     * @returns {undefined}     */
    this.newInfo = function(message) {
        $('#' + message.class).html(message.msg);
        $('#' + message.class).fadeIn("slow");
        
        setTimeout(function(){
            $('#' + message.class).fadeOut();
        }, 3000)
    }
    
    /**
     * 接收房间数据
     * @param {type} message
     * @returns {undefined}
     */
    this.roomInfo = function(message) {
        var url = roomUrl+ '&room_id='+ message.id;
        location.href = url;
    }
    
    /**
     * 创建房间
     * @returns {undefined}
     */
    this.createRoom = function() {
        var soket = this;
        $(function(){
            $('#createRoomAction').on('click', function() {
                msg = new Object();
                msg.cmd = 'createRoom';
                msg.uid = uid;
                msg.username = username;
                console.log(msg);
                soket.ws.send($.toJSON(msg));
            })
        })
        
    }
}

var client = new WebSoket();
client.run();
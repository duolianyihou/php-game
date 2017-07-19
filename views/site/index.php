<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"
              />
        <title>象棋对战平台-风居住的地方</title>
        <link href="static/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"
              />
        <!--[if lt IE 9]>
          <link rel="stylesheet" type="text/css" href="static/admin/plugins/jquery-ui/jquery.ui.1.10.2.ie.css"
          />
        <![endif]-->
        <link href="static/admin/assets/css/main.css" rel="stylesheet" type="text/css" />
        <link href="static/admin/assets/css/plugins.css" rel="stylesheet" type="text/css" />
        <link href="static/admin/assets/css/responsive.css" rel="stylesheet" type="text/css"
              />
        <link href="static/admin/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="static/admin/assets/css/fontawesome/font-awesome.min.css">
        <!--[if IE 7]>
          <link rel="stylesheet" href="static/admin/assets/css/fontawesome/font-awesome-ie7.min.css">
        <![endif]-->
        <!--[if IE 8]>
          <link href="static/admin/assets/css/ie8.css" rel="stylesheet" type="text/css" />
        <![endif]-->
        <script type="text/javascript" src="static/admin/assets/js/libs/jquery-1.10.2.min.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js">
        </script>
        <script type="text/javascript" src="static/admin/bootstrap/js/bootstrap.min.js">
        </script>
        <script type="text/javascript" src="static/admin/assets/js/libs/lodash.compat.min.js">
        </script>
        <!--[if lt IE 9]>
          <script src="static/admin/assets/js/libs/html5shiv.js">
          </script>
        <![endif]-->
        <script type="text/javascript" src="static/admin/plugins/touchpunch/jquery.ui.touch-punch.min.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/event.swipe/jquery.event.move.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/event.swipe/jquery.event.swipe.js">
        </script>
        <script type="text/javascript" src="static/admin/assets/js/libs/breakpoints.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/respond/respond.min.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/cookie/jquery.cookie.min.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/slimscroll/jquery.slimscroll.min.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/slimscroll/jquery.slimscroll.horizontal.min.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/sparkline/jquery.sparkline.min.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/daterangepicker/moment.min.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/daterangepicker/daterangepicker.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/blockui/jquery.blockUI.min.js">
        </script>
        <script type="text/javascript" src="static/admin/plugins/uniform/jquery.uniform.min.js">
        </script>
        <script type="text/javascript" src="static/admin/assets/js/app.js">
        </script>
        <script type="text/javascript" src="static/admin/assets/js/plugins.js">
        </script>
        <script type="text/javascript" src="static/admin/assets/js/plugins.form-components.js">
        </script>


        <script src="./websoket/jquery.json.js"></script>
        <script src="./websoket/console.js"></script>
        <script src="./config.js" charset="utf-8"></script>
        <script src="./websoket/comet.js" charset="utf-8"></script>
        <script src="./websoket/chat.js" charset="utf-8"></script>
        <script>
            $(document).ready(function () {
                App.init();
                Plugins.init();
                FormComponents.init()
            });

            var uid = <?= $_SESSION['user']['uid']; ?>;
            var username = '<?= $_SESSION['user']['username']; ?>';
            var online = '<?php echo $this->route(['api/unline']) ?>';
            var louout = '<?= $this->route(['user/logout'])?>';
            var roomUrl = '<?= $this->route(['site/room'])?>';
        </script>
        <script type="text/javascript" src="static/admin/assets/js/custom.js">
        </script>
    </head>
    <body class="breakpoint-1200" style="height: 367px;">
        <header class="header navbar navbar-fixed-top" role="banner">
            <div class="container">
                <ul class="nav navbar-nav">
                    <li class="nav-toggle">
                        <a href="javascript:void(0);" title="">
                            <i class="icon-reorder">
                            </i>
                        </a>
                    </li>
                </ul>
                <a class="navbar-brand" href="<?= $this->route(['site/index']); ?>">
                    <img src="static/admin/assets/img/logo.png" alt="logo">
                    <strong>
                        风居住的地方
                    </strong>
                </a>
                <ul class="nav navbar-nav navbar-left hidden-xs hidden-sm">
                    <li>
                        <a href="javascript:;" url="<?= $this->route(['site/room']); ?>" id="createRoomAction">
                            创建房间
                        </a>
                    </li>
                    <li>
                        <a href="<?= $this->route(['site/ai']); ?>">
                            人机对战
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-male">
                            </i>
                            <span class="username">
                                <?= $_SESSION['user']['username'] ?>
                            </span>
                            <i class="icon-caret-down small">
                            </i>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?= $this->route(['user/logout']); ?>">
                                    <i class="icon-key">
                                    </i>
                                    退出
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </header>
        <div id="container" class="fixed-header sidebar-closed">
            <div id="content">
                <div class="container">
                    <div class="crumbs">
                        <ul id="breadcrumbs" class="breadcrumb">
                            <li>
                                <i class="icon-home">
                                </i>
                                <a href="index.html">
                                    <font>
                                    <font>
                                    SWOOLE象棋对战平台
                                    </font>
                                    </font>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="page-header">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="page-title">
                                    <span>
                                        <h5>
                                            <?php echo tools::currentTime() . ',' . $_SESSION['user']['username'] . '!'; ?>
                                        </h5>
                                    </span>
                                    <span>
                                        <h6>
                                            欢迎访问象棋对战平台!
                                        </h6>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="page-title">
                                    <span id="system_danger"></span>
                                    <span id="system_success"></span>
                                    <span id="system_room"></span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <ul class="page-stats">
                                    <li>
                                        <div class="summary">
                                            <span>
                                                对战房间
                                            </span>
                                            <h5>
                                                1000
                                            </h5>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="summary">
                                            <span>
                                                在线数
                                            </span>
                                            <h5 id="online_num">
                                                0 人
                                            </h5>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="summary">
                                            <span>
                                                观战数
                                            </span>
                                            <h5>
                                                500 人
                                            </h5>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="summary">
                                            <span>
                                                大厅人数
                                            </span>
                                            <h5 id="lobby_num">
                                                0 人
                                            </h5>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>



                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="widget ">
                                <div class="widget-content">
                                    <table class="table table-hover" id="zaixian">
                                        <thead>
                                            <tr>
                                                <td>用户</td>
                                                <td>胜</td>
                                                <td>负</td>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7 pull-right">
                            <div class="widget">
                                <div class="widget-content">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <td>房间号</td>
                                                <td>红</td>
                                                <td>黑</td>
                                                <td>状态</td>
                                                <td>操作</td>
                                            </tr>
                                        </thead>
                                        <tbody id="room">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </body>

</html>
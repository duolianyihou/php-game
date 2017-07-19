<!DOCTYPE html>
<html lang="en">  
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"
    />
    <title>风居住的地方</title>
    <link href="static/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"
    />
    <link href="static/admin/assets/css/main.css" rel="stylesheet" type="text/css" />
    <link href="static/admin/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="static/admin/assets/css/responsive.css" rel="stylesheet" type="text/css"
    />
    <link href="static/admin/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="static/admin/assets/css/login.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="static/admin/assets/css/fontawesome/font-awesome.min.css">
    <!--[if IE 7]>
      <link rel="stylesheet" href="static/admin/assets/css/fontawesome/font-awesome-ie7.min.css">
    <![endif]-->
    <!--[if IE 8]>
      <link href="static/admin/assets/css/ie8.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <script type="text/javascript" src="static/admin/assets/js/libs/jquery-1.10.2.min.js">
    </script>
    <script type="text/javascript" src="static/admin/bootstrap/js/bootstrap.min.js">
    </script>
    <script type="text/javascript" src="static/admin/assets/js/libs/lodash.compat.min.js">
    </script>
    <!--[if lt IE 9]>
      <script src="static/admin/assets/js/libs/html5shiv.js">
      </script>
    <![endif]-->
    <script type="text/javascript" src="static/admin/plugins/uniform/jquery.uniform.min.js">
    </script>
    <script type="text/javascript" src="static/admin/plugins/validation/jquery.validate.min.js">
    </script>
    <script type="text/javascript" src="static/admin/plugins/nprogress/nprogress.js">
    </script>
    <script type="text/javascript" src="static/admin/assets/js/login.js">
    </script>
    <script>
      $(document).ready(function() {
        Login.init()
      });
    </script>
  </head>
  
  <body class="login" background="static/admin/bg/bg.jpg">

    <div class="logo">
      <img src="static/admin/assets/img/logo.png" alt="logo" />
      <strong>
      象棋对战
      </strong></div>
    <div class="box">
      <div class="content">
        <form class="form-vertical login-form" action="index.html" method="post">
          <input type="hidden" id="login_url" value="<?= $this->route(['user/login']); ?>"/>
          <input type="hidden" id="loginSuccessUrl" value="<?= $this->route(['site/index']); ?>"/>
          <input type="hidden" class="act" value="login"/>

          <h3 class="form-title">
            登录
          </h3>
          <div class="alert fade in alert-danger" style="display: none;">
           输入任何用户名密码进入.
          </div>
          <div class="form-group">
            <div class="input-icon">
              <i class="icon-user">
              </i>
              <input type="text" name="username" id="l_username" class="form-control" placeholder="用户名"
              autofocus="autofocus" data-rule-required="true" data-msg-required="请输入用户名."
              />
            </div>
          </div>
          <div class="form-group">
            <div class="input-icon">
              <i class="icon-lock">
              </i>
              <input type="password" name="password" id="l_password" class="form-control" placeholder="密码"
              data-rule-required="true" data-msg-required="请输入密码."
              />
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="submit btn btn-primary pull-right">
              登录
              <i class="icon-angle-right">
              </i>
            </button>
          </div>
        </form>
        <form class="form-vertical register-form" action="index.html" method="post"
        style="display: none;">
          <input type="hidden" id="reg_url" value="<?= $this->route(['user/reg']); ?>"/>
          <input type="hidden" id="regSuccessUrl" value="<?= $this->route(['site/index']); ?>"/>

          <h3 class="form-title">
            免费注册
          </h3>
          <div class="alert fade in alert-danger" style="display: none;">
           
          </div>
          <div class="form-group">
            <div class="input-icon">
              <i class="icon-user">
              </i>
              <input type="text" name="username" id="r_username" class="form-control" placeholder="用户名"
              autofocus="autofocus" data-rule-required="true" data-msg-required="请输入用户名."/>
            </div>
          </div>
          <div class="form-group">
            <div class="input-icon">
              <i class="icon-lock">
              </i>
              <input type="password" name="password" class="form-control" placeholder="密码"
              id="register_password" data-rule-required="true" data-msg-required="请输入密码."/>
            </div>
          </div>
          <div class="form-group">
            <div class="input-icon">
              <i class="icon-ok">
              </i>
              <input type="password" id="passwrod2" name="password_confirm" class="form-control" placeholder="确认密码"
              data-rule-required="true" data-rule-equalTo="#register_password" equalTo="密码不一致" data-msg-required="请输入确认密码."/>
            </div>
          </div>
          <div class="form-actions">
            <button type="button" class="back btn btn-default pull-left">
              <i class="icon-angle-left">
              </i>
              返回
              </i>
            </button>
            <button type="submit" class="submit btn btn-primary pull-right">
              注册
              <i class="icon-angle-right">
              </i>
            </button>
          </div>
        </form>
      </div>
      <div class="inner-box">
        <div class="content">
          <i class="icon-remove close hide-default">
          </i>
          <a href="#" class="sign-up">
            还没有账号吗？注册
          </a>
        </div>
      </div>
    </div>
  </body>

</html>
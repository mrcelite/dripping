<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="#" type="image/png">
    <title>{{$title}}</title>
    <link href="http://resources.inner.xiyibang.com/manager/css/style.css" rel="stylesheet">
    <link href="http://resources.inner.xiyibang.com/manager/css/style-responsive.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://resources.inner.xiyibang.com/manager/js/html5shiv.js"></script>
    <script src="http://resources.inner.xiyibang.com/manager/js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-body">
<div class="container">

    <form class="form-signin">
        <div class="form-signin-heading text-center">
            <h1 class="sign-title">Sign In</h1>
            <img src="http://resources.inner.xiyibang.com/manager/images/login-logo.png" alt=""/>
        </div>
        <div class="login-wrap">
            <input type="text" name="username" class="form-control js-username" placeholder="User ID" autofocus>
            <input type="password" name="password" class="form-control js-password" placeholder="Password">
            <input type="hidden" name="_token" class="js-token" value="{{$_token}}">
            <button class="btn btn-lg btn-login btn-block js-login" type="button">
                <i class="fa fa-check"></i>
            </button>

            <div class="registration">
                Not a member yet?
                <a class="" href="registration.html">
                    Signup
                </a>
            </div>
            <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
                <span class="pull-right">
                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>

                </span>
            </label>

        </div>

        <!-- Modal -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Forgot Password ?</h4>
                    </div>
                    <div class="modal-body">
                        <p>Enter your e-mail address below to reset your password.</p>
                        <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                        <button class="btn btn-primary" type="button">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal -->

    </form>

</div>
<script src="http://resources.inner.xiyibang.com/manager/js/jquery-1.10.2.min.js"></script>
<script src="http://resources.inner.xiyibang.com/manager/js/bootstrap.min.js"></script>
<script src="http://resources.inner.xiyibang.com/manager/js/modernizr.min.js"></script>
<script type="application/javascript">
$('.js-login').click(function () {
    var username = $('.js-username').val();
    var password = $('.js-password').val();
    if (username && password) {
        var params = {
            'username': username,
            'password': password,
            '_token':$('.js-token').val()
        };

        $.post('/manager/login/login', params, function (res) {

            if (res.code == 400) {
                alert('提交退出申请成功！我们会尽快为您处理。');
                return false;
            } else {
                window.location = '/manager/index';
            }
        });
    }
});
</script>
</body>
</html>
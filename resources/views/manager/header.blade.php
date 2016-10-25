<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="keywords" content="admin, dashboard, bootstrap, template, flat, modern, theme, responsive, fluid, retina, backend, html5, css, css3">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="#" type="image/png">
    <title>{{ $userId }}</title>
    <!--icheck-->
    <link href="{{ $resourcesHost }}manager/js/iCheck/skins/minimal/minimal.css" rel="stylesheet">
    <link href="{{ $resourcesHost }}manager/js/iCheck/skins/square/square.css" rel="stylesheet">
    <link href="{{ $resourcesHost }}manager/js/iCheck/skins/square/red.css" rel="stylesheet">
    <link href="{{ $resourcesHost }}manager/js/iCheck/skins/square/blue.css" rel="stylesheet">
    <!--dashboard calendar-->
    <link href="{{ $resourcesHost }}manager/css/clndr.css" rel="stylesheet">
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="{{ $resourcesHost }}manager/js/morris-chart/morris.css">
    <!--multi-select-->
    <link rel="stylesheet" type="text/css" href="{{ $resourcesHost }}manager/js/jquery-multi-select/css/multi-select.css" />
    <!--file upload-->
    <link rel="stylesheet" type="text/css" href="{{ $resourcesHost }}manager/css/bootstrap-fileupload.min.css" />
    <!--tags input-->
    <link rel="stylesheet" type="text/css" href="{{ $resourcesHost }}manager/js/jquery-tags-input/jquery.tagsinput.css" />
    <link rel="stylesheet" type="text/css" href="{{ $resourcesHost }}manager/js/ios-switch/switchery.css" />
    <!--common-->
    <link href="{{ $resourcesHost }}manager/css/style.css" rel="stylesheet">
    <link href="{{ $resourcesHost }}manager/css/style-responsive.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="{{ $resourcesHost }}manager/js/html5shiv.js"></script>
    <script src="{{ $resourcesHost }}manager/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="sticky-header">
<section>
    <div class="main-content" >

        <!-- header section start-->
        <div class="header-section">
            <!--notification menu start -->
            <div class="menu-right">
                <ul class="notification-menu">
                    <li>
                        <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ $resourcesHost }}manager/images/photos/user-avatar.png" alt="" />
                            John Doe
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                            <li><a href="#"><i class="fa fa-user"></i>个人信息</a></li>
                            <li><a href="#"><i class="fa fa-cog"></i>个性化设置</a></li>
                            <li><a href="#"><i class="fa fa-sign-out"></i>退出</a></li>
                        </ul>
                    </li>

                </ul>
            </div>

        </div>
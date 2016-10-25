<!-- left side start-->
<div class="left-side sticky-left-side">

    <!--logo and iconic logo start-->
    <div class="logo">
        <a href="index.html"><img src="http://resources.inner.xiyibang.com/manager/images/logo.png" alt=""></a>
    </div>

    <div class="logo-icon text-center">
        <a href="index.html"><img src="http://resources.inner.xiyibang.com/manager/images/logo_icon.png" alt=""></a>
    </div>
    <!--logo and iconic logo end-->

    <div class="left-side-inner">

        <!-- visible to small devices only -->
        <div class="visible-xs hidden-sm hidden-md hidden-lg">
            <div class="media logged-user">
                <img alt="" src="http://resources.inner.xiyibang.com/manager/images/photos/user-avatar.png" class="media-object">
                <div class="media-body">
                    <h4><a href="#">{{ $userId }}</a></h4>
                    <span>"Hello There..."</span>
                </div>
            </div>

            <h5 class="left-nav-title">Account Information</h5>
            <ul class="nav nav-pills nav-stacked custom-nav">
                <li><a href="#"><i class="fa fa-user"></i> <span>Profile</span></a></li>
                <li><a href="#"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
                <li><a href="#"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
            </ul>
        </div>

        <!--sidebar nav start-->
        <ul class="nav nav-pills nav-stacked custom-nav">
            @foreach( $userModule as $module )
                @if ($module['module_id'] == 1)
                    <li class="active"><a href="{{ $module['module_route'] }}"><i class="fa"></i> <span>{{ $module['module_name'] }}</span></a></li>
                @else
                    <li class="menu-list"><a href="{{ $module['module_route'] }}"><i class="fa"></i> <span>{{ $module['module_name'] }}</span></a>
                @endif
                @if (isset($module['module_lists']))
                    <ul class="sub-menu-list">
                    @foreach( $module['module_lists'] as $item )
                        <li><a href="{{ $item['module_route'] }}">{{ $item['module_name'] }}</a></li>
                    @endforeach
                    </ul>
                </li>
                @endif
            @endforeach
        </ul>
        <!--sidebar nav end-->

    </div>
</div>
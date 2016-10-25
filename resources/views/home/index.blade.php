<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>{{$title}}</title>
    <link rel='stylesheet' href="http://resources.inner.xiyibang.com/css/bootstrap.css" type='text/css' media='all'/>
    <link rel='stylesheet' href="http://resources.inner.xiyibang.com/css/all.css" type='text/css' media='all'/>
</head>
<body>
<div>
    @foreach( $cardLists as $item )
    <membership_card class="format-image group">
        <h2 class="post-title pad">
            <a href="/home/show/{{ $item->id }}"> {{ $item->card_number }}</a>
        </h2>
        <div class="post-inner">
            <div class="post-deco">
                <div class="hex hex-small">
                    <div class="hex-inner"><i class="fa"></i></div>
                    <div class="corner-1"></div>
                    <div class="corner-2"></div>
                </div>
            </div>
            <div class="post-content pad">
                <div class="entry custome">
                    姓名:{{ $item->contact_name }} &nbsp;&nbsp; 联系电话:{{ $item->phone_number }}
                </div>
            </div>
        </div>
    </membership_card>
    @endforeach
</div>
</body>
</html>
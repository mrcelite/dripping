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
    <membership_card class="format-image group">
        <h2 class="post-title pad">
             卡号:{{ $cardInfo->card_number }}
        </h2>
        <div class="post-inner">
            <div class="post-content pad">
                <div class="entry custome">
                    姓名:{{ $cardInfo->contact_name }} &nbsp;&nbsp; 联系电话:{{ $cardInfo->phone_number }}
                </div>
            </div>
            <div class="post-content pad">
                面值:{{ $cardInfo->card_credit }}<br>
                剩余额度:{{ $cardInfo->card_balance }}<br>
            </div>
        </div>
    </membership_card>
</div>
</body>
</html>
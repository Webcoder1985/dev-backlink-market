<h1>Hi {{ $params['user_fullname']}}, </h1>

Your Withdrawal Request has just been approved.<br>
<br>
We just send {{ $params['amount']}} to your PayPal account: {{ $params['paypal_email']}}<br>
<br>
You can download a .pdf receipt from the Members Area -> <a href="{{route('mywithdrawlist')}}">Withdrawals</a><br>
<br>
Regards
<br>
Your {{env('APP_NAME')}} Team

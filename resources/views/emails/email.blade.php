<!DOCTYPE html>
<html>
<head>
    <title>Email from TasksProject</title>
</head>
<body>

<p>Hello {{ $details[0]["name"]  }}</p>
<p>You have one or more tasks expired:</p>
<hr>
<ul>
    @foreach($details as $detail)
        <li style="margin-bottom: 10px;">Task {{ $detail["title"] }} which was {{ $detail["status"]  }} has expired on {{ $detail["expiration_date"] }}</li>
    @endforeach
</ul>

</body>
</html>

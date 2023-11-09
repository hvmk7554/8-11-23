<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body style="width: 50%; margin: 10px auto;border:1px solid grey;">
 <div style="height: 40px;background-color: grey; color:white">
   <h4 style="font-family: 'Times New Roman', Times, serif; font-size:17px;text-align:center; color: white">Email notice</h4>
    </div>
    <div style="padding:15px 12px">
        <h4 style="font-family: 'Times New Roman', Times, serif; font-size: 17px; text-align:center; color: black">User name: {{$mailData['name']}}</h4>
         <h4  style="font-family: 'Times New Roman', Times, serif; font-size: 17px; text-align:center; color: black">Email : {{$mailData['email']}}</h4>
        @if (isset($mailData['password']))
         <h4 style="font-family: 'Times New Roman', Times, serif; font-size: 17px; text-align:center; color: black">Mật khẩu: {{$mailData['password']}} </h4>
@endif
    </div>
</body>
</html>
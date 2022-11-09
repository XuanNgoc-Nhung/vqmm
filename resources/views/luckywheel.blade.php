<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" rel="stylesheet">
    <link rel="stylesheet" href="css/styleCustom.css">
    <link rel="stylesheet" href="./vongQuay/style/style.css">
    <title>Sinh nhật SOC - Ai cũng có quà</title>
</head>

<body>
<div>
    <div id="app"
         style="min-height: 100vh;background: url({{ asset('storage/background.png') }});
background-repeat: no-repeat;background-size: 100% 100%;">
        <trang-chu/>
    </div>
</div>
<div class="ipadEmBeCamHoa" style="display: none">
    <img style="max-width: 70%" src="/embe.png" alt="">
</div>
</body>
<script src="{{asset('js/trang-chu.js?t='.Carbon\Carbon::now()->timestamp)}}"></script>
</html>

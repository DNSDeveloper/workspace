<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error</title>
    <style>
        .img {
            display: flex;
            justify-content: center;
            align-content: center;
            align-items: center;
        }

        .btn {
            display: flex;
            justify-content: center;
            align-content: center;
            align-items: center;
        }

        button:hover {
            cursor: pointer;
        }

        button {
            background-color: aqua;
            border: none;
            font-size: 50px;
            width: 10%;
            padding-top: 20px;
            padding-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="img">
        <img src="{{ url('error/500.jpg') }}" width="40%" height="40%" alt="">
    </div>
    <div class="btn">
        <button id="button" onclick="history.back()">Kembali</button>
    </div>
</body>

</html>
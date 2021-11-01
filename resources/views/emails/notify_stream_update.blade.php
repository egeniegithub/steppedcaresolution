<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" , initial-scale="1.0">
    <title>Stream Update</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        #main-email {
            display: flex !important;
            width: 100%;
            flex-direction: column !important;
            align-items: center !important;
            align-content: center !important;
        }

        #email-a {
            width: 100%;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            align-content: center !important;
            max-width: 1140px;
            min-height: 100vh;

        }

        #email-content {
            width: 75%;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
        }

        #content-e {
            width: 100%;
            font-size: 16px;
            color: #212529;
            align-self: flex-start
        }
    </style>
</head>

<body style="margin: 0 !important; padding: 0 !important;">
<div id="main-email">
    <div id="email-a">
        <div id="email-content">
            <div id="content-e">
                <p style="margin : 10px 0px">
                <h2>Hello! {{$data['username']}}</h2><br>
                <p style="margin : 10px 0px">Admin Has updated the Form ({{$data['stream_name']}}) that you have been assigned</p>
                <p style="margin : 10px 0px">Regards,</p>
                <p style="margin : 10px 0px">SCS Team</p>
            </div>
            <br>
            <br>
        </div>
    </div>
</div>
<br>
<br>
<div
    style="box-sizing: border-box; background-color:#cccccc90; padding:5px; width:100%; margin-top:36px; display:flex; flex-direction:column; justify-content:center; align-items:center">
    <div style="width:75%; display:flex; justify-content:center; align-items:center; padding:10px; color: #212529;">
        © {{date("Y")}} | &nbsp; scs.egenienext.com
    </div>
</div>
</body>

</html>

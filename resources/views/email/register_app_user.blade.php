<!DOCTYPE html>
<html>
<head>
    <title>Registered User</title>
</head>
<body>
<section class="content">
    <div class="box-header with-border">
            <div class='box box-default'> <br>
               
                    <div class="row">
                    	<p> Dear , <span>{{$data['full_name']}}</span></p>
                        <p> Thank You For Signing Up to Kwiz App </p>
                        <p> Your Email Verification Code is <b>{{$data['otp_code']}}</b> </p>

                    </div>
                   
            </div>
    </div>

</section>
</body>
</html>
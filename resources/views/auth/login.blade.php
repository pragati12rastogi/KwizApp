@extends('layouts.app')

@section('js')

<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function(){
        $(".otp-send").click(function(){
            send_otp();
        })
    });
    function send_otp(){
        var send_on = $('#phone').val();
        if(send_on == ''){
            $("#send_to-err").text('Please Enter Phone Number').show();
            return false;
        }else{
            $("#send_to-err").text('').hide();
        }

        $.ajax({
            type: "get",
            url: '/send/otp/login',
            data: {
                phone: send_on
            },
            success: function (response){
                
                $("#alert-div").empty();
                if(response.status == 'success'){
                    var str = '<div class="alert alert-success alert-block">'+
                                    '<button type="button" class="close" data-dismiss="alert">×</button>'+ 
                                    '<strong>'+response.message+'</strong>'+
                                '</div>';
                    $('.otp-input').val('').show();
                    $('.send_btn').hide();
                    $("#alert-div").append(str);
                    $('#login_footer').show();
                }else{
                    var str = '<div class="alert alert-danger alert-block">'+
                                    '<button type="button" class="close" data-dismiss="alert">×</button>'+ 
                                    '<strong>'+response.message+'</strong>'+
                                '</div>';
                    $("#alert-div").append(str);
                    $('.otp-input').val('').show();
                }
            },
                error: function(error) {
                    var str = '<div class="alert alert-danger alert-block">'+
                                    '<button type="button" class="close" data-dismiss="alert">×</button>'+ 
                                    '<strong>'+error.responseJSON.message+'</strong>'+
                                '</div>';
                    $("#alert-div").append(str);
                    
                }
        })  
    }
</script>
@endsection
@section('content')
    <div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-5">
            <img src="/dist/img/login.jpg" alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">
            <div class="card-body">
              <div class="brand-wrapper">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    <img src="/dist/img/kwizz_app_logo_5.png" alt="KwizzApp Logo" class="">
                </a>
              </div>
              <p class="login-card-description">Login your account</p>
              <div class="col-md-12 p-0" id="alert-div">
                  @include('flash-message')
              </div>
              <form method="POST" action="{{ route('login') }}">
                        @csrf

                  <div class=" row">
                      <label for="phone" class="col-md-6 col-form-label ">{{ __('Phone Number') }}</label>

                      <div class="col-md-12">
                          <input id="phone" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="10" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                          <span class="invalid-feedback" role="alert" id="send_to-err"></span>
                          @error('phone')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                  </div>
                  <div class=" row otp-input " style="display: none">
                            <label for="otp" class="col-md-6 col-form-label ">{{ __('OTP') }}</label>

                            <div class="col-md-12">
                                <input id="otp" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="6" class="form-control @error('otp') is-invalid @enderror" name="otp" required autocomplete="current-otp">

                                @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class=" row mb-0 send_btn">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-block login-btn otp-send" >
                                    {{ __('Send OTP') }}
                                </button>
                            </div>
                        </div>
                  </div>
                  <div class="card-footer" id="login_footer" style="display: none;background-color: rgb(105 103 103 / 15%)">
                        <div class=" row mb-0">
                            <div class="col-md-4">
                                <a href="#" class="" onclick="send_otp()">Resend Code</a>
                            </div>
                            <div class="col-md-3 offset-md-5">
                                <button type="submit" class="btn btn-block login-btn">Login</button>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div>
          </div>
        </div>
      </div>
      
    </div>
@endsection

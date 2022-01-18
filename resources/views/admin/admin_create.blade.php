@extends('layouts.main')
@section('title', 'Add App User')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/admin/user/list">Manage Admin Users</a></li>
<li class="breadcrumb-item active">Add Admin User</li>
@endsection

@section('css')
<style type="text/css">
    label{
      font-weight: 500!important;
    }
    sup{
      color: red;
    }
    .capital{
      text-transform: capitalize;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }

</style>
@endsection
@section('js')
<script>
  $(function() {
    
    $.validator.addMethod("notValidIfSelectFirst", function(value, element, arg) {
        return arg !== value;
    }, "This field is required.");

    $('#user_create_form').validate({ // initialize the plugin
        rules: {

            name: {
                required: true,
                maxlength:200
            },
            email: {
                required: true
            },
            phone: {
                required: true
            },
            // password: {
            //     required: true
            // },
            // confirm_pass: {
            //     required: true
            // },
            role: {
                required: true
            }
        }
    });

});
</script>
@endsection
@section('content')
<div class="container-fluid">
    @include('flash-message')
</div>
<div class="container-fluid">
    
    <div class="card">
        <div class="card-header">
           <div class="row">
                <div class="col-md-12">
                    <div class="col-md-2 card-head-left">
                      <h5 class="m-0">Add Admin User</h5>
                    </div>
                </div>
            </div>
        </div>  
        <form  action="{{url('/admin/user/create')}}" enctype="multipart/form-data" method="POST" id="user_create_form" files="true">
            
        <div class="card-body">
               @csrf
                <div class="row">

                    <div class="col-md-6">
                        <label>{{__('Name')}} <sup>*</sup></label>
                        <input type="text" class="capital form-control input-css @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" autocomplete="off">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    <div class="col-md-6 ">
                        <label>{{__('Email')}} <sup>*</sup></label>
                        <input autocomplete="off" type="email" id="email" class="input-css form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                </div><br>
                <!-- <div class="row">
                    <div class="col-md-6 ">
                        <label>{{__('Password')}} <sup>*</sup></label>
                        <input autocomplete="off" type="password" class="input-css form-control @error('password') is-invalid @enderror" name="password" id='password' value="{{ old('password') }}">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6 ">
                        <label>{{__('Confirm Password')}} <sup>*</sup></label>
                        <input autocomplete="off" type="password" id="confirm_pass" class="input-css form-control @error('confirm_pass') is-invalid @enderror" name="confirm_pass" value="{{ old('confirm_pass') }}">
                        @error('confirm_pass')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                </div><br> -->
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label for="role">{{__('Role')}} <sup>*</sup></label><br>
                            <select class="select2bs4 form-control @error('role') is-invalid @enderror" id="role" name="role" >
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{$role['role_id']}}" {{ (old('role')== $role['role_id'])?'selected':''}}>{{$role['role_name']}}</option>
                                @endforeach
                            </select>
                        </div> 
                        @error('role')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-6 ">
                        <label>{{__('Phone')}} <sup>*</sup></label>
                        <input autocomplete="off" type="number" id="phone" class="input-css form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                </div><br>
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label for="profile_picture">{{__('Profile Picture')}}</label><br>
                            <input type="file" class="input-css @error('profile_picture') is-invalid @enderror" id="profile_picture"  name="profile_picture" accept="image/*">
                        </div> 
                        @error('profile_picture')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                </div>
        </div> 
        <div class="card-footer">
            <div class="form-group row mb-0">
                <div class="col-md-1 offset-md-11">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>  
        </form>
        
    </div>
</div>
@endsection

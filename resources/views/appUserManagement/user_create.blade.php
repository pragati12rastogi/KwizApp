@extends('layouts.main')
@section('title', 'Add App User')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/app/user/management">Manage App Users</a></li>
<li class="breadcrumb-item active">Add App User</li>
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
    
    $('#user_create_form').validate({ // initialize the plugin
        rules: {

            full_name: {
                required: true,
                maxlength:200
            },
            dob: {
                required: true
            },
            email: {
                required: '#phone:blank'
            },
            phone: {
                required: '#email:blank'
            },
            status:{
                required:true
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
                      <h5 class="m-0">Add App User</h5>
                    </div>
                </div>
            </div>
        </div>  
        <form  action="{{url('/app/user/management/create')}}" enctype="multipart/form-data" method="POST" id="user_create_form" files="true">
            
        <div class="card-body">
               @csrf
                <div class="row">

                    <div class="col-md-6">
                        <label>{{__('Name')}} <sup>*</sup></label>
                        <input type="text" class="capital form-control input-css @error('full_name') is-invalid @enderror" name="full_name"
                            value="{{ old('full_name') }}" autocomplete="off">
                            @error('full_name')
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
                 <div class="row">
                    <div class="col-md-6 ">
                        <label>{{__('Date Of Birth')}} <sup>*</sup></label>
                        <input autocomplete="off" type="text" class="datepicker input-css form-control @error('dob') is-invalid @enderror" data-toggle="datetimepicker" name="dob" id='dob' value="{{ old('dob') }}">
                        @error('dob')
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
                            <label for="role">{{__('Status')}} <sup>*</sup></label><br>
                            <select class="select2bs4 form-control @error('status') is-invalid @enderror" id="status" name="status" >
                                <option value="">Select Status</option>
                                @foreach($status as $st)
                                    <option value="{{$st['id']}}" {{ (old('status')== $st['id'])?'selected':''}}>{{$st['status']}}</option>
                                @endforeach
                                
                            </select>
                        </div> 
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
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
                </div><br>
                
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

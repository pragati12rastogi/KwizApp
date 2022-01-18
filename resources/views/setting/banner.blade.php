@extends('layouts.main')
@section('title', 'Banner & PopUp')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Banner & PopUp</li>
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
    
    $('#banner_form').validate({ // initialize the plugin
        rules: {

            display: {
                required: true
            }
            
        }
    });
    $('#popup_form').validate({
        rules: {

            display: {
                required: true
            }
            
        }  
    })
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
                
                <div class="col-md-8">
                  <h5 class="m-0">Banner Setting</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/setting/banner')}}" enctype="multipart/form-data" method="POST" id="banner_form" files="true">
            
        <div class="card-body">
               @csrf
               
               <div class="row">

                    <div class="col-md-3" style="color: indianred">
                        <label><b>Updated By :</b></label>
                        <span>{{isset($banner['updated_by_name'])? $banner['updated_by_name'] : '' }}</span>
                    </div>
                    <div class="col-md-3" style="color: indianred">
                        <label><b>Last Updated By :</b></label>
                        <span>{{isset($banner['last_updated_at'])? date('d-m-Y',strtotime($banner['last_updated_at'])) : '' }}</span>
                    </div>
                    
               </div><br>

                <div class="row">
                    <input type="hidden" name="banner_id" value="{{isset($banner['id'])? $banner['id'] : '' }}">
                    <div class="col-md-6 col-sm-12">
                        <label>{{__('Select Display')}}<sup>*</sup></label><br>
                        <select class="select2bs4 form-control" name="display">
                            <option value="">Select</option>
                            <option value="0" {{isset($banner['display'])?(($banner['display']==0)?'selected':''):''}}>No</option>
                            <option value="1" {{isset($banner['display'])?(($banner['display']==1)?'selected':''):''}}>Yes</option>
                        </select>
                        @error('display')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label for="">{{__('Select Banner')}} </label>
                        <br>
                        @if(isset($banner['banner_img']))
                            @if($banner['banner_img'] != "" || $banner['banner_img'] != null)
                                @if (file_exists(public_path().'/upload/admin_profile/'.$banner['banner_img'] ))
                                    <img src="{{asset('/upload/admin_profile/')}}/{{$banner['banner_img']}}" height="50" width="100"><br><br>
                                @endif
                            @endif
                        @endif
                        
                        <input type="file" accept="image/*" name="upd_banner"  id="" class="upd_banner">
                        {!! $errors->first('upd_user_photo', '<p class="help-block">:message</p>') !!} 
                    </div>
                    <input type="text" name="old_banner" value="{{isset($banner['banner_img'])?$banner['banner_img']:''}}" hidden>
                    
                </div><br>
                
            </div>
            <div class="card-footer">
                <div class="form-group row mb-0">
                    <div class="col-md-1 offset-md-11">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-thumbs-up"></i> Save</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
    <div class="card">
        <div class="card-header">
           <div class="row">
                
                <div class="col-md-8">
                  <h5 class="m-0">Pop-Up Setting</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/setting/popup')}}" enctype="multipart/form-data" method="POST" id="popup_form" files="true">
            
        <div class="card-body">
               @csrf
               
               <div class="row">

                    <div class="col-md-3" style="color: indianred">
                        <label><b>Updated By :</b></label>
                        <span>{{isset($popup['updated_by_name'])? $popup['updated_by_name'] : '' }}</span>
                    </div>
                    <div class="col-md-3" style="color: indianred">
                        <label><b>Last Updated By :</b></label>
                        <span>{{isset($popup['last_updated_at'])? date('d-m-Y',strtotime($popup['last_updated_at'])) : '' }}</span>
                    </div>
                    
               </div><br>

                <div class="row">
                    <input type="hidden" name="popup_id" value="{{isset($popup['id'])? $popup['id'] : '' }}">
                    <div class="col-md-6 col-sm-12">
                        <label>{{__('Select Display')}}<sup>*</sup></label><br>
                        <select class="select2bs4 form-control" name="display">
                            <option value="">Select</option>
                            <option value="0" {{isset($popup['display'])?(($popup['display']==0)?'selected':''):''}}>No</option>
                            <option value="1" {{isset($popup['display'])?(($popup['display']==1)?'selected':''):''}}>Yes</option>
                        </select>
                        @error('display')
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
                        <button type="submit" class="btn btn-primary"><i class="fa fa-thumbs-up"></i> Save</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
</div>
@endsection

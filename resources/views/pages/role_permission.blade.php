@extends('layouts.main')
@section('title', 'Assign Role Permission')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Assign Role Permission</a></li>
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
    .card .card-body span {
    	font-size: 13px;
	}
    ul{
        list-style: none;
    }

</style>

@endsection
@section('js')

<script>
    $(function() {
        
        mark_checkbox();
        
    });


    function mark_checkbox(){
        var role_val = $('#role').val();
        $.ajax({
            dataType: "json",
            url: "/admin/get/section/list",
            type: "GET",
            data:{"role":role_val},
            success: function(result)
            {
                $('.treeview').empty();
                var str ='';
                $.each(result,function(index,value){
                    var checkbox ='';
                    if(value.role_id != null){
                        checkbox = 'checked';
                    }
                    var sub = '<ul >';
                    $.each(value.children,function(ind,val){
                        sub = sub+'<li><input type="checkbox" class="mr-1" name="menu[]" value="'+val.id+'" '+checkbox+'>'+
                        '<label class="1custom-unchecked " >'+val.name+'</label>';
                    });
                    sub =sub+'</ul>';
                    str = str+'<li><input type="checkbox" class="mr-1" name="menu[]" value="'+value.id+'" '+checkbox+'>'+
                        '<label class="1custom-unchecked " >'+value.name+'</label>'+sub;
                });

                $('.treeview').append(str);
                $('input[type="checkbox"]').change(checkboxChanged);
            }
        });

    }



    function checkboxChanged() {debugger
        var $this = $(this),
            checked = $this.prop("checked"),
            container = $this.parent(),
            siblings = container.siblings();
        container.find('input[type="checkbox"]')
        .prop({
            indeterminate: false,
            checked: checked
        })
        .siblings('label')
        .removeClass('custom-checked custom-unchecked custom-indeterminate')
        .addClass(checked ? 'custom-checked' : 'custom-unchecked');

        checkSiblings(container, checked);
    }

    function checkSiblings($el, checked) {
        var parent = $el.parent().parent(),
            all = true,
            indeterminate = false;

        $el.siblings().each(function() {
          return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
        });

        if (all && checked) {
          parent.children('input[type="checkbox"]')
          .prop({
              indeterminate: false,
              checked: checked
          })
          .siblings('label')
          .removeClass('custom-checked custom-unchecked custom-indeterminate')
          .addClass(checked ? 'custom-checked' : 'custom-unchecked');

          checkSiblings(parent, checked);
        } 
        else if (all && !checked) {
          indeterminate = parent.find('input[type="checkbox"]:checked').length > 0;

          parent.children('input[type="checkbox"]')
          .prop("checked", checked)
          .prop("indeterminate", indeterminate)
          .siblings('label')
          .removeClass('custom-checked custom-unchecked custom-indeterminate')
          .addClass(indeterminate ? 'custom-indeterminate' : (checked ? 'custom-checked' : 'custom-unchecked'));

          checkSiblings(parent, checked);
        } 
        else {
          $el.parents("li").children('input[type="checkbox"]')
          .prop({
              indeterminate: true,
              checked: true
          })
          .siblings('label')
          .removeClass('custom-checked custom-unchecked custom-indeterminate')
          .addClass('custom-indeterminate');
        }
    }
</script>
@endsection
@section('content')
<div class="container-fluid ">
    @include('flash-message')
    <div class="card">
        <div class="card-header">
           <div class="row">
                <div class="col-md-12">
                    
                      <h5 class="m-0">Assign Role Permission</h5>
                    
                </div>
            </div>
        </div>
        <form  action="{{url('/admin/role/management')}}" enctype="multipart/form-data" method="POST" id="blog_form" files="true">
            
	        <div class="card-body">
	               @csrf
	                <div class="row">

	                    <div class="col-md-6">
	                        <div class="form-group">
                                <label for="status">{{__('Select Role')}} <sup>*</sup></label><br>
                                <select class="select2bs4 form-control @error('role') is-invalid @enderror" id="role" name="role" onchange="mark_checkbox()">
                                    @foreach($role as $rol)
                                        <option value="{{$rol['role_id']}}" {{ (isset($selected)?$selected['role_id']== $rol['role_id']:0)?'selected':''}}>{{$rol['role_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
	                            @error('role')
	                                <span class="invalid-feedback" role="alert">
	                                    <strong>{{ $message }}</strong>
	                                </span>
	                            @enderror
	                    </div>
	                   
	                </div><br>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="admin treeview list-unstyled">
                                
                            </ul>
                        </div>
                    </div>
	                
	        </div> 
	        <div class="card-footer">
	            <div class="form-group row mb-0">
	                <div class="col-md-1 offset-md-11">
	                    <button type="submit" class="btn btn-dark">Submit</button>
	                </div>
	            </div>
	        </div>  
        </form>
        
    </div><br><br>
</div>
@endsection

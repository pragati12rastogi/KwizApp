@extends('layouts.main')
@section('title', 'Contest View')

@section('user',Auth::user()->name)

@section('breadcrumb')

<li class="breadcrumb-item"><a href="/contest/summary">Manage Contest</a></li>
<li class="breadcrumb-item active">Contest View</li>
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
    .ans_option{
        padding: 5px;
        background: lightgreen;
        border: 1px solid #dad7d7;
        border-radius: 20px;
    }
</style>

@endsection
@section('js')

<script>
    
</script>
@endsection
@section('content')
<div class="container-fluid">
    @include('flash-message')
</div>
<div class="container-fluid">
    
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Contest View</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
        </div>
        <div class="card-body">
                <div class="text-center">
                    
                    @if($master['contest_icon'] != "" || $master['contest_icon'] != null)
                        @if (file_exists(public_path().'/upload/quiz_cat_icon/'.$master['contest_icon'] ))
                            <img src="{{asset('/upload/quiz_cat_icon/')}}/{{$master['contest_icon']}}" height="50" width="100">
                        @endif
                    @endif
                    <br>
                    <label for=""><b>{{__('Contest Icon')}}</b></label>                    
                </div>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-active table-bordered">
                            <tbody>
                                <tr>
                                    <td>
                                        <label for="contest_name"><b>{{__('Contest Name')}} : </b></label><span><i> {{$master['contest_name']}}</i></span>
                                    </td>
                                    <td>
                                        <label for="join_user"><b>{{__('Total User Can Join')}} : </b></label>
                                        <span><i> {{$master['user_can_join']}}</i></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="contest_name"><b>{{__('Start Time')}} : </b></label>
                                        <span><i>{{date('d-m-Y h:i A',strtotime($master['start_time']))}}</i></span>
                                    </td>
                                    <td>
                                        <label for="end_time"><b>{{__('End Time')}} : </b></label>
                                        <span><i>{{date('d-m-Y h:i A',strtotime($master['end_time']))}}</i></span>

                                    </td>
                                </tr> 
                                <tr>
                                    <td>
                                        <label for="end_time"><b>{{__('Contest Fee')}} : </b></label>
                                        <span><i>{{$master['contest_fee']}}</i></span>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        <div class="card-footer">
            <div class="form-group row mb-0">
                
            </div>
        </div>
    
        
    </div>

    @if(count($reward)>0)
    <div class="card card-default collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Contest Reward Distribution</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
            </div>
            <div class="card-body">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                  @foreach($reward as $r)
                                    <tr>
                                        <td>
                                            <label for="contest_name"><b>Winner No. {{$r['position']}} : </b></label>
                                        </td>
                                        <td>
                                            <span><i>{{$r['position_amount']}}</i> Cash</span>
                                        </td>
                                    </tr>
                                  @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
            <div class="card-footer">
                <div class="form-group row mb-0">
                    
                </div>
            </div>
        </div>
    @endif          

    @if(count($question)>0)
        <div class="card card-default collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Contest Questions</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
            </div>
            <div class="card-body">
                
                @foreach($question as $i => $q)

                  @php $y =$i+1; 
                  @endphp
                    <div class="row div_tocopy ques_count">
                        <div class="col-md-12">
                            <label class=""><b>{{__('Quiz Question')}} {{$y}}</b></label>
                            <p>{{$q['question']}}</p>
                            <br>
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <label for="cat_icon" class="{{($q['answer']=='option1')?'ans_option':''}}"><b>{{__('Option 1')}} : </b></label>
                                <span>{{$q['option1']}}</span>
                            </div> 
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <label for="cat_option2" class="{{($q['answer']=='option2')?'ans_option':''}}"><b>{{__('Option 2')}} : </b></label>
                                <span>{{$q['option2']}}</span>
                            </div>            
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <label for="cat_option3" class="{{($q['answer']=='option3')?'ans_option':''}}"><b>{{__('Option 3')}} : </b></label>
                                <span>{{$q['option3']}}</span>
                            </div> 
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <label for="cat_option4" class="{{($q['answer']=='option4')?'ans_option':''}}"><b>{{__('Option 4')}} : </b></label>
                                <span>{{$q['option4']}}</span>
                            </div> 
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <label for="" ><b>{{__('Question Points')}} : </b></label>
                                <span>{{$q['question_point']}}</span>
                            </div> 
                        </div>
                        <div class="col-md-3 ">
                            <div class="form-group">
                                <label for="" ><b>{{__('Question Time')}} : </b></label>
                                <span>{{$q['question_time']}} min</span>
                            </div> 
                        </div>
                        
                    </div>
                    <hr>
                @endforeach
                    
            </div>
            <div class="card-footer">
                <div class="form-group row mb-0">
                    
                </div>
            </div>
        </div>
    @endif
    <br>
</div>
@endsection

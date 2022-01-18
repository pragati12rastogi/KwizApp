@extends('layouts.main')
@section('title', 'Quiz Creation View')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/quiz/category/question/list">Manage Quiz Creation</a></li>
<li class="breadcrumb-item active">Quiz Creation View</li>
@endsection
@section('css')
  <style>
    .card-btn-right{
      float: right;
    }
    .card-btn-div{
      display: inline;
    }
    .card-head-left{
      float: left;
      padding-top: 5px;
    }
    .ans_option {
        padding: 5px;
        background: lightgreen;
        border: 1px solid #dad7d7;
        border-radius: 20px;
    }
  </style>
@endsection
@section('js')

<script>
    
    $(function () {
        
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
                
                    <div class="col-md-11">
                      <h5>Quiz Creation View</h5>
                    </div>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
              <div class="col-12 table-responsive">
                <table class="table table-striped">
                  <tbody>
                      <tr>
                        <td><lable>Category Name : </lable><span>{{$quiz[0]['quiz_category_name']}}</span></td>
                        <td><lable>Quiz Title : </lable><span>{{$quiz[0]['quiz_title']}}</span></td>
                        <td><lable>Quiz Time : </lable><span>{{$quiz[0]['quiz_time']}}</span></td>
                        
                      </tr> 
                  </tbody>
                </table>
              </div>

            </div>
            @foreach($quiz as $i => $q)

              @php $y =$i+1; 
              @endphp
              <div class="row div_tocopy ques_count">
                      <div class="col-md-12">
                          <label>{{__('Quiz Question')}} {{$y}} :</label>
                          <span><i>{{$q['question']}}</i></span>
                              
                      </div>
                      <div class="col-md-3 ">
                          <div class="form-group" >
                              <label for="cat_icon">{{__('Option 1')}} :</label>
                              <span class="{{($q['answer']=='option1')?'ans_option':''}}">{{$q['option1']}}</span>
                          </div> 
                                      
                      </div>
                      <div class="col-md-3 ">
                          <div class="form-group">
                              <label for="cat_icon">{{__('Option 2')}} :</label>
                              <span class="{{($q['answer']=='option2')?'ans_option':''}}">{{$q['option2']}}</span>
                          </div> 
                                      
                      </div>
                      <div class="col-md-3 ">
                          <div class="form-group">
                              <label for="cat_icon">{{__('Option 3')}} :</label>
                              <span class="{{($q['answer']=='option3')?'ans_option':''}}">{{$q['option3']}}</span>
                          </div> 
                                      
                      </div>
                      <div class="col-md-3 ">
                          <div class="form-group">
                              <label for="cat_icon">{{__('Option 4')}} :</label>
                              <span class="{{($q['answer']=='option4')?'ans_option':''}}">{{$q['option4']}}</span>
                          </div> 
                                      
                      </div>

                      <div class="col-md-3 ">
                          <div class="form-group">
                              <label for="cat_icon">{{__('Question Coin')}} :</label>
                              <span class="">{{$q['question_point']}} Coins</span>
                          </div>                 
                      </div> 

                      <div class="col-md-3 ">
                          <div class="form-group">
                              <label for="cat_icon">{{__('Question Time')}} :</label>
                              <span class="">{{$q['question_time']}} min</span>
                          </div>                 
                      </div>   
              </div>
              <hr>
            @endforeach
        </div>
    </div>
    <!-- @if(count($reward)>0) -->
      <!-- <div class="card card-default collapsed-card">
              <div class="card-header">
                  <h3 class="card-title">Quiz Reward Distribution</h3>

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
                                              <span><i>{{$r['position_amount']}}</i> Coins</span>
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
          </div> -->
    <!-- @endif -->
    <br>
</div>
@endsection

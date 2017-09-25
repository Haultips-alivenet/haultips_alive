@extends('layouts.user-dashboard-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')
<?php //print_r($quesdetails); ?>
            <div class="col-md-8">
                 
           <div class="_dash_rft">
    <div class="tab-content panels-faq">
      <div class="tab-pane active" id="tab1">
                           @if(Session::has('success'))
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{Session::get('success')}}
                            </div>
                        @endif   
        <div class="panel-group" id="help-accordion-1">
            <?php $i=1; ?>
         @foreach($quesdetails as $detail)   
          <div class="panel panel-default panel-help">
            <a href="#opret-produkt{{$i}}" data-toggle="collapse" data-parent="#help-accordion-1" onclick="getquestionAns({{$i}},{{$detail->shippingId}},{{$detail->carrier_id}});">
              <div class="panel-heading">
                <h2>{{App\ShippingDetail::getDeliveryName($detail->table_name,$detail->shippingId).' - '.$detail['first_name'].' '.$detail['last_name']}}</h2>
              </div>
            </a>
            <div id="opret-produkt{{$i}}" class="collapse">
              <div class="panel-body" id="allquesans{{$i}}">
              
              </div>
                
               
                
            </div>
              
          </div>
         <?php $i++; ?>
         @endforeach
        </div>
      </div>
          {!! Form::open(array('url'=>'user/faq/answer','class'=>'form-horizontal','id'=>'userfaq_form')) !!}
          <input type="hidden" name="question_master_id" id="question_master_id">
          <input type="hidden" name="question_id" id="question_id">
          <input type="hidden" name="answer" id="answer">
          
          {!! Form::close() !!}
      <div class="tab-pane" id="tab2">
        <div class="panel-group" id="help-accordion-2">      
          <div class="panel panel-default panel-help">
            <a href="#help-three" data-toggle="collapse" data-parent="#help-accordion-2">
              <div class="panel-heading">
                <h2>Lorem ipsum?</h2>
              </div>
            </a>
            <div id="help-three" class="collapse in">
              <div class="panel-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloribus nesciunt ut officiis accusantium quisquam minima praesentium, beatae fugit illo nobis fugiat adipisci quia distinctio repellat culpa saepe, optio aperiam est!</p>
                <p><strong>Example: </strong>Facere, id excepturi iusto aliquid beatae delectus nemo enim, ad saepe nam et.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>    
  </div>
  </div>
@endsection
 @yield('script')
 
<script>
    
  
    function getquestionAns(id,sid,cid){
       // $("#opret-produkt"+id).toggle();
      // alert(sid);
        $.ajax({ 
        type: 'get',
        url: '{{url('getquesAns')}}',
        data: 'id='+sid+'&cid='+cid,
        //dataType: 'json',
        //cache: false,

        success: function(data) {
      //alert(data);
         //$("."+ id).toggle();
         $('#allquesans'+id).html(data);
       
       //$("#opret-produkt"+id).toggle();
        }

   });
    }
    function getreply(id){
        
        $('._question_tb_0'+id).toggle();
    }
    
    function reply_ans(id){
       var ids=id.split("_");
       var question_master_id = ids[0];
       var question_id = ids[1];
       var ans = $('#answerof'+question_id).val();
       if(ans) {
      $('#question_master_id').val(question_master_id);
      $('#question_id').val(question_id);
      $('#answer').val(ans);
      document.getElementById("userfaq_form").submit();
      } else {
          alert("Please Enter Answer!");
          return false;
      }
    }
    </script>
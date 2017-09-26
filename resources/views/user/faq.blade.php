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
        <div class="panel-group" id="help-accordion-1">
            <?php $i=1; ?>
         @foreach($quesdetails as $detail)   
          <div class="panel panel-default panel-help">
            <a href="#opret-produkt{{$i}}" data-toggle="collapse" data-parent="#help-accordion-1" onclick="getquestionAns({{$i}},{{$detail->shippingId}},{{$detail->carrier_id}});">
              <div class="panel-heading">
                <h2>{{ App\ShippingDetail::getDeliveryName($detail->table_name, ($detail->shippingId) ? $detail->shippingId : 0).' - '.$detail['first_name'].' '.$detail['last_name'] }}</h2>
              </div>
            </a>
            <div id="opret-produkt{{$i}}" class="collapse">
              <div class="panel-body" id="allquesans{{$i}}">
              
              </div>
                
                <div class="quest_txt">
                  <h4> <span class="pull-right" onclick="$('._question_tb_0{{$i}}').toggle();"><i class="fa fa-plus-circle"></i></span></h4>
                  <div class="clearfix"></div>
                    <div class="_question_tb_0{{$i}}" style="display: none">
                    <div class="input-group">
                      <input type="text" name="question" id="question_faq{{$detail->shippingId}}" class="form-control input-sm" maxlength="64" placeholder="Please enter your Question" />
                      <span class="input-group-addon btn-primary"  onclick="askquestion({{$detail->shippingId}},{{$detail->user_id}});">
                        Submit <i class="fa fa-question" aria-hidden="true"></i>
                      </span>
                    </div>
                   
                    </div>
               </div>
                
                
            </div>
              
          </div>
         <?php $i++; ?>
         @endforeach
        </div>
      </div>
         {!! Form::open(array('url'=>'partner/faq/question','class'=>'form-horizontal','id'=>'partnerfaq_form')) !!}
          <input type="hidden" name="shiping_id" id="shiping_id">
          <input type="hidden" name="user_id" id="user_id">
          <input type="hidden" name="question" id="question">
          
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
    function askquestion(shiping_id,user_id){
       
        var question = $('#question_faq'+shiping_id).val();
        
        if(question) {
         $('#shiping_id').val(shiping_id);
         $('#question').val(question);
         $('#user_id').val(user_id);
          document.getElementById("partnerfaq_form").submit();
        } else {
            alert("Please Enter Question?");
            return false;
        }
    }
    </script>
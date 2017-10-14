@extends('layouts.admin-layout') @section('title') Admin Panel | Haultips @endsection @section('body')
<?php error_reporting(0);//print_r($chatdetails); die;?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="activity_box activity_box1">
                <h3>chat</h3>
                <div class="scrollbar _scrollbar" id="style-2">
                    <?php  $tempArr = Session::get('currentUser'); foreach($chatdetails as $value) { if($value->user_type_id!=4) { ?>
                    <div class="activity-row activity-row1">
                        <div class="col-xs-2 col-md-2 activity-img"><img src="{{asset('public/uploads/userimages/'.$value->image)}}" class="img-responsive" width="60px" height="80px" alt=""><span>{{date('h:sa',strtotime($value->created_at))}}</span></div>
                        <div class="col-xs-8 col-md-8 activity-img1">
                            <div class="activity-desc-sub">
                                <h5>{{$value->first_name}}</h5>
                                <p>{{$value->message}}</p>
                            </div>
                        </div>
                        <div class="col-xs-2 activity-desc1"></div>
                        <div class="clearfix"> </div>
                    </div>
                    <?php } if($value->user_type_id==4) { ?>
                    <div class="activity-row activity-row1">
                        <div class="col-xs-2 activity-desc1"></div>
                        <div class="col-xs-8 activity-img2">
                            <div class="activity-desc-sub1">
                                <h5>{{$tempArr["first_name"]}}</h5>
                                <p>{{$value->message}}</p>
                            </div>

                        </div>
                        <div class="col-xs-2 activity-img"><img src="{{asset('public/uploads/userimages/'.$profile_pic->image)}}" class="img-responsive" width="60px" height="80px" alt=""><span>{{date('h:sa',strtotime($value->created_at))}}</span></div>
                        <div class="clearfix"> </div>
                    </div>
                    <?php } ?>
                    <input type="hidden" name="hidden_profile" id="hidden_profile" value="{{asset('public/uploads/userimages/'.$value->image)}}">
                    <?php } ?>

                    <div class="" id="chatmsg">

                    </div>
                </div>
              <form method="post" id="chatform">

                    <input type="hidden" name="hidden_channel_id" id="hidden_channel_id" value="{{$channel_id}}">
                    <input type="text" name="chat_message" id="chat_message" value="Enter your text" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Enter your text';}" required="">
                    <input type="submit" value="Send1" required="">
                </form>
            </div>
        </div>
    </div>
</div>

<!--body wrapper end-->

@endsection
@section('script')
<!-- <script src="//js.pusher.com/3.0/pusher.min.js"></script>-->
    <script>
    var pusher = new Pusher("{{env("PUSHER_KEY")}}", {
                      cluster: "{{env("PUSHER_CLUSTER")}}",
                    })
    //var channel = pusher.subscribe('support-channel');
    var channel = pusher.subscribe('support-channel{{Auth::user()->id}}');
    channel.bind('my-event', function(data) {
        var current_time = new Date().toLocaleTimeString();
      //$('#chatmsg').append('<h5>' + data.message + '(' + data.fname + ')</h5>');
      //push to user
      var a='<div class="activity-row activity-row1">'+
        '<div class="col-xs-3 col-md-3 activity-img">'+
            '<img src="'+data.profile_pic+'" class="img-responsive" width="60px" height="80px" alt=""><span>'+current_time+'</span></div>'+
        '<div class="col-xs-8 col-md-8 activity-img1">'+
            '<div class="activity-desc-sub">'+
                '<h5>'+data.fname+'</h5>'+
                '<p>'+data.message+'</p>'+
            '</div>'+
        '</div>'+
        '<div class="col-xs-4 activity-desc1"></div>'+
        '<div class="clearfix"> </div>'+
    '</div>';
    var user_id = $('#hidden_channel_id').val();
    if(user_id==data.user_id) {
    $('#chatmsg').append(a);
    }
    var objDiv = document.getElementById("style-2");
        objDiv.scrollTop = objDiv.scrollHeight;
    });

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $(document).on('submit', '#chatform', function(e) {
        e.preventDefault();
        var ajax_url = "{{ url('send_message_by_support') }}";
        var pusher = new Pusher("{{env("PUSHER_KEY")}}", {
                      cluster: "{{env("PUSHER_CLUSTER")}}",
                    })
        var socketId = null;
        pusher.connection.bind('connected', function() {

            socketId = pusher.connection.socket_id;
            $.ajax ({
                type: "POST",
                url: ajax_url,
                cache: false,
                data: {
                  chat_message: $('#chat_message').val(),
                  profile_pic: $('#hidden_profile').val(),
                  channel_id: $('#hidden_channel_id').val(),
                  socket_id: socketId // pass socket_id parameter to be used by server
                },
                dataType: "html",
               success: function(msg) {
                    if(msg == 1){
                    var current_time = new Date().toLocaleTimeString();
                     // $('#chatmsg').append('<h5>' + $('#chat_message').val() + '({{ Auth::user()->first_name }})</h5>');
                    var a='<div class="activity-row activity-row1">'+
                        '<div class="col-xs-2 activity-desc1"></div>'+
                        '<div class="col-xs-7 activity-img2">'+
                            '<div class="activity-desc-sub1">'+
                               ' <h5>{{ Auth::user()->first_name }}</h5>'+
                                '<p>'+$('#chat_message').val()+'</p>'+
                           '</div>'+
                        '</div>'+
                       '<div class="col-xs-3 activity-img"><img src="{{asset('public/uploads/userimages/'.$profile_pic->image)}}" class="img-responsive" width="60px" height="80px" alt=""><span>'+current_time+'</span></div>'+
                        '<div class="clearfix"> </div>'+
                    '</div>';
                    $('#chatmsg').append(a);
                    var objDiv = document.getElementById("style-2");
                        objDiv.scrollTop = objDiv.scrollHeight;
                        $('#chat_message').val('');
                    }
                },
                error : function(msg, status) {
                    alert(msg+status);
                }
            });
        });
    });
});

    </script>
    @endsection

@extends('layouts.user-dashboard-layout') @section('title') Online Truck Booking | Packers and Movers - HAULTIPS @endsection @section('body')

<div class="col-md-8">
    <div class="_dash_rft" id="_dash_rft">
        <div class="panel _panel" id="#messages-main">
            <div class="ms-body" >
                <div class="action-header clearfix">
                    <div class="visible-xs" id="ms-menu-trigger">
                        <i class="fa fa-bars"></i>
                    </div>

                    <div class="pull-left hidden-xs">
                        <img src="{{$profile_pic}}" alt="" class="img-avatar m-r-10">
                        <div class="lv-avatar pull-left">

                        </div>
                        <span>{{$name}}</span>
                    </div>

<!--                    <ul class="ah-actions actions">
                        <li>
                            <a href="">
                                <i class="fa fa-trash"></i>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <i class="fa fa-check"></i>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <i class="fa fa-clock-o"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-sort"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="">Latest</a>
                                </li>
                                <li>
                                    <a href="">Oldest</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-bars"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a href="">Refresh</a>
                                </li>
                                <li>
                                    <a href="">Message Settings</a>
                                </li>
                            </ul>
                        </li>
                    </ul>-->
                </div>
                <div class="height_bx" id="scroll_height">
                    <?php  $tempArr = Session::get('currentUser'); foreach($chatdetails as $value) { if($value->user_type_id==4) { ?>
                    <div class="message-feed media">
                        <div class="pull-left">
                            <img src="{{asset('public/uploads/userimages/'.$value->image)}}" alt="" class="img-avatar" width="60px" height="80px">
                        </div>
                        <div class="media-body">
                            <div class="mf-content">
                                {{$value->message}}
                            </div>
                            <small class="mf-date"><i class="fa fa-clock-o"></i> {{date('d-m-Y h:sa',strtotime($value->created_at))}}</small>
                        </div>
                    </div>
                    <?php } if($value->user_type_id!=4) { ?>
                    <div class="message-feed right">
                        <div class="pull-right">
                            <img src="{{$profile_pic}}" alt="" class="img-avatar" width="60px" height="80px">
                        </div>
                        <div class="media-body">
                            <div class="mf-content">
                               {{$value->message}}
                            </div>
                            <small class="mf-date"><i class="fa fa-clock-o"></i> {{date('d-m-Y h:sa',strtotime($value->created_at))}}</small>
                        </div>
                    </div>
                     <?php } ?>
                    <?php } ?>   
                 <div class="" id="chatmsg">
                        
                    </div>
                </div>
                 <form method="post" id="chatform">
                     <input type="hidden" name="hidden_profile" id="hidden_profile" value="{{$profile_pic}}">
                      <input type="hidden" name="hidden_user_id" id="hidden_user_id" value="{{$tempArr["id"]}}">
                <div class="msb-reply">
                    <textarea name="chat_message" id="chat_message" placeholder="What's on your mind..."></textarea>
                    <button><i class="fa fa-paper-plane-o"></i></button>
                </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
</script>
@endsection

@section('script')

    <script>
    var pusher = new Pusher("{{env("PUSHER_KEY")}}", {
                      cluster: "{{env("PUSHER_CLUSTER")}}",
                    })
    var channel = pusher.subscribe('my-channel-{{Auth::user()->id}}');
    channel.bind('my-event', function(data) {
      //$('#chatmsg').append('<h5>' + data.message + '(' + data.fname + ')</h5>');
    var today = new Date();
    var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var dateTime = date+' '+time;
   //push to support
    var a='<div class="message-feed media">'+
        '<div class="pull-left">'+
            '<img src="'+data.profile_pic+'" alt="" class="img-avatar">'+
        '</div>'+
        '<div class="media-body">'+
            '<div class="mf-content">'+
                 data.message+data.user_id+
            '</div>'+
            '<small class="mf-date"><i class="fa fa-clock-o"></i> '+dateTime+'</small>'+
        '</div>'+
    '</div>';
    var user_id = $('#hidden_user_id').val();
   // if(user_id==data.user_id) {
    $('#chatmsg').append(a);
   // }
    var objDiv = document.getElementById("scroll_height");
        objDiv.scrollTop = objDiv.scrollHeight;
    });

$(document).ready(function() {
    var objDiv = document.getElementById("scroll_height");
        objDiv.scrollTop = objDiv.scrollHeight;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $(document).on('submit', '#chatform', function(e) {
        e.preventDefault();
        var ajax_url = "{{ url('send_message_by_user') }}";
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
                  user_id: $('#hidden_user_id').val(),
                  socket_id: socketId // pass socket_id parameter to be used by server
                },
                dataType: "html",
                success: function(msg) {
                    if(msg == 1){
                       var today = new Date();
                       var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
                       var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                       var dateTime = date+' '+time;
                      //$('#chatmsg').append('<h5>' + $('#chat_message').val() + '({{ Auth::user()->first_name }}))</h5>');
                    var a='<div class="message-feed right">'+
                        '<div class="pull-right">'+
                            '<img src="{{$profile_pic}}" alt="" class="img-avatar">'+
                        '</div>'+
                        '<div class="media-body">'+
                            '<div class="mf-content">'+
                               $('#chat_message').val()+
                            '</div>'+
                            '<small class="mf-date"><i class="fa fa-clock-o"></i> '+dateTime+'</small>'+
                        '</div>'+
                    '</div>';
                    $('#chatmsg').append(a);
                    var objDiv = document.getElementById("scroll_height");
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
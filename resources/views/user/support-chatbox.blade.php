@extends('layouts.user-layout')
@section('title')
    Online Truck Booking | Packers and Movers - HAULTIPS
@endsection

@section('body')

<section class="main_pages _howitwork">
    <div class="container">
      <div class="row">
        <div class="col-md-12" id="chatmsg">
                        
        </div>
        <form method="post" id="chatform">
            {{ csrf_field() }}
            <input type="text" name="chat_message" id="chat_message">
            <input type="submit">
        </form>
      </div>
    </div>
    </div>
</section>
@endsection

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//js.pusher.com/3.0/pusher.min.js"></script>
    <script>
    var pusher = new Pusher("{{env("PUSHER_KEY")}}", {
                      cluster: "{{env("PUSHER_CLUSTER")}}",
                    })
    var channel = pusher.subscribe('support-channel');
    channel.bind('my-event', function(data) {
      $('#chatmsg').append('<h5>' + data.message + '(' + data.fname + ')</h5>');
    });

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $(document).on('submit', '#chatform', function(e) {
        e.preventDefault();
        var ajax_url = "{{ url('send-message-s') }}";
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
                  socket_id: socketId // pass socket_id parameter to be used by server
                },
                dataType: "html",
                success: function(msg) {
                    if(msg == 1){
                      $('#chatmsg').append('<h5>' + $('#chat_message').val() + '({{ Auth::user()->first_name }})</h5>');
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


$(document).on('click', '.panel-heading span.clickable', function(e){
    var $this = $(this);
	if(!$this.hasClass('panel-collapsed')) {
		$this.parents('.panel').find('.panel-body').slideUp();
		$this.addClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
	} else {
		$this.parents('.panel').find('.panel-body').slideDown();
		$this.removeClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-plus').addClass('glyphicon-minus');
	}
});

function initialize() {
    var myLatlng = new google.maps.LatLng(53.3333,-3.08333),
    mapOptions = {
        zoom: 11,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
        }
var map = new google.maps.Map(document.getElementById('map'), mapOptions),
contentString = 'Some address here..',
infowindow = new google.maps.InfoWindow({
    content: contentString,
    maxWidth: 500
});

var marker = new google.maps.Marker({
    position: myLatlng,
    map: map
});

google.maps.event.addListener(marker, 'click', function() {
    infowindow.open(map,marker);
});

google.maps.event.addDomListener(window, "resize", function() {
    var center = map.getCenter();
    google.maps.event.trigger(map, "resize");
    map.setCenter(center);
    });
}

google.maps.event.addDomListener(window, 'load', initialize);






function list_toggle(val)
 {$('.test-dwn'+val).slideToggle(); }

/* Truck booking*/


/*number increase and decrease */




function add(value){
    var currentVal = parseInt($("#qty" + value).val());    
    if (!isNaN(currentVal)) {
        $("#qty" + value).val(currentVal + 1);
    }
};

function minus(value){
    var currentVal = parseInt($("#qty" + value).val());    
    if (!isNaN(currentVal)) {
        $("#qty" + value).val(currentVal - 1);
    }
};

function closeOver(f, value){
    return function(){
        f(value);
    };
}

$(function () {
    var numButtons = 2;    
    for (var i = 1; i <= numButtons; i++) {
        $("#add" + i).click(closeOver(add, i));
        $("#minus" + i).click(closeOver(minus, i));
    }
});
$(document).ready(function(){
    $(".scrollbar").niceScroll();

});
function increaseValue(id)
{
   
    var val=$("#"+id).val();
    $("#"+id).val(parseInt(val)+1);  
}

function decreaseValue(id)
{
    var val=$("#"+id).val();
    if(val=="" || val==null || val==0)
    {
      $("#"+id).val('0');
    }
    else
    {
       $("#"+id).val(parseInt(val)-1);
    }
}
/* left side menu*/

  function htmlbodyHeightUpdate(){
        var height3 = $( window ).height()
        var height1 = $('.nav').height()+50
        height2 = $('.main').height()
        if(height2 > height3){
            $('html').height(Math.max(height1,height3,height2)+10);
            $('body').height(Math.max(height1,height3,height2)+10);
        }
        else
        {
            $('html').height(Math.max(height1,height3,height2));
            $('body').height(Math.max(height1,height3,height2));
        }
        
    }
    $(document).ready(function () {
        htmlbodyHeightUpdate()
        $( window ).resize(function() {
            htmlbodyHeightUpdate()
        });
        $( window ).scroll(function() {
            height2 = $('.main').height()
            htmlbodyHeightUpdate()
        });
    });

    function toggle(val){
        $('.ulist'+val).slideToggle();
    }
    $('.edit_btn').click(function(){
            $('._me_dis').toggle();
            $('._me_input').toggle();
    });
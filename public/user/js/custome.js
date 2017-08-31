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


$(document).on('click', '.panel-heading span.clickable', function(e){
    var $this = $(this);
	if(!$this.hasClass('panel-collapsed')) {
		$this.parents('.panel').find('.panel-body').slideUp();
		$this.addClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
	} else {
		$this.parents('.panel').find('.panel-body').slideDown();
		$this.removeClass('panel-collapsed');
		$this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
	}
})

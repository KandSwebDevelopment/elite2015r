<?php
// Zen Cart Google Mapinator
// (k) Erik(Design75) / Graham Bevins (Snorkpants)
// (c) 2006 Phoenix Web Development
// (c) 2012 Zen4All
// Email: info@zen4all.nl
// Version 1.1

?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLEMAP_KEY ; ?>" type="text/javascript">
</script>
	<script type="text/javascript">

//<![CDATA[
var map;
var directionsDisplay;
var directionsService;
var stepDisplay;
var markerArray = [];


function GoogleMapLoad(){
		directionsService = new google.maps.DirectionsService();

	 var myLatlng = new google.maps.LatLng(<?php echo GOOGLEMAP_LAT ; ?>,<?php echo GOOGLEMAP_LNG ; ?>);

		var myOptions = {
				zoom: <?php echo GOOGLEMAP_ZOOM ; ?>,
				center: myLatlng,
				mapTypeId: google.maps.MapTypeId.<?php echo GOOGLEMAP_MAPTYPE ; ?>,
				



				scaleControl:true
	}
	map = new google.maps.Map(document.getElementById("map"), myOptions);
	var rendererOptions = {map: map};
	directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
	stepDisplay = new google.maps.InfoWindow();
	directionsDisplay.setMap(map);
	directionsDisplay.setPanel(document.getElementById('route'));

	var contentString = '<div id="content">'+
		'<?php echo GOOGLEMAP_STORE_INFORMATION ; ?>'+
		'</div>';

	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});

	var image = '<?php echo GOOGLEMAP_MARKER_IMAGE ; ?>';
	var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			draggable:false,
			icon: image,
			animation: google.maps.Animation.DROP,
			title:"<?php echo GOOGLEMAP_MARKER_TITLE ; ?>"
	});

	google.maps.event.addListener(marker, 'click', toggleBounce);
	google.maps.event.addListener(marker, 'mouseover', function() {
		infowindow.open(map,marker);
	});
		function toggleBounce() {

		if (marker.getAnimation() != null) {
			marker.setAnimation(null);
		} else {
			marker.setAnimation(google.maps.Animation.BOUNCE);
		}
	}
}




function calcRoute() {
	for (var i = 0; i < markerArray.length; i++) {
		markerArray[i].setMap(null);
	}

	markerArray = [];
	var end = '16 Broughshane Street, Ballymena, BT43 6EB, UK';
	var start = document.getElementById('postcode').value + ', UK';
	var request = {
			origin: start,
			destination: end,
			travelMode: google.maps.TravelMode.DRIVING
	};


		directionsService.route(request, function(response, status){
				if(status==google.maps.DirectionsStatus.OK){
						var warnings=document.getElementById('warnings_panel');
						warnings.innerHTML='<b>'+response.routes[0].warnings+'</b>';
						directionsDisplay.setDirections(response);showSteps(response);
				}
		});
}
function showSteps(directionResult){
		var myRoute=directionResult.routes[0].legs[0];
		for(var i=0;i<myRoute.steps.length;i++){
				var marker=new google.maps.Marker({position:myRoute.steps[i].start_point,map: map});
				attachInstructionText(marker, myRoute.steps[i].instructions);markerArray[i] = marker;}}function attachInstructionText(marker, text){google.maps.event.addListener(marker,'click',function(){stepDisplay.setContent(text);stepDisplay.open(map, marker);});}


var postcodeValid = new RegExp("(A[BL]|B[ABDHLNRST]?|C[ABFHMORTVW]|D[ADEGHLNTY]|E[HNX]?|F[KY]|G[LUY]?|H[ADGPRSUX]|I[GMPV]|JE|K[ATWY]|L[ADELNSU]?|M[EKL]?|N[EGNPRW]?|O[LX]|P[AEHLOR]|R[GHM]|S[AEGKLMNOPRSTY]?|T[ADFNQRSW]|UB|W[ADFNRSV]|YO|ZE)[1-9]?[0-9]|([E|N|NW|SE|SW|W]1|EC[1-4]|WC[12])[A-HJKMNPR-Y]|[SW|W](([1-9][0-9]|[2-9])|EC[1-9][0-9]) [0-9][ABD-HJLNP-UW-Z]{2}","");

$(document).ready(function(){

$('#getDir').click(function(e){
		e.preventDefault();
		var postcode = $('#postcode').val();
		if(postcodeValid.test(postcode.toUpperCase())){
				if(postcode.toUpperCase()!='BT43 6EB'){
				calcRoute();
				$('.mapPrint').css({'display':'inline'});
				}else{
						$('#warnings_panel').html('Your postcode is the same as our postcode');
						$('.mapPrint').css({'display':'none'});
				}
		}else{
				$('#warnings_panel').html('Your postcode has not been recognised, please re-enter');
				$('.mapPrint').css({'display':'none'});
		};
});

$('#postcode').focusin(function(){
		$('#warnings_panel').html('');
});
$('#postcode').blur(function(){
		$('#postcode').val($('#postcode').val().toUpperCase());
})


});//End doc ready


function printDiv(divName) {
 var printContents = document.getElementById(divName).innerHTML;
 w=window.open();
 w.document.write(printContents);
 w.print();
 w.close();
}

//]]>
</script>
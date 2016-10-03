<?php
// Zen Cart Google Mapinator
// (k) Erik(Design75) / Graham Bevins (Snorkpants)
// (c) 2006 Phoenix Web Development
// (c) 2012 Zen4All
// Email: info@zen4all.nl
// Version 1.1

?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLEMAP_KEY ; ?>&amp;sensor=true" type="text/javascript">
</script>
  <script type="text/javascript">

//<![CDATA[

function GoogleMapLoad() 
{
  var myLatlng = new google.maps.LatLng(<?php echo GOOGLEMAP_LAT ; ?>,<?php echo GOOGLEMAP_LNG ; ?>);
  
  var myOptions = {
    zoom: <?php echo GOOGLEMAP_ZOOM ; ?>,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.<?php echo GOOGLEMAP_MAPTYPE ; ?>
  }
  
  var map = new google.maps.Map(document.getElementById("map"), myOptions);
  
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

//]]>
</script>
 @extends('layouts.app')
@section('title', 'SkillMap Home')
@section('content')


    <div id="content"> <div id="map" style="width: 100%; height: 100%;"></div>
  </div>
  
</div>
 <script>
var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 18,
          center: new google.maps.LatLng(1.212580,35.099358),
          mapTypeId: 'roadmap'
        });



}

    </script>
 
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgv-L7ZHb-NOVg6Nn-fJDAl7K_7i-pSec&callback=initMap">
    </script>
    @endsection
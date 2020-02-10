 @extends('layouts.app')
@section('title', 'SkillMap Home')
@section('content')

    <div id="content"> <div id="map" style="height: 100%;"></div>
  </div>
  
</div>

     <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: new google.maps.LatLng(0.5,35.3),
          mapTypeId: 'roadmap'
        });

        

        var iconBase =
            '/images/';

        var icons = {
          parking: {
            icon: iconBase + 'gardener.svg',
            scale: 0.2
          },
          library: {
            icon: iconBase + 'electrician.svg',
            scale: 0.15
          },
          info: {
            icon: iconBase + 'mason.svg',
            scale: 0.15
          }
        };

        var features = [
          
         {
            position: new google.maps.LatLng(0.5131953371116661,35.30581963062285),
            type: 'info'
          }, {
            position: new google.maps.LatLng(0.4802590486577686,35.30200016498564),
            type: 'info'
          }
          
        ];

        // Create markers.
        features.forEach(function(feature) {
          var marker = new google.maps.Marker({
            position: feature.position,
            icon: icons[feature.type].icon,
           draggable: false,
            scaledSize: new google.maps.Size(25, 25),
             map: map

          });
        });
      }
    </script>
    
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ env('API_KEY') }}&callback=initMap">
    </script>
    @endsection
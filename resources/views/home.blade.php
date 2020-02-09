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
          zoom: 18,
          center: new google.maps.LatLng(0.6711880,35.5070199),
          mapTypeId: 'roadmap'
        });

        

        var iconBase = './images/';
        var icons = {
          agent: {
            icon: iconBase + 'Mason.svg'
          },
          mobile-fix: {
            icon: iconBase + 'ComputerRepaire.svg'
          },
          electrician2: {
            icon: iconBase + 'Electrician.svg'
          }




        };

        var features = [
          
          <?php echo $msg; ?>
          
        ];

        // Create markers.
        features.forEach(function(feature) {
          var marker = new google.maps.Marker({
            position: feature.position,
            icon: icons[feature.type].icon,
            map: map
          });
        });
      }
    </script>
    
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ env('API_KEY') }}&callback=initMap">
    </script>
    @endsection
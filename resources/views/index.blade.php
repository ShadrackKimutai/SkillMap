@extends('layouts.app')
@section('title', 'SkillMap Home')
@section('content')

<div id="content"> <div id="map" style="height: 100%;"></div>
</div>

</div>
<script type="text/javascript">

  var iconBase =
  '/images/';

  var icons = {
    accountant: {
      icon: iconBase + 'accountant.svg',
      scale: 0.15
    },
    agent: {
      icon: iconBase + 'agent.svg',
      scale: 0.15
    },
    architect: {
      icon: iconBase + 'architect.svg',
      scale: 0.15
    },
    builderaid: {
      icon: iconBase + 'builderaid.svg',
      scale: 0.15
    },
    carpenter: {
      icon: iconBase + 'carpenter.svg',
      scale: 0.15
    },
    chef: {
      icon: iconBase + 'chef.svg',
      scale: 0.15
    },
    cleaner: {
      icon: iconBase + 'cleaner.svg',
      scale: 0.15
    },
    computeraideddesigner: {
      icon: iconBase + 'computeraideddesigner.svg',
      scale: 0.15
    },
    computerprogrammer: {
      icon: iconBase + 'computerprogrammer.svg',
      scale: 0.15
    },
    computerrepair: {
      icon: iconBase + 'computerrepair.svg',
      scale: 0.15
    },
    electricinstaller: {
      icon: iconBase + 'electricinstaller.svg',
      scale: 0.15
    },
    electrician: {
      icon: iconBase + 'electrician.svg',
      scale: 0.15
    },
    gardener: {
      icon: iconBase + 'gardener.svg',
      scale: 0.15
    },
    indoordesigner: {
      icon: iconBase + 'indoordesigner.svg',
      scale: 0.15
    },
    laundry: {
      icon: iconBase + 'laundry.svg',
      scale: 0.15
    },
    mason: {
      icon: iconBase + 'mason.svg',
      scale: 0.15
    },
    painter: {
      icon: iconBase + 'painter.svg',
      scale: 0.15
    },
    phonerepair: {
      icon: iconBase + 'phonerepair.svg',
      scale: 0.15
    },
    plumber: {
      icon: iconBase + 'plumber.svg',
      scale: 0.15
    },
    tvinstaller: {
      icon: iconBase + 'tvinstaller.svg',
      scale: 0.15
    }
  };

  var markers = [
  @foreach($data as $entre)
  {
    title: '{{ $entre->name }}',
    lat: {{ $entre->lat }},
    lng:{{ $entre->lng }},
    type: '{{ $entre->icon }}',
    "description": "<img src='/storage/avatars/{{ $entre->avatar }}' width='100px' height='100px'></div><div style='float:right; padding: 10px;'>"+"<h5>{{ $entre->name }}</h5> <b>{{  $entre->expertice }} - Rated {{ $entre->rating }}</b><br>Availability:{{ $entre->availability }}<br>Phone:{{ $entre->contacts }} <br>"
                
  },

  @endforeach
  ];

  window.onload = function () {
    LoadMap();
  }
  function LoadMap() {
    var mapOptions = {
      center: new google.maps.LatLng(markers[0].lat,markers[0].lng),
      zoom: 12,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map"), mapOptions);

        //Create and open InfoWindow.
        var infoWindow = new google.maps.InfoWindow();

        for (var i = 0; i < markers.length; i++) {
          var data = markers[i];
          var myLatlng = new google.maps.LatLng(data.lat, data.lng);
          var marker = new google.maps.Marker({
            position: myLatlng,
            icon: icons[data.type].icon,
            map: map,
            title: data.title

          });

            //Attach click event to the marker.
            (function (marker, data) {
              google.maps.event.addListener(marker, "click", function (e) {
                    //Wrap the content inside an HTML DIV in order to set height and width of InfoWindow.
                    infoWindow.setContent("<div style='float:left'>" + data.description + "</div>");
                    infoWindow.open(map, marker);
                  });
            })(marker, data);
          }
        }
      </script>

      <script async defer
      src="https://maps.googleapis.com/maps/api/js?key={{ env('API_KEY') }}&callback=initMap">
    </script>
    @endsection
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

        var features = [
          @foreach($data as $entre)
          {
            position: new google.maps.LatLng({{ $entre->location }}),
            type: '{{$entre->icon}}'
          },
          @endforeach
       
        ];

        // infowindows
        var infoWindowContent = [
        @foreach($data as $entre)
        { 
          ['<div class="info_content"><br> {{ $entre->name }} <p> {{ $entre->contacts }} </p></div>'],

        }
        @endforeach
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
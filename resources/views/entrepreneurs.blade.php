@extends('layouts.app')

@section('content')
<style type="text/css">
  .modal-backdrop {
    z-index: -1;

  </style>
  <div class="container">
   <div style="height: 100px; width:100%" class="row"></div>
   <div class="row justify-content-center">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <tr>
            <th>profile</th>
            <th>Name</th>
            <th>Expert</th>
            <th>Area</th>
            <th>Contacts</th>
            <th>Rating</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $entre)
          <tr> <td><img src="/storage/avatars/{{ $entre->avatar }}" width="100px" height="100px"></td>
            <td> {{ $entre->name }}               </td>
            <td>        {{ $entre->expertice }}         </td>
            <td>       {{ $entre->area }}          </td>
            <td>        {{ $entre->contacts }}         </td>

            <td width="90px">  
              <center>       
                @for ($i = 0; $i < round($entre->rating); $i++)
                <img src="{{asset('/images/star.svg')}}" width="16px" alt="⭐">
                @endfor    
              </center>
            </td>
            <td>


              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" data-loc="{{ $entre->location }}">
                Locate
              </button>


              <!-- Modal -->
              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header"><h4 class="modal-title" id="myModalLabel">Skilled Workman Location</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>

                    </div>
                    <div class="modal-body">
                      <div class="row">

                      </div>
                      <div class="row">
                        <div class="col-md-12 modal_body_map">
                          <div class="location-map" id="location-map">
                            <div style="width: 300px; height: 250px;" id="map_canvas"></div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <!-- Placed at the end of the document so the pages load faster -->
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
      <script src="//maps.googleapis.com/maps/api/js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
      <script type="text/javascript">
    // Code goes here

    $(document).ready(function() {
      var map = null;
      var myMarker;
      var myLatlng;

      function initializeGMap(lat, lng) {
        myLatlng = new google.maps.LatLng(lat, lng);

        var myOptions = {
          zoom: 12,
          zoomControl: true,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

        myMarker = new google.maps.Marker({
          position: myLatlng
        });
        myMarker.setMap(map);
      }

  // Re-init map before show modal
  $('#myModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    mylocale=button.data('loc').split(',');
    
    initializeGMap(mylocale[0],mylocale[1]);
    $("#location-map").css("width", "100%");
    $("#map_canvas").css("width", "100%");
  });

  // Trigger map resize event after modal shown
  $('#myModal').on('shown.bs.modal', function() {
    google.maps.event.trigger(map, "resize");
    map.setCenter(myLatlng);
  });
});
</script>
@endsection
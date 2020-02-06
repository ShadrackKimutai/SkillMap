@extends('layouts.app')

@section('content')
<style>
#map{
  width:100%;
  height:480px;
}

.modal-backdrop {
  z-index: -1;
}
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
               
                <td width="120px">         @for ($i = 0; $i < round($entre->rating); $i++)
                                        <img src="{{asset('/images/star.png')}}" width="16px">
                                    @endfor    </td>
                <td> 

                 <a class="openmodal" href="#contact"  data-toggle="modal" data-id="Peggy Guggenheim Collection - Venice">Contact</a>
                 <div class="modal fade" id="contact" role="dialog" >
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content" id="back" >  
                      <div class="modal-header">
                        <h4>Contact<h4>
                        </div>
                        <div class="modal-body">    
                          <div id="map"></div>
                        </div>
                        <div class="modal-footer">
                          <a class="btn btn-default" data-dismiss="modal">Close</a>
                        </div>      
                      </div>
                    </div>


                </td>
            </tr>
            @endforeach
        </table>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 modal_body_content">
              <p></p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 modal_body_map">
              <div class="location-map" id="location-map">
                <div style="width: 600px; height: 400px;" id="map"></div>
              </div>
            </div>
          </div>
          <div class="row">
            
          </div>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
  var map;        
            var myCenter=new google.maps.LatLng(44.5403, -78.5463);
var marker=new google.maps.Marker({
    position:myCenter
});

function initialize() {
  var mapProp = {
      center:myCenter,
      zoom: 14,
      draggable: false,
      scrollwheel: false,
      mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  
  map=new google.maps.Map(document.getElementById("map"),mapProp);
  marker.setMap(map);
    
  google.maps.event.addListener(marker, 'click', function() {
      
    infowindow.setContent(contentString);
    infowindow.open(map, marker);
    
  }); 
};
google.maps.event.addDomListener(window, 'load', initialize);

google.maps.event.addDomListener(window, "resize", resizingMap());

$("#myMapModal").on("shown.bs.modal", function () {
    google.maps.event.trigger(map, "resize");
});



</script>
<script type="text/javascript" src="{{ asset('js/map.js')}}">

@endsection

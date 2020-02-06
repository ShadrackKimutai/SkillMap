@extends('layouts.app')

@section('content')
   <style>
  .locationMap {
    height: 300px;
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
        <th>Expert In</th>
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
               
                <td>         {{ $entre->rating}}        </td>
                <td>    <a type="button" onclick="" ="open" class="btn btn-primary" data-toggle="modal" data-target="#myModal" data-lngltd="{{ $entre->location }}">
    Locate
  </button></td>
            </tr>
            @endforeach
</table>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mapModal">
          Location
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">
            &times;
          </span>
        </button>
      </div>
      <div class="modal-body">
       <gmap-map
          class="locationMap"
          :center="{lat:10, lng:10}"
          :zoom="1"
        ></gmap-map>
      </div>
    </div>
  </div>
</div>

<script>
   methods: {
initMapModal(event){
  event.preventDefault();
  setTimeout(this.mapResize, 200);
},

mapResize(){
  Vue.$gmapDefaultResizeBus.$emit('resize');
},

}


</script>
@endsection

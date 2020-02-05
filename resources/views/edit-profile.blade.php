@extends('layouts.app')

@section('content')

                       
 <div class="container">
        <div class="row">
            @if ($message = Session::get('success'))

                <div class="alert alert-success alert-block">

                    <button type="button" class="close" data-dismiss="alert">×</button>

                    <strong>{{ $message }}</strong>

                </div>

            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div style="height: 100px; width:100%" class="row"></div>
    <div class="row justify-content-center">@auth
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Profile</div>

                <div class="card-body">
        <div class="row justify-content-center">

            <div class="profile-header-container">
                <div class="profile-header-img">
                    <img class="rounded-circle" src="/storage/avatars/{{ $user->avatar }}" width="180px" height="180px"/>
                    <!-- badge -->
                    <div class="rank-label-container">
                        <span class="label label-default rank-label">{{$user->name}}</span>
                    </div>
                </div>
            </div>

        </div>
        <div class="row justify-content-center">
            <form action="/edit-profile" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="file" class="form-control-file" name="avatar" id="avatarFile" aria-describedby="fileHelp">
                    <small id="fileHelp" class="form-text text-muted">Please upload a valid image file. Size of image should not be more than 400kb.</small>
                </div>


       

                <div class="form-group row">
                            <label for="expertice" class="col-md-4 col-form-label text-md-right">{{ __('Expertice') }}</label>

                            <div class="col-md-6">
                                <Select id="expertice" type="text" class="form-control{{ $errors->has('expertice') ? ' is-invalid' : '' }}" name="expertice" value="{{ old('expertice') }}" required autofocus>
                                    <option>Electrician</option>
                                    <option>Plumber</option>
                                    <option>Mason</option>
                                    <option>TV Installer</option>
                                    <option>Cleaner</option>
                                    <option>Computer Repairer</option>
                                    <option>Phone Repairer</option>

                                </Select>
                                @if ($errors->has('expertice'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('expertice') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="rating" class="col-md-4 col-form-label text-md-right">Rating:<br> <small>0-Worst 5-Best</small></label>

                            <div class="col-md-6">
                                <input id="rating" type="number" class="form-control{{ $errors->has('rating') ? ' is-invalid' : '' }}" name="rating" value="{{ old('rating') }}" required max="5" min="0">

                                @if ($errors->has('rating'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('rating') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                         <div class="form-group row">
                            <label for="availability" class="col-md-4 col-form-label text-md-right">{{ __('Availability') }}</label>

                            <div class="col-md-6">
                                <Select id="availability" type="text" class="form-control{{ $errors->has('availability') ? ' is-invalid' : '' }}" name="availability" value="{{ old('availability') }}" required autofocus>
                                    <option>Full Time</option>
                                    <option>Weekends</option>
                                    <option>After Working Hours</option>
                                    <option>Contractual</option>

                                </Select>
                                @if ($errors->has('availability'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('availability') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="area" class="col-md-4 col-form-label text-md-right">{{ __('Area or Region') }}</label>

                            <div class="col-md-6">
                                <input id="area" type="text" class="form-control{{ $errors->has('area') ? ' is-invalid' : '' }}" name="area" value="{{ old('area') }}" required autofocus placeholder="e.g. Maili Tisa">

                                @if ($errors->has('area'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('area') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="contacts" class="col-md-4 col-form-label text-md-right">{{ __('Phone Number') }}</label>

                            <div class="col-md-6">
                                <input id="contacts" type="text" class="form-control{{ $errors->has('contacts') ? ' is-invalid' : '' }}" name="contacts" value="{{ old('contacts') }}" placeholder="e.g. 0720000000" required autofocus>

                                @if ($errors->has('contacts'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('contacts') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                          
                            <label for="location" class="col-md-4 col-form-label text-md-right">{{ __('Office Map location') }}</label>

                            <div class="col-md-6">
                                <br><br>
                                <div id="map" style="float: right;"></div>
                                <br>
                                <br>
                                <input id="current" type="text" class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" value="{{ old('location') }}" placeholder=" e.g. 0.4855634,35.3357629" required autofocus>

                                @if ($errors->has('location'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('location') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
       
    </div>
</div>
</div>
 @else

                <h2>Login to Access this Info</h2>

        @endauth
</div>
</div>
</div>

<style>
  #map {
    height: 200px; 
    width: 400px;
  }
</style>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key={{ env('API_KEY') }}&callback=initMap">
</script>
<script>
  var directionsDisplay;
  var directionsService = new google.maps.DirectionsService();
  var map;
  var endMarker;

  function initialize() {
    directionsDisplay = new google.maps.DirectionsRenderer();
    var rvtti = new google.maps.LatLng(0.5017803033458579,35.31238567829131);
    var mapOptions = {
      zoom: 12,
      center: rvtti

    }
    map = new google.maps.Map(document.getElementById("map"), mapOptions);
    directionsDisplay.setMap(map);
    dropPin();
  }

  function dropPin() {
        // if any previous marker exists, let's first remove it from the map
        if (endMarker) {
          endMarker.setMap(null);
        }
        // create the marker
        endMarker = new google.maps.Marker({
          position: map.getCenter(),
          map: map,
          draggable: true,
        });
        copyMarkerpositionToInput();
        // add an event "onDrag"
        google.maps.event.addListener(endMarker, 'dragend', function() {
          copyMarkerpositionToInput();
        });
      }

  function copyMarkerpositionToInput() {
        // get the position of the marker, and set it as the value of input
        document.getElementById("current").value = endMarker.getPosition().lat() +','+  endMarker.getPosition().lng();
  }

  function calcRoute() {
        var start = document.getElementById("start").value;
        var end = document.getElementById("current").value;
        var request = {
          origin:start,
          destination:end,
          travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function(result, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(result);
          }
        });
    }
  google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@endsection
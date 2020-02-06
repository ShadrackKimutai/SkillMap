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
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Profile</div>

                <div class="card-body">
        <div class="row justify-content-center">
@auth
            <div class="profile-header-container">
                <div class="profile-header-img">
                   
                    <img class="rounded-circle" src="/storage/avatars/{{ $user->avatar }}" width="180px" height="180px"/> 

                    <!-- badge -->
                    <div class="rank-label-container">
                        
                    </div>
                </div>
            </div>

        </div>
        <div class="row ">
            <h3>  {{$user->name}} </h3>
          <hr>
      <br>
      <h3>{{$user->expertice}}</h3>


        </div>
<br>
       <font style="font-size: 1em;" >Rating:  @for ($i = 0; $i < round($user->rating); $i++)
            <img src="{{asset('/images/star.png')}}" width="16px">
        @endfor
        <hr>
       <i class="fa fa-map"></i> Area: {{$user->area}}
        <hr>
        <i class="fa fa-map-marker"></i> Location: {{ $user->location }}
        <hr>
        <i class="fa fa-phone"></i> Contact: {{ $user->contacts}}
    </font>
    @else

        <div class="row justify-content-center">
             <div class="profile-header-container">
                <div class="profile-header-img">
                    Login to View
                </div>
            </div>
        </div>
    @endauth
    </div>
</div>
</div>
</div>
</div>
</div>

@endsection
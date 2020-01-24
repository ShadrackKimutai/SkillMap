@extends('layouts.app')

@section('content')
   
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
            </tr>
            @endforeach
</table>
        </div>
    </div>
</div>
</div>

@endsection

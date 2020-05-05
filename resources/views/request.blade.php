@extends('layouts.app')
@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <!--If user is guest -->
        @guest
        <div class="card-body">
          <p>Please register to make requests.</p>
          <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        </div>
        @else
        <!--If user requested the item or is admin-->
        @if(Auth::user()->id == $item_request->requested_by | Auth::user()->admin == 1)
        <div class="card-header">
          <b>Request</b>
        </div>
        <!--Display error/success messeges-->
        @if($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        @if(\Session::has('success'))
        <br />
        <div class="alert alert-success">
          <p>{{ \Session::get('success') }}</p>
        </div>
        @endif

        <!--Displays request information -->
        <div class="card-body">
          <table class="table table-striped" border="1" >
            <tr>
              <th>Request Number</th>
              <td>{{ $item_request->id }}</td>
            </tr>
            <tr>
              <th>Item Requested</th>
              <td><a href="/items/{{ $item_request->item_requested }}">{{ $item_request->item_requested  }}</a></td>
            </tr>
            <tr>
              <th>Requested By</th>
              <td>{{ $user_email }}</td>
            </tr>
            <tr>
              <th>Requested State</th>
              <td>{{ $item_request->state }}</td>
            </tr>
            <tr>
              <th>Reason</th>
              <td style="max-width:150px;">{{ $item_request->description }}</td>
            </tr>
            <tr>
              <td colspan="2">
                <!--If request has not been resolved -->
                @if( $item_request->state == 'Open' )
                <!--If user made the request -->
                @if( $item_request->requested_by == Auth::user()->id )
              </td>
            </tr>
          </table>
        </div>
        <!--Let them edit it and delete it -->
        <div class="card-header">
          <b>Edit</b>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('requests.update', $item_request->id) }}"  enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <table class="table table-striped" border="1" >
              <tr>
                <th>Description</th>
                <th><textarea name="description" maxlength="500" rows="10" cols="40"></textarea></th>
                <input type="hidden" name="request_id" value="{{ $item_request->id }}" />
              </tr>
              <th>Actions</th>
              <th><input type="submit" class="btn btn-primary" />
                <input type="reset" class="btn btn-danger" /></th>
              </tr>
            </table>
          </form>
        </div>
        <form action="{{ action('ItemRequestController@destroy', $item_request['id']) }}" method="POST">@csrf
          <input name="_method" type="hidden" value="DELETE">
          <button class="btn btn-danger" type="submit">Delete</button>
        </form>
        <!--If user did not make the request and is an admin -->
        @else if( $item_request->requested_by != Auth::user()->id && Auth::user()->admin == 1)
        <!--Let them resolve it -->
        <a href="/requests/{{ $item_request['id'] }}/Approved/" class="btn btn-primary">Approve</a>
        <a href="/requests/{{ $item_request['id'] }}/Denied/" class="btn btn-danger">Reject</a>
        @endif
        <!--If the user is an admin and the request has been resolved-->
        @else if(Auth::user()->admin == 1)
        <!--Let them delete it -->
        <form action="{{ action('ItemRequestController@destroy', $item_request['id']) }}" method="POST">@csrf
          <input name="_method" type="hidden" value="DELETE">
          <button class="btn btn-danger" type="submit">Delete</button>
        </form>
        @endif
      </td>
    </tr>
  </table>
</div>
@endif
@endguest
</div>
</div>
</div>
@endsection

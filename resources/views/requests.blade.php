@extends('layouts.app')
@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <!--If user is guest -->
        @guest
        <div class="card-body">
          <p>Please register to view item.</p>
          <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        </div>
        @else
        <div class="card-header">Display all requests</div>
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

        <div class="card-body">
          <table class="table table-striped">
            <!--If user is an admin-->
            @if (Auth::user()->admin == 1)
            <thead>
              <tr>
                <th>Item Requested</th>
                <th>Requested By</th>
                <th>Status</th>
                <th colspan="2">Action</th>
              </tr>
            </thead>
            <tbody>
              <!--For every request-->
              @foreach($item_requests as $item_request)
              <tr>
                <td>{{ $item_request['itemId'] }}</td>
                <td>{{ $item_request['userId'] }}</td>
                <td>{{ $item_request['state'] }}</td>

                <td><a href="{{ action('ItemRequestController@show', $item_request['id']) }}" class="btn btn-primary">Details</a></td>
                <!--If admin did not submit the request and the request has not been resolved -->
                @if( $item_request->userId != Auth::user()->id && $item_request->state == 'Open' )
                <td><a href="/requests/{{ $item_request['item_requested'] }}/approve/" class="btn btn-primary">Approve</a></td>
                <td><a href="/requests/{{ $item_request['item_requested'] }}/deny/" class="btn btn-danger">Reject</a></td>
                @endif
                <!--If request has been resolved-->
                @if( $item_request->state != 'Open')
                <form action="{{ action('ItemRequestController@destroy', $item_request['id']) }}" method="POST">@csrf
                  <input name="_method" type="hidden" value="DELETE">
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
                @endif
              </tr>
              @endforeach
            </tbody>
            <!--For normal users-->
            @else
            <thead>
              <tr>
                <th>Item Requested</th>
                <th>Status</th>
                <th colspan="2">Action</th>
              </tr>
            </thead>
            <tbody>
              <!--For each request-->
              @foreach($item_requests as $item_request)
              <!--If the request was made by the uesr -->
              @if( $item_request->requested_by == Auth::user()->id)
              <tr>
                <td>{{ $item_request['item_requested'] }}</td>
                <td>{{ $item_request['state'] }}</td>

                <td>
                  <a href="{{ action('ItemRequestController@show', $item_request['id']) }}" class="btn btn-primary">Details</a>
                  <!--If request has not been resolved-->
                  @if( $item_request->state != "Open" )
                  <form action="{{ action('ItemRequestController@destroy', $item_request['id']) }}" method="POST">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <button class="btn btn-danger" type="submit">Delete</button>
                  </form>
                  @endif
                </td>
              </tr>
              @endif
              @endforeach
            </tbody>
            @endif
          </table>
        </div>
        @endguest
      </div>
    </div>
  </div>
</div>
@endsection

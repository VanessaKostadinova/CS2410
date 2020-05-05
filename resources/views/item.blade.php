@extends('layouts.app')
@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <!--If user is not logged in -->
        @guest
        <div class="card-body">
          <p>Please register to view item.</p>
          <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        </div>
        @else
        <div class="card-header">
          <b>Item</b>
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

        <div class="card-body">
          <table class="table table-striped" border="1" >
            <tr>
              <th>Item Name</th>
              <td>{{ $item->name }}</td>
            </tr>
            <tr>
              <th>Item Number</th>
              <td>{{ $item->id }}</td>
            </tr>
            <tr>
              <th>Item Type</th>
              <td>{{ $item->category }}</td>
            </tr>
            <tr>
              <th>Item Colour</th>
              <td>{{ $item->colour }}</td>
            </tr>
            <tr>
              <th>Description</th>
              <td style="max-width:150px;">{{ $item->description }}</td>
            </tr>
            <!--If item has an image-->
            @if( $item->image != 'noimage.jpg' && $item->image != '' && $item->image != null)
            <tr>
              <td colspan="2">
                <img style="width:100%;height:100%" src="{{ asset('storage/images/'.$item->image) }}">
              </td>
            </tr>
            @endif
            <tr>
              <td colspan="2">
                <!--If user is an admin or if they made the item-->
                @if ((Auth::user()->admin == 1 | Auth::user()->id == $item['found_by']) && $item->state == "Open")
                <a href="{{ action('ItemController@edit', $item['id']) }}" class="btn btn-primary">Edit</a>
                <form action="{{ action('ItemController@destroy', $item['id']) }}" method="POST">
                  @csrf
                  <input name="_method" type="hidden" value="DELETE">
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
                @endif
              </td>
            </tr>
          </table>
        </div>
        <!--If the item was not made by the user -->
        @if (Auth::user()->id != $item['found_by'])
        <div class="card-header">
          <b>Request</b>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ url('requests') }}" enctype="multipart/form-data">
            @csrf
            <table class="table table-striped" border="1" >
              <tr>
                <th>Description</th>
                <th><textarea name="description" maxlength="500" rows="10" cols="40"></textarea></th>
                <input type="hidden" name="item_requested" value="{{ $item->id }}" />
              </tr>
              <th>Actions</th>
              <th><input type="submit" class="btn btn-primary" />
                <input type="reset" class="btn btn-danger" /></th>
              </tr>
            </table>
          </form>
        </div>
        @endif
        @endguest
      </div>
    </div>
  </div>
</div>
@endsection

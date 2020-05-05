@extends('layouts.app')
@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-14">
      <div class="card">
        <div class="card-header">Filter</div>
        <div class="card-body">
          <form action="{{ route('items.index') }}">
            <div class="row">
              <!--Filter section -->
              <select name="sort_by" value="{{ $sort_by }}">
                @foreach(['id', 'colour', 'date_found', 'name'] as $col)
                <option @if($col == $sort_by) selected @endif value="{{ $col }}"> {{ ucfirst($col) }}</option>
                @endforeach
              </select>

              <select name="order_by" value="{{ $order_by }}">
                @foreach(['asc', 'desc'] as $order)
                <option @if($order == $order_by) selected @endif value="{{ $order }}"> {{ ucfirst($order) }}</option>
                @endforeach
              </select>
              <button type="submit" class="btn btn-primary">Filter</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-14">
        <div class="card">
          <div class="card-header">Display all items</div>
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
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Category</th>
                  <th>Colour</th>
                  <th>Date Found</th>
                  <!--If user is a guest-->
                  @guest
                  <th colspan="1">Action</th>
                  <!--If user is not a guest-->
                  @else
                  <th colspan="3">Action</th>
                  @endguest
                </tr>
              </thead>
              <tbody>
                <!--For each item -->
                @foreach($items as $item)
                <tr>
                  <td>{{ $item['name'] }}</td>
                  <td>{{ $item['category'] }}</td>
                  <td>{{ $item['colour'] }}</td>
                  <td>{{ $item['date_found'] }}</td>
                  <!--If guest, as to register -->
                  @guest
                  <td><a href="{{ route('register') }}" class="btn btn-primary">Register</a></td>
                  <!--Else, let them view details -->
                  @else
                  <td><a href="{{ action('ItemController@show', $item['id']) }}" class="btn btn-primary">Details</a></td>
                  <!--If the user is an admin or created the item they can edit it -->
                  @if (Auth::user()->admin == 1 | Auth::user()->id == $item['found_by'])
                  <td><a href="{{ action('ItemController@edit', $item['id']) }}" class="btn btn-primary">Edit</a></td>
                  <td>
                    <form action="{{ action('ItemController@destroy', $item['id']) }}" method="POST">
                      @csrf
                      <input name="_method" type="hidden" value="DELETE">
                      <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                  </td>
                  @endif
                  @endguest
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection

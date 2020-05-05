@extends('layouts.app')
@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header">
          <b>Edit Item</b>
        </div>
        @guest
        <div class="card-body">
          <p>Please register to edit item.</p>
          <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        </div>
        @else

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
          <form method="POST" action="{{ route('items.update', $item->id) }}"  enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <table class="table table-striped" border="1" >
              <tr>
                <th>Name</th>
                <th><input name="name" type="text" value="{{ $item->name }}" required /></th>
              </tr>
              <tr>
                <th>Category</th>
                <th>
                  <select name="category" value="{{ $item->category }}">
                    @foreach(['Phone', 'Pet', 'Jewellery'] as $opt)
                    <option @if($opt == $item->category) selected @endif value="{{ $opt }}"> {{ ucfirst($opt) }}</option>
                    @endforeach
                  </select>
                </th>
              </tr>
              <tr>
                <th>Colour</th>
                <th>
                  <select name="colour" value="{{ $item->colour }}">
                    @foreach(['Black', 'White', 'Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Pink','Orange', 'None'] as $opt)
                    <option @if($opt == $item->colour) selected @endif value="{{ $opt }}"> {{ ucfirst($opt) }}</option>
                    @endforeach
                  </select>
                </th>
              </tr>
              <tr>
                <th>Description</th>
                <th><textarea name="description" maxlength="500" rows="10" cols="40">{{ $item->description }}</textarea></th>
              </tr>
              <tr>
                <th>Date Found</th>
                <th><input name="date_found" type="date" value="{{ $item->date_found }}" required /></th>
              </tr>
              <tr>
                <th>Location Found</th>
                <th><input name="location_found" type="text" value="{{ $item->location_found }}" required /></th>
              </tr>
              <tr>
                <th>Image</th>
                <th><input name="image" type="file" name="image" value="{{ $item->image }}"/></th>
              </tr>
              <tr>
                <th>Actions</th>
                <th><input type="submit" class="btn btn-primary" />
                  <input type="reset" class="btn btn-danger" /></th>
                </tr>
              </table>
            </form>
          </div>
        </div>
        @endguest
      </div>
    </div>
  </div>
  @endsection

@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10 col-sm-12">
      <div class="card">
        <div class="card-header">
          Register Items
        </div>
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
            <form method="POST" action="{{ url('items') }}" enctype="multipart/form-data">
              @csrf
              <table class="table table-striped" border="1" >
              <tr>
                <th>Name</th>
                <th><input name="name" type=text required /></th>
              </tr>
              <tr>
                <th>Category</th>
                <th>
                <select id="category" name="category">
                  <option value="phone">Phone</option>
                  <option value="pet">Pet</option>
                  <option value="jewellery">Jewellery</option>
                </select>
                </th>
              </tr>
              <tr>
                <th>Colour</th>
                <th>
                  <select id="colour" name="colour">
                    <option value="Black">Black</option>
                    <option value="White">White</option>
                    <option value="Red">Red</option>
                    <option value="Blue">Blue</option>
                    <option value="Yellow">Yellow</option>
                    <option value="Green">Green</option>
                    <option value="Purple">Purple</option>
                    <option value="Pink">Pink</option>
                    <option value="Orange">Orange</option>
                    <option value="None">None</option>
                  </select>
                </th>
              </tr>
              <tr>
                <th>Description</th>
                <th><textarea name="description" maxlength="500" rows="10" cols="40"></textarea></th>
              </tr>
              <tr>
                <th>Date Found</th>
                <th><input name="date_found" type="date" required /></th>
              </tr>
              <tr>
                <th>Location Found</th>
                <th><input name="location_found" type="text" required /></th>
              </tr>
              <tr>
                <th>Image</th>
                <th><input name="image" type="file" name="image" /></th>
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
    </div>
  </div>
</div>
@endsection

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Item;

class ItemController extends Controller
{

  //Opens view to create a new item
  public function create(){
    return view('create_item');
  }

  //Stores a new item
  public function store(Request $request){
    //Validates vital info
    $item = $this->validate(request(), [
      'category' => 'required',
      'colour' => 'required',
      'name' => 'required',
      'date_found' => 'required',
      'location_found' => 'required',
        'image' => 'sometimes|image|mimes:jpg,jpeg,png,gif|max:1024'
    ]);

    //If user entered an image
    if($request->hasFile('image')){
      $fileNameWithExt = $request->file('image')->getClientOriginalName();
      $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
      $extension = $request->file('image')->getClientOriginalName();
      $fileNameToStore = $filename . '_' . time() . '.' . $extension;

      $path = $request->file('image')->storeAs('public/images', $fileNameToStore);
    }
    else{
      $fileNameToStore = 'noimage.jpg';
    }

    $item = new Item;
    $item->name = $request->input('name');
    $item->category = $request->input('category');
    $item->colour = $request->input('colour');
    $item->description = $request->input('description');
    $item->date_found = $request->input('date_found');
    $item->location_found = $request->input('location_found');
    $item->state = 'Open';
    $item->found_by = Auth::user()->id;

    $item->save();
    return back()->with('success', 'Item has been added');
  }

  //Opens view to see all items
  public function index(Request $request){
    $sort_by = 'id';
    $order_by = 'desc';
    $query_by = null;

    if($request->has('sort_by')){
      $sort_by = $request->query('sort_by');
    }
    if($request->has('order_by')){
      $order_by = $request->query('order_by');
    }
    if($request->has('query_by')){
      $query_by = $request->query('query_by');
    }

    $items = Item::orderBy($sort_by, $order_by)->get();
    return view('items', compact('items', 'order_by', 'sort_by', 'query_by'));
  }

  //Opens view to show singe item
  public function show($item_key){
    $item = Item::where('id', $item_key)->firstOrFail();

    return view('item', [
      'item' => $item
    ]);
  }

  //Opens page to edit existing item
  public function edit($item_key){
    $item = Item::where('id', $item_key)->firstOrFail();
    //If item has been requested
    if($item->state != "Open"){
      return redirect()->back()->withErrors(['Item cannot be edited at this time.']);
    }
    else{
      $item = Item::where('id', $item_key)->firstOrFail();
      return view('edit', compact('item'));
    }
  }

  //Updates existing item
  public function update(Request $request, $item_key){
    $item = Item::where('id', $item_key)->firstOrFail();

    //If item has been requested
    if($item->state != "Open"){
      return redirect()->back()->withErrors(['Item cannot be edited at this time.']);
    }

    else{
      $this->validate(request(), [
        'name'=>'required',
        'date_found'=>'required',
        'location_found'=>'required',
          'image' => 'sometimes|image|mimes:jpg,jpeg,png,gif|max:1024'
      ]);

      $item->name = $request->input('name');
      $item->category = $request->input('category');
      $item->colour = $request->input('colour');
      $item->description = $request->input('description');
      $item->date_found = $request->input('date_found');
      $item->location_found = $request->input('location_found');
      $item->updated_at = now();

      //If user entered an image
      if($request->hasFile('image')){
        $fileNameWithExt = $request->file('image')->getClientOriginalName();
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('image')->getClientOriginalName();
        $fileNameToStore = $filename . '_' . time() . '.' . $extension;

        $path = $request->file('image')->storeAs('public/images', $fileNameToStore);
      }
      else{
        $fileNameToStore = 'noimage.jpg';
      }

      $item->save();

      return redirect('items')->with('success', 'Item has been updated.');
    }
  }

  //Destroys an item
  public function destroy($item_key){
    $item = Item::where('id', $item_key)->firstOrFail();

    //If item does not exist
    if(!$item){
      return redirect('items')->withErrors(['Item was not found.']);
    }

    //If item has been requested
    if($item->state != "Open"){
      return redirect()->back()->withErrors(['Item has been requested or claimed.']);
    }

    //If user is not an admin and did not create the item
    else if(Auth::user()->admin != 1 && $item->created_by != Auth::user()->id){
      return redirect()->back()->withErrors(['You cannot perform this action.']);
    }
    else{
      $item->delete();
      return redirect('items')->with('success', 'Item has been deleted.');
    }
  }
}

<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\ItemRequest;
use App\Item;
use App\User;
use Mail;

class ItemRequestController extends Controller
{
  //Updates Request State
  //Updates Item State
  //Sends Email
  public function resolve_request($request_id, $new_state){
    //Validate new state
    if($new_state == "Approved" | $new_state == "Denied"){
      $item_request = ItemRequest::where('id', $request_id)->firstOrFail();

      //If this user submitted the request
      if($item_request->requested_by == Auth::user()->id){
        return redirect()->back()->withErrors(['You cannot perform this action.']);
      }

      //If user is admin
      else if(Auth::user()->admin == 1){

        $item = Item::where('id', $item_request->item_requested)->firstOrFail();
        $user = User::where('id', $item_request->requested_by)->firstOrFail();

        //If item has been claimed already
        if($item->state == 'Claimed'){
          return redirect()->back()->withErrors(['Item has already been claimed.']);
        }

        else{
          //If request has been approved
          if($new_state == "Approved"){
            $item->state = "Claimed";
            $item->save();
          }
          $item_request->state = $new_state;
          $item_request->updated_at = now();
          $item_request->save();

          $email = $user->email;
          $data = array('name'=>"$user->name",
          'status'=>"$item_request->state");

          //Sends email
          Mail::send(['text'=>'mail'], $data, function($message) use($email){
            $message->to($email, 'Botus')->subject('Your Item Request Has Been Reviewed');
            $message->from('botusmcbotface@gmail.com','FiLo');
          });

          $item_requests = ItemRequest::all();
          return view('requests', [
            'item_requests' => $item_requests
          ]);
        }
      }

      else{
        return redirect()->back()->withErrors(['You cannot perform this action.']);
      }
    }

    else{
      return redirect()->back()->withErrors(['Please enter valid state.']);
    }
  }

  //Opens create view
  public function create(){
    return view('create_request');
  }

  //Opens view for all requests
  public function index(){
    $item_requests = ItemRequest::all();
    return view('requests', [
      'item_requests' => $item_requests
    ]);
  }

  //Updates a request
  public function update(Request $request, $request_key){
    $item_request = ItemRequest::where('id', $request_key)->firstOrFail();
    //If user is a guest
    if(Auth::guest()){
      return redirect('welcome')->withErrors(['You cannot perform this action.']);
    }
    //If the user did not make the request
    else if(Auth::user()->id != $item_request->requested_by){
      return redirect('requests')->withErrors(['You cannot perform this action.']);
    }
    else{
      $this->validate(request(), [
        'description'=>'required',
      ]);

      $item_request->description = $request->input('description');
      $item_request->updated_at = now();

      $item_request->save();

      return redirect()->back()->with('success', 'Request has been updated.');
    }
  }

  //Stores a new request
  public function store(Request $request){
    //If user is a guest
    if(Auth::guest()){
      return redirect('items')->withErrors(['You cannot perform this action.']);
    }
    //Validate vital information
    $item_request = $this->validate(request(), [
      'description' => 'required',
      'item_requested' => 'required'
    ]);

    $item = Item::where('id', $request->input('item_requested'))->firstOrFail();

    //If item does not exist
    if(!$item){
      return redirect('items')->withErrors(['Item was not found.']);
    }
    else{
      $item_requests = ItemRequest::where('item_requested', $request->input('item_requested'))->get();
      //Check if same request already exists.
      foreach ($item_requests as $item_request){
        if($item_request->requested_by == Auth::user()->id){
          return redirect()->back()->withErrors(['Request already exists']);
        }
      }
      $item_request = new ItemRequest;
      $item_request->item_requested = $request->input('item_requested');
      $item_request->requested_by = Auth::user()->id;
      $item_request->state = 'Open';
      $item_request->description = $request->input('description');
      $item_request->save();
      return redirect('items')->with('success', 'Request has been submitted');
    }
  }

  //Opens view to show single request
  public function show($request_key){
    $item_request = ItemRequest::where('id', $request_key)->firstOrFail();
    //If the user didn't make it or is not an admin
    if(Auth::user()->id != $item_request->requested_by && Auth::user()->admin != 1){
      return redirect('requests')->withErrors(['You cannot perform this action.']);
    }

    $user = User::where('id', $item_request->requested_by)->firstOrFail();
    $user_email = $user->email;
    return view('request', [
      'item_request' => $item_request,
      'user_email'=> $user_email
    ]);
  }

  //Destroys request
  public function destroy($request_key){
    $item_request = ItemRequest::where('id', $request_key)->first();

    //If request doesn't exist
    if(!$item_request){
      return redirect('requests')->withErrors(['Request was not found.']);
    }
    //If request is unresolved and user is an admin and did not create it
    else if($item_request->state == "Open" && Auth::user()->admin != 1 && $item_request->requested_by != Auth::user()->id){
      return redirect('requests')->withErrors(['Please resolve request before deleting.']);
    }
    //If user didn't create it and is not admin
    else if($item_request->requested_by != Auth::user()->id && Auth::user()->admin != 1){
      return redirect()->back()->withErrors(['You cannot perform this action.']);
    }

    else{
      $item_request->delete();
      return redirect('requests')->with('success', 'Request has been deleted.');
    }
  }
}

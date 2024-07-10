<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function __construct()
     {
 
 
         $this->authorizeResource(User::class);
 
         $this->middleware(['auth']);
     }

    public function index()
    {   
        
        $users =  User::
        latest()->paginate(10);

        if($users->lastPage() >= request('page')){
            return view('users.index',compact('users'));
        }
        return to_route('users.index',['page'=>$users->lastPage()]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());
        $request->session()->flash('message','Created profile Sucessfully');
        return to_route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show( User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $page = request('page');
        return view('users.edit',compact('user','page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        $request->session()->flash('message','Update profile Sucessfully');
        return to_route('users.edit',$user->id);
        // return to_route('users.index',['page'=>request('page')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        request()->session()->flash('message','User profile deleted Sucessfully');
        
        return to_route('users.index',['page'=>request('page')]);
    }
}

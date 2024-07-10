@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                @include('users.links')

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        
                    @endif
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @if($users->total()>0)
                            @foreach ($users as $user )
                            <tr>
                                <th scope="row">{{$user->id}}</th>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{ucfirst($user->role)}}</td>
                                <td>
                                    <div >
                                    <a href="{{ route('users.edit', [$user->id]) }}?page={{request('page')}}" class="">
                                        <img
                                            src="assets/img/icons/edit.svg" alt="img" class="icon-adjustment">
                                    </a>
                                    <form id="pdelete_from_{{$user->id}}" action="{{ route('users.destroy', $user->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="page" id="page" value="{{ request('page') }}">
                                        {{-- <button class="btn btn-danger"> Delete</button> --}}

                                        <a href="javascript:void(0);"  data-bs-toggle="tooltip" data-bs-placement="top" title="User deleted">
                                            <img
                                            src="assets/img/icons/delete.svg" alt="img" class="icon-adjustment _delete_p" data-id="{{$user->id}}">
                                        </a>  

                                    </form>
                                </div>
                                </td>
                              </tr>

                            
                            @endforeach
                          
                            @else
                            <tr>
                                
                                <td colspan="4">No record found</td>
                                
                              </tr>

                              @endif
                        </tbody>
                      </table>
                      <div class="container">
                        {{ $users->onEachSide(5)->links()}}
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                {{-- <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div> --}}
                <div class="card-body">
                    <form action="{{route('post.add')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            @if(Session::has('success'))
                                <div class="alert alert-success show" role="alert">
                                    <strong>Success!</strong> {{Session::get('success')}}
                                </div>
                            @endif
                            @if(Session::has('error'))
                                <div class="alert alert-danger show" role="alert">
                                    <strong>Error!</strong> {{Session::get('error')}}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Post Title</label>
                                    <input type="text" class="form-control" name="title" placeholder="Post Title" value="@if(isset($post)) {{$post->title}} @endif" required>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="">Post Image</label>
                                    <input type="file" class="form-control" name="image" >
                                    @if(isset($post)) 
                                    <img src="{{asset('post/'.$post->image)}}" width="50" alt=""> 
                                    @endif
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="">Post Description</label>
                                    <textarea name="description" id="description" class="form-control">@if(isset($post)) {{$post->description}} @endif</textarea>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="@if(isset($post)) {{$post->id}} @endif">
                            @if(isset($post)) 
                                <button class="btn btn-info mt-2">Update</button>
                            @else
                                <button class="btn btn-info mt-2">Create</button>
                            @endif
                        </div>
                    </form>
                </div>

                @if(!isset($post))
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="example">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $value)
                                    <tr>
                                        <td>{{$value->title}}</td>
                                        <td><img src="{{asset('post/'.$value->image)}}" alt="" width="100"></td>
                                        <td>{{$value->description}}</td>
                                        <td>
                                            <a href="{{route('edit',$value->id)}}" class="btn btn-info">Edit</a> 
                                            <a href="{{route('destroy',$value->id)}}" onclick="return confirm('Are you sure!! You want to delete')" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection



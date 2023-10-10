@extends('layouts.app')

<style>
    .user-profile{
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-items: center;
    }
    .user_name{
        margin-left: 10px;
    }
    .post-img{
        width: 100%;
    }
    .likes .fa{
        font-size: 30px;
        padding: 5px;
        cursor: pointer;
    }
    .hide{
        display: none !important;
    }
    .show{
        display: block;
    }

    .fa-thumbs-up{
        color: blue;
    }
</style>

@section('content')
    <main>

        <div class="container">

            <div class="gallery">
                <div class="row">
                @foreach ($posts as $item)
                <div class="card my-3">
                    <div class="card-body">
                    <div class="col-md-12">
                        <div class="post-container">
                            <div class="post-row">
                                <div class="user-profile">
                                    <img width="60" height="60" style="border-radius: 50%" src="{{asset('images/'.$item->user_image)}}">
                                    <div>
                                        <h4 class="user_name">{{$item->name}}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="posts m-2">
                                <div class="card-body">
                                    <h3 class="post-text">{{$item->title}} </h3>
                                    <p>{!!$item->description!!}</p>
                                    <img src="{{asset('post/'.$item->image)}}" class="post-img">
                                    <p class="likeCommentCount{{$item->id}}">Like: {{$item->totalLike}}, Comments: {{$item->totalComments}}</p>
                                    <div class="likes">
                                        <i class="fa fa-thumbs-o-up like{{$item->id}} @if($item->isLiked) hide @endif" onclick="like({{$item->id}})" aria-hidden="true"></i>
                                        <i class="fa fa-thumbs-up unlike{{$item->id}} @if(!$item->isLiked) hide @endif" onclick="unlike({{$item->id}})" aria-hidden="true"></i>
                                        <i class="fa fa-commenting" onclick="showComment({{$item->id}})" aria-hidden="true"></i>
                                        <div class="comments{{$item->id}} hide">
                                            @foreach($item->getComment as $comment)
                                            <div class="user-profile mt-2">
                                                <img width="30" height="30" style="border-radius: 50%" src="{{asset('images/'.$comment->image)}}">
                                                <div>
                                                    <h6 class="user_name"><b>{{$comment->name}}: </b> {{$comment->comment}}</h6>
                                                </div>
                                                <span style="position: absolute; right:60px; cursor: pointer; color:blue" onclick="getReply({{$item->id}},{{$comment->id}})">Reply</span>
                                            </div>
                                            <div class="subcomment{{$comment->id}} mx-5 hide">
                                                <div class="subCommentDetails{{$comment->id}}">
                                                    
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <textarea name="" class="form-control mt-2 commmentText{{$item->id}}-{{$comment->id}}" placeholder="Reply to comment"></textarea>
                                                    <button class="btn btn-info mx-2" onclick="addComment({{$item->id}}, {{$comment->id}})">Send</button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <textarea name="" class="form-control mt-2 commmentText{{$item->id}}" placeholder="Comment something..."></textarea>
                                            <button class="btn btn-info mx-2" onclick="addComment({{$item->id}})">Send</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                @endforeach
            </div>
            </div>
            <!-- End of gallery -->

        </div>
        <!-- End of container -->

    </main>
@endsection

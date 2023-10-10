<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="//cdn.ckeditor.com/4.22.1/basic/ckeditor.js"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('posts') }}">
                    Home
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('home') }}">
                                        {{ __('Dashboard') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>

        $(document).ready(function() {
            CKEDITOR.replace('description');
        });
        
        new DataTable('#example');

        function like(post_id){
            $.ajax({
                url: '{{route('likePost')}}',
                type:"post",
                data: {
                '_token': '{{csrf_token()}}',
                'post_id': post_id
                },
                success: function(response){
                    var result = JSON.parse(response);
                    $('.unlike'+post_id).removeClass('hide');
                    $('.like'+post_id).addClass('hide');
                    $('.likeCommentCount'+post_id).html('Like: '+result.likes+', Comments: '+result.comment);
                }
            })
        }

        function unlike(post_id){
            $.ajax({
                url: '{{route('unlikePost')}}',
                type:"post",
                data: {
                '_token': '{{csrf_token()}}',
                'post_id': post_id
                },
                success: function(response){
                    var result = JSON.parse(response);
                    $('.unlike'+post_id).addClass('hide');
                    $('.like'+post_id).removeClass('hide');
                    $('.likeCommentCount'+post_id).html('Like: '+result.likes+', Comments: '+result.comment);
                }
            })
        }

        function showComment(post_id){
            if($('.comments'+post_id).hasClass('hide')){
                $('.comments'+post_id).removeClass('hide');
            }else{
                $('.comments'+post_id).addClass('hide');
            }
        }

        function addComment(post_id, parent_comment_id = 0){
            if(parent_comment_id == 0){
                var comment = $('.commmentText'+post_id).val();
            }else{
                var comment = $('.commmentText'+post_id+'-'+parent_comment_id).val();
            }
            if(comment != ''){
                $.ajax({
                    url: '{{route('addComment')}}',
                    type:"post",
                    data: {
                    '_token': '{{csrf_token()}}',
                    'post_id': post_id,
                    'comment': comment,
                    'parent_comment_id': parent_comment_id
                    },
                    success: function(response){
                        var result = JSON.parse(response);
                        $('.commmentText'+post_id).val('');
                        $('.likeCommentCount'+post_id).html('Like: '+result.likes+', Comments: '+result.comment);
                        if(parent_comment_id == 0){
                            $('.comments'+post_id).removeClass('hide');
                            $('.comments'+post_id).append(result.html);
                        }else{
                            $('.commmentText'+post_id+'-'+parent_comment_id).val('');
                            $('.subCommentDetails'+parent_comment_id).append(result.subcomment);
                        }
                    }
                })
            }
        }

        function getReply(post_id, comment_id){
            $.ajax({
                url: '{{route('getReply')}}',
                type:"post",
                data: {
                '_token': '{{csrf_token()}}',
                'post_id': post_id,
                'comment_id': comment_id
                },
                success: function(response){
                    $('.subcomment'+comment_id).removeClass('hide')
                    $('.subCommentDetails'+comment_id).html(response);
                }
            })
        }

    </script>
</body>
</html>

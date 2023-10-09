<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->data['posts'] = Post::where('user_id',Auth::user()->id)->get();
        return view('home',$this->data);
    }

    public function posts(){
        $this->data['posts'] = Post::get();
        return view('welcome',$this->data);
    }
}

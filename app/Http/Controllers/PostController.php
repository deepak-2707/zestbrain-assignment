<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(empty($request->id)){
            $request->validate([
                'title' => 'required',
                'image' => 'required'
            ]);
    
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('post'), $imageName);
    
            $post = new Post();
            $post->user_id = Auth::user()->id;
            $post->title = $request->title;
            $post->slug = str_replace(' ','-',$request->title);
            $post->image = $imageName;
            $post->description = $request->description;
            if($post->save()){
                return back()->with('success','Post create successfully.');
            }else{
                return back()->with('error','Failed to create post!!!');
            }
        }else{
            $request->validate([
                'title' => 'required'
            ]);
            $post = Post::where('id',$request->id)->first();
            if(!empty($request->image)){
                $imageName = time().'.'.$request->image->extension();  
                $request->image->move(public_path('post'), $imageName);
            }else{
                $imageName = $post->image;
            }
            $post->user_id = Auth::user()->id;
            $post->title = $request->title;
            $post->slug = str_replace(' ','-',$request->title);
            $post->image = $imageName;
            $post->description = $request->description;
            if($post->save()){
                return redirect()->route('home')->with('success','Post updated successfully.');
            }else{
                return back()->with('error','Failed to update post!!!');
            }
        }   
        

    }


    public function edit($id)
    {
        $this->data['post'] = Post::where('id',$id)->first();
        return view('home',$this->data);
    }

    public function destroy($id)
    {
        Post::where('id',$id)->delete();
        return redirect()->route('home')->with('success','Post deleted successfully.');
    }
}

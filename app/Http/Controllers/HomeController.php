<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Likes;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;

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

        $loggedInUserId = Auth::user()->id;

        $this->data['posts'] = Post::select(
            'users.name',
            'users.image as user_image',
            'posts.*',
            \DB::raw('COUNT(likes.post_id) as totalLike'),
            \DB::raw('(SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id AND parent_id = 0) as totalComments'),
            \DB::raw('(COUNT(likes.post_id) > 0 AND MAX(likes.user_id = ?)) as isLiked')
        )
            ->leftJoin('users', 'users.id', '=', 'posts.user_id')
            ->leftJoin('likes', 'likes.post_id', '=', 'posts.id')
            ->groupBy('posts.id', 'users.name', 'users.image')
            ->setBindings([$loggedInUserId])
            ->with('getComment')
            ->orderBy('posts.id','desc')
            ->get();
            // echo "<pre>"; print_r($this->data['posts']); exit;
        return view('welcome',$this->data);
    }

    public function likePost(Request $request){
        $likes = new Likes();
        $likes->user_id = Auth::user()->id;
        $likes->post_id = $request->post_id;
        $likes->save();

        $count = Likes::where('post_id', $request->post_id)->count();
        $countComment = Comments::where(['post_id' => $request->post_id, 'parent_id' => 0])->count();
        print_r(json_encode(array(
            'likes' => $count,
            'comment' => $countComment,
        )));
    }

    public function unlikePost(Request $request){
        Likes::where(['post_id'=> $request->post_id, 'user_id' => Auth::user()->id])->delete();
        $count = Likes::where('post_id', $request->post_id)->count();
        $countComment = Comments::where(['post_id' => $request->post_id, 'parent_id' => 0])->count();
        print_r(json_encode(array(
            'likes' => $count,
            'comment' => $countComment,
        )));
    }

    public function addComment(Request $request){
        $comm = new Comments();
        $comm->user_id = Auth::user()->id;
        $comm->post_id = $request->post_id;
        $comm->parent_id = $request->parent_comment_id;
        $comm->comment = $request->comment;
        $comm->save();

        $countLike = Likes::where('post_id', $request->post_id)->count();
        $countComment = Comments::where(['post_id' => $request->post_id, 'parent_id' => 0])->count();

        $html = '<div class="user-profile mt-2">
                    <img width="30" height="30" style="border-radius: 50%" src="images/'.Auth::user()->image.'">
                    <div>
                        <h6 class="user_name"><b>'.Auth::user()->name.': </b> '.$request->comment.'</h6>
                    </div>
                    <span style="position: absolute; right:60px; cursor: pointer; color:blue" onclick="getReply('.$request->post_id.','.$comm->id.')">Reply</span>
                </div>
                <div class="subcomment'.$comm->id.' mx-5 hide">
                    <div class="subCommentDetails'.$comm->id.'">
                        
                    </div>
                    <div class="d-flex align-items-center">
                        <textarea name="" class="form-control mt-2 commmentText'.$request->post_id.'-'.$comm->id.'" placeholder="Reply to comment"></textarea>
                        <button class="btn btn-info mx-2" onclick="addComment('.$request->post_id.', '.$comm->id.')">Send</button>
                    </div>
                </div>';

        $subcomment = '';
        if($request->parent_comment_id != 0){
            $subcomment .= '<div class="user-profile mt-2">
                        <img width="30" height="30" style="border-radius: 50%" src="images/'.Auth::user()->image.'">
                        <div>
                            <h6 class="user_name"><b>'.Auth::user()->name.': </b> '.$request->comment.'</h6>
                        </div>
                    </div>';
        }

        print_r(json_encode(array(
            'likes' => $countLike,
            'comment' => $countComment,
            'html' => $html,
            'subcomment' => $subcomment
        )));
    }

    public function getReply(Request $request){
        $countComment = Comments::select('comments.*','users.name','users.image')->where(['post_id' => $request->post_id, 'parent_id' => $request->comment_id])
                        ->join('users','users.id','=','comments.user_id')->get();
        $html = '';
        foreach ($countComment as $key => $value) {
            $html .= '<div class="user-profile mt-2">
                        <img width="30" height="30" style="border-radius: 50%" src="images/'.$value->image.'">
                        <div>
                            <h6 class="user_name"><b>'.$value->name.': </b> '.$value->comment.'</h6>
                        </div>
                    </div>';
        }

        return $html;
    }
}

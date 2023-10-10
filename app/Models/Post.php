<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function getComment(){
        return $this->hasMany('App\Models\Comments','post_id', 'id')->join('users','users.id','=','comments.user_id')->select('comments.*', 'users.name', 'users.image')->where('comments.parent_id',0);
    }
}

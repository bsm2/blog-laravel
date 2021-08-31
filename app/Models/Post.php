<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use HasFactory;
    use Sluggable;
    protected $fillable =['title','slug','description','image','user_id'];

    public function user(){
        return $this->BelongsTo(User::class);
    }

    public function sluggable(){
        return[
            'slug'=>[
                'source'=>'title'
            ]
            ];
    }
}

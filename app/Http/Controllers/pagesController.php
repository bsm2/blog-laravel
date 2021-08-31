<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class pagesController extends Controller
{
    public function index(){
        $post= Post::orderBy('updated_at','DESC')->first();
        return view('index')->with('post',$post);;
    }
}

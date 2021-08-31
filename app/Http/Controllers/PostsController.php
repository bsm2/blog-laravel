<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Cviebrock\EloquentSluggable\Services\SlugService;


class PostsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',['except'=>['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts= Post::orderBy('updated_at','DESC')->get();
        return view('blog')->with('posts',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('addBlog');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'image'=>'required|mimes:jpg,png,jpeg'
        ]);

        $imgName=rand().preg_replace('/\s+/', '', $request->title).'.'.$request->image->extension();
        $request->image->move(public_path('images'),$imgName);
        $slug = SlugService::createSlug(Post::class,'slug',$request->title);

        Post::create([
            'title'=>$request->input('title'),
            'description'=>$request->input('description'),
            'slug'=>$slug,
            'image'=>$imgName,
            'user_id'=>auth()->user()->id
        ]);

        return redirect('/blog')->with('msg','POST ADDED');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {

        return view('post')->with('post',Post::where('slug',$slug)->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('editPost')->with('post',Post::where('id',$id)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'required',
        ]);
 
        $slug = SlugService::createSlug(Post::class,'slug',$request->title);
        $post = Post::where('id',$id)->first();
        
        if ($request->image !== null) {
            $imgName=rand().preg_replace('/\s+/', '', $request->title).'.'.$request->image->extension();
            $request->image->move(public_path('images'),$imgName);
        }else{
            $imgName=$post->image;
        }
        Post::where('id',$id)->update([
            'title'=>$request->input('title'),
            'description'=>$request->input('description'),
            'slug'=>$slug,
            'image'=>$imgName,
            'user_id'=>auth()->user()->id
        ]);

        return redirect('/blog')->with('msg','POST UPDATED');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Post::where('id',$id)->delete();
        return redirect('/blog')->with('msg','POST DELETED');
    }
}

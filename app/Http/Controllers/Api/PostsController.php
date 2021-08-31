<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostsResource;
use App\Http\Requests\PostsRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use Cviebrock\EloquentSluggable\Services\SlugService;

class PostsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api',['except'=>['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts= Post::orderBy('updated_at','DESC')->get();
        return  PostsResource::collection($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $newPost=Post::create([
            'title'=>$request->input('title'),
            'description'=>$request->input('description'),
            'slug'=>$slug,
            'image'=>$imgName,
            'user_id'=>auth()->user()->id
        ]);

        return new PostsResource($newPost);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post=Post::where('slug',$slug)->first();
        return new PostsResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostsRequest $request, $id)
    { 
        
        $slug = SlugService::createSlug(Post::class,'slug',$request->title);
        $oldPost = Post::where('id',$id)->first();
        
        if ($request->image !== null) {
            $imgName=rand().preg_replace('/\s+/', '', $request->title).'.'.$request->image->extension();
            $request->image->move(public_path('images'),$imgName);
        }else{
            $imgName=$oldPost->image;
        }
        Post::where('id',$id)->update([
            'title'=>$request->input('title'),
            'description'=>$request->input('description'),
            'slug'=>$slug,
            'image'=>$imgName,
            'user_id'=>auth()->user()->id
        ]);

        return new PostsResource(['msg'=>"done"]);
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
        return response('post deleted',204);
    }
}

@extends('layouts.app')

@section('content')
    <div class="w-4/5 m-auto text-center">
        <div class="py-15 border-b border-gray-200">
            <h1 class="text-6xl text-blue-900">
                Blog Posts
            </h1>
        </div>
    </div>
    {{-- {{$posts}} --}}
    @if(session()->has('msg'))
        <div class="px-6 py-4 border-t">
            <p class="border rounded-lg p-4 bg-blue-200">
                {{session()->get('msg')}}
            </p>
        </div>
    @endif
    @if (Auth::check())
        <div class="pt-15 w-4/5 m-auto">
            <a 
                href="/blog/create"
                class="bg-blue-500 uppercase bg-transparent text-gray-100 text-xs font-extrabold py-3 px-5 rounded-3xl">
                Add post
            </a>
        </div>
    @endif
   
    @foreach ($posts as $post)
        <div class="sm:grid grid-cols-2 gap-20 w-4/5 mx-auto py-15 border-b border-gray-200">
            <div>
                <img src="{{asset('images/'.$post->image)}}" alt="">
            </div>
            <div>
                <h2 class="text-gray-700 font-bold text-5xl pb-4">
                    {{$post->title}}
                </h2>
                <div class="text-gray-500">
                    By <span class="fontt-bold italic text-gray-800">{{$post->user->name}}</span>, 
                    <small>{{date('JS M Y',strtotime($post->updated_at))}}</small>
                </div>
                <p class="text-xl text-gray-700 pt-8 pb-10 leading-8 font-light">
                    {{ \Illuminate\Support\Str::limit($post->description, 150, $end='...') }}
                </p>
                <a href="/blog/{{$post->slug}}" class="uppercase bg-blue-500 text-gray-100 text-lg font-extraboard py-4 px-4 rounded-3xl">
                    continue reading
                </a>
                @if (isset(Auth::user()->id) && Auth::user()->id == $post->user_id)
                <span class="float-right">
                    <a 
                        href="/blog/{{ $post->id }}/edit"
                        class="text-gray-700 italic hover:text-gray-900 bg-green-200 pb-1 border-b-2 py-2 px-4 rounded-2xl">
                        Edit
                    </a>
                </span>

                <span class="float-right">
                    <form
                        class="text-red-500 pr-3 " 
                        action="/blog/{{ $post->id }}"
                        method="POST">
                        @csrf
                        @method('delete')
                        <button
                            type="submit">
                            Delete
                        </button>

                    </form>
                </span>
            @endif
            </div>
        </div>
    @endforeach
    
@endsection
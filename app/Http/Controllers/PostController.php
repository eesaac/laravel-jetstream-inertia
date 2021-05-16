<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(\request('tag')){
//            $posts = Tag::where('name', \request('tag'))->firstOrFail()->posts;
            $posts = Post::with('tags')->whereHas('tags', function($query) {
                $query->whereName(\request('tag'));
            })
                ->orderBy('id', 'desc')
                ->filter(\Illuminate\Support\Facades\Request::only('search'))
                ->paginate();

                $posts->appends(['tag' => \request('tag')])
                ->links();
        }else{
//            $posts = Post::with('tags')->latest()->get();
            $posts = Post::with('tags')
                ->orderBy('id', 'desc')
                ->filter(\Illuminate\Support\Facades\Request::only('search'))
                ->paginate();
        }



        return Inertia::render('Post/Index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Post/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd(collect($request->tags)->flatten(function ($item){
//            return $item;
//        }));
//        $post->tags()->attach(request('tags'));

        $post = Auth::user()->posts()->create(
            $this->validatePost()
        );

        $tags = $this->createTags($request);
        $post->tags()->attach($tags);

//        foreach ($tags as $tag){
//            $post->tags()->attach($tag);
//        }

        return redirect()->route('post.index')->with('message', "Post created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return Inertia::render('Post/Show', [
            'post' => $post,
            'tags' => $post->tags
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $tags = $post->tags()->pluck('name');

        return Inertia::render('Post/Edit', [
            'post' => $post,
            'tags' => $tags
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
//        $post = Auth::user()->posts()->update(
//            $this->validatePost()
//        );

        $post->update(
            $this->validatePost()
        );

        $tags = $this->createTags($request);
        $post->tags()->sync($tags);

        return redirect()->back()->with('message', 'Post updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('post.index')->with('message', 'Post deleted.');
    }

    protected function validatePost()
    {
        return request()->validate([
            'title' => 'required',
            'tags' => '',
            'body' => 'required'
        ]);
    }

    protected function createTags($request)
    {
        $tags = [];

        if($request->tags){
            foreach ($request->tags as $tag)
            {
                array_push($tags,
                    Tag::firstOrCreate(['name' => $tag])->id
                );
            }
        }

        return $tags;
    }
}

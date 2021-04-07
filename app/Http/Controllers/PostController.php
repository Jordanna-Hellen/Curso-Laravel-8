<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdatePost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(){

        $posts = Post::latest()->paginate(15);

        return view("admin/posts/index", compact("posts"));
    }

    public function create(){

        return view("admin/posts/create");
    }

    public function store(StoreUpdatePost $request){

        $data = $request->all();

        if ($request->image->isValid()) {

            $nameFile = Str::of($request->title)->slug('-').'.'.$request->image->getClientOriginalExtension();

           $image = $request->image->storeAs('posts', $nameFile);
           $data['image'] = $image;
        }

        Post::create($data);

        return redirect()->route("posts/index");
    }

    public function show($id){
        //$post = Post::where('id', $id)->first();
        if(!$post = Post::find($id)){
           return redirect()->route('posts/index');
        }
        return view('admin/posts/show', compact('post'));
    }

    public function destroyd($id){
        if(!$post = Post::find($id)){
            return redirect()->route('posts/index');
        }

        if(Storage::exists($post->image))
                Storage::delete($post->image);

        $post->delete();

        return redirect()
            ->route('posts/index')
            ->with('message', 'Post Deletado com sucesso');
    }

    public function edit($id){
        //$post = Post::where('id', $id)->first();
        if(!$post = Post::find($id)){
           return redirect()->route('posts/index');
        }
        return view('admin/posts/edit', compact('post'));
    }

    public function update(StoreUpdatePost $request, $id){
        //$post = Post::where('id', $id)->first();
        if(!$post = Post::find($id)){
           return redirect()->route('posts/index');
        }

        $data = $request->all();

        if ($request->image && $request->image->isValid()) {

            if(Storage::exists($post->image))
                Storage::delete($post->image);

            $nameFile = Str::of($request->title)->slug('-').'.'.$request->image->getClientOriginalExtension();

           $image = $request->image->storeAs('posts', $nameFile);
           $data['image'] = $image;
        }

        $post->update();

        return redirect()
            ->route('posts/index')
            ->with('message', 'Post Atualizado com sucesso');
    }

    public function search(Request $request){

        $filters = $request->all();

        $posts = Post::where('title', '=', $request->search)
                        ->orWhere('content', 'LIKE', "%{$request->search}%")
                        ->paginate();

        return view('admin/posts/index', compact('posts', 'filters'));
    }
}

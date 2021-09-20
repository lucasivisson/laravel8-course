<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdatePost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(StoreUpdatePost $request)
    {
        $data = $request->all();

        if($request->image->isValid())
        {
            $nameFile = Str::of($request->title)->slug('-').'.'.$request->image->getClientOriginalExtension();

            $image = $request->image->storeAs('posts', $nameFile);
            $data['image'] = $image;
        }

        Post::create($data);

        return redirect()
                ->route('posts.index')
                ->with('message', 'Post criado com sucesso');
    }

    public function show($id)
    {
        $post = Post::find($id);
        if(!$post) {
            return redirect()->route('posts.index');
        }
        // $post = Post::where('id', $id)->first();

        return view('admin.posts.show', compact('post'));
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if(!$post)
            return redirect()->route('posts.index');

        $post->delete();

        return redirect()
                ->route('posts.index')
                ->with('message', 'Post deletado com sucesso');
    }

    public function edit($id)
    {
        $post = Post::find($id);
        if(!$post)
            return redirect()->back();

        return view('admin.posts.edit', compact('post'));
    }

    public function update(StoreUpdatePost $request, $id)
    {
        $post = Post::find($id);
        if(!$post)
            return redirect()->back();

        $data = $request->all();

        if($request->image != null && $request->image->isValid())
        {
            if(Storage::exists($post->image))
            {
                Storage::delete($post->image);
            }

            $nameFile = Str::of($request->title)->slug('-').'.'.$request->image->getClientOriginalExtension();

            $image = $request->image->storeAs('posts', $nameFile);
            $data['image'] = $image;
        }

        $post->update($data);

        return redirect()
                ->route('posts.index')
                ->with('message', 'Post atualizado com sucesso');
    }

    public function search(Request $request)
    {
        $filters = $request->except('_token');

        // LIKE pesquisa pela correspondencia da palavra, o %% pesquisa tanto no inicio quanto no fim se é igual.
        $posts = Post::where('title', '=', $request->search)
                    ->orWhere('content', 'LIKE', "%{$request->search}%")
                    ->paginate();
        /*
        $posts = Post::where('title', '=', $request->search)
            ->orWhere('content', 'LIKE', "%{$request->search}%")
            ->toSql();
        dd($posts);
        */
        return view('admin.posts.index', compact('posts', 'filters'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use URL;

use App\Models\Page;
use App\Models\Post;
use App\Models\Comment;

class PostController extends Controller
{
    public function postsCategory()
    {
        $page = Page::where('slug', 'news-category')->first();
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);

        return view('pages.posts', compact('page', 'posts'));
    }

    public function postsCategory2()
    {
        $postCategory = Page::where('slug', $page)->first();
        $postCategories = Page::where('slug', 'posts')->get()->toTree();
        $post = Post::where('page_id', $postCategory->id)->paginate(10);

        return view('pages.posts-category', compact('postsCategory', 'posts', 'postsCategories'));
    }

    public function postSingle($page)
    {
        $post = Post::where('slug', $page)->first();
        $next = Post::where('id', '>', $post->id)->oldest('id')->first();
        $prev = Post::where('id', '<', $post->id)->latest('id')->first();
        $posts = Post::where('id', '!=', $post->id)->orderBy('created_at', 'desc')->take(5)->get();

        return view('pages.post', compact('post', 'next', 'prev', 'posts'));
    }

    public function saveComment(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required|min:5|max:500',
        ]);

        $url = explode('posts/', URL::previous());
        $postSingle = Post::where('slug', $url[1])->first();

        if ($request->id == $postSingle->id) {
            $comment = new Comment;
            $comment->parent_id = $request->id;
            $comment->parent_type = 'App\posts';
            $comment->name = \Auth::user()->name;
            $comment->email = \Auth::user()->email;
            $comment->comment = $request->comment;
            // $comment->stars = (int) $request->stars;
            $comment->save();
        }

        if (!$comment) {
            return redirect()->back()->with('status', 'Ошибка!');
        }

        return redirect()->back()->with('status', 'Отзыв добавлен!');
    }
}

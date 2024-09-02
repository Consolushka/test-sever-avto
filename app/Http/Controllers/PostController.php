<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\PaginatedPostsRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function index(PaginatedPostsRequest $request): AnonymousResourceCollection
    {
        return PostResource::collection(
            Post::query()->paginate(perPage: $request->getPerPage(), page: $request->getPage())
        );

    }

    public function show(Post $post): PostResource
    {
        return new PostResource($post->load('comments'));
    }

    public function store(CreatePostRequest $request): PostResource
    {

        $model = new Post();
        $model->title = $request->getTitle();
        $model->description = $request->getDescription();
        $model->save();

        Mail::raw("Пост $model->id был создан", function ($message) {
            $message->to('admin@admin.com')
                ->subject('Создание поста');
        });

        return new PostResource($model);
    }

    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        $post->title = $request->getTitle();
        $post->description = $request->getDescription();
        $post->save();

        return new PostResource($post);
    }

    public function destroy(Post $post): PostResource
    {
        $post->delete();

        return new PostResource($post);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    public function index(Post $post): AnonymousResourceCollection
    {
        return CommentResource::collection($post->comments);
    }

    public function store(Post $post, CreateCommentRequest $request): CommentResource
    {
        $model = new Comment();
        $model->post_id = $post->id;
        $model->content = $request->getContentValue();
        $model->save();

        return new CommentResource($model);
    }

    public function show(Post $post, Comment $comment): CommentResource
    {
        return new CommentResource($comment);
    }

    public function update(Post $post, Comment $comment, UpdateCommentRequest $request): CommentResource
    {
        $comment->content = $request->getContentValue();
        $comment->save();

        return new CommentResource($comment);
    }

    public function destroy(Post $post, Comment $comment): CommentResource
    {
        $comment->delete();

        return new CommentResource($comment);
    }
}

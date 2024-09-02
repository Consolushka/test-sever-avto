<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Comment\CommentResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Post $resource
 */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->resource->id,
            'title'       => $this->resource->title,
            'description' => $this->resource->description,
            'comments'    => CommentResource::collection($this->whenLoaded('comments')),
            'created_at'  => $this->resource->created_at->format('Y-m-d H:i:s'),
            'updated_at'  => $this->resource->updated_at->format('Y-m-d H:i:s'),
            'deleted_at'  => $this->resource->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}

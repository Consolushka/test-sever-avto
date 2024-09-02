<?php

namespace App\Http\Requests\Comment;

trait HasCommentModelRequestTrait
{
    private const CONTENT_FIELD = 'content';

    public function getContentValue(): string
    {
        return $this->validated(self::CONTENT_FIELD);
    }

    private function commentRules(): array
    {
        return [
            self::CONTENT_FIELD => ['required', 'string', 'min:1', 'max:1000'],
        ];
    }

}
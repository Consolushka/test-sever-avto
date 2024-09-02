<?php

namespace App\Http\Requests\Post;

trait HasPostModelRequestTrait
{
    private const TITLE_KEY = 'title';
    private const DESCRIPTION_KEY = 'description';

    public function getTitle(): string
    {
        return $this->validated(self::TITLE_KEY);
    }

    public function getDescription(): string
    {
        return $this->validated(self::DESCRIPTION_KEY);
    }

    private function postRules(): array
    {
        return [
            self::TITLE_KEY => ['required', 'string', 'min:5', 'max:255'],
            self::DESCRIPTION_KEY => ['required', 'string', 'min:10'],
        ];
    }
}
<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

final class PaginatedPostsRequest extends FormRequest
{
    private const PAGE_KEY = 'page';
    private const PER_PAGE_KEY = 'perPage';

    public function getPage(): int
    {
        return $this->validated(self::PAGE_KEY) ?? 1;
    }

    public function getPerPage(): int
    {
        return $this->validated(self::PER_PAGE_KEY) ?? 10;
    }

    public function rules(): array
    {
        return [
            self::PAGE_KEY     => ['nullable', 'integer'],
            self::PER_PAGE_KEY => ['nullable', 'integer'],
        ];
    }
}
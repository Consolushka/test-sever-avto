<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class CommentTests extends TestCase
{
    public function test_it_returns_list(): void
    {
        $generatedComments = [
            [
                'id'         => 1,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-08-31 14:02:34',
                'updated_at' => '2023-08-31 14:02:34',
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-09-30 14:02:34',
                'updated_at' => '2023-09-30 14:02:34',
                'deleted_at' => null,
            ]
        ];

        $generatedPosts = [
            [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ],
            [
                'id'          => 2,
                'title'       => 'Post #2',
                'description' => 'Post #2 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => $generatedComments
            ],
            [
                'id'          => 3,
                'title'       => 'Post #3',
                'description' => 'Post #3 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ]];

        $expectedJson = [
            'data' => $generatedComments
        ];

        $this->seedFakePosts($generatedPosts);

        $response = $this->getJson('api/posts/2/comments')->json();

        self::assertEquals($expectedJson, $response);
    }

    public function test_it_returns_empty_list(): void
    {
        $generatedComments = [
            [
                'id'         => 1,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-08-31 14:02:34',
                'updated_at' => '2023-08-31 14:02:34',
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-09-30 14:02:34',
                'updated_at' => '2023-09-30 14:02:34',
                'deleted_at' => null,
            ]
        ];

        $generatedPosts = [
            [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ],
            [
                'id'          => 2,
                'title'       => 'Post #2',
                'description' => 'Post #2 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => $generatedComments
            ],
            [
                'id'          => 3,
                'title'       => 'Post #3',
                'description' => 'Post #3 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ]];

        $expectedJson = [
            'data' => []
        ];

        $this->seedFakePosts($generatedPosts);

        $response = $this->getJson('api/posts/3/comments')->json();

        self::assertEquals($expectedJson, $response);
    }

    public function test_it_returns_single_comment(): void
    {
        $generatedComments = [
            [
                'id'         => 1,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-08-31 14:02:34',
                'updated_at' => '2023-08-31 14:02:34',
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-09-30 14:02:34',
                'updated_at' => '2023-09-30 14:02:34',
                'deleted_at' => null,
            ]
        ];

        $generatedPosts = [
            [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ],
            [
                'id'          => 2,
                'title'       => 'Post #2',
                'description' => 'Post #2 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => $generatedComments
            ],
            [
                'id'          => 3,
                'title'       => 'Post #3',
                'description' => 'Post #3 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ]];

        $id = 2;

        $expectedJson = [
            'data' => $generatedComments[1]
        ];

        $this->seedFakePosts($generatedPosts);

        $response = $this->getJson("api/posts/2/comments/$id")->json();

        self::assertEquals($expectedJson, $response);
    }

    public function test_it_throws_on_null_comment(): void
    {
        $generatedComments = [
            [
                'id'         => 1,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-08-31 14:02:34',
                'updated_at' => '2023-08-31 14:02:34',
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-09-30 14:02:34',
                'updated_at' => '2023-09-30 14:02:34',
                'deleted_at' => null,
            ]
        ];

        $generatedPosts = [
            [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ],
            [
                'id'          => 2,
                'title'       => 'Post #2',
                'description' => 'Post #2 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => $generatedComments
            ],
            [
                'id'          => 3,
                'title'       => 'Post #3',
                'description' => 'Post #3 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ]];

        $id = 5;

        $this->seedFakePosts($generatedPosts);

        $errorMessage = $this->getJson("api/posts/2/comments/$id")->json('message');

        self::assertEquals("No query results for model [App\Models\Comment] $id", $errorMessage);
    }

    public function test_it_throws_on_deleted_comment(): void
    {
        $generatedComments = [
            [
                'id'         => 1,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-08-31 14:02:34',
                'updated_at' => '2023-08-31 14:02:34',
                'deleted_at' => '2023-08-31 14:02:34',
            ],
            [
                'id'         => 2,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-09-30 14:02:34',
                'updated_at' => '2023-09-30 14:02:34',
                'deleted_at' => null,
            ]
        ];

        $generatedPosts = [
            [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ],
            [
                'id'          => 2,
                'title'       => 'Post #2',
                'description' => 'Post #2 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => $generatedComments
            ],
            [
                'id'          => 3,
                'title'       => 'Post #3',
                'description' => 'Post #3 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ]];

        $id = 1;

        $this->seedFakePosts($generatedPosts);

        $errorMessage = $this->getJson("api/posts/2/comments/$id")->json('message');

        self::assertEquals("No query results for model [App\Models\Comment] $id", $errorMessage);
    }

    public function test_it_validated_content_field(): void
    {
        $generatedComments = [
            [
                'id'         => 1,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-08-31 14:02:34',
                'updated_at' => '2023-08-31 14:02:34',
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-09-30 14:02:34',
                'updated_at' => '2023-09-30 14:02:34',
                'deleted_at' => null,
            ]
        ];

        $generatedPosts = [
            [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ],
            [
                'id'          => 2,
                'title'       => 'Post #2',
                'description' => 'Post #2 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => $generatedComments
            ],
            [
                'id'          => 3,
                'title'       => 'Post #3',
                'description' => 'Post #3 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ]];

        $expectedJson = 'The content field is required.';

        $this->seedFakePosts($generatedPosts);

        $response = $this->postJson("api/posts/2/comments",[
            'content' => ''
        ])->json('message');

        self::assertEquals($expectedJson, $response);
    }

    public function test_it_validated_content_field_max_length(): void
    {
        $generatedComments = [
            [
                'id'         => 1,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-08-31 14:02:34',
                'updated_at' => '2023-08-31 14:02:34',
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-09-30 14:02:34',
                'updated_at' => '2023-09-30 14:02:34',
                'deleted_at' => null,
            ]
        ];

        $generatedPosts = [
            [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ],
            [
                'id'          => 2,
                'title'       => 'Post #2',
                'description' => 'Post #2 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => $generatedComments
            ],
            [
                'id'          => 3,
                'title'       => 'Post #3',
                'description' => 'Post #3 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ]];

        $expectedJson = 'The content field must not be greater than 1000 characters.';

        $this->seedFakePosts($generatedPosts);

        $response = $this->postJson("api/posts/2/comments",[
            'content' => Str::random(1001)
        ])->json('message');

        self::assertEquals($expectedJson, $response);
    }

    public function test_it_creates_comment(): void
    {
        $generatedComments = [
            [
                'id'         => 1,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-08-31 14:02:34',
                'updated_at' => '2023-08-31 14:02:34',
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-09-30 14:02:34',
                'updated_at' => '2023-09-30 14:02:34',
                'deleted_at' => null,
            ]
        ];

        $generatedPosts = [
            [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ],
            [
                'id'          => 2,
                'title'       => 'Post #2',
                'description' => 'Post #2 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => $generatedComments
            ],
            [
                'id'          => 3,
                'title'       => 'Post #3',
                'description' => 'Post #3 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ]];

        $expectedJson = [
            'id'         => 3,
            'post_id'    => 2,
            'content'    => 'Comment #3',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ];

        $this->seedFakePosts($generatedPosts);

        $response = $this->postJson("api/posts/2/comments",[
            'content' => 'Comment #3'
        ])->json('data');

        self::assertEquals($expectedJson, $response);

        $dbComment = Comment::query()->find(3);
        self::assertNotNull($dbComment);
        self::assertEquals('Comment #3', $dbComment->content);
    }

    public function test_it_updates_comment(): void
    {
        $generatedComments = [
            [
                'id'         => 1,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-08-31 14:02:34',
                'updated_at' => '2023-08-31 14:02:34',
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'post_id'    => 2,
                'content'    => 'Comment #1',
                'created_at' => '2023-09-30 14:02:34',
                'updated_at' => '2023-09-30 14:02:34',
                'deleted_at' => null,
            ]
        ];

        $generatedPosts = [
            [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ],
            [
                'id'          => 2,
                'title'       => 'Post #2',
                'description' => 'Post #2 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => $generatedComments
            ],
            [
                'id'          => 3,
                'title'       => 'Post #3',
                'description' => 'Post #3 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
                'comments'    => []
            ]];

        $newContent = 'Comment #22';
        $id = 2;

        $expectedJson = [
            'id'         => 2,
            'post_id'    => 2,
            'content'    => $newContent,
            'created_at' => '2023-09-30 14:02:34',
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'deleted_at' => null,
        ];

        $this->seedFakePosts($generatedPosts);

        $response = $this->patchJson("api/posts/2/comments/$id",[
            'content' => $newContent
        ])->json('data');

        self::assertEquals($expectedJson, $response);

        $dbComment = Comment::query()->find($id);
        self::assertNotNull($dbComment);
        self::assertEquals($newContent, $dbComment->content);
    }

    private function seedFakePosts(array $posts): void
    {
        $comments = array_merge(...array_map(function ($item) {
            return $item['comments'];
        }, array_filter($posts, function ($post) {
            return !empty($post['comments']);
        })));

        $posts = array_map(function ($post) {
            unset($post['comments']);
            return $post;
        }, $posts);

        Post::factory()->createMany($posts);
        Comment::factory()->createMany($comments);
    }

}
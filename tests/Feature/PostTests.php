<?php

namespace Tests\Feature;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostTests extends TestCase
{
    public function test_it_returns_list(): void
    {
        $generatedPosts = [
            [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
            ],
            [
                'id'          => 2,
                'title'       => 'Post #2',
                'description' => 'Post #2 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
            ],
            [
                'id'          => 3,
                'title'       => 'Post #3',
                'description' => 'Post #3 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
            ]];
        $expectedJson = [
            'data'  => $generatedPosts,
            'links' => [
                'first' => 'http://localhost/api/posts?page=1',
                'last'  => 'http://localhost/api/posts?page=1',
                'prev'  => null,
                'next'  => null
            ],
            'meta'  => [
                'current_page' => 1,
                'from'         => 1,
                'last_page'    => 1,
                'links'        => [
                    0 => [
                        'url'    => null,
                        'label'  => '&laquo; Previous',
                        'active' => false
                    ],
                    1 => [
                        'url'    => 'http://localhost/api/posts?page=1',
                        'label'  => '1',
                        'active' => true
                    ],
                    2 => [
                        'url'    => null,
                        'label'  => 'Next &raquo;',
                        'active' => false
                    ]
                ],
                'path'         => 'http://localhost/api/posts',
                'per_page'     => 10,
                'to'           => 3,
                'total'        => 3
            ]
        ];

        $this->seedFakePosts($generatedPosts);

        $response = $this->getJson('api/posts?page=1&size=3')->json();

        self::assertEquals($expectedJson, $response);
    }

    public function test_it_returns_list_wo_deleted_posts(): void
    {
        $generatedPosts = [
            [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
            ],
            [
                'id'          => 2,
                'title'       => 'Post #2',
                'description' => 'Post #2 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => '2023-08-31 14:02:34',
            ],
            [
                'id'          => 3,
                'title'       => 'Post #3',
                'description' => 'Post #3 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => '2023-08-31 14:02:34',
                'deleted_at'  => null,
            ]];

        $expectedJson = [
            'data'  => array_values(array_filter($generatedPosts, function ($item) {
                return $item['deleted_at'] === null;
            })),
            'links' => [
                'first' => 'http://localhost/api/posts?page=1',
                'last'  => 'http://localhost/api/posts?page=1',
                'prev'  => null,
                'next'  => null
            ],
            'meta'  => [
                'current_page' => 1,
                'from'         => 1,
                'last_page'    => 1,
                'links'        => [
                    0 => [
                        'url'    => null,
                        'label'  => '&laquo; Previous',
                        'active' => false
                    ],
                    1 => [
                        'url'    => 'http://localhost/api/posts?page=1',
                        'label'  => '1',
                        'active' => true
                    ],
                    2 => [
                        'url'    => null,
                        'label'  => 'Next &raquo;',
                        'active' => false
                    ]
                ],
                'path'         => 'http://localhost/api/posts',
                'per_page'     => 10,
                'to'           => 2,
                'total'        => 2
            ]];

        $this->seedFakePosts($generatedPosts);

        $response = $this->getJson('api/posts?page=1&size=3')->json();

        self::assertEquals($expectedJson, $response);
    }

    public function test_it_returns_single_post(): void
    {
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
                'deleted_at'  => '2023-08-31 14:02:34',
                'comments'    => []
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
        $id = 3;

        $expectedJson = [
            'data' => array_values(array_filter($generatedPosts, function ($item) use ($id) {
                return $item['id'] === $id;
            }))[0]
        ];

        $this->seedFakePosts($generatedPosts);

        $response = $this->getJson("api/posts/$id")->json();

        self::assertEquals($expectedJson, $response);
    }

    public function test_it_returns_throws_with_no_post(): void
    {
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
                'deleted_at'  => '2023-08-31 14:02:34',
                'comments'    => []
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
        $id = 4;

        $this->seedFakePosts($generatedPosts);

        $errorMessage = $this->getJson("api/posts/$id")->json('message');


        self::assertEquals("No query results for model [App\Models\Post] $id", $errorMessage);
    }

    public function test_it_returns_throws_with_deleted_post(): void
    {
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
                'deleted_at'  => '2023-08-31 14:02:34',
                'comments'    => []
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

        $this->seedFakePosts($generatedPosts);

        $errorMessage = $this->getJson("api/posts/$id")->json('message');


        self::assertEquals("No query results for model [App\Models\Post] $id", $errorMessage);
    }

    public function test_it_creates_post(): void
    {
        $newPostTitle = 'Post #4';
        $newPostDescription = 'Post #4 description';
        $expectedJson = [
            'data' => [
                'id'          => 1,
                'title'       => $newPostTitle,
                'description' => $newPostDescription,
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'  => null,
            ]
        ];

        $actualResponse = $this->postJson("api/posts", [
            'title'       => $newPostTitle,
            'description' => $newPostDescription
        ]);

        $actualResponse->assertStatus(201);
        self::assertEquals($expectedJson, $actualResponse->json());


        $dbPost = Post::query()->where('title', $newPostTitle)->first();
        $this->assertNotNull($dbPost);
        self::assertEquals($newPostTitle, $dbPost->title);
        self::assertEquals($newPostDescription, $dbPost->description);
    }

    public function test_it_requires_title_and_description(): void
    {
        $expectedJson = [
            'title'       => ['The title field is required.'],
            'description' => ['The description field is required.'],
        ];

        $actualResponse = $this->postJson("api/posts", [
            'title'       => '',
            'description' => null
        ])->json('errors');


        self::assertEquals($expectedJson, $actualResponse);
    }

    public function test_it_validates_title_and_description_min_length(): void
    {
        $expectedJson = [
            'title'       => ['The title field must be at least 5 characters.'],
            'description' => ['The description field must be at least 10 characters.'],
        ];

        $actualResponse = $this->postJson("api/posts", [
            'title'       => 'fi',
            'description' => 'one two'
        ])->json('errors');


        self::assertEquals($expectedJson, $actualResponse);
    }

    public function test_it_validates_title_max_length(): void
    {
        $expectedJson = [
            'title' => ['The title field must not be greater than 255 characters.'],
        ];

        $actualResponse = $this->postJson("api/posts", [
            'title'       => Str::random(256),
            'description' => 'one two three four'
        ])->json('errors');


        self::assertEquals($expectedJson, $actualResponse);
    }

    public function test_it_updates_post(): void
    {
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
                'deleted_at'  => '2023-08-31 14:02:34',
                'comments'    => []
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

        $newPostTitle = 'Post #11';
        $newPostDescription = 'Post #11 description';

        $expectedJson = [
            'data' => [
                'id'          => 1,
                'title'       => $newPostTitle,
                'description' => $newPostDescription,
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at'  => null,
            ]
        ];

        $actualResponse = $this->patchJson("api/posts/$id", [
            'title'       => $newPostTitle,
            'description' => $newPostDescription
        ]);

        $actualResponse->assertStatus(200);
        self::assertEquals($expectedJson, $actualResponse->json());


        $dbPost = Post::query()->find($id);
        $this->assertNotNull($dbPost);
        self::assertEquals($newPostTitle, $dbPost->title);
        self::assertEquals($newPostDescription, $dbPost->description);
    }

    public function test_it_throws_on_update_null_post(): void
    {
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
                'deleted_at'  => '2023-08-31 14:02:34',
                'comments'    => []
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

        $id = 4;
        $this->seedFakePosts($generatedPosts);

        $newPostTitle = 'Post #4';
        $newPostDescription = 'Post #4 description';

        $actualResponse = $this->patchJson("api/posts/$id", [
            'title'       => $newPostTitle,
            'description' => $newPostDescription
        ]);

        self::assertEquals("No query results for model [App\Models\Post] $id", $actualResponse->json('message'));


        $dbPost = Post::query()->find($id);
        $this->assertNull($dbPost);
    }

    public function test_it_deletes_post(): void
    {
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
                'deleted_at'  => '2023-08-31 14:02:34',
                'comments'    => []
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

        $deletedDate = Carbon::now()->format('Y-m-d H:i:s');

        $expectedJson = [
            'data' => [
                'id'          => 1,
                'title'       => 'Post #1',
                'description' => 'Post #1 description',
                'created_at'  => '2023-08-31 14:02:34',
                'updated_at'  => $deletedDate,
                'deleted_at'  => $deletedDate,
            ]
        ];

        $actualResponse = $this->deleteJson("api/posts/$id");

        $actualResponse->assertStatus(200);
        self::assertEquals($expectedJson, $actualResponse->json());


        $dbPost = Post::withTrashed()->find($id);
        $this->assertNotNull($dbPost);
        self::assertEquals($deletedDate, $dbPost->updated_at->format('Y-m-d H:i:s'));
        self::assertEquals($deletedDate, $dbPost->deleted_at->format('Y-m-d H:i:s'));
    }

    public function test_it_throws_on_delete_null_post(): void
    {
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
                'deleted_at'  => '2023-08-31 14:02:34',
                'comments'    => []
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

        $id = 4;
        $this->seedFakePosts($generatedPosts);

        $actualResponse = $this->deleteJson("api/posts/$id");

        self::assertEquals("No query results for model [App\Models\Post] $id", $actualResponse->json('message'));


        $dbPost = Post::query()->find($id);
        $this->assertNull($dbPost);
    }

    private function seedFakePosts(array $posts): void
    {
        $posts = array_map(function ($post) {
            unset($post['comments']);
            return $post;
        }, $posts);

        Post::factory()->createMany($posts);
    }
}

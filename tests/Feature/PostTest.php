<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_post()
    {
        $user = factory('App\User')->create();

        $response = $this->actingAs($user, 'api')
                        ->json('POST', '/api/posts', [
                            'user_id' => $user->id,
                            'title' => 'Intro to blogging',
                            'slug' => str_slug('Intro to blogging'),
                            'content' => 'This is an introduction to blogging.',
                            'status' => true,
                            'tags' => 1
                        ]);

        $response
            ->assertStatus(201)
            ->assertJson(['data' => [
                'id' => 1,
                'title' => 'Intro to blogging',
                'slug' => str_slug('Intro to blogging'),
                'content' => 'This is an introduction to blogging.',
                'status' => true,
            ]]);
    }

    /** @test */
    public function a_user_needs_to_be_authenticated_to_create_a_post () {
        $response = $this->json('POST', '/api/posts', [
                        'user_id' => 1,
                        'title' => 'Intro to blogging',
                        'slug' => str_slug('Intro to blogging'),
                        'content' => 'This is an introduction to blogging.',
                        'status' => true,
                        'tags' => 1
                    ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    /** @test */
    public function a_user_can_retrieve_a_list_of_posts() {
        factory('App\Post', 5)->create()->each(function ($post) {
            $post->tags()->attach(factory('App\Tag')->create());
        });

        $response = $this->json('GET', '/api/posts');

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['data' => [[
                'id',
                'title',
                'slug',
                'content',
                'status',
                'created_at',
                'updated_at',
                'user',
                'tags',
                'comments',
            ]]]);
    }

    /** @test */
    public function a_user_can_fetch_a_single_post() {
        $post = factory('App\Post')->create();
        $post->tags()->attach(factory('App\Tag')->create());

        $response = $this->json('GET', '/api/posts/' . $post->id);

        $response
            ->assertStatus(200)
            ->assertSee($post->title);
    }

    /** @test */
    public function a_user_can_update_own_post() {
        $user = factory('App\User')->create();
        $post = factory('App\Post')->create([
            'user_id' => $user->id,
            'title' => 'Intro to blogging',
            'slug' => str_slug('Intro to blogging'),
            'content' => 'This is an introduction to blogging.',
            'status' => 0
        ]);
        $tag = factory('App\Tag')->create();
        $post->tags()->attach($tag);

        $response = $this->actingAs($user, 'api')
                        ->json('PUT', 'api/posts/' . $post->id, [
                            'title' => 'Title updated',
                            'slug' => str_slug('Title updated'),
                            'content' => 'Content updated',
                            'status' => 1,
                            'tags' => $tag->id
                        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['data' => [
                'id' => 1,
                'title' => 'Title updated',
                'slug' => str_slug('Title updated'),
                'content' => 'Content updated',
                'status' => 1
            ]]);
    }

    /** @test */
    public function a_user_can_delete_a_post() {
        $user = factory('App\User')->create();
        $post = factory('App\Post')->create();
        $post->tags()->attach(factory('App\Tag')->create());

        $response = $this->actingAs($user, 'api')
                        ->json('DELETE', 'api/posts/' . $post->id);

        $response->assertStatus(204);
    }
}

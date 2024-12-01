<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecommendationTest extends TestCase
{
        public function test_recommendations_are_generated_correctly()
{
    $user = User::factory()->create();

    $course1 = Course::factory()->create(['category' => 'Technology']);
    $course2 = Course::factory()->create(['category' => 'Technology']);
    $course3 = Course::factory()->create(['category' => 'Health']);

    // المستخدم يفضل دورة واحدة
    $user->recommendations()->create(['course_id' => $course1->id]);

    $response = $this->actingAs($user)->get('/recommend/' . $user->id);

    $response->assertStatus(200);
    $response->assertSee($course2->name);
    $response->assertDontSee($course3->name);

    }
}


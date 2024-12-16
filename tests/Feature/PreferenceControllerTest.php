<?php

namespace Tests\Feature;

use Database\Factories\CategoryFactory;
use Database\Factories\PreferenceFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User\User;
use Database\Factories\UserFactory;
use App\Models\Preference\Preference;

class PreferenceControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
//test create
    public function test_create_preference_success()
    {
        $user = UserFactory::new()->create(); 

        $this->actingAs($user);

        $category = CategoryFactory::new()->create();

        $data = [
            'category_id' => $category->id
        ];

        $response = $this->postJson('/api/preference/create', $data);

        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Preference saved successfully!',
        ]);

        $this->assertDatabaseHas('preferences', [
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);
    }


    public function test_create_invalid_preference_category()
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $data = [
            'category_id' => 99999,
        ];

        $response = $this->postJson('/api/preference/create', $data);

        $response->assertStatus(400)
                 ->assertJsonValidationErrors('category_id');
    }


//test list
    public function test_list_preference_success()
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        Preference::create([
            'user_id' => $user->id,
            'category_id' => '10',
        ]);

        $response = $this->getJson('/api/preference/list');

        $response->assertStatus(200);
    }


//test update
    public function test_update_preference_success()
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $category = CategoryFactory::new()->create();

        $preference = Preference::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $new_category = CategoryFactory::new()->create();

        $data = [
            'category_id' => $new_category->id
        ];

        $response = $this->putJson("/api/preference/update/{$preference->id}", $data);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Preference updated successfully!',
                ]);

        $this->assertDatabaseHas('preferences', [
            'id' => $preference->id,
            'category_id' => $new_category->id
        ]);
    }

    public function test_update_preference_invalid_category()
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $category = CategoryFactory::new()->create();

        $preference = Preference::create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $data = [
            'category_id' => 99999999
        ];

        $response = $this->putJson("/api/preference/update/{$preference->id}", $data);

        $response->assertStatus(400)
                ->assertJsonValidationErrors(['category_id']);
    }

    public function test_update_preference_invalid_id()
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $data = [
            'category_id' => 99999
        ];

        $response = $this->putJson("/api/preference/update/999999", $data);

        $response->assertStatus(404);
    }


// test delete
    public function test_delete_preference_success()
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $category = CategoryFactory::new()->create();

        $preference = Preference::create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $response = $this->deleteJson("/api/preference/delete/{$preference->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Preference successfully deleted!'
                ]);

        $this->assertDatabaseMissing('preferences', [
            'id' => $preference->id
        ]);
    }

    public function test_delete_preference_id_not_found()
    {
        $user = UserFactory::new()->create();

        $this->actingAs($user);

        $response = $this->deleteJson('/api/preference/delete/99999');

        $response->assertStatus(404);
    }
}

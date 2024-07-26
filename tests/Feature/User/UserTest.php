<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();

    }


     public function testCanWithAuthorizationStoreUser(): void
     {

         $userData = User::factory()->raw();
         $header = $this->makeAuth();

         $response = $this->postJson('/api/users', $userData, $header);

         $response
            ->assertCreated()
            ->assertJsonStructure([
                'message',
                'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);

     }

     public function testWithoutAuthorizationCannotStoreUser(): void
     {
         $userData = User::factory()->raw();
         $header = $this->makeUnauthorizedAuth();

         $response = $this->postJson('/api/users', $userData, $header);

         $response
            ->assertUnauthorized();

     }

     public function testCanListUsers(): void
     {

        $header = $this->makeAuth();
        $response = $this->getJson('/api/users', $header);

        $response->assertOK();

     }

     public function testCanEditUser(): void
     {
        $header = $this->makeAuth();
        $user = User::factory()->create();

        $response = $this->getJson('/api/users/' . $user['id'], $header);

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);

     }

     public function testCanDeleteUser(): void
     {
        $header = $this->makeAuth();
        $user = User::factory()->create();

        $response = $this->deleteJson('/api/users/' . $user['id'], [], $header);

        $response->assertOk()->assertJsonCount(1);

     }

}

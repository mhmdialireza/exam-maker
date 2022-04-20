<?php

namespace Tests\Unit\Api\V1;

use Tests\TestCase;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserTest extends TestCase
{
       public function setUp(): void
       {
           parent::setUp();
           $this->artisan('migrate:refresh');
       }

    public function test_should_create_a_new_user()
    {
        $newUser = [
            'full_name' => 'Amir Salehi',
            'email' => 'isamirsalehi@gmail.com',
            'mobile' => '09121112222',
            'password' => '123456',
        ];
        $response = $this->post('api/v1/users', $newUser);

        $this->assertEquals(201, $response->status());
        $newUser['password'] = json_decode($response->getContent(), true)['data']['password'];
        $this->assertDatabaseHas('users', $newUser);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile',
                'password',
            ],
        ]);
    }

    public function test_it_must_throw_a_exception_if_we_dont_send_parameters()
    {
        $response = $this->post('api/v1/users', []);

        $this->assertEquals(422, $response->status());
    }

    public function test_should_update_the_information_of_user()
    {
        $user = $this->createUsers()[0];

        $response = $this->put('api/v1/users', [
            'id' => (string)$user->getId(),
            'full_name' => 'Amir SalehiUpdated',
            'email' => 'isamirsalehi@gmail.comAAAAAA',
            'mobile' => '09126669999',
        ]);

        $this->assertEquals(200, $response->status());
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile',
            ],
        ]);
    }

    public function test_it_must_throw_a_exception_if_we_dont_send_parameters_to_update_info()
    {
        $response = $this->call('PUT', 'api/v1/users', []);

        $this->assertEquals(422, $response->status());
    }

    public function test_should_update_password()
    {
        $user = $this->createUsers()[0];

        $response = $this->call('PUT', 'api/v1/users/change-password', [
            'id' => (string)$user->getId(),
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ]);

        $this->assertEquals(200, $response->status());
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile',
            ],
        ]);
    }

    public function test_it_must_throw_a_exception_if_we_dont_send_parameters_to_update_password()
    {
        $response = $this->call('PUT', 'api/v1/users/change-password', []);

        $this->assertEquals(422, $response->status());
    }

    public function test_should_delete_a_user()
    {
        $user = $this->createUsers()[0];
        $response = $this->call('DELETE', 'api/v1/users', [
            'id' => (string)$user->getId(),
        ]);

        $this->assertEquals(200, $response->status());
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }

    public function test_should_get_users()
    {
        $this->createUsers(10);
        $pagesize = 2;

        $response = $this->call('GET', 'api/v1/users', [
            'page' => 1,
            'pagesize' => $pagesize,
        ]);

        $data = json_decode($response->getContent(), true);

        $this->assertEquals($pagesize, count($data['data']));
        $this->assertEquals(200, $response->status());
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }

    public function test_should_get_filtered_users()
    {
        $pagesize = 3;
        $userEmail = 'isamirsalehi@gmail.com';
        $response = $this->call('GET', 'api/v1/users', [
            'search' => $userEmail,
            'page' => 1,
            'pagesize' => $pagesize,
        ]);

        $data = json_decode($response->getContent(), true);

        foreach ($data['data'] as $user) {
            $this->assertEquals($user['email'], $userEmail);
        }
        $this->assertEquals(200, $response->status());
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }

    private function createUsers(int $count = 1): array
    {
        $userRepository = $this->app->make(UserRepositoryInterface::class);
        $userData = [
            'full_name' => '7azmoon',
            'email' => '7azmoon@gmail.com',
            'mobile' => '09391112222',
            'password' => '1234567890',
            'password_confirmation' => '1234567890',
        ];

        $users = [];

        foreach (range(0, $count) as $item) {
            $users[] = $userRepository->create($userData);
        }

        return $users;
    }
}

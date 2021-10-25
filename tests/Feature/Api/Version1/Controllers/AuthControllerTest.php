<?php
namespace Tests\Feature\Api\Version1\Controllers;

use Tests\Feature\Api\Version1\Bases\ApiControllerTestCase;

class AuthControllerTest extends ApiControllerTestCase {

    // api.auth.login
    public function test_login_when_all_fields_are_empty() {
        $response = $this->post('/api/v1/auth/login');
        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "errors" => [
                    "account"
                ]
            ]);
    }

    public function test_login_when_account_filled_only() {
        $response = $this->post('/api/v1/auth/login', [
            'account' => 'staff',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                "errors" => [
                    "password"
                ]
            ]);
    }

    public function test_login_when_password_incorrect() {
        $response = $this->post('/api/v1/auth/login', [
            'account'  => 'user',
            'password' => 'wrong-password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "ok",
                "data" => [
                    "message"
                ]
            ]);
    }

    public function test_login_when_account_not_found() {
        $response = $this->post('/api/v1/auth/login', [
            'account'  => 'user-no-found',
            'password' => 'correct-password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "ok",
                "data" => [
                    "message"
                ],
            ]);
    }

    public function test_login_when_form_data_correct() {
        $response = $this->post('/api/v1/auth/login', [
            'account'  => 'user',
            'password' => 'user-user',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "accessToken",
                    "tokenType",
                    "expiresIn"
                ]
            ]);
    }

    // api.auth.logout
    public function test_logout_when_not_logged_in() {
        $response = $this->get('/api/v1/auth/logout');

        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                "ok",
                "data" => [
                    "message"
                ]
            ]);
    }

    public function test_logout_when_logged_in() {
        $response = $this
            ->withAuthorization()
            ->get('/api/v1/auth/logout');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "ok",
                "data" => [
                    "message"
                ]
            ]);
    }

}

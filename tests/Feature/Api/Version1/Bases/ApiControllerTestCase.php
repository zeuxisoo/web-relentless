<?php
namespace Tests\Feature\Api\Version1\Bases;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiControllerTestCase extends TestCase {

    protected string $accessToken = "";

    protected array $users = [
        1 => ['account' => 'user', 'password' => 'user-user'],
    ];

    protected function setUp(): void {
        parent::setUp();

        // Create default user
        foreach($this->users as $user) {
            User::factory()->create([
                'username' => $user['account'],
                'password' => Hash::make($user['password']),
            ]);
        }

        // Login first, and set the access token
        $this->fetchAccessToken();
    }

    protected function tearDown(): void {
        $this->accessToken = "";

        parent::tearDown();
    }

    protected function fetchAccessToken($userId = 1) {
        $response = $this->post('/api/v1/auth/login', $this->users[$userId]);

        $content = $response->getContent();
        $struct  = json_decode($content);
        $data    = $struct->data;
        $token   = $data->accessToken;

        $this->accessToken = $token;

        return $this;
    }

    protected function withAuthorization() {
        return $this->withHeaders([
            'Authorization' => "Bearer {$this->accessToken}"
        ]);
    }

    protected function getWithData($uri, array $data) {
        $server = $this->transformHeadersToServerVars([]);
        $cookies = $this->prepareCookiesForRequest();

        return $this->call('GET', $uri, $data, $cookies, [], $server);
    }

}

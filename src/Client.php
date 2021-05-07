<?php

namespace App;

use Exception;
use App\DTO\UpdateUserDTO;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client as GuzzleHttpClient;

class Client
{
    const RESPONSE_STATUS_OK = 'OK';

    protected string $apiUrl;
    protected string $token;

    public function __construct(
        protected GuzzleHttpClient $guzzle
    ) {
        $this->apiUrl = $_SERVER['API_URL'];
    }

    public function auth(string $login, string $pass): bool
    {
        $response = $this->getResponse('GET', 'auth', [
            RequestOptions::QUERY => [
                'login' => $login,
                'pass' => $pass
            ]
        ]);

        try {
            $date = json_decode($response->getBody(), flags: JSON_THROW_ON_ERROR);
            $this->token = $date->status == static::RESPONSE_STATUS_OK
                ? $date->token
                : throw new Exception();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function getUser(string $user)
    {
        $this->checkAuth();

        $response = $this->getResponse('GET', "get-user/{$user}", [
            RequestOptions::QUERY => [
                'token' => $this->token
            ]
        ]);

        return json_decode($response->getBody(), flags: JSON_THROW_ON_ERROR);
    }

    public function updateUser(string $id, UpdateUserDTO $data)
    {
        $this->checkAuth();

        $response = $this->getResponse('POST', "user/{$id}/update", [
            RequestOptions::QUERY => [
                'token' => $this->token
            ],
            RequestOptions::JSON => $data
        ]);

        return json_decode($response->getBody(), flags: JSON_THROW_ON_ERROR);
    }

    private function checkAuth()
    {
        return isset($this->token) ?: throw new Exception('Unauthorized');
    }

    protected function makeUrl(string $path): string
    {
        return $this->apiUrl . '/' . $path;
    }

    protected function getResponse(string $method, string $path, array $options): Response
    {
        try {
            return $this->guzzle->request($method, $this->makeUrl($path), $options);
        } catch (ClientException $e) {
            return $e->getResponse();
        }
    }
}

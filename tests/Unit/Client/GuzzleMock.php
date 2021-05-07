<?php

namespace Tests\Unit\Client;

use App\Client;
use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Promise\FulfilledPromise;

class GuzzleMock
{
    private string $token = '87ahsn8aos7yd0a8shdpo987q0dwuasiold';

    public function __invoke(RequestInterface $request, array $options)
    {
        $response = null;
        parse_str($request->getUri()->getQuery(), $params);
        $path = $request->getUri()->getPath();

        if ($path == '/auth') {
            $response = ($params['login'] == 'test' && $params['pass'] = '12345')
                ? $this->getAuthorizedResponse($path)
                : $this->getUnauthorizedResponse();
        } else {
            $response = $params['token'] != $this->token
                ? $this->getUnauthorizedResponse()
                : $this->getAuthorizedResponse($path);
        }

        return new FulfilledPromise($response);
    }

    private function getUnauthorizedResponse(): Response
    {
        return new Response(401, body: json_encode([
            'status' => 'Error'
        ]));
    }

    private function getAuthorizedResponse(string $path): Response
    {
        try {
            return new Response(body: $this->getBody($path));
        } catch (Exception) {
            return new Response(404, body: json_encode([
                'status' => 'Not found'
            ]));
        }
    }

    private function getBody(string $path)
    {
        switch ($path) {
            case '/get-user/ivanov':
                return $this->getUserBody();
                break;
            case '/user/1/update':
                return $this->getUpdateRequest();
                break;
            case '/auth':
                return $this->getAuthBody();
                break;

            default:
                throw new Exception();
        }
    }

    private function getAuthBody(): string
    {
        return json_encode([
            'status' => Client::RESPONSE_STATUS_OK,
            'token' => $this->token
        ]);
    }

    private function getUserBody(): string
    {
        return '
        {
            "status": "OK",
            "active": "1",
            "blocked": false,
            "created_at": 1587457590,
            "id": 23,
            "name": "Ivanov Ivan",
            "permissions": [
            {
            "id": 1,
            "permission": "comment"
            },
            {
            "id": 2,
            "permission": "upload photo"
            },
            {
            "id": 3,
            "permission": "add event"
            }
            ]
        }
        ';
    }

    private function getUpdateRequest(): string
    {
        return '
        {
            "status": "OK"
        } 
        ';
    }
}

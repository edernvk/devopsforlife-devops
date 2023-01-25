<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Request;

class FreshdeskController extends Controller
{
    protected $apikey;
    protected $apipassword;
    protected $domainName;

    public function __construct() {
        $this->apikey = env('FRESHDESK_KEY');
        $this->apipassword = env('FRESHDESK_PASS');
        $this->domainName = 'https://penzebr.freshdesk.com/api/v2/';
    }

    public function freshdesk() {
        $endpoint = $this->domainName."tickets";
        $client = new Client();
        $headers = [
            'auth' => [$this->apikey, $this->apipassword],
            'Content-Type' => 'application/json'
        ];

        // $response = $client->requestAsync('GET', $endpoint, [
        //     'auth' => [$this->apikey, $this->apipassword],
        //     'Content-Type' => 'application/json'
        // ]);

        $request = new Request('GET', $endpoint, $headers);
        $response = $client->sendAsync($request);

        sleep(500);

        $response->then(
            function (ResponseInterface $res) {
                return [
                    'status' => $res->getStatusCode(),
                    'content' => $res->getBody()
                ];
            },
            function (RequestException $e) {
                return [
                    'status' => $e->getStatusCode(),
                    'message' => $e->getMessage(),
                    'content' => $e->getBody()
                ];
            }
        );

        return $response->wait();
    }
}

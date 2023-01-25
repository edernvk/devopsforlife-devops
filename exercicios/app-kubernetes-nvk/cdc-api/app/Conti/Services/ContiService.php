<?php

namespace Conti\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use Conti\Interfaces\ContiInterface;

class ContiService implements ContiInterface
{
    /**
     * @return Client
     */
    private function _client()
    {
        $domain = config('conti.host');

        return new Client([
            "base_uri" => $domain,
            "headers" => [
                "Accept" => "*/*",
                'Authorization' => 'Bearer '. config('conti.token')
            ],
            "http_errors" => false,
            "verify" => false,
            "allow_redirects" => true
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFuncionarios()
    {
        $response = $this->_client()->get('/api/colaborador');

        return collect(json_decode($response->getBody()->getContents()));
    }

    public function getFuncionario($userCpf)
    {
        $response = $this->_client()->get('/api/colaborador/cpf/'.$userCpf);
        return collect(json_decode($response->getBody()->getContents()));
    }
}

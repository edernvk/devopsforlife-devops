<?php 

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class QrCodeService
{

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function generateCode(string $url): string 
    {
        $query = http_build_query([
            'url' => $url
        ]);

        $response = $this->client->get("https://qrtag.net/api/qr.png?{$query}");
        
        $name = Uuid::uuid4() . '.png';

        if (!Storage::put("qrcodes/$name", $response->getBody())) {
            throw new \Exception('unable to generate qr code ');
        }

        return $name;
    }
}
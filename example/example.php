<?php
declare(strict_types=1);

require '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

// Example HTML content
$content = file_get_contents('example.html');

// Build the HTTP Client
$client = new Client(['base_uri' => 'http://localhost:8000']);

// Build the request
$response = $client->request('POST', '/', [
    'form_params' => [
        'document' => [
            'orientation' => 'landscape'
        ],
        'content' => '<h1>Created on: ' . (new DateTime())->format('Y-m-d H:i:s'). '</h1>' . (string)$content
    ],
    'debug' => false
]);

// Some info output
echo PHP_EOL;
echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
echo 'Response Header: ' . $response->getHeader('content-type')[0] . PHP_EOL;

$bodyStream = $response->getBody();
$bodsSize = $bodyStream->getSize();

if ($response->getHeader('content-type')[0] === 'application/pdf') {
    $fileStream = fopen('example.pdf', 'w+');
} else {
    $fileStream = fopen('response.html', 'w+');
}

// Write the stream
$read = 0;
while ($bodsSize > $read) {
    fwrite($fileStream, $bodyStream->read($read));
    $read = $read + 12;
}
fclose($fileStream);

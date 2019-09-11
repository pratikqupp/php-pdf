<?php
declare(strict_types=1);

require '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

$content = file_get_contents('example.html');

$client = new Client(['base_uri' => 'http://localhost:8000']);
$response = $client->request('POST', '/', [
    'form_params' => [
        'content' => '<h1>Created on: ' . (new DateTime())->format('Y-m-d H:i:s'). '</h1>' . $content
    ],
    'debug' => true
]);

echo PHP_EOL;
echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
echo 'Response Header: ' . $response->getHeader('content-type')[0] . PHP_EOL;

$bodyStream = $response->getBody();
$bodsSize = $bodyStream->getSize();

if ($response->getHeader('content-type')[0] === 'application/pdf') {
    $fileStream = fopen('test.pdf', 'w+');
} else {
    $fileStream = fopen('response.html', 'w+');
}

$read = 0;
while ($bodsSize > $read) {
    fwrite($fileStream, $bodyStream->read($read));
    $read = $read + 12;
}
fclose($fileStream);

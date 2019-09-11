<?php
declare(strict_types=1);
/**
 * Copyright (c) Florian Krämer (https://florian-kraemer.net)
 *
 * Licensed under The GPL License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Florian Krämer (https://florian-kraemer.net)
 * @author    Florian Krämer
 * @link      https://github.com/Phauthentic
 * @license   https://opensource.org/licenses/GPL GPL License
 */

namespace App\Infrastructure\Http;

use App\Infrastructure\Pdf\Document\Orientation;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

/**
 * Pdf Middleware
 */
class PdfMiddleware implements MiddlewareInterface
{
    /**
     * Response Factory
     *
     * @return \Psr\Http\Message\ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * Stream Factory
     *
     * @var \Psr\Http\Message\StreamFactoryInterface
     */
    protected $streamFactory;

    /**
     * @inheritDoc
     */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getMethod() !== 'POST') {
            return $handler->handle($request);
        }

        $response = $this->responseFactory->createResponse();
        $body = (array)$request->getParsedBody();

        $defaults = [
            'engine' => 'WkHtmlToPdf',
            'document' => [
                'encoding' => 'UTF-8',
                'orientation' => [
                    'left' => 10,
                    'right' => 10,
                    'top' => 10,
                    'bottom' => 10
                ]
            ],
            'content' => '<h1>Test</h1>'
        ];

        $body = array_merge($defaults, $body);

        try {
            $pdf = new \App\Infrastructure\Pdf\Document\PdfDocument(
                $body['content']
            );

            $engine = new \App\Infrastructure\Pdf\WkHtmlToPdfEngine();
            $stream = $this->streamFactory->createStream($engine->generate($pdf));
        } catch (Throwable $e) {
            $stream = $this->streamFactory->createStream($e->getMessage());

            return $response
                ->withStatus(500)
                ->withBody($stream);
        }

        return $response
            ->withAddedHeader('Content-Type', 'application/pdf')
            ->withBody($stream);
    }
}

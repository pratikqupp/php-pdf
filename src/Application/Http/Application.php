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
namespace App\Application\Http;

use Narrowspark\HttpEmitter\SapiEmitter;
use Narrowspark\HttpEmitter\SapiStreamEmitter;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Application.
 */
class Application
{
    /**
     * Server Request Factory
     *
     * @var \Psr\Http\Message\RequestFactoryInterface
     */
    protected $requestFactory;

    /**
     * Request Handler / Middleware Stack
     *
     * @var \Psr\Http\Server\RequestHandlerInterface
     */
    protected $requestHandler;

    /**
     * Constructor
     *
     * @param \Psr\Http\Message\ServerRequestFactoryInterface $requestFactory RequestFactory
     * @param \Psr\Http\Server\RequestHandlerInterface        $requestHandler Request Handler
     */
    public function __construct(
        ServerRequestFactoryInterface $requestFactory,
        RequestHandlerInterface $requestHandler
    ) {
        $this->requestFactory = $requestFactory;
        $this->requestHandler = $requestHandler;
    }

    /**
     * Runs the application and sends the data to the client.
     *
     * - Builds the server request object
     * - Builds the middleware stack and runs it
     * - Emits the response to the client
     *
     * @return void
     */
    public function run(): void
    {
        $request = $this->createRequestObject();
        $response = $this->handleRequest($request);
        $this->emitResponse($response);
    }

    /**
     * Calls the middleware stack
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request Request
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        return $this->requestHandler->handle($request);
    }

    /**
     * Emits the response
     *
     * @return void
     */
    protected function emitResponse(ResponseInterface $response): void
    {
        $size = $response->getBody()->getSize();
        if ($size === null || $size <= 1024) {
            $emitter = new SapiEmitter();
        } else {
            $emitter = new SapiStreamEmitter();
        }

        $emitter->emit($response);
    }

    /**
     * Creates the server request object
     *
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    protected function createRequestObject(): ServerRequestInterface
    {
        return $this->requestFactory->createServerRequest(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_SERVER
        );
    }
}

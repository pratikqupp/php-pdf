<?php
declare(strict_types=1);

namespace App\Infrastructure\Http;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * ServerRequestFactory
 */
class ServerRequestFactory implements ServerRequestFactoryInterface
{
    /**
     * Create a new server request.
     * Note that server-params are taken precisely as given - no parsing/processing
     * of the given values is performed, and, in particular, no attempt is made to
     * determine the HTTP method or URI, which must be provided explicitly.
     *
     * @param  string              $method       The HTTP method associated with the request.
     * @param  UriInterface|string $uri          The URI associated with the request. If
     *                                           the value is a string, the factory MUST
     *                                           create a UriInterface instance based on
     *                                           it.
     * @param  array               $serverParams Array of SAPI parameters with which to seed
     *                                           the generated request instance.
     * @return ServerRequestInterface
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        $psr17Factory = new Psr17Factory();

        $creator = new ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );

        return $creator->fromGlobals();
    }
}

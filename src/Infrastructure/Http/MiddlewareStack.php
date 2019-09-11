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

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

/**
 * Middleware Stack
 */
class MiddlewareStack implements RequestHandlerInterface
{
    /**
     * @var callable[]
     */
    protected $queue = [];

    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     */
    public function __construct(ContainerInterface $container, ?array $queue = [])
    {
        $this->container = $container;
        $this->queue = $queue;
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (count($this->queue) === 0) {
            throw new RuntimeException('No middleware configured.');
        }

        $middleware = \array_shift($this->queue);

        if ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, clone $this);
        }

        if (!$this->container instanceof ContainerInterface || !$this->container->has($middleware)) {
            throw new InvalidArgumentException($middleware);
        }

        \array_unshift($this->queue, $this->container->get($middleware));

        return $this->handle($request);
    }
}

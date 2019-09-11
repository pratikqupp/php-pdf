<?php
declare(strict_types=1);
/**
 * Copyright (c) Florian Krämer (https://florian-kraemer.net)
 *
 * Licensed under The GPL License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Florian Krämer (https://florian-kraemer.net)
 * @author        Florian Krämer
 * @link          https://github.com/Phauthentic
 * @license       https://opensource.org/licenses/GPL GPL License
 */

use Phauthentic\Application\Http\HttpApplicationInterface;

require_once '../vendor/autoload.php';

/*******************************************************************************
 * Container
 ******************************************************************************/
$container = new \League\Container\Container();
$container->delegate(
	new \League\Container\ReflectionContainer
);

$container->add(\Psr\Container\ContainerInterface::class, $container);
$container->add(\Psr\Http\Message\ResponseFactoryInterface::class, \Nyholm\Psr7\Factory\Psr17Factory::class);
$container->add(\Psr\Http\Message\StreamFactoryInterface::class, \Nyholm\Psr7\Factory\Psr17Factory::class);
$container->add(\Psr\Http\Message\ServerRequestFactoryInterface::class, \App\Infrastructure\Http\ServerRequestFactory::class);
$container->add(\Psr\Http\Message\ResponseFactoryInterface::class, \Nyholm\Psr7\Factory\Psr17Factory::class);
$container->add(\Psr\Http\Message\StreamFactoryInterface::class, Nyholm\Psr7\Factory\Psr17Factory::class);

$container->add(\Psr\Http\Server\RequestHandlerInterface::class, function () use ($container) {
	return new \App\Infrastructure\Http\MiddlewareStack($container, [
		\App\Infrastructure\Http\PdfMiddleware::class
	]);
});

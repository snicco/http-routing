<?php

declare(strict_types=1);

namespace Snicco\Component\HttpRouting\Tests\fixtures;

use Psr\Http\Message\ResponseInterface;
use Snicco\Component\HttpRouting\Http\Psr7\Request;
use Snicco\Component\HttpRouting\Middleware\Middleware;

use Snicco\Component\HttpRouting\Middleware\NextMiddleware;

use const SEEK_END;

final class FoobarMiddleware extends Middleware
{
    private string $val;

    public function __construct(string $foo = null, string $bar = null)
    {
        if (! $foo && ! $bar) {
            $this->val = 'foobar_middleware';
        } else {
            $this->val = $foo . '_' . ($bar ?: 'foobar_middleware');
        }
    }

    protected function handle(Request $request, NextMiddleware $next): ResponseInterface
    {
        $response = $next($request);

        $body = $response->getBody();
        $body->seek(0, SEEK_END);

        $body->write(':' . $this->val);

        return $response;
    }
}

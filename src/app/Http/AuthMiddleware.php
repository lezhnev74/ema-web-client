<?php
declare(strict_types=1);

namespace AppClient\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AuthMiddleware
{
    
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        //
        // Protect home page from unauthenticated usage
        //
        
        // Do we have a cookie set?
        $cookies = $request->getCookieParams();
        
        if (!isset($cookies['ema_access_token'])) {
            return $response->withRedirect('/login', 302);
        }
        
        $response = $next($request, $response);
        
        return $response;
        
    }
}
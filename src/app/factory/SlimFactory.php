<?php
declare(strict_types=1);

namespace AppClient\factory;

use AppClient\Http\AuthMiddleware;
use Carbon\Carbon;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Dflydev\FigCookies\SetCookies;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\App;


final class SlimFactory
{
    function __invoke(ContainerInterface $container)
    {
        $app = new \Slim\App;
        
        $this->addErrorHandlers($app);
        
        $app->getContainer()['view'] = function ($container) {
            return new \Slim\Views\PhpRenderer(__DIR__ . '/../views/');
        };

////////////////////////////
        
        
        $app->get('/', function (Request $request, Response $response) {
            $cookies = $request->getCookieParams();
            
            // show home page
            return $this->view->render($response, 'home.php', [
                'api_base_url' => config('app.api.base_url'),
                'access_token' => $cookies['ema_access_token'],
            ]);
        })->add(new AuthMiddleware());
        
        $app->get('/logout', function (Request $request, Response $response) {
            
            // Drop the cookie
            $response = FigResponseCookies::expire($response, 'ema_access_token');
            
            return $response->withRedirect('/', 302);
            
        })->add(new AuthMiddleware());
        
        
        $that = $this;
        $app->get('/login', function (Request $request, Response $response) use ($that) {
            
            // show login page
            return $this->view->render($response, 'login.php', [
                'google_client_id' => env('GOOGLE_CLIENT_ID'),
                'google_redirect_url' => $that->makeUrl($this->router->pathFor('auth_google_callback')),
            ]);
        });
        
        $app->get('/login/google/callback', function (Request $request, Response $response, array $args) use ($that) {
            
            $code = $request->getQueryParams()['code'];
            
            // now exchange it to access_token
            $client          = new Client([
                'base_uri' => config('app.api.base_url'),
                'http_errors' => false,
            ]);
            $server_response = $client->request('GET', config('app.api.exchange_path')
                                                       . "?code=" . $code
                                                       . "&redirect_uri=" . $that->makeUrl($this->router->pathFor('auth_google_callback'))
            );
            $answer          = $server_response->getBody()->getContents();
            $answer_json     = json_decode($answer, true);
            
            if (!isset($answer_json['access_token'])) {
                throw new \Exception("Unable to exchange auth_code to access_token");
            }
            
            // Ok cool!
            // set a cookie with this token
            // Ref: https://github.com/dflydev/dflydev-fig-cookies
            // Get a collection representing the cookies in the Set-Cookie headers
            // of a PSR-7 Response
            $setCookies = SetCookies::fromResponse($response)
                                    ->with(
                                        SetCookie::create("ema_access_token")
                                                 ->withValue($answer_json['access_token'])
                                                 ->withPath('/')
                                                 ->withHttpOnly(true)
                                                 ->withExpires(Carbon::now()->addWeek())
                                    );
            // Render the Set-Cookie headers and add them to the headers of a
            // PSR-7 Response.
            $response = $setCookies->renderIntoSetCookieHeader($response);
            
            return $response->withRedirect('/', 302);
            
        })->setName('auth_google_callback');
        
        return $app;
    }
    
    private function makeUrl(string $url): string
    {
        return config('app.app_base_url') . $url;
    }
    
    private function addErrorHandlers(App $app): void
    {
        $app->getContainer()['phpErrorHandler'] = function ($c) {
            return function (Request $request, Response $response, \Throwable $exception) {
                
                // Default response
                if (env('APP_ENV', 'local') == 'production') {
                    $response->getBody()->write('Something went wrong');
                } else {
                    $response->getBody()->write(
                        $exception->getMessage() . "<br>"
                        . $exception->getFile() . ":" . $exception->getLine()
                    );
                }
                
                
                // Log trace string
                $last_exception = $exception;
                $trace_string   = $last_exception->getTraceAsString();
                while ($last_exception = $last_exception->getPrevious()) {
                    $trace_string .= "\n\n========== prev exception ========== \n\n";
                    $trace_string .= $last_exception->getMessage() . "\n";
                    $trace_string .= $last_exception->getTraceAsString();
                };
                log_problem(get_class($exception) . ": " . $exception->getMessage(), [
                    'trace' => $trace_string,
                ]);
                
                
                return $response;
            };
        };
        $app->getContainer()['errorHandler']    = function ($c) {
            return function (Request $request, Response $response, \Throwable $exception) {
                // Default response
                // Default response
                if (env('APP_ENV', 'local') == 'production') {
                    $response->getBody()->write('Something went wrong');
                } else {
                    $response->getBody()->write(
                        $exception->getMessage() . "<br>"
                        . $exception->getFile() . ":" . $exception->getLine()
                    );
                }
                
                // Log trace string
                $last_exception = $exception;
                $trace_string   = $last_exception->getTraceAsString();
                while ($last_exception = $last_exception->getPrevious()) {
                    $trace_string .= "\n\n========== prev exception ========== \n\n";
                    $trace_string .= $last_exception->getMessage() . "\n";
                    $trace_string .= $last_exception->getTraceAsString();
                };
                log_problem(get_class($exception) . ": " . $exception->getMessage(), [
                    'trace' => $trace_string,
                ]);
                
                return $response;
            };
        };
        
        $app->getContainer()->get('settings')['displayErrorDetails']
            = env('APP_ENV', 'local') == 'local';
    }
}
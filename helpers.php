<?php
use DummyConfigLoader\Config;
use Interop\Container\ContainerInterface;
use Monolog\Logger;
use voku\helper\UTF8;

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @author This code is taken from Laravel's helper file
     *
     * @param  mixed $value
     *
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}


if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @author This code is taken from Laravel's helper file
     *
     * @param  string $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    function env($key, $default = null)
    {
        static $dotenv = null;
        if (!$dotenv) {
            $dotenv = new Dotenv\Dotenv(__DIR__);
            $dotenv->load();
        }
        
        $value = getenv($key);
        
        if ($value === false) {
            return value($default);
        }
        
        switch (UTF8::strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'null':
            case '(null)':
                return null;
        }
        
        if (UTF8::strlen($value) > 1 && UTF8::str_starts_with($value, '"') && UTF8::str_ends_with($value, '"')) {
            return UTF8::substr($value, 1, -1);
        }
        
        return $value;
    }
}


if (!function_exists('log_info')) {
    function log_info(string $message, array $context = []): void
    {
        $logger = container()->get(Logger::class);
        $logger->info($message . "\n", $context);
    }
}

if (!function_exists('log_problem')) {
    function log_problem(string $message, array $context = []): void
    {
        $logger = container()->get(Logger::class);
        $logger->error($message . "\n", $context);
    }
}


if (!function_exists('container')) {
    function container(bool $restart = false): ContainerInterface
    {
        static $container = null;
        
        if (is_null($container) || $restart) {
            $container = null;
            $builder   = new \DI\ContainerBuilder();
            $builder->useAutowiring(true);
            $builder->useAnnotations(false);
            
            $env = config('app.env');
            $builder->addDefinitions(config('factory'));
            try {
                $builder->addDefinitions(config('factory_' . $env));
            } catch (\Exception $e) {
                // config is not available for current environment
            }
            
            $container = $builder->build();
            
        }
        
        return $container;
    }
}

if (!function_exists('config')) {
    function config($key, $default = null)
    {
        static $config = null;
        if (is_null($config)) {
            $config = new Config(__DIR__ . '/config');
        }
        
        return $config->get($key, $default);
    }
}

if (!function_exists('storage_path')) {
    function storage_path($relative = '')
    {
        if (UTF8::substr($relative, 0, 1) != DIRECTORY_SEPARATOR) {
            $relative = '/' . $relative;
        }
        
        return config('path.storage', __DIR__ . '/../storage') . $relative;
    }
}


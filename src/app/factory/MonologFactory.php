<?php
declare(strict_types=1);

namespace AppClient\factory;

use Interop\Container\ContainerInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

final class MonologFactory
{
    function __invoke(ContainerInterface $c): Logger
    {
        $prefix = config('app.storage_path');
        $path   = $prefix . '/app.log';
        
        $handler = new StreamHandler($path);
        $handler->setFormatter(new LineFormatter(null, null, true));
        
        $log = new Logger(config('app.env'));
        $log->pushHandler($handler);
        
        return $log;
    }
}
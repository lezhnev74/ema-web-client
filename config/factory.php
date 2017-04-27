<?php


use AppClient\factory\MonologFactory;
use AppClient\factory\SlimFactory;
use function DI\factory;
use Monolog\Logger;
use Slim\App;


return [
    
    //
    // APP LAYER --------------------------------------
    //
    
    App::class => factory(SlimFactory::class),
    Logger::class => factory(MonologFactory::class),

];


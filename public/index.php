<?php

use App\Enums\Http\Status;
use Core\Router;

use Dotenv\Dotenv;

define('BASE_DIR', dirname(__DIR__)); // /var/www/html

require_once BASE_DIR . "/vendor/autoload.php";
require_once BASE_DIR . "/Core/helper.php";

try {
    Dotenv::createUnsafeImmutable(BASE_DIR)->load();

    require_once BASE_DIR . '/routes/api.php';

    die(Router::dispatch($_SERVER['REQUEST_URI']));
}
catch (Throwable $exception)
{
    die(
    jsonResponse(
        Status::tryFrom((int) $exception->getCode()) ?? Status::UNPROCESSABLE_ENTITY,
        [
            'errors' => [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace(),
            ]
        ]
    )
    );
}
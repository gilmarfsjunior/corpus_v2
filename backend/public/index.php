<?php

declare(strict_types=1);

require __DIR__ . '/../bootstrap/app.php';

use App\Shared\Http\Router;
use App\Shared\Http\Response;

$routes = require __DIR__ . '/../routes/web.php';
$router = new Router($routes);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_GET['request_uri'] ?? ($_SERVER['REQUEST_URI'] ?? '/');
$uri = parse_url($uri, PHP_URL_PATH);

$response = $router->dispatch($method, $uri);

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $value) {
    header(sprintf('%s: %s', $name, $value));
}

echo $response->getBody();

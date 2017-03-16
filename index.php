<?php

require __DIR__ . '/vendor/autoload.php';

$app = new App\App;

$c = $app->getContainer();

$c['routeNotFoundHandler'] = function ($c) {
    return function ($res) {
      // TODO: Inject a view in setBody()
        return $res->setBody('<h1>Page not found</h1>')->withStatus(404);
        // ->withHeader('Content-Type', 'text/plain');
    };
};

$c['methodNotAllowedHandler'] = function ($c) {
    // TODO: redirect to home page?
    die('methodNotAllowedHandler');
};

$c['config'] = function ($c) {
    return [
    'db_driver' => 'mysql',
    'db_host' => 'localhost',
    'db_name' => 'phpframework',
    'db_user' => 'aideus',
    'db_password' => 'xyzAxyz',
  ];
};

$c['db'] = function ($c) {
    $config = $c->config;
    return new PDO(
      "{$config['db_driver']}:host={$config['db_host']};dbname={$config['db_name']}",
      $config['db_user'],
      $config['db_password']
    );
};

$app->get('/', [App\Controllers\HomeController::class, 'show']);
$app->get('/users', [new App\Controllers\UserController($c->db), 'show']);

$app->run();

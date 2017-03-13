<?php

require __DIR__ . '/vendor/autoload.php';

$app = new App\App;

$c = $app->getContainer();

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

$app->get('/', function () {
    echo 'Home';
});

$app->post('/signup', function () {
    echo 'Sign Up';
});

$app->map('/users', function () {
    echo 'Users';
}, ['GET', 'POST']);

$app->run();

<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;
use ZendService\Amazon\S3\S3;

$key    = 'AMAZON_KEY';
$secret = 'AMAZON_SECRET_KEY';

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../log/development.log',
));

// definitions
$app->get('/', function () use ($app, $key, $secret) {

    $s3 = new S3($key, $secret);
    $s3->registerStreamWrapper("s3");

    $finder = new Finder();
    $finder->name('*.JPG');
    $files = $finder->in('s3://testing-bucket-name');

    return $app['twig']->render('homepage.twig', array(
        'files' => $files,
    ));
})
->bind('homepage');

$app->run();
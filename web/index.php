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

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// definitions
$app->get('/', function () use ($app, $key, $secret) {

    // temp pictures array until I can read it from S3...
    $pictures = array(
        array('filename' => '047.JPG'),
        array('filename' => '052.JPG'),
        array('filename' => '064.JPG'),
        array('filename' => '076.JPG'),
        array('filename' => '082.JPG'),
        array('filename' => '090.JPG'),
        array('filename' => '096.JPG'),
        array('filename' => '100.JPG'),
        array('filename' => '105.JPG'),
        array('filename' => '107.JPG'),
        array('filename' => '112.JPG'),
        array('filename' => '117.JPG'),
        array('filename' => '120.JPG'),
        array('filename' => '125.JPG'),
        array('filename' => '126.JPG'),
        array('filename' => '133.JPG'),
        array('filename' => '147.JPG'),
        array('filename' => 'P1020836.JPG'),
        array('filename' => 'P1020841.JPG'),
        array('filename' => 'P1020844.JPG'),
        array('filename' => 'P1020858.JPG'),
        array('filename' => 'P1020861.JPG'),
        array('filename' => 'P1020863.JPG'),
        array('filename' => 'P1020869.JPG'),
        array('filename' => 'P1020871.JPG')
    );


    $s3 = new S3($key, $secret);
    $s3->registerStreamWrapper("s3");

    $finder = new Finder();
    $finder->name('*.JPG');
    $files = $finder->in('s3://testing-bucket-name');

    return $app['twig']->render('homepage.twig', array(
        'files' => $files,
        'pictures'  => $pictures
    ));
})->bind('homepage');

$app->get('/about', function () use ($app) {
    return $app['twig']->render('about.twig');
})->bind('about');

$app->run();
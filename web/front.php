<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();
$routes = include __DIR__.'/../src/app.php';

$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

try {
    extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__.'/../src/pages/%s.php', $_route);

    $response = new Response(ob_get_clean());
} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response->send();

/*
 * require_once __DIR__.'/../vendor/autoload.php';
// once again multiple inclusion

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$request = Request::createFromGlobals();
$routes = new RouteCollection();


$routes->add('hello', new Route('/hello/{name}', array('name' => 'World')));
$routes->add('bye', new Route('/bye'));

// MAP for redirection

$path = $request->getPathInfo();
// get the URL content after front.php/{PATH} and watch if road isset
if (isset($map[$path])) {
    ob_start(); // Dont send information before ob_get_clean();
    extract($request->query->all(), EXTR_SKIP); // extract every variable the request->query->all() get.
    include sprintf(__DIR__.'/../src/pages/%s.php', $map[$path]);
    $response = new Response(ob_get_clean()); // Release
} else {
    $response = new Response('Not Found', 404);
}

$response->send();
 */
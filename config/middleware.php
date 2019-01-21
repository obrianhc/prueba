<?php
// Slim Middleware
// Check the user is logged in when necessary.


$loggedInMiddleware = function ($request, $response, $next) {
    $route = $request->getAttribute('route');    
    $routeName = $route->getName();
    $groups = $route->getGroups();
    $methods = $route->getMethods();
    $arguments = $route->getArguments();

    # Define routes that user does not have to be logged in with. All other routes, the user
    # needs to be logged in with.
    $publicRoutesArray = array(
        'login',
        'post-login',        
        'forgot-password',
        'logout',
        'index'
    );

    if (!isset($_SESSION['username']) && !in_array($routeName, $publicRoutesArray))
    {
        // redirect the user to the login page and do not proceed.
        $response = $response->withRedirect('/cms/en/login');
    }
    else
    {
        // Proceed as normal...
        $response = $next($request, $response);
    }

    return $response;
};
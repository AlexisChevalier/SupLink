<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 16/02/13
 * File: routes.php
 */


$routes = Array(
    '' => Array(
        'controller' => 'home',
        'method' => 'index'
    ),
    'shorten/generate' => Array(
        'controller' => 'home',
        'method' => 'shorten'
    ),
    '{url}/stats' => Array(
        'controller' => 'home',
        'method' => 'stats_url'
    ),
    'user/logout' => Array(
        'controller' => 'home',
        'method' => 'logout'
    ),
    'user/signup' => Array(
        'controller' => 'home',
        'method' => 'signup'
    ),
    'user/login' => Array(
        'controller' => 'home',
        'method' => 'login'
    ),
    'user/options' => Array(
        'controller' => 'home',
        'method' => 'editAccount'
    ),
    'validate/{id}/{hash}' => Array(
        'controller' => 'home',
        'method' => 'validate'
    ),
    /* AJAX */
    'ajaxActions/deleteUrl' => Array(
        'controller' => 'ajaxActions',
        'method' => 'deleteUrl'
    ),
    'ajaxActions/editUrl' => Array(
        'controller' => 'ajaxActions',
        'method' => 'editUrl'
    ),
    'ajaxActions/enableUrl' => Array(
        'controller' => 'ajaxActions',
        'method' => 'enableUrl'
    ),
    'ajaxActions/disableUrl' => Array(
        'controller' => 'ajaxActions',
        'method' => 'disableUrl'
    ),
    'ajaxActions/urlShorten' => Array(
        'controller' => 'ajaxActions',
        'method' => 'shortenUrl'
    ),
    /* URL CATCHER */
    '{url}' => Array(
        'controller' => 'home',
        'method' => 'shortened_url'
    ),
);
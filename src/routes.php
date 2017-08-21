<?php

// Import namespaces
use Technews\Provider\NewsControllerProvider;
use Technews\Provider\AdminControllerProvider;

// Define routes
$app->mount('/', new NewsControllerProvider() );
$app->mount('/admin', new AdminControllerProvider() );
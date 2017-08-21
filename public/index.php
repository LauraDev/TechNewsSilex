<?php
// index.php


// 1- Definir les constantes
define('PATH_ROOT', dirname( __DIR__) );
define('PATH_PUBLIC', PATH_ROOT. '/public' );
define('PATH_SRC', PATH_ROOT. '/src' );
define('PATH_RESSOURCES', PATH_ROOT. '/ressources' );
define('PATH_VIEWS', PATH_RESSOURCES. '/views' );
define('PATH_VENDOR', PATH_ROOT. '/vendor' );


// 1- Autochargement par Composer
// Gère automatiquement toutes les dépendances
require_once PATH_VENDOR.'/autoload.php';


// 2- Instancier la Classe Application de Silex
$app = new Silex\Application();

// 3- App configuration
require PATH_SRC.'/app.php';

// 8- Execution de Silex
$app->run();

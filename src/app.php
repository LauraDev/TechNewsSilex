<?php

// 1 - Import namespaces
use Silex\Provider\AssetServiceProvider;

// 2- Activer le débuggage
$app['debug'] = true;

// 3- Gestion de nos controllers
require PATH_SRC.'/routes.php';


// 4-1 Activation de Twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => [
        __DIR__.'/../ressources/views',
        __DIR__.'/../ressources/layout',
    ],
));

// 4-2 Ajout des extensions Technews pour Twig
$app->extend('twig', function($twig, $app) {
    $twig->addExtension(new Technews\Extension\technewsTwigExtension());
    return $twig;
});

// 5- Activation de Asset (pour récup CSS/JS/IMG...)
$app->register(new AssetServiceProvider() );


// 6-1 Activation de Doctrine DBAL
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'     => 'localhost',
        'dbname'   => 'technews2',
        'user'     => 'root',
        'password' => ''
    ),
));

// 6-2 Activation de IDIORM
$app->register(new Idiorm\Silex\Provider\IdiormServiceProvider(), array(
    'idiorm.db.options' => array(
        'connection_string'   => 'mysql:host=localhost;dbname=technews2',
        'username'     => 'root',
        'password' => '',
        'id_column_overrides' => array(
        'view_articles' => 'IDARTICLE'
        )
    ),
));
// 7- Permet le rendu d'un controller dans la vue
$app->register(new Silex\Provider\HttpFragmentServiceProvider());


// 8- Récupération des catégories
$app['technews_categories'] = function() use($app) {
    return $app['db']->fetchAll('SELECT * FROM categorie');
};

// 9- Récupération des tags
$app['technews_tags'] = function() use($app) {
    return $app['db']->fetchAll('SELECT * FROM tags');
};


// 11- Register Session
$app->register(new Silex\Provider\SessionServiceProvider());

// 10- Register Security
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'main' => array(
            'pattern' => '^/', // s'applique à toutes les urls
            'http' => true,
            'anonymous' => true, // autoriser les utilisateurs anonymes
            'form' => array(
                'login_path' => '/connexion',
                'check_path' => '/connexion/login_check'                
            ),
            'logout' => array(
                'logout_path' => '/deconnexion'
            ),
            'users' => function() use ($app) 
            {
                return new Technews\Provider\AuteurProvider($app['idiorm.db']);
            }
        )
    ),
    // Definition des routes demandant authentification
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN', 'http'),
        array('^/auteur', 'ROLE_AUTEUR', 'http')
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_AUTEUR')
    )

));

$app['security.encoder.digest'] = function() use ($app) {
    return new Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder('sha1', false, 1);
};


$app['security.default_encoder'] = function() use ($app) {
    return $app['security.encoder.digest'];
};

// 11- Formulaire
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));

// 11- Retourne $app
return $app;
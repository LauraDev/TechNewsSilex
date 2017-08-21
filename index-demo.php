<?php
// index.php


// Import namespace
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;


// 1- Autochargement par Composer
// Gère automatiquement toutes les dépendances
require_once __DIR__.'/vendor/autoload.php';


// 2- Instancier la Classe Application de Silex
$app = new Application();


// 3- Activer le débuggage
$app['debug'] = true;


// 4- Définitions des routes
$app->get('/', function() {
    return 'Page Accueil';
});

// $app->match('/hello', function() {
//     return new Response('Page Hello');
// });

// Enregistrement dans App du prenom.default utilisable dans toute l'app
// Protect permet de ne pas intancier des le chargement par le container - au cas ou on veuille passer des parametres
$app['prenom.default'] = $app->protect(function() {
    return 'Lolo';
});

$app['nom.default'] = function() {
    return 'Traore';
};

// Danjs SIlex la route est détectée grace a match
// La fonction anonyme (Closure et représentant un Controller) est exécutée
// Une réponse Html et un code status Http sont renvoyés au navigateur


// Définition d'une route affichant le prénom entré en URL si pas de prénom affichage du prenom.default
$app->match('/hello/{prenom}', function($prenom) use($app) { // Use app permet de passer le nom.default dans le scope de cette fonction
    return new Response("Hello $prenom".' '.$app['nom.default']);  // je peut donc utiliser nom.default apres le use 
})->method('GET|POST')->value('prenom', $app['prenom.default']() ); // prenom.default etant une fonction je rajout les () apres // pas besoin d'utiliser $app quand on est dans method


// 5- Execution de Silex
$app->run();
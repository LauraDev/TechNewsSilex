<?php

namespace Technews\Provider;

use Silex\Api\ControllerProviderInterface;


// Implémentation de l'interface Controller Provider
// L'interface fait le lien entre les routes et l'application
class NewsControllerProvider implements ControllerProviderInterface
{

    public function connect(\Silex\Application $app)
    {
        # Créer une instance de Silex\ControllerCollection
        $controllers = $app['controllers_factory'];


            # Page d'accueil
            $controllers

                # On asscie une route à un controller et une action
                ->get('/', 'Technews\Controller\NewsController::indexAction')
                # En option je peut donner un nom a la route qui servira plus tard à la création de lien
                ->bind('technews_home');




            # Page Catégories
            $controllers

                # On asscie une route à un controller et une action
                ->get('/categorie/{libellecategorie}', 'Technews\Controller\NewsController::categorieAction')
                # Spécifier le type de parametre attendu avec une Regex
                ->assert('libellecategorie' , '[^/]+')
                # Attribuer une valeur par default
                ->value('libellecategorie', 'computer')
                # Nom de la route qui servira plus tard à la création de lien
                ->bind('technews_categorie');


            # Page Articles
            $controllers

                # On associe une route à un controller et une action
                ->get('/{libellecategorie}/{slugarticle}_{idarticle}.html', 'Technews\Controller\NewsController::articleAction')
                # Spécifier le type de parametre attendu avec une Regex
                ->assert('libellecategorie' , '[^/]+')
                ->assert('idarticle' , '[0-9]')
                # En option je peut donner un nom a la route qui servira plus tard à la création de lien
                ->bind('technews_article');


            # Page inscription Admin
            $controllers

                # On associe une route à un controller et une action
                ->get('/inscription', 'Technews\Controller\NewsController::inscriptionAction')
                ->bind('technews_inscription');
            
            # POST
            $controllers
                ->post('/inscription', 'Technews\Controller\NewsController::inscriptionPostAction')
                # En option je peut donner un nom a la route qui servira plus tard à la création de lien
                ->bind('technews_inscriptionPost');




            # Page connexion Admin
            $controllers

                # On associe une route à un controller et une action
                ->get('/connexion', 'Technews\Controller\NewsController::connexionAction')
                # Nom de la route qui servira plus tard à la création de lien
                ->bind('technews_connexion');



            # Page déconnexion admin
            $controllers

                # On associe une route à un controller et une action
                ->get('/deconnexion', 'Technews\Controller\NewsController::deconnexionAction')
                ->bind('technews_deconnexion');
                


        // On retourne la liste des controllers (ControllerCollection)
        return $controllers;
    }

}

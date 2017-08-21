<?php

namespace Technews\Provider;

use Silex\Api\ControllerProviderInterface;


// Implémentation de l'interface Controller Provider
// L'interface fait le lien entre les routes et l'application
class AdminControllerProvider implements ControllerProviderInterface
{

    public function connect(\Silex\Application $app)
    {
        # Créer une instance de Silex\ControllerCollection
        $controllers = $app['controllers_factory'];


            # Page ajout Articles
            $controllers

                # On asscie une route à un controller et une action
                ->get('/article/ajouter', 'Technews\Controller\AdminController::ajoutArticleAction')
                # Spécifier le type de parametre attendu avec une Regex
                ->assert('libellecategorie' , '[^/]+')
                ->assert('idarticle' , '[0-9]')
                # En option je peut donner un nom a la route qui servira plus tard à la création de lien
                ->bind('technews_articleAjout');


        // On retourne la liste des controllers (ControllerCollection)
        return $controllers;
    }



}

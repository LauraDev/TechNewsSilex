<?php

namespace Technews\Controller;

use Silex\Application;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class  NewsController
{

    //Affichage de la page d'accueil
    public function indexAction(Application $app) {
      
      # Connexion à la BDD et récupération des articles
      $articles = $app['db']->fetchAll('SELECT * FROM view_articles');

      # Récup des articles en spotlight
      $spotlight = $app['db']->fetchAll('SELECT * FROM view_articles WHERE SPOTLIGHTARTICLE = 1');


      return $app['twig']->render('index.html.twig', [
        'articles' => $articles,
        'spotlight' => $spotlight
      ]);
    }





    //Affichage de la page Catégories
    public function categorieAction(Application $app, $libellecategorie) {

        # Tous les articles de cette categorie
        $articlesParCategorie = $app['db']->fetchAll("SELECT * FROM view_articles WHERE LIBELLECATEGORIE = '$libellecategorie' " );
        

        return $app['twig']->render('categorie.html.twig', [
          'articlesParCategorie' => $articlesParCategorie,
          'libellecategorie' => $libellecategorie
        ]);
    }







    //Affichage de la page Articles
    public function articleAction(Application $app, $libellecategorie, $slugarticle, $idarticle) {

      # Article selectionne
      $monArticle = $app['db']->fetchAll("SELECT * FROM view_articles WHERE IDARTICLE = '$idarticle' " );

      # Tous les autres article de la meme categorie
      $articlesParCategorie = $app['db']->fetchAll("SELECT * FROM view_articles WHERE LIBELLECATEGORIE = '$libellecategorie' AND NOT IDARTICLE = '$idarticle' " );
      

      return $app['twig']->render('article.html.twig', [
        'monArticle' => $monArticle,
        'articlesParCategorie' => $articlesParCategorie,
        'libellecategorie' => $libellecategorie
      ]);
    }






      //Affichage de la page d'inscription admin
      public function inscriptionAction(Application $app) {
          return $app['twig']->render('inscription.html.twig');
      }


      //Affichage de la page d'inscription admin
      public function inscriptionPostAction(Application $app, Request $request ) {
          # Vérification et sécurisation des données post

          # Connexion à la BDD
          $auteur = $app['idiorm.db']->for_table('auteur')->create();
          
          # Affectation des valeurs
          $auteur->PRENOMAUTEUR  =  $request->get('PRENOMAUTEUR');
          $auteur->NOMAUTEUR     =  $request->get('NOMAUTEUR');
          $auteur->EMAILAUTEUR   =  $request->get('EMAILAUTEUR');
          $auteur->MDPAUTEUR     =  $app['security.encoder.digest']->encodePassword($request->get('MDPAUTEUR'), '');
          $auteur->ROLESAUTEUR   =  $request->get('ROLEAUTEUR');

          // Persister en BDD
          $auteur->save();

          // Email de confirmation ou bienvenue/ envoie d'une notif à l'admin ...

          // Redirection sur la page de connexion
          return $app->redirect('connexion?inscription=success');
      }





    //Affichage de la page de connexion admin
    public function connexionAction(Application $app, Request $request) {

        return $app['twig']->render('connexion.html.twig', [
          'error' => $app['security.last_error']($request),
          'last_username' => $app['session']->get('_security.last_username')
        ]);



        // // some default data for when the form is displayed the first time
        // $data = array(
        //     'email' => 'Votre email',
        //     'password' => '***************',
        // );

        // $form = $app['form.factory']->createBuilder(FormType::class, $data)
        //     ->add('E-mail')
        //     ->add('Mot-de-passe')
        //     // ->add('submit', SubmitType::class, [
        //     //     'label' => 'Connexion',
        //     // ])
        //     ->getForm();

        //     $form->handleRequest($request);

        // // display the form
        // return $app['twig']->render('connexion.html.twig', array('form' => $form->createView()));
    }


    //Affichage de la page de déconnexion admin
    public function deconnexionAction(Application $app) {

        $app['session']->clear();
        return $app->redirect( $app['url_generator']->generate('technews_home'));
    }







    public function menu(Application $app, $active) {

      # Récupération des catégories
      $categories = $app['idiorm.db']->for_table('categorie')->find_result_set();
      

      # Transmission à la vue
      return $app['twig']->render('menu.html.twig', ['categories' => $categories, 'active' => $active ]);
    }






    
    public function sidebar(Application $app) {
      # Récupération des infos pour la sidebar
      $sidebar = $app['idiorm.db']->for_table('view_articles')
                                  ->order_by_desc('DATECREATIONARTICLE')
                                  ->limit (5)
                                  ->find_result_set();

      $special = $app['idiorm.db']->for_table('view_articles')
                                  ->where('SPECIALARTICLE', 1)
                                  ->find_result_set();
      # Transmission à la vue
      return $app['twig']->render('sidebar.html.twig', [
        'sidebar' => $sidebar,
        'special' => $special
      ]);
    }

}

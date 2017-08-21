<?php
namespace Technews\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Technews\Model\Auteur;

class AuteurProvider implements UserProviderInterface
{

    private $_db;

    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function supportsClass($class)
    {
        return $class === 'Technews\Model\Auteur';
    }

    public function refreshUser(UserInterface $auteur)
    {
        if(!$auteur instanceof Auteur)
        {
            throw new UnsupportedUserException(
                sprintf('Les instances de "%s" ne sont pas autorisées.',
                get_class($auteur))
            );
        }

        return $this->loadUserByUsername($auteur->getUsername());
    }

    public function loadUserByUsername($EMAILAUTEUR)
    {
        $auteur = $this->_db->for_table('auteur')
                            ->where('EMAILAUTEUR', $EMAILAUTEUR)
                            ->find_one();

        if(empty($auteur)) 
        {
            throw new UsernameNotFoundException(
                sprintf('Cet utilisateur n\'existe pas.', $EMAILAUTEUR));
        }
        return new Auteur($auteur->IDAUTEUR, $auteur->NOMAUTEUR, 
        $auteur->PRENOMAUTEUR, $auteur->EMAILAUTEUR, $auteur->MDPAUTEUR, 
        $auteur->ROLESAUTEUR);        
    }
}
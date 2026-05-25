<?php

namespace App\Services ;

use App\Entity\LignePanier;
use App\Entity\Panier;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Service qui gère le panier en utilisant la session Symfony.
 *
 * Cette classe implémente l'interface GestionPanierInterface (pattern Strategy).
 * Le but est de stocker les articles du panier dans la session de l'utilisateur.
 */
class PanierSession implements GestionPanierInterface
{
    private const CART_KEY = 'cart_items';
    
    public function __construct( private SessionInterface $session ) {}

    public function ajouterArticle(LignePanier $item, Panier $panier): Panier
    {
        $items = $this->session->get(self::CART_KEY, []);
        $items[$item->getProduit()->getId()] = $item;
        
        $this->session->set(self::CART_KEY, $items);
        return $panier;
    }

    public function retirerArticle(LignePanier $item, Panier $panier): Panier
    {
        $oldCart = $this->session->get( $panier->getId() ) ;
        unset( $oldCart [ $item->getId() ] ) ;
        $this->session->set( $panier->getId() , $oldCart ) ;
        return $panier ;
    }

    /**
     * Récupère le panier complet depuis la session.
     * On crée un objet Panier vide puis on y ajoute
     * chaque article stocké dans la session.
     */
    public function recupererPanier(string $cartIdentifier): Panier
    {
        $current_cart = new Panier() ;
        $cart = $this->session->get( $cartIdentifier, [] ) ;
        foreach ( $cart as $item ) {
            $current_cart->addLignePanier($item);
        }
        return $current_cart ;
    }

    public function viderPanier(string $cartIdentifier): void
    {
        $this->session->remove( $cartIdentifier ) ;
    }
}

?>
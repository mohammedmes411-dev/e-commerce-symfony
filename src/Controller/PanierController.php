<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\LignePanier;
use App\Helpers\GestionnairePanier;
use App\Repository\ProduitRepository;
use App\Services\GestionPanierInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur responsable de la gestion du panier d'achat.
 * 
 * Ce contrôleur utilise le pattern Strategy via GestionPanierInterface pour rester
 * flexible : on pourrait changer le stockage (session, base de données...)
 * sans modifier ce contrôleur. C'est le principe Open/Closed des SOLID.
 */
class PanierController extends AbstractController
{

    // Clé de session du panier
    private const CART_SESSION_KEY = 'cart_items';

    public function __construct( 
        #[Autowire('@App\Services\PanierSession')]
        private GestionPanierInterface $_strategy , 
        private GestionnairePanier $_cartHandler , 
        private ProduitRepository $_prodRepo
    ) {  }

    #[Route('/cart', name: 'ecommerce_cart')]
    public function afficherPanier() : Response
    {
        return $this->render('/files/panier.html.twig', [
            'panier' => $this->_strategy->recupererPanier( self::CART_SESSION_KEY )
        ]);
    }

    /**
     * Ajoute un article au panier.
     * On récupère la quantité depuis le formulaire POST,
     * on vérifie que le produit existe en base,
     * puis on délègue la logique au GestionnairePanier.
     */
    #[Route('/addToCart/{id}', name: 'ecommerce_addToCart')]
    public function ajouterAuPanier( Request $request , int $id ) : Response
    {
        /* Récupération de la quantité */
        $quantity = $request->request->get( 'quantity' ) ;

        /* Vérification de l'existence du produit */
        $product = $this->_prodRepo->find( $id ) ;
        if ( !$product ) throw $this->createNotFoundException('Product not found') ;

        $item = new LignePanier() ;
        $item->setProduit( $product ) ;
        $item->setQuantite( $quantity ) ;

        $cart = new Panier();
        $this->_cartHandler->gerer($item, $cart, $this->_strategy);

        /* Retour au panier */
        return $this->redirectToRoute("ecommerce_cart");
    }
}

?>
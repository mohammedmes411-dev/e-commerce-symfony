<?php 

namespace App\Controller;

use App\Entity\LignePanier;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Dto\AjoutArticleDTO ;
use App\Repository\ProduitRepository;
use App\Services\GestionPanierInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApiPanierController extends AbstractController implements GestionPanierInterface
{
    /* Injection de dépendances */
    public function __construct
    (
        private EntityManagerInterface $_entityManager,
        private readonly ProduitRepository $_prodRepo
    )
    {  }
    
    #[Route('api/addItem', name: 'ecommerce_addItem', methods: ['POST'])]
    public function apiAddItem(Request $request): Response
    {
        /* Décodage du corps de la requête */
        $data = json_decode($request->getContent(), true);
        
        // Création manuelle du DTO
        $dto = new AjoutArticleDTO();
        
        // Vérifier si l'ID du panier est fourni dans la requête
        $cartId = $data['cart']['id'] ?? $data['cartId'] ?? null;
        if ($cartId) {
            // Trouver le panier existant
            $dto->cart = $this->_entityManager->find(Panier::class, $cartId);
            if (!$dto->cart) {
                // Panier non trouvé, en créer un nouveau
                $dto->cart = new Panier();
                $dto->cart->setDateCreation(new \DateTimeImmutable());
            }
        } else {
            // Pas d'ID de panier fourni, créer un nouveau panier
            $dto->cart = new Panier();
            $dto->cart->setDateCreation(new \DateTimeImmutable());
        }
        
        // Créer l'article du panier
        $dto->item = new LignePanier();
        $prodId = $data['item']['Product']['id'] ?? $data['item']['produit']['id'] ?? null;
        $product = $this->_prodRepo->find($prodId);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        $dto->item->setProduit($product);
        $dto->item->setPrix($data['item']['Price'] ?? $data['item']['prix'] ?? 0.0);
        $dto->item->setQuantite($data['item']['Quantity'] ?? $data['item']['quantite'] ?? 1);
        
        /* Ajout du produit trouvé au panier */
        $updatedCart = $this->ajouterArticle($dto->item, $dto->cart);
        
        // Retourner la réponse JSON
        return $this->json([
            'success' => true,
            'message' => 'Item added to cart',
            'cart' => [
                'id' => $updatedCart->getId(),
                'total_items' => $updatedCart->getLignesPanier()->count()
            ]
        ]);
    }

    #[Route('api/removeItem', name: 'ecommerce_removeItem', methods: ['DELETE'])]
    public function apiRemoveItem(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        // Trouver l'ID de la ligne du panier
        $cartItemId = $data['cartItemId'] ?? $data['item']['id'] ?? null;
        
        if (!$cartItemId) {
            return $this->json(['error' => 'Cart item ID is required'], 400);
        }
        
        // Trouver l'article
        $cartItem = $this->_entityManager->find(LignePanier::class, $cartItemId);
        
        if (!$cartItem) {
            return $this->json(['error' => 'Cart item not found'], 404);
        }
        
        // Obtenir le panier
        $cart = $cartItem->getPanier();
        
        if (!$cart) {
            return $this->json(['error' => 'Cart not found'], 404);
        }
        
        // Supprimer l'article
        $this->retirerArticle($cartItem, $cart);
        
        return $this->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'cart' => [
                'id' => $cart->getId(),
                'remaining_items' => $cart->getLignesPanier()->count()
            ]
        ]);
    }

    #[Route('api/getCart', name:'ecommerce_getCart', methods: ['GET'])]
    public function apiGetCart(Request $request): Response
    {
        $cartId = $request->query->get('cartId');
        
        if (!$cartId) {
            return $this->json(['error' => 'Cart ID is required'], 400);
        }
        
        $cart = $this->_entityManager->find(Panier::class, $cartId);
        
        if (!$cart) {
            return $this->json(['error' => 'Cart not found'], 404);
        }
        
        return $this->json([
            'id' => $cart->getId(),
            'created_at' => $cart->getDateCreation(),
            'items' => $cart->getLignesPanier()->count()
        ]);
    }

    #[Route('api/clearCart', name: 'ecommerce_clearCart', methods: ['DELETE'])]
    public function apiClearCart(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $cartId = $data['cartId'] ?? null;
        
        if (!$cartId) {
            return $this->json(['error' => 'Cart ID is required'], 400);
        }
        
        $cart = $this->_entityManager->find(Panier::class, $cartId);
        
        if (!$cart) {
            return $this->json(['error' => 'Cart not found'], 404);
        }
        
        $this->viderPanier($cartId);
        
        return $this->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }

    /* Implémentation de la méthode d'ajout */
    public function ajouterArticle(LignePanier $item, Panier $panier): Panier
    {
        /* Requêtes de base de données */
        $item->setPanier($panier);  // Définir la relation
        $panier->addLignePanier($item);
        
        $this->_entityManager->persist($item);
        $this->_entityManager->persist($panier);
        $this->_entityManager->flush();
        
        return $panier;  // Retourner le panier d'origine
    }
    
    /* Implémentation de la méthode de retrait */
    public function retirerArticle(LignePanier $item, Panier $panier): Panier
    {
        /* Requêtes de base de données */
        $panier->removeLignePanier($item);
        $this->_entityManager->remove($item);
        $this->_entityManager->flush();
        
        return $panier;
    }
    
    /* Implémentation de la récupération du panier */
    public function recupererPanier(string $cartIdentifier): Panier
    {
        return $this->_entityManager->find(Panier::class, $cartIdentifier);
    }
    
    /* Implémentation du vidage de panier */
    public function viderPanier(string $cartIdentifier): void
    {
        $cart = $this->_entityManager->find(Panier::class, $cartIdentifier);
        
        if ($cart) {
            foreach ($cart->getLignesPanier() as $item) {
                $this->_entityManager->remove($item);
            }
            $this->_entityManager->flush();
        }
    }
}
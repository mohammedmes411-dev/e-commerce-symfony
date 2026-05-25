<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DetailsProduitController extends AbstractController
{

    private readonly ProduitRepository $_prodRepo ;
    public function __construct( ProduitRepository $prodRepo )
    {
        $this->_prodRepo = $prodRepo ;
    }

    #[Route('/productDetails/{id}', name: 'ecommerce_prodDetails')]
    public function afficherDetailsProduit( int $id ) : Response
    {
        return $this->render('/files/details_produit.html.twig', [
            'produitSelectionne' => $this->_prodRepo->find($id)
        ]);
    }
}

?>
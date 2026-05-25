<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProduitsCategorieController extends AbstractController
{

    private readonly CategorieRepository $_categRepo ;
    private readonly ProduitRepository $_prodRepo ;
    public function __construct( CategorieRepository $categRepo, ProduitRepository $prodRepo)
    {
        $this->_categRepo = $categRepo ;
        $this->_prodRepo = $prodRepo ;
    }

    #[Route('/productsThroughCategory/{id}', name: 'ecommerce_productsThroughCategory')]
    public function afficherProduitsParCategorie( int $id ) : Response
    {
        $categorieChoisie = $this->_categRepo->find($id);
        return $this->render('/files/produits_par_categorie.html.twig', [
            'categorieSelectionnee' => $categorieChoisie,
            'produitsAffiches'      => $this->_prodRepo->findBy(['categorie' => $categorieChoisie])
        ]);
    }
}

?>
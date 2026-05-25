<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoriesController extends AbstractController
{

    private readonly CategorieRepository $_categRep ;
    public function __construct( CategorieRepository $categRep )
    {
        $this->_categRep = $categRep ;
    }

    #[Route('/', name: 'ecommerce_homePage')]
    public function afficherCategories() : Response
    {
        return $this->render('/files/categories.html.twig', [
            'categories' => $this->_categRep->findAll()
        ]);
    }
}

?>
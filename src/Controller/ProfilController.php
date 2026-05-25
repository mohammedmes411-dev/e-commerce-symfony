<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    #[Route('/profile', name: 'ecommerce_profilePage')]
    public function afficherProfil() : Response
    {
        return $this->render('/files/profil.html.twig');
    }
}

?>
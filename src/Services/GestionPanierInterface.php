<?php

namespace App\Services ;

use App\Entity\LignePanier;
use App\Entity\Panier;

interface GestionPanierInterface
{
    public function ajouterArticle( LignePanier $item , Panier $panier ) : Panier ;
    public function retirerArticle( LignePanier $item , Panier $panier ) : Panier ;
    public function recupererPanier( string $cartIdentifier ) : Panier ;
    public function viderPanier( string $cartIdentifier ) : void ;
}

?>
<?php

namespace App\Helpers ;

use App\Entity\Panier;
use App\Entity\LignePanier;
use App\Services\GestionPanierInterface;

class GestionnairePanier
{
    public function gerer( LignePanier $item , Panier $panier , GestionPanierInterface $strategie ) : Panier
    {
        return $strategie->ajouterArticle( $item , $panier ) ;
    }
}

?>
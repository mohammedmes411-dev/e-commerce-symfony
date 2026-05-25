<?php

namespace App\Dto ;

use App\Entity\LignePanier;
use App\Entity\Panier;

class AjoutArticleDTO
{
    public ?LignePanier $item = null ; 
    public ?Panier $cart = null ;
}

?>
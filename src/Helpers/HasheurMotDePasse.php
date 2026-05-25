<?php

namespace App\Helpers;

use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HasheurMotDePasse
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function hacherMotDePasse(Utilisateur $utilisateur, string $plainPassword): string
    {
        return $this->hasher->hashPassword($utilisateur, $plainPassword);
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use App\Helpers\HasheurMotDePasse;

/**
 * Contrôleur qui gère l'authentification des utilisateurs.
 *
 * Il s'occupe de trois actions :
 * - Affichage du formulaire de connexion
 * - Déconnexion (gérée automatiquement par Symfony via security.yaml)
 * - Inscription d'un nouvel utilisateur avec hashage du mot de passe
 */
class AuthController extends AbstractController
{

    public function __construct(
        private HasheurMotDePasse $passwordHelper
    ) {}

    #[Route('/login', name: 'ecommerce_login')]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('/files/connexion.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
        ]);
    }

    #[Route('/logout', name: 'ecommerce_logout')]
    public function deconnexion(): void {  }

    /**
     * Inscription d'un nouvel utilisateur.
     * On récupère les données du formulaire, on crée un objet Utilisateur,
     * on hache le mot de passe pour la sécurité (jamais stocker en clair),
     * puis on persiste l'utilisateur en base de données.
     */
    #[Route('/register', name: 'ecommerce_register')]
    public function inscription( Request $request, EntityManagerInterface $em ) : Response
    {
        $user = new Utilisateur();
        $user->setEmail($request->request->get('email'));
        $user->setNomComplet($request->request->get('fullName'));
        $user->setRoles(['ROLE_USER']);

        $hashedPassword = $this->passwordHelper->hacherMotDePasse(
            $user,
            $request->request->get('password')
        );
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('ecommerce_login');
    }
}

?>
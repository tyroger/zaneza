<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * Permet de se connecter à son compte 
     * @Route("/login", name="user_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render(
            'user/login.html.twig',
            [
                'hasError' => $error !== null,
                'username' => $username,
                "connectAct"    => "se connecter"
            ]
        );
    }


    /**
     * Permet de se deconnecter
     * @return void
     *@Route("/logout", name ="user_logout")
     */
    public function logout()
    {
        // Actions gerées par symfony seul
    }



    /**
     * Permet d'afficher la page d'accueil de l'utilisateur
     *
     * @Route("/show", name="user_show")
     */
    public function show()
    {
        return $this->render(
            "/user/journal.html.twig",
            [
                "user" => $this->getuser(),
            ]
        );
    }



}

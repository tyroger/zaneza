<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AccountController extends AbstractController
{
    /**
     * Afficher le formulaire d'inscription
     * @return Response
     * @Route("/register", name="account_register")
     */
    public function accountRegister(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        // instancier un nouvel utilisateur
        $user = new User();
        // Créer un formulaire et récuperer les données de l'utilisateur
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        // vérifier le formulaire et persister ses données dans la base de donnée
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();

            //addFlash ne fonctionne pas! trouver pourquoi!!!
            $this->addFlash(
                'Success',
                "Votre compte à bien été créé"
            );
            //si le formulaire est valide, rediriger vers la page de connexion
            return $this->redirectToRoute('user_login');
        }
        //à l'appel de la fonction afficher la page de création d'un compte utilisateur
        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }



    /**
     * Afficher les informations existantes du profil
     * avec possibilité de modification
     * 
     *@Route("account/show", name = "account_show")
     *
     *@return Response
     */
    public function accountShow(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addflash('succes', 'vos modifications ont été prise en compte');
        }
        return $this->render(
            'account/profile.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }



    /**
     * Formulaire de modification du mot de passe
     * 
     * @Route("/account/password-update", name="account_password")
     *
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!password_verify($passwordUpdate->getOldPassword(), $user->gethash())) {
                $form->get('oldPassword')->addError(new FormError("mot de passe incorrect"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('succes', 'Votre mot de passe à bien été modifié');
                return $this->redirectToRoute('user_show');
            }
        }
        return $this->render(
            'account/password.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    

    /**
     * Permet de supprimer le compte personnelle
     *
     * @Route("/account/delete", name = "account_delete")
     * 
     * @return void
     * 
     */
    public function accountDelete(UserRepository $repo, EntityManagerInterface $manager,TokenStorageInterface $tokenStorage,SessionInterface $session)
    {
        $id = $this->getUser();
        $user = $repo->findOneById($id);
        $manager->remove($user);
        $manager->flush();
        $tokenStorage->setToken(null);
		  $session->invalidate();
        return $this->redirectToRoute("homepage");
    }
}

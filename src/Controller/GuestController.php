<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Invitation;
use App\Form\GuestAddType;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GuestController extends AbstractController
{
    /**
     * Permet d'ajouter des convives
     * 
     *@Route("/guest/add/{eventId}", name="guest_add")
     */
    public function guestAdd($eventId, Request $request, EventRepository $repo, UserRepository $userRepo, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        //récuperation de l'évènement en question
        $myEvent = $repo->findOneById($eventId);

        //A qui sera envoyé l'invitation.
        $guest = new User();
        $guestForm = $this->createForm(GuestAddType::class, $guest);
        $guestFormView = $guestForm->createView();
        $guestForm->handleRequest($request);
        $guestName = $guestForm->get('guestName')->getData();

        // guestForm déplacée cause erreur lors de la soumission d'un user existant au niveau du mail "mail déjà existant" 
        if ($guestForm->isSubmitted()) {
            //générér une nouvelle invitation pour l'évènement choisi
            $myInvitation = new Invitation();
            //inserer pour réinitialiser toutes les etats des invitations à zéro
            $myInvitation->setSent(false)->setAccepted(false)->setRefused(false);
            //vérifier si l'adresse email entrée existe déjà dans la base
            $guestExiste = $userRepo->findOneByEmail($guest->getEmail());
            // pour un utilisateur existant => récupérer son adresse email  - à mettre à jour
            if ($guestExiste) {
                $myInvitation->setUser($guestExiste);
                $myInvitation->setGuestName($guestName);

                // $myEvent->addInvited($emailUser);
            } else {
                //si utilisateur non existant => en créer un et le rajouter dans la BDD
                $hash = $encoder->encodePassword($guest, $guest->getHash());
                $guest->setHash($hash);
                $guest->setFirstName($guestName);
                $manager->persist($guest);
                $manager->flush();
                $myInvitation->setUser($guest);
                $myInvitation->setGuestName($guestName);
            }
            $myInvitation->setEvent($myEvent);
            $manager->persist($myInvitation);
            $manager->flush();
        }
        return $this->render('guest/guestAdd.html.twig', [
            'guestForm' => $guestFormView,
            'event' => $myEvent,
        ]);
    }

    /**
     * Permet de supprimer un convive
     *
     * @Route("/guest/delete/{eventId}/{userId}", name="guest_delete")
     */
    public function guestDelete($eventId, $userId, InvitationRepository $repo, EntityManagerInterface $manager)
    {
        //Identification de l'evenement en question
        $event = $repo->findOneByEvent($eventId);
        /*indentification de l'utilisateur en question
        en utilisant findOneByUser la fonction ne marche pas à clarifier*/
        $guest = $repo->findOneById($userId);

        $manager->remove($guest);
        $manager->flush();

        return $this->redirectToRoute(
            'guest_add',
            [
                "eventId" => $eventId
            ]
        );
    }
}

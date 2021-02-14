<?php

namespace App\Controller;

use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvitationController extends AbstractController
{

    /**
     * Permet d'afficher l'ensemble des invitations reçues
     *
     * @Route("/show/invitation", name="invitation_showAll")
     */
    public function myInvitations()
    {
        $jourSemaine = array('Dim', 'lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam');
        return $this->render('invitation/myInvitations.html.twig', ['JourSemaine' => $jourSemaine]);
    }


    /**
     * Permet d'afficher une invitation particulière reçue
     *
     *  @Route("/show/invitation/{invitationId}", name="invitation_show")
     * 
     */
    public function showOneInvitation($invitationId, InvitationRepository $repo)
    {
        //identification de l'évènement en question
        $invitation = $repo->findOneById($invitationId);
        //identification des invités liés à l'évènement
        $guests = $invitation->getUser();

        return $this->render(
            "invitation/invitation.html.twig",
            [
                'invitation' => $invitation,
                'guests'     => $guests
            ]
        );
    }


    /**
     * Permet d'identifier les invités qui ont réçu des invitations
     *
     * @Route("/invitation/sent/{eventId}", name="invitation_sent")
     */
    public function invitationSent($eventId, InvitationRepository $repo, EntityManagerInterface $manager)
    {
        // recupération du tableau contenant les invitations liées à l'évènement
        $invitations = $repo->findByEvent($eventId);
        //Vérification de l'état SENT pour chaque invité
        foreach ($invitations as $invitation) {
            //si l'état sent est à 0, le passer à 1 pour signifier l'envois de l'invitation
            if ($invitation->getSent() == false) {
                /*****Integration de la procédure d'envois d'email*****/
                /*****début*****/
                $guestEmail = $invitation->getUser()->getEmail();
                $owner = $this->getUser()->getFirstName();
                $dateOfEvent = $invitation->getEvent()->getDate();

                //interger ici les vérifications de l'email

                if (isset($_POST['confirmEmail'])) {
                    $to        = $guestEmail;
                    $subject   = 'Invitation de ' . $owner;
                    $message   = $this->render('mailer/emailInvitation.html.twig', ['dateOfEvent' => $dateOfEvent]);
                    $headers   = [];
                    $headers[] = 'From: invitation@tucoderas.net';
                    $headers[] = 'Content-type: text/html; charset=UTF-8';
                    $result    = mail($to, $subject, $message, implode("\r\n", $headers));

                    if (!$result) {
                        return new Response(`l'invitation n'a pas pu être envoyé`);
                    }
                }
                /*****fin*****/
                $invitation->setSent(true);
            }
            //validation de la modification dans la base de donnée
            $manager->flush();
        }
        // retour à la page d'ajout d'invités
        return $this->redirectToRoute(
            'guest_add',
            ["eventId" => $eventId]
        );
    }


    /**
     * Permet de confirmer l'acceptation d'une invitation
     *
     *@Route("/invitation/accepted/{invitationId}" , name="invitation_accepted")
     */
    public function invitationAccepted($invitationId, InvitationRepository $repo, EntityManagerInterface $manager)
    {
        //récuperer une invitation réçu
        $myInvitation = $repo->findOneById($invitationId);
        //vérifier l'état du status "accepted"
        $myInvitation->getAccepted();
        //si l'état du status "accepted" est à 0 le passer à 1
        if (($myInvitation->getSent() == true) && ($myInvitation->getAccepted() == false)) {
            $myInvitation->setAccepted(true);
            // var_dump($myInvitation);
            $manager->flush();
        }
        //revenir à la page de mes invitations reçues
        return $this->redirectToRoute('invitation_showAll');
    }



    /**
     * Permet de confirmer le refus d'une invitation
     *
     *@Route("/invitation/refused/{invitationId}" , name="invitation_refused")
     */
    public function invitationRefused($invitationId, InvitationRepository $repo, EntityManagerInterface $manager)
    {
        //récuperer une invitation réçu
        $myInvitation = $repo->findOneById($invitationId);
        //vérifier l'état du status "refused"
        $myInvitation->getRefused();
        //si l'état du status "refused" est à 0 le passer à 1
        if (($myInvitation->getSent() == true) && ($myInvitation->getRefused() == false)) {
            $myInvitation->setRefused(true);
            // var_dump($myInvitation);
            $manager->flush();

            //ajouter un flash de confirmation de la suppression de l'invitation
        }
        //revenir à la page de mes invitations reçues
        return $this->redirectToRoute('invitation_showAll');
    }
}

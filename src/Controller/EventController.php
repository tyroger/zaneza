<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Invitation;
use App\Form\EventAddType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    /**
     * Permet de créer un évènement
     * 
     * @Route("/event", name="event_register")
     */

    public function eventRegister(Request $request, EntityManagerInterface $manager)
    {
        $event = new Event();
        $form = $this->createForm(EventAddType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $user->addEvent($event);

            $manager->persist($user);
            $manager->persist($event);
            $manager->flush();

            $myInvitation = new Invitation();
            $myInvitation->setUser($this->getUser());
            $myInvitation->setSent(true);
            $myInvitation->setAccepted(true);
            $myInvitation->setEvent($event);

            $manager->persist($myInvitation);
            $manager->flush();

            $this->addflash('succes', 'votre évènement a été correctement créé!');

            return $this->redirectToRoute('guest_add', [
                'eventId' => $event->getId(),
            ]);
        }
        return $this->render('event/eventAdd.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * Permet d'afficher la liste de tout les évènements 
     *
     * @param EventRepository $repo
     * 
     * @Route("/event/show/{userId}",name="event_show")
     */
    public function eventShow($userId, EventRepository $repo)
    {
        $userId = $this->getUser();
        $events = $repo->findByOwner($userId);
        SORT($events);
        return $this->render(
            'event/eventShow.html.twig',
            [
                "events" => $events,
            ]
        );
    }




    /**
     * Permet de mettre à jour un évènement existant
     *
     * @Route("/event/update/{eventId}", name="event_update")
     */
    public function eventUpdate($eventId, Request $request, EntityManagerInterface $manager, EventRepository $repo)
    {
        $event = $repo->findOneById($eventId);
        $form = $this->createForm(EventAddType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($event);
            $manager->flush();
            //addFlash ne fonctionne pas! trouver pourquoi!!!
            $this->addflash('succes', 'vos modifications ont été prise en compte');
            return $this->redirectToRoute('event_show', ['id' => $eventId, 'userId' => $this->getUser()]);
        }
        return $this->render(
            'event/eventAdd.html.twig',
            [
                'form' => $form->createView(),
                "event" => $event,
            ]
        );
    }



    /**
     * Permet de supprimer un évènement existant
     *
     * @Route("/event/delete/{eventId}", name="event_delete")
     */
    public function eventDelete($eventId, EventRepository $repo, EntityManagerInterface $manager)
    {
        $userId = $this->getUser()->getId();
        $event = $repo->findOneById($eventId);
        $manager->remove($event);
        $manager->flush();
        return $this->redirectToRoute('event_show', ['userId' => $userId]);
    }


    /* 
    créer une fonction qui afficher le prochain évènement organisé

    créer une fonction qui affiche le dérniere évènement passé au quel j'ai été invité

    */
}

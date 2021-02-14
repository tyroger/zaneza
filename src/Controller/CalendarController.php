<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalendarController extends AbstractController
{
    /**
     * afficher tous les évènements inférieurs à aujourd'hui:
     * 
     * @Route("/calendar", name="calendar")
     */
    public function eventsCalendar(EventRepository $repo)
    {
        // définir la personne concernée
        $userId = $this->getUser()->getId();

        // aujourdui la date de référence
        $today = new \DateTime("now");
        $futureEvents = [];
        $lastsEvents = [];

        // récuperation du tableau des événements
        $events = $repo->findByOwner($userId);

        // comparaison de la date de chaque événement
        foreach ($events as $event) {
            $eventDate = $event->getDate();

            // evaluer la différence entre les deux date
            $dateInterval = $today->diff($eventDate);

            // récupérer le nombre des jours de différence
            $difference = $dateInterval->format('%R%a');

            // si la différence est positive, récuperer le tableau et le trier dans l'ordre ascendant
            // puis récuperer la première valeur du tableau
            if ($difference >= 0) {
                array_push($futureEvents, $event);
                SORT($futureEvents);
                return $futureEvents;
            }

            // si la différence est négative, récuperer le tableau et le trier dans l'ordre descendant
            // puis récuperer la première valeur du tableau
            elseif ($difference < 0) {
                array_push($lastEvents, $event);
                SORT($lastEvents);
                return $futureEvents;
            }
        }

        return $this->redirectToRoute('user_show', [
            'futureEvents' => $futureEvents,
            'lastsEvents'  => $lastsEvents
        ]);
    }
}

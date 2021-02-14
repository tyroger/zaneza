<?php

namespace App\Controller;

use App\Entity\Contribution;
use App\Form\ContributionType;
use App\Repository\ContributionRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContributionController extends AbstractController
{

    /**
     * Perment d'ajouter une contribution sur un évènement
     * 
     * @Route("/contribution/add/{eventId}", name="contribution_add")
     *
     * @return Response
     */
    public function contributionAdd($eventId, EventRepository $repo, ContributionRepository $contribRepo, Request $request, EntityManagerInterface $manager)
    {   //afficher le tableau des contributions
        $contributions = $contribRepo->findByEvent($eventId);
        //créer une nouvelle contribution
        $contribution = new Contribution();
        //identification de l'invité
        $contribution->setParticipant($this->getUser());
        //identification de l'évènement
        $event = $repo->findOneById($eventId);
        $contribution->setEvent($event);
        
        //saisi contribution dans le formulaire 
        $form = $this->createForm(ContributionType::class, $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($contribution);
            $manager->flush();
            //revenir sur la même page.
            return $this->redirectToRoute(
                'contribution_add',
                [
                    'eventId' => $eventId,
                    'participantId' => $contribution->getParticipant(), // a voir l'utilité
                    'contributions' => $contributions
                ]
            );
        }
        return $this->render(
            'contribution/contribution.html.twig',
            [
                'form' => $form->createView(),
                // récuperation de l'objet "event"
                'event' => $contribution->getEvent(),
                'contributions' => $contributions
            ]
        );
    }


    /**
     * permet de mettre à jour sa propre contribution 
     * 
     * @Route("/contribution/update/{contributionId}", name="contribution_update")
     * 
     */
    public function contributionUpdate($contributionId, ContributionRepository $contribRepo, Request $request, EntityManagerInterface $manager)
    {  
         // récupération de la contribution en question
        $myContribution = $contribRepo->findOneById($contributionId);

        //le var_dump ne fonctionne pas!!! 
        // var_dump($myContribution);

        //récuperer dans le formulaire les infos existantes
        $form = $this->createForm(ContributionType::class, $myContribution);
        //récuperer le nouveau contenu du formulaire
        $form->handleRequest($request);

        //valider et envoyer dans la base de donnée
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($myContribution);
            $manager->flush();

            return $this->redirectToRoute('contribution_add',['eventId'=>$myContribution->getEvent()->getId()]);
        }
        // retour à la même vue
        return $this->render(
            'contribution/contribution.html.twig',
            [
                'form' => $form->createView(),
                // récuperation de l'objet "event"
                'event' => $myContribution->getEvent()
            ]
        );
    }


    /**
     * permet de supprimer sa propre contribution 
     * 
     * @Route("/contribution/delete/{contributionId}", name="contribution_delete")
     */
    public function contributionDelete($contributionId, ContributionRepository $contribRepo, EntityManagerInterface $manager)
    {
        //Récupération de la contribution à supprimer
        $myContribution = $contribRepo->findOneById($contributionId);
        //Supprimer la contribution
        $manager->remove($myContribution);
        // mise à jour de la base de donnée
        $manager->flush();
        return $this->redirectToRoute(
            'contribution_add',
            [
                'eventId' => $myContribution->getEvent()->getId()
            ]
        );
    }

}

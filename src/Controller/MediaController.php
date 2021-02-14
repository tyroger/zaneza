<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MediaController extends AbstractController
{
  /**
   * accès page d'ajout des images
   *
   * @Route("/media/add/{eventId}", name="media_add")
   */
  public function mediaAdd($eventId, EventRepository $repo, Request $request, EntityManagerInterface $manager)
  {
    // récuperation de l'évènement (l'objet)
    $event = $repo->findOneById($eventId);
    // récuperation de l'auteur
    $userId = $this->getUser()->getId();

    // création d'un nouveau media
    $media = new Media();

    // création du formulaire
    $form = $this->createForm(
      MediaType::class,
      $media
    );

    //récuperation du formulaire
    $form->handleRequest($request);


    // validation du formulaire
    if ($form->isSubmitted() && $form->isValid()) {
      $media->setMediaAuthor($this->getUser());
      $media->setMediaEvent($event);
      $manager->persist($media);

      $manager->flush();

      //nom du dossier final de récuperation sur le serveur
      $dirName = "event_" . $eventId;

      // définir l'emplacement du fichier
      $dir = "uploads/$dirName";
      if (!file_exists($dir)) {
        mkdir($dir);
      }
      $fileName = "Media_" . $media->getId();
      $finalPath = $dir . "/" . $fileName;
      move_uploaded_file($media->getUrl(), $finalPath);

      $media->setUrl($finalPath);
      $manager->flush();
    }

    return $this->render(
      'media/mediaUpload.html.twig',
      [
        "form" => $form->createView(),
        'event' => $event,
      ]
    );
  }


  /**
   * Afficher l'ensemble des medias appartenant à un évènement
   * 
   * @Route("/media/showAll/{eventId}", name="media_showAll")
   */
  public function mediaShowAll()
  {
    return $this->render('media/showAllMedia.html.twig');
  }

  /**
   * Afficher un média appartennant à un evenement
   * 
   * @Route("/media/show/{eventId}/{mediaId}", name="media_show")
   */
  public function mediaShow()
  {

    return $this->render('media/showOneMedia.html.twig');
  }
}

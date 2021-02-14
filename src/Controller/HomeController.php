<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home()
    {
        return $this->render('home/home.html.twig',
        [
            "title"         => "Auberge Espagnole",
            "slogan1"       => "Pour les adeptes des soirées auberge espagnole.",
            "slogan2"       => "Planifiez et suivez tous vos événements en ligne !",
            "connectAct"    => "se connecter",
            "subscribAct"   => "Je crée mon compte",
            "service1"      => "Créez et plannifiez vos évènements",
            "service2"      => "Invitez vos amis et vos proches",
            "service3"      => "Partagez vos meilleurs moments",
        ]);
    }
}

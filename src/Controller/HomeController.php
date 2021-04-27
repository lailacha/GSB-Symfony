<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Redirige l'utilisateur vers le portail si il n'est pas connecté ou vers le tableau de bord dans le cas inverse
     * @Route("/welcome", name="welcome")
     */
    public function index(): Response
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('app_login');
        }
        else{
            return $this->render('home/index.html.twig');
        }
    }
}

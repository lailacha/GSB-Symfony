<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController

{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
        ->getRepository(User::class)
        ->findAll();

        $user =  [
            ['name' => 'John'],
        ['name' => 'Mireille']
    ];


        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController','user' => $users
        ]);
    }
}

<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Visiteur;
use App\Entity\Lignefraishorsforfait;
use App\Repository\FicheFraisRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AppTest extends KernelTestCase
{


    public function testRepo()
    {
        $kernel = self::bootKernel();
        $test = self::$container->get(FicheFraisRepository::class)->count([]);
        $this->assertEquals(2, $test);
    }


    public function testTest()
    {




        $this->assertEquals(2, 1 + 1);
    }


    public function test_user_can_report_fortfait()
    {
        //GIVEN
        // Je suis un user authentifié et je souhaite reporter un frais
        // 
        $user = new Visiteur();
        $user->id = "test";
        $user->nom = "test";
        $user->prenom = "laila";
        $user->login = "test";
        $user->mdp = "test";







        //WHEN
        // Si je clique sur l'icone j'envoie une requete POST


        //THEN
        // Je reporte le frais sur la fiche du mois prochain


    }
}

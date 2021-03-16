<?php

namespace App\Tests;

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
}

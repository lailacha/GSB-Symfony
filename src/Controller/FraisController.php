<?php

namespace App\Controller;

use App\Entity\Visiteur;
use App\Entity\LigneFraisForfait;
use App\Repository\VisiteurRepository;
use App\Repository\FicheFraisRepository;

use App\Repository\FraisForfaitRepository;
use App\Repository\LigneFraisHFRepository;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\LigneFraisForfaitRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FraisController extends AbstractController
{


    private $LigneFraisForfaitRepository;
    private $fraisForfaitRepository;
    private $fraisHorsForfaitRepository;

    public function __construct(LigneFraisForfaitRepository $ligneFraisForfaitRepository, FraisForfaitRepository $fraisForfaitRepository, LigneFraisHFRepository $fraisHorsForfaitRepository)
    {
        $this->ligneFraisForfaitRepository = $ligneFraisForfaitRepository;
        $this->fraisForfaitRepository = $fraisForfaitRepository;
        $this->fraisHorsForfaitRepository = $fraisHorsForfaitRepository;
    }

    /**
     * @Route("/frais", name="frais")
     */
    public function index(): Response
    {

        $frais = $this->getDoctrine()->getRepository(LigneFraisForfait::class)->find(2);
        return $this->render('frais/index.html.twig', compact('frais'));
    }

    /**
     * @Route("/frais/validation", name="validateFrais")
     */
    public function validation(VisiteurRepository $visiteurRepository)
    {
        $visiteurs = $visiteurRepository->findAll();

        return $this->render('frais/validation.html.twig', compact('visiteurs'));
    }

    /**
     * @Route("/frais/mois", name="sendMois")
     */
    public function sendMois(Request $request)
    {
        return $this->json($this->ficheFraisRepository->findBy(['idvisiteur' => $request->get('id')]), 200, [], ['groups' => 'fiche:mois']);
    }

    /**
     * @Route("/frais/fiche", name="sendFiche")
     */
    public function sendFiche(Request $request)
    {
        $fraisHF = $this->fraisHorsForfaitRepository->findBy([
            'idvisiteur' => $request->get('idvisiteur'),
            "mois" => $request->get('mois')
        ]);
        $fraisF = $this->fraisForfaitRepository->findBy([
            'idvisiteur' => $request->get('idvisiteur'),
            "mois" => $request->get('mois')
        ]);

        dd($fraisF);
        return $this->render('frais/tab-fiche.html.twig', compact('fraisHF', 'fraisF'));
    }

    //TODO écrire un service qui récup les frais HF et FF by mois et idvisiteur
}

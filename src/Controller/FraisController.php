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


    private $ligneFraisForfaitRepository;
    private $fraisForfaitRepository;
    private $ligneFraisHorsForfaitRepository;

    public function __construct(LigneFraisForfaitRepository $ligneFraisForfaitRepository, FraisForfaitRepository $fraisForfaitRepository, LigneFraisHFRepository $ligneFraisHorsForfaitRepository)
    {
        $this->ligneFraisForfaitRepository = $ligneFraisForfaitRepository;
        $this->fraisForfaitRepository = $fraisForfaitRepository;
        $this->ligneFraisHorsForfaitRepository = $ligneFraisHorsForfaitRepository;
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
    public function sendMois(Request $request, FicheFraisRepository $ficheFraisRepository)
    {
        return $this->json($ficheFraisRepository->findBy(['idvisiteur' => $request->get('id')]), 200, [], ['groups' => 'fiche:mois']);
    }

    /**
     * @Route("/frais/fiche", name="sendFiche")
     */
    public function sendFiche(Request $request)
    {


        $fraisF = $this->ligneFraisForfaitRepository->findBy([
            'visiteur' => "a17", "mois" => $request->get('mois')
        ]);

        $fraisHF = $this->ligneFraisHorsForfaitRepository->findBy([
            'idvisiteur' => "a17",
            "mois" => $request->get('mois')
        ]);

        return $this->render('frais/frais-tab.html.twig', compact('fraisF', 'fraisHF'));
    }

    //TODO écrire un service qui récup les frais HF et FF by mois et idvisiteur
}

<?php

namespace App\Controller;

use App\Entity\Visiteur;
use App\Entity\LigneFraisForfait;
use App\Repository\UserRepository;
use App\Repository\FicheFraisRepository;
use App\Service\FraisService;
use App\Repository\FraisForfaitRepository;
use App\Repository\LigneFraisHFRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    private $LignefraishorsforfaitRepository;
    private $fraisSevice;
    private $ficheFraisRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, FicheFraisRepository $ficheFraisRepository, LigneFraisForfaitRepository $ligneFraisForfaitRepository, FraisForfaitRepository $fraisForfaitRepository, LigneFraisHFRepository $LignefraishorsforfaitRepository, FraisService $fraisService)
    {
        $this->ligneFraisForfaitRepository = $ligneFraisForfaitRepository;
        $this->fraisForfaitRepository = $fraisForfaitRepository;
        $this->LignefraishorsforfaitRepository = $LignefraishorsforfaitRepository;
        $this->fraisService = $fraisService;
        $this->ficheFraisRepository = $ficheFraisRepository;
        $this->entityManager = $entityManager;
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
    public function validation(UserRepository $userRepository)
    {
        $visiteurs = $userRepository->findAll();

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
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function sendFiche(Request $request, UserRepository $userRepository)
    {

        $visiteur = $userRepository->find($request->get('visiteur'));
        $mois = $request->get('mois');


        $fraisF = $this->ligneFraisForfaitRepository->findBy([
            'visiteur' => $visiteur, "mois" => $mois
        ]);

        $fraisHF = $this->LignefraishorsforfaitRepository->findBy([
            'idvisiteur' => $visiteur,
            "mois" => $mois
        ]);


        return $this->render('frais/frais-tab.html.twig', compact('fraisF', 'fraisHF', 'visiteur','mois'));
    }


    /**
     * @Route("/frais/fiche/validate", name="validateFiche")
     */
    public function validerFiche(Request $request)
    {
        $fiche = $this->ficheFraisRepository->findOneBy([
            'idvisiteur' => $request->get('idVisiteur'),
            'mois' => $request->get('mois')
        ]);

        if (is_null($fiche))
        {
            $this->addFlash('error', "La fiche n'existe pas");
            return $this->redirectToRoute('validateFrais', 404);

        }

        $fiche->setIdetat($this->entityManager->getReference('App\Entity\Etat', 'CL'));
        $this->entityManager->persist($fiche);
        $this->entityManager->flush();
        $this->addFlash('sucess', 'Super le frais est enregistré!');
        return $this->redirectToRoute('validateFrais');

    }


    /**
     * @Route("/frais/fiche/paiement", name="paiement_action")
     */
    public function actionPaiement(Request $request)
    {
        $fiche = $this->ficheFraisRepository->findOneBy([
            'idvisiteur' => $request->get('idVisiteur'),
            'mois' => $request->get('mois')
        ]);

        if (is_null($fiche))
        {
            $this->addFlash('error', "La fiche n'existe pas");
            return $this->redirectToRoute('validateFrais', 404);
        }

        if ($fiche->getIdetat() === "VA")
            $fiche->setIdetat($this->entityManager->getReference('App\Entity\Etat', 'RB'));
        else
            if ($fiche->getIdetat()==="CL")
            {
                $fiche->setIdetat($this->entityManager->getReference('App\Entity\Etat', 'VA'));

            }

        $this->entityManager->persist($fiche);
        $this->entityManager->flush();
        $this->addFlash('sucess', 'Super le frais a été modifié!');
        $this->redirect($this->Session->read('referer'));
        $this->Session->delete('referer');
    }


}

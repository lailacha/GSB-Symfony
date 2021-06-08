<?php

namespace App\Controller;

use App\Repository\FicheFraisRepository;
use App\Repository\FraisForfaitRepository;
use App\Repository\LigneFraisForfaitRepository;
use App\Repository\LigneFraisHFRepository;
use App\Repository\UserRepository;
use App\Service\FraisService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class FraisController
 * @package App\Controller
 */
class FraisController extends AbstractController
{


    /**
     * @var LigneFraisForfaitRepository
     */
    private $ligneFraisForfaitRepository;
    /**
     * @var FraisForfaitRepository
     */
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
     * Renvoie la vue permettant de choisir un visiteur pour récupérer sa fiche de frais
     *
     * @Route("/frais/validation", name="validateFrais")
     */
    public function validation(UserRepository $userRepository)
    {
        $visiteurs = $userRepository->findAll();

        return $this->render('frais/validation.html.twig', compact('visiteurs'));
    }

    /**
     * Renvoie les fiches disponibles (mois) pour un visiteur donné
     *
     * @Route("/frais/mois", name="sendMois")
     * @param Request $request
     * @param FicheFraisRepository $ficheFraisRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendMois(Request $request, FicheFraisRepository $ficheFraisRepository)
    {
        return $this->json($ficheFraisRepository->findBy(['idvisiteur' => $request->get('id')]), 200, [], ['groups' => 'fiche:mois']);
    }

    /**
     * Renvoie la fiche de frais du visiteur en question
     *
     * @Route("/frais/fiche", name="sendFiche")
     * @param Request $request
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
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
     *Permet de valider une fiche de frais
     *
     * @Route("/frais/fiche/validate", name="validateFiche")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
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
        $this->addFlash('sucess', 'Super la fiche est validée!');
        return $this->redirectToRoute('validateFrais');

    }


    /**
     * Change l'état d'une fiche selon l'état initial
     *
     * - Si une fiche est validée on l'a rembourse
     * - Si une fiche est cloturée, on l'a valide
     *
     * @Route("/frais/fiche/paiement", name="paiement_action")
     * @param Request $request
     * @return Response
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

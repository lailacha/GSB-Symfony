<?php

namespace App\Controller;

use App\Service\FraisService;
use App\Entity\Lignefraishorsforfait;
use App\Form\LignefraishorsforfaitType;
use App\Repository\FicheFraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FraisForfaitRepository;
use App\Repository\LigneFraisHFRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\LigneFraisForfaitRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/lignefraishorsforfait")
 */
class LignefraishorsforfaitController extends AbstractController
{


    private $ligneFraisForfaitRepository;
    private $fraisForfaitRepository;
    private $ligneFraisHorsForfaitRepository;
    private $entityManager;
    private $ficheFraisRepository;

    public function __construct(FicheFraisRepository $ficheFraisRepository, EntityManagerInterface $entityManager, LigneFraisForfaitRepository $ligneFraisForfaitRepository, FraisForfaitRepository $fraisForfaitRepository, LigneFraisHFRepository $ligneFraisHorsForfaitRepository)
    {
        $this->ligneFraisForfaitRepository = $ligneFraisForfaitRepository;
        $this->entityManager = $entityManager;
        $this->ficheFraisRepository = $ficheFraisRepository;
        $this->fraisForfaitRepository = $fraisForfaitRepository;
        $this->ligneFraisHorsForfaitRepository = $ligneFraisHorsForfaitRepository;
    }

    /**
     * @Route("/", name="lignefraishorsforfait_index", methods={"GET"})
     */
    public function index(LigneFraisHFRepository $ligneFraisHFRepository): Response
    {
        return $this->render('lignefraishorsforfait/index.html.twig', [
            'lignefraishorsforfaits' => $ligneFraisHFRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="lignefraishorsforfait_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lignefraishorsforfait = new Lignefraishorsforfait();
        $form = $this->createForm(LignefraishorsforfaitType::class, $lignefraishorsforfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lignefraishorsforfait);
            $entityManager->flush();

            return $this->redirectToRoute('lignefraishorsforfait_index');
        }

        return $this->render('lignefraishorsforfait/new.html.twig', [
            'lignefraishorsforfait' => $lignefraishorsforfait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lignefraishorsforfait_show", methods={"GET"})
     */
    public function show(Lignefraishorsforfait $lignefraishorsforfait): Response
    {
        return $this->render('lignefraishorsforfait/show.html.twig', [
            'lignefraishorsforfait' => $lignefraishorsforfait,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lignefraishorsforfait_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lignefraishorsforfait $lignefraishorsforfait): Response
    {
        $form = $this->createForm(LignefraishorsforfaitType::class, $lignefraishorsforfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lignefraishorsforfait_index');
        }

        return $this->render('lignefraishorsforfait/edit.html.twig', [
            'lignefraishorsforfait' => $lignefraishorsforfait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/fraisHF/delete/{id}", name="lignefraishorsforfait_delete", methods={"POST"})
     */
    public function delete(Request $request, Lignefraishorsforfait $lignefraishorsforfait): Response
    {
        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lignefraishorsforfait);
            $entityManager->flush();
            return $this->json(["rep" => "reussi"], 200, [], []);
        } else
            return $this->json(["rep" => "non"], 400, [], []);
    }

    /**
     * @Route("/fraisHF/report/{id}", name="lignefraishorsforfait_report", methods={"POST"})
     */
    public function reportFrais(Request $request, Lignefraishorsforfait $lignefraishorsforfait, FraisService $fraisService)
    {
        $visiteur = $lignefraishorsforfait->getIdvisiteur()->getId();

        if ($this->isCsrfTokenValid('report', $request->request->get('_token'))) {
            $ficheExist = $fraisService->checkIfFicheExist($visiteur, $lignefraishorsforfait->getMois());
            if ($ficheExist) {

                $this->entityManager->remove($lignefraishorsforfait);
                $this->entityManager->flush();
                $lastMonth = $this->ficheFraisRepository->getLastMonthByVisiteur($visiteur);
                $lastFiche = $this->ficheFraisRepository->findBy([
                    'idvisiteur' => $visiteur,
                    'mois' => $lastMonth,
                ]);

                $rep = 'Super';
            } else {
                $rep = 'Impossible de cloturer la fiche';
            }

            return $this->json(["rep" => $rep], 200, [], []);
        } else
            return $this->json(["rep" => "non"], 400, [], []);
    }
}

<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Fichefrais;
use App\Service\FraisService;
use Doctrine\ORM\EntityManager;
use App\Entity\LigneFraisForfait;
use App\Repository\EtatRepository;
use App\Entity\Lignefraishorsforfait;
use App\Form\LignefraishorsforfaitType;
use App\Repository\FicheFraisRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FraisForfaitRepository;
use App\Repository\LigneFraisHFRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\LigneFraisForfaitRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/Lignefraishorsforfait")
 */
class LignefraishorsforfaitController extends AbstractController
{


    private $ligneFraisForfaitRepository;
    private $fraisForfaitRepository;
    private $LignefraishorsforfaitRepository;
    private $entityManager;
    private $ficheFraisRepository;

    public function __construct(FicheFraisRepository $ficheFraisRepository, EntityManagerInterface $entityManager, LigneFraisForfaitRepository $ligneFraisForfaitRepository, FraisForfaitRepository $fraisForfaitRepository, LigneFraisHFRepository $LignefraishorsforfaitRepository)
    {
        $this->ligneFraisForfaitRepository = $ligneFraisForfaitRepository;
        $this->entityManager = $entityManager;
        $this->ficheFraisRepository = $ficheFraisRepository;
        $this->fraisForfaitRepository = $fraisForfaitRepository;
        $this->LignefraishorsforfaitRepository = $LignefraishorsforfaitRepository;
    }

    /**
     * @Route("/", name="Lignefraishorsforfait_index", methods={"GET"})
     */
    public function index(LigneFraisHFRepository $ligneFraisHFRepository): Response
    {
        return $this->render('Lignefraishorsforfait/index.html.twig', [
            'Lignefraishorsforfaits' => $ligneFraisHFRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="Lignefraishorsforfait_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $Lignefraishorsforfait = new Lignefraishorsforfait();
        $form = $this->createForm(LignefraishorsforfaitType::class, $Lignefraishorsforfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Lignefraishorsforfait);
            $entityManager->flush();

            return $this->redirectToRoute('Lignefraishorsforfait_index');
        }

        return $this->render('Lignefraishorsforfait/new.html.twig', [
            'Lignefraishorsforfait' => $Lignefraishorsforfait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="Lignefraishorsforfait_show", methods={"GET"})
     */
    public function show(Lignefraishorsforfait $Lignefraishorsforfait): Response
    {
        return $this->render('Lignefraishorsforfait/show.html.twig', [
            'Lignefraishorsforfait' => $Lignefraishorsforfait,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="Lignefraishorsforfait_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Lignefraishorsforfait $Lignefraishorsforfait): Response
    {
        $form = $this->createForm(LignefraishorsforfaitType::class, $Lignefraishorsforfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('Lignefraishorsforfait_index');
        }

        return $this->render('Lignefraishorsforfait/edit.html.twig', [
            'Lignefraishorsforfait' => $Lignefraishorsforfait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/fraisHF/delete/{id}", name="Lignefraishorsforfait_delete", methods={"POST"})
     */
    public function delete(Request $request, Lignefraishorsforfait $Lignefraishorsforfait): Response
    {
        if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($Lignefraishorsforfait);
            $entityManager->flush();
            return $this->json(["rep" => "reussi"], 200, [], []);
        } else
            return $this->json(["rep" => "non"], 400, [], []);
    }

    /**
     * @Route("/fraisHF/report/{id}", name="Lignefraishorsforfait_report", methods={"POST"})
     */
    public function reportFrais(Request $request, Lignefraishorsforfait $Lignefraishorsforfait, FraisService $fraisService)
    {
      if ($this->isCsrfTokenValid('report', $request->request->get('_token'))) {

           $rep = $fraisService->reportFrais($Lignefraishorsforfait);
            return $this->json(["rep" => $rep], 200, [], []);
        } else
            return $this->json(["rep" => "Erreur de Token"], 400, [], []);
    }
}

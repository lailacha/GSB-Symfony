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
 * @Route("/Lignefraishorsforfait")
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
     * Permet de supprimer un frais hors forfait
     * @Route("/fraisHF/delete/{id}", name="lignefraishorsforfait_delete", methods={"POST"})
     * @param \App\Entity\Lignefraishorsforfait $Lignefraishorsforfait
     * @param Request $request
     * @return Response
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
     * Permet de reporter un frais hors forfait
     * @Route("/fraisHF/report/{id}", name="lignefraishorsforfait_report", methods={"POST"})
     * @param Request $request
     * @param \App\Entity\Lignefraishorsforfait $Lignefraishorsforfait
     * @param FraisService $fraisService
     * @return Response
     */
    public function reportFrais(Request $request, Lignefraishorsforfait $Lignefraishorsforfait, FraisService $fraisService): Response
    {
        if ($this->isCsrfTokenValid('report', $request->request->get('_token'))) {

            $rep = $fraisService->reportFrais($Lignefraishorsforfait);
            return $this->json(["rep" => $rep], 200, [], []);
        } else
            return $this->json(["rep" => "Erreur de Token"], 400, [], []);
    }
}

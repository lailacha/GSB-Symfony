<?php

namespace App\Controller;

use App\Entity\LigneFraisForfait;
use App\Form\LigneFraisForfaitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FraisForfaitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\LigneFraisForfaitRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/ligne/frais/forfait")
 */
class LigneFraisForfaitController extends AbstractController
{


    private $ligneFraisForfaitRepository;
    private $fraisForfaitRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, LigneFraisForfaitRepository $ligneFraisForfaitRepository, FraisForfaitRepository $fraisForfaitRepository)
    {
        $this->ligneFraisForfaitRepository = $ligneFraisForfaitRepository;
        $this->entityManager = $entityManager;
        $this->fraisForfaitRepository = $fraisForfaitRepository;
    }

    /**
     * Permet de supprimer un frais forfait
     * @Route("/DELETE/{id}", name="ligne_frais_forfait_delete", methods={"POST"})
     * @param Request $request
     * @param \App\Entity\LigneFraisForfait $ligneFraisForfait
     * @return Response
     */
    public function delete(Request $request, LigneFraisForfait $ligneFraisForfait): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ligneFraisForfait->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ligneFraisForfait);
            $entityManager->flush();
            return $this->json(["rep" => "reussi"], 200, [], []);
        } else
            return $this->json(["rep" => "non"], 400, [], []);
    }

    /**
     * Permet de modifier la quantité d'un forfait
     * @Route("/majFrais", name="ligne_frais_forfait_maj", methods={"POST"})
     * @param Request $request
     * @param LigneFraisForfaitRepository $ligneFraisForfaitRepository
     * @return Response
     */
    public function majFraisForfait(Request $request, LigneFraisForfaitRepository $ligneFraisForfaitRepository) : Response
    {
        if ($this->isCsrfTokenValid('maj', $request->request->get('_token'))) {

            $KeyFrais = $request->request->get('lesFraisForfait');
            foreach ($KeyFrais as $key => $quantite) {

                $fraisForfait = $ligneFraisForfaitRepository->findOneBy([
                    'visiteur' => $request->request->get('idVisiteur'),
                    'mois' => $request->request->get('mois'),
                    'fraisForfait' => $key
                ]);
                $fraisForfait->setQuantite($quantite);
                $this->entityManager->flush();

            }

            return $this->json(["rep" => "Frais modifié!"], 200, [], []);
        }
        return $this->json(["rep" => "Erreur de Token"], 400, [], []);

    }
}





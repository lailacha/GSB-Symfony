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
 * @Route("/ligne/frais/forfait")
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
     * @Route("/", name="ligne_frais_forfait_index", methods={"GET"})
     */
    public function index(LigneFraisForfaitRepository $ligneFraisForfaitRepository): Response
    {
        return $this->render('ligne_frais_forfait/index.html.twig', [
            'ligne_frais_forfaits' => $ligneFraisForfaitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ligne_frais_forfait_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ligneFraisForfait = new LigneFraisForfait();
        $form = $this->createForm(LigneFraisForfaitType::class, $ligneFraisForfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ligneFraisForfait);
            $entityManager->flush();

            return $this->redirectToRoute('ligne_frais_forfait_index');
        }

        return $this->render('ligne_frais_forfait/new.html.twig', [
            'ligne_frais_forfait' => $ligneFraisForfait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ligne_frais_forfait_show", methods={"GET"})
     */
    public function show(LigneFraisForfait $ligneFraisForfait): Response
    {
        return $this->render('ligne_frais_forfait/show.html.twig', [
            'ligne_frais_forfait' => $ligneFraisForfait,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ligne_frais_forfait_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LigneFraisForfait $ligneFraisForfait): Response
    {
        $form = $this->createForm(LigneFraisForfaitType::class, $ligneFraisForfait);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ligne_frais_forfait_index');
        }

        return $this->render('ligne_frais_forfait/edit.html.twig', [
            'ligne_frais_forfait' => $ligneFraisForfait,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/DELETE/{id}", name="ligne_frais_forfait_delete", methods={"POST"})
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
     * @Route("/majFrais", name="ligne_frais_forfait_maj", methods={"POST"})
     */
    public function majFraisForfait(Request $request, LigneFraisForfaitRepository $ligneFraisForfaitRepository)
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
            
     
      


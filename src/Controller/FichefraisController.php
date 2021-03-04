<?php

namespace App\Controller;

use App\Entity\Fichefrais;
use App\Entity\FicheSearch;
use App\Form\FichefraisType;
use App\Form\FichesearchType;
use App\Form\FicheType;
use App\Repository\FicheFraisRepository;
use App\Repository\LigneFraisForfaitRepository;
use App\Repository\LigneFraisHFRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fichefrais")
 */
class FichefraisController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="fichefrais_list", methods={"GET"})
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param FicheFraisRepository $ficheFraisRepository
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request, FicheFraisRepository $ficheFraisRepository): Response
    {

        $search = new Fichefrais();

       $form = $this->createForm(FichefraisType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $query = $ficheFraisRepository->getAll($search);

        }

        $fiches = $paginator->paginate( $ficheFraisRepository->getAll($search), $request->query->getInt('page', 1), 10);

        return $this->render('fichefrais/index.html.twig', [
            'fichefrais' => $fiches,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="fichefrais_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
       // $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('message', TextareaType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
        }
        $fichefrai = new Fichefrais();
       /*
        $form = $this->createForm(FicheType::class, $fichefrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fichefrai);
            $entityManager->flush();

            return $this->redirectToRoute('fichefrais_index');
        }*/

        return $this->render('fichefrais/new.html.twig', [
            'fichefrai' => $fichefrai,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="fichefrais_show", methods={"GET"})
     * @param Fichefrais $fichefrai
     * @param LignefraishorsforfaitRepository $LignefraishorsforfaitRepository
     * @param LigneFraisForfaitRepository $ligneFraisForfaitRepository
     * @return Response
     */
    public function show(Fichefrais $fichefrai, LigneFraisHFRepository $lignefraishorsforfaitRepository, LigneFraisForfaitRepository $ligneFraisForfaitRepository): Response
    {
        $lesfraisF = $ligneFraisForfaitRepository->findBy([
            'visiteur' => $fichefrai->getIdvisiteur(), "mois" => $fichefrai->getMois()
        ]);

        $lesfraisHF = $lignefraishorsforfaitRepository->findBy([
            'idvisiteur' => $fichefrai->getIdvisiteur(), "mois" => $fichefrai->getMois()
        ]);

        return $this->render('fichefrais/show.html.twig',  compact('fichefrai', 'lesfraisHF', 'lesfraisF'));

    }

    /**
     * @Route("admin/{mois}/edit", name="fichefrais_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Fichefrais $fichefrai): Response
    {
        $form = $this->createForm(FichefraisType::class, $fichefrai);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fichefrais_index');
        }

        return $this->render('fichefrais/edit.html.twig', [
            'fichefrai' => $fichefrai,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/{mois}", name="fichefrais_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Fichefrais $fichefrai): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fichefrai->getMois(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fichefrai);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fichefrais_index');
    }


    /**
     * @Route("admin/paiement/{id}", name="fiche_frais_paiement", methods={"GET"})
     */
    public function paiement(Fichefrais $fiche): Response
    {
        if(!$fiche)
        {
            return $this->json(["rep" => "Il n'y a pas de fiche"], 400, [], []);
        }
        if ($fiche->getIdetat()->getId() === "VA")
        { $fiche->setIdetat($this->entityManager->getReference('App\Entity\Etat', 'RB'));}
    else
        if ($fiche->getIdetat()->getId() ==="CL")
            {
                $fiche->setIdetat($this->entityManager->getReference('App\Entity\Etat', 'VA'));
            }
        $this->entityManager->persist($fiche);
        $this->entityManager->flush();
        return $this->json(["rep" => "Super"], 200, [], []);

    }
}

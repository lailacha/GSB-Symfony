<?php

namespace App\Controller;

use App\Entity\Fichefrais;
use App\Entity\FicheSearch;
use App\Entity\LigneFraisForfait;
use App\Entity\Lignefraishorsforfait;
use App\Form\FichefraisType;
use App\Form\FichesearchType;
use App\Form\FicheType;
use App\Repository\FicheFraisRepository;
use App\Repository\FraisForfaitRepository;
use App\Repository\LigneFraisForfaitRepository;
use App\Repository\LigneFraisHFRepository;
use App\Service\FraisService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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
     * @param Request $request
     * @param FraisService $fraisService
     * @param LigneFraisForfaitRepository $ligneFraisForfaitRepository
     * @param FicheFraisRepository $ficheFraisRepository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function new(Request $request, FraisService $fraisService, FraisForfaitRepository $fraisForfaitRepository, LigneFraisForfaitRepository $ligneFraisForfaitRepository,LigneFraisHFRepository $lignefraishorsforfaitRepository, FicheFraisRepository $ficheFraisRepository): Response
    {

       // $user = $this->getUser()->getId();
        $user = $this->entityManager->getReference('App\Entity\User', $this->getUser()->getId());
        //On vérifie si une fiche existe déjà sinon on en crée une
        $mois = $fraisService->getMois(date('d/m/Y'));
        $ficheExist = $fraisService->checkIfFicheExist($user, $mois);
        //dd($user, $mois, $ficheExist);

        if($ficheExist)
        {
//
//            //Si elle existe déjà

            $forfaitEtape = $ligneFraisForfaitRepository->findOneBy([
                'visiteur' => $user,
                'fraisForfait' => 'ETP',
                'mois' => $mois
            ]);


            $forfaitNuit = $ligneFraisForfaitRepository->findOneBy([
                'visiteur' => $user,
                'mois' => $mois,
                'fraisForfait' => 'NUI'
            ]);

            $forfaitKilometre = $ligneFraisForfaitRepository->findOneBy([
                'visiteur' => $user,
                'mois' => $mois,
                'fraisForfait' => 'KM'
            ]);

            $forfaitRepas = $ligneFraisForfaitRepository->findOneBy([
                'visiteur' => $user,
                'mois' => $mois,
                'fraisForfait' => 'REP'
            ]);



            //dd($forfaitEtape, $forfaitNuit, $forfaitKilometre, $forfaitRepas);

//
            $form = $this->createFormBuilder()
                ->add('ETP', TextType::class, ['label' =>'Forfait étape','attr' => ['class' => 'form-control',  'placeholder'=> $forfaitEtape->getQuantite()]])
                ->add('NUI', TextType::class , ['label' =>'Forfait nuitée','attr' => ['class' => 'form-control',  'placeholder'=> $forfaitNuit->getQuantite()]])
                ->add('KM', TextType::class , ['label' =>'Forfait kilomètrage','attr' => ['class' => 'form-control',  'placeholder'=> $forfaitKilometre->getQuantite()]])
                ->add('REP', TextType::class , ['label' =>'Forfait repas','attr' => ['class' => 'form-control',  'placeholder'=> $forfaitRepas->getQuantite()]])
                ->add('Envoyer', SubmitType::class , ['attr' => ['class' => 'form-control bg-green-dark text-white mt-3'],
                    'label' =>'Envoyer'])
                ->getForm();


        }
        else
        {
            //on créé la fiche
            $newFiche = new Fichefrais();
            $newFiche->setIdvisiteur($user);
            $newFiche->setMois($mois);
            $newFiche->setIdetat($this->entityManager->getReference('App\Entity\Etat', "CR"));
            $newFiche->setDatemodif(new \DateTime("now"));
            $this->entityManager->persist($newFiche);
            $this->entityManager->flush();

            //on initialise ses frais forfaits
            $fraisForfaitRepository->initFraisForfait($user, $mois);

            $form = $this->createFormBuilder()
                ->add('ETP', TextType::class, ['label' =>'Forfait étape', 'attr' => ['class' => 'form-control','label' =>'Envoyer' ]])
                ->add('NUI', TextType::class ,['label' =>'Forfait nuitée','attr' => ['class' => 'form-control']])
                ->add('KM', TextType::class , ['label' =>'Forfait kilomètre','attr' => ['class' => 'form-control']])
                ->add('REP', TextType::class , ['label' =>'Forfait repas','attr' => ['class' => 'form-control']])
                ->add('Envoyer', SubmitType::class , ['attr' => ['class' => 'form-control bg-green-dark text-white mt-3'],
                    'label' =>'Envoyer'])
                ->getForm();

        }

       //Sinon

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();


            //ajout des frais forfait en bdd
            foreach ($data as $key => $value)
            {

                //si le frais n'existe pas déjà, on le créé

               //Recuperer les frais correspondant
                $fraisForfait = $ligneFraisForfaitRepository->findOneBy([
                    'visiteur' => $user,
                    'mois' => $mois,
                    'fraisForfait' => $key
                ]);

                //sinon on le modifiie
                $fraisForfait->setQuantite($value);
                $this->entityManager->persist($fraisForfait);
                $this->entityManager->flush();



            }
        }


        $formFraisHF = $this->createFormBuilder()
            ->add('libelle', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('montant', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('date', DateType::class , ['attr' => ['class' => 'form-control '], 'widget' => 'single_text'])
            ->add('Envoyer', SubmitType::class, ['attr' => ['class' => 'mt-3 btn bg-green-dark text-white']])
            ->getForm();

        $formFraisHF->handleRequest($request);
        if ($formFraisHF->isSubmitted() && $formFraisHF->isValid()) {

            // data is an array with "name", "email", and "message" keys
            $data = $formFraisHF->getData();

            $newFrais = new LigneFraisHorsForfait();

            $newFrais->setIdvisiteur($this->entityManager->getReference('App\Entity\User', $user));
           $newFrais->setDate($data['date']);
            $newFrais->setMontant($data['montant']);
            $newFrais->setLibelle($data['libelle']);
            $newFrais->setMois($mois);
            $this->entityManager->persist($newFrais);
            $this->entityManager->flush();


        }


        $fichefrai = new Fichefrais();
        $lesFraisHF = $lignefraishorsforfaitRepository->findBy([
            'idvisiteur'=> $user,
            'mois'=> $mois
        ]);


        return $this->render('fichefrais/new.html.twig', [
            'fichefrai' => $fichefrai,
            'form' => $form->createView(),
            'formFraisHF' => $formFraisHF->createView(),
            'fraisHF' =>$lesFraisHF
        ]);
    }

    /**
     * @Route("admin/{id}", name="fichefrais_show", methods={"GET"})
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


    /**
     * @Route("/show/list", name="fiche_frais_month")
     */
    public function showVisteurFicheByMonth(FicheFraisRepository $ficheFraisRepository)
    {

        $fiches = $ficheFraisRepository->findBy(['idvisiteur' => $this->getUser()]);

        return $this->render('fichefrais/user-list.html.twig', compact('fiches'));

    }

    /**
     * @Route("visiteur/show/fiche", name="fiche_frais_visiteur")
     */
    public function showVisiteurFiche(Request $request, FicheFraisRepository $ficheFraisRepository, LigneFraisForfaitRepository $ligneFraisForfaitRepository, LigneFraisHFRepository $lignefraishorsforfaitRepository)
    {
       $fichefrai = $ficheFraisRepository->find($request->get('idfrais'));

        $lesfraisF = $ligneFraisForfaitRepository->findBy([
            'visiteur' => $fichefrai->getIdvisiteur(), "mois" => $fichefrai->getMois()
        ]);

        $lesfraisHF = $lignefraishorsforfaitRepository->findBy([
            'idvisiteur' => $fichefrai->getIdvisiteur(), "mois" => $fichefrai->getMois()
        ]);

        return $this->render('fichefrais/show.html.twig',  compact('fichefrai', 'lesfraisHF', 'lesfraisF'));

    }



    /**
     * @Route("menu", name="fiche_frais_menu", methods={"GET"})
     */
    public function show_visiteur_menu()
    {

        return $this->render('visiteur/menu-fiches.html.twig', [
            'fichefrai' => $fichefrai,
            'form' => $form->createView(),
        ]);

    }

}

<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    /**
     * @Route("/entreprise", name="app_entreprise")
     */
    // public function index(ManagerRegistry $doctrine): Response
    // {
    //     // test en ajoutant un tableau
    //     // $tableau = ["valeur 1","valeur 2","valeur 3","valeur 4","valeur 5"];

    //     // fonction qui récupère les entreprises dans la BDO
    //     // https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database
    //     $entreprises = $doctrine->getRepository(Entreprise::class)->findAll();
    //     return $this->render('entreprise/index.html.twig', [
    //         'entreprises' => $entreprises

    //         // test en ajoutant à name, une valeur (ici -> Mickael)
    //         // 'name'=>'Mickael',
    //         // 'tableau' => $tableau
    //     ]);


    public function index(ManagerRegistry $doctrine): Response
    {
        $entreprises = $doctrine->getRepository(Entreprise::class)->findBy([], ["raisonSociale" => "DESC"]);
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    // ADD FORMULAIRE (mettre avant les recherches en détails -> risque de confusion)
    // bien mettre l'id dans la route si on veux cibler une donnée
    /**
     * @Route("/entreprise/add", name="add_entreprise")
     * @Route("/entreprise/{id}/edit", name="edit_entreprise")
     */
    public function add(ManagerRegistry $doctrine, Entreprise $entreprise = null, Request $request): Response {

        if(!$entreprise) {
            $entreprise = new Entreprise();
        }


        // construit un formulaire à partir d'un builder (EntrepriseType)
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        // récupère les données de l'objet pour les envoyer dans le formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et que les filtes ont été validés (fonctions natives de symfony)
        if($form->isSubmitted() && $form->isValid()) {

            $entreprise = $form->getData();
            // recupère depuis doctrine, le manager qui est initialisé (où se situe le persist et le flush)
            $entityManager = $doctrine->getManager();
            // équivalent tu prepare();
            $entityManager->persist($entreprise);
            // équivalent du execute() -> insert into
            $entityManager->flush();

            return $this->redirectToRoute('app_entreprise');
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('entreprise/add.html.twig', [
            // création d'une variable qui fait passer le formulaire qui a était créé visuellement
            'formAddEntreprise' => $form->createView(),
            'edit' => $entreprise->getId()
        ]);
    }

    // SUPPRESSION ENTREPRISE + EMPLOYE LIE
    /**
     * @Route("entreprise/{id}/delete", name="delete_entreprise")
     */
    public function delete(ManagerRegistry $doctrine, Entreprise $entreprise) {

        $entityManager = $doctrine->getManager();
        // enleve de la collection de la base de données
        $entityManager->remove($entreprise);
        $entityManager->flush();

        return $this->redirectToRoute('app_entreprise');
    }

    // ACTION 
    /**
     * @Route("/entreprise/{id}", name="show_entreprise")
     */
    public function show(Entreprise $entreprise): Response 
    {
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise
        ]);
    }

}

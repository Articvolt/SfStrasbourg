<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    /**
     * @Route("/employe", name="app_employe")
     */
    // VERSION FINDALL identique à FINDBY mais on ne peux pas trier par ordre croissant ou décroissant
    // réponse HTTP -> response
    public function index(ManagerRegistry $doctrine): Response
    {
        $employes = $doctrine->getRepository(Employe::class)->findAll();
        return $this->render('employe/index.html.twig', [
            'employes' => $employes
        ]);
    }

    // FONCTION QUI TRIE ORDRE CROISSANT NOM ET SEULEMENT CEUX DONT VILLE EST STRASBOURG
    // public function index(ManagerRegistry $doctrine): Response
    // {
    //     $employes = $doctrine->getRepository(Employe::class)->findBy(["ville" =>"Strasbourg"], ["nom" => "ASC"]);
    //     return $this->render('employe/index.html.twig', [
    //         'employes' => $employes
    //     ]);
    // }


    // ADD FORMULAIRE (mettre avant les recherches en détails -> risque de confusion)
    /**
     * @Route("/employe/add", name="add_employe")
     * @Route("/employe/{id}/edit", name="edit_employe")
     */
    public function add(ManagerRegistry $doctrine, Employe $employe = null, Request $request): Response {

        // construit un formulaire à partir d'un builder (EmployeType)
        $form = $this->createForm(EmployeType::class, $employe);
        // récupère les données de l'objet pour les envoyer dans le formulaire
        $form->handleRequest($request);

        // si le formulaire est soumis et que les filtes ont été validés (fonctions natives de symfony)
        if($form->isSubmitted() && $form->isValid()) {

            $employe = $form->getData();
            // recupère depuis doctrine, le manager qui est initialisé (où se situe le persist et le flush)
            $entityManager = $doctrine->getManager();
            // équivalent tu prepare();
            $entityManager->persist($employe);
            // équivalent du execute() -> insert into
            $entityManager->flush();

            return $this->redirectToRoute('app_employe');
        }

        // vue pour afficher le formulaire d'ajout
        return $this->render('employe/add.html.twig', [
            // création d'une variable qui fait passer le formulaire qui a était créé visuellement
            'formAddEmploye' => $form->createView(),
            'edit' => $employe->getId()
        ]);
    }

    // SUPPRESSION EMPLOYE
    /**
     * @Route("employe/{id}/delete", name="delete_employe")
     */
    public function delete(ManagerRegistry $doctrine, Employe $employe) {

        $entityManager = $doctrine->getManager();
        // enleve de la collection de la base de données
        $entityManager->remove($employe);
        $entityManager->flush();

        return $this->redirectToRoute('app_employe');
    }

    // RESPECTER LA SYNTAXE PRECISE DE LA ROUTE !
    /**
     * @Route("/employe/{id}", name="show_employe")
     */
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe
        ]);
    }
}

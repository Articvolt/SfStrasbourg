<?php

namespace App\Controller;

use App\Entity\Employe;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    /**
     * @Route("/employe", name="app_employe")
     */
    // VERSION FINDALL identique à FINDBY mais on ne peux pas trier par ordre croissant ou décroissant
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

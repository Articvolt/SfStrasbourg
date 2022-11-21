<?php

namespace App\Controller;

use App\Entity\Entreprise;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    /**
     * @Route("/entreprise", name="app_entreprise")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        // test en ajoutant un tableau
        // $tableau = ["valeur 1","valeur 2","valeur 3","valeur 4","valeur 5"];

        // fonction qui récupère les entreprises dans la BDO
        // https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database
        $entreprises = $doctrine->getRepository(Entreprise::class)->findAll();
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises

            // test en ajoutant à name, une valeur (ici -> Mickael)
            // 'name'=>'Mickael',
            // 'tableau' => $tableau
        ]);
    }
}

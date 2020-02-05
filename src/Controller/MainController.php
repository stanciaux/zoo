<?php

namespace App\Controller;

use App\Entity\Family;
use App\Form\FamilyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * @Route("/form/family", name="familyForm")
     */
    public function familyForm(EntityManagerInterface $em, Request $request)
    {
        $newFamily = new Family();
        $formFamily = $this->createForm(FamilyType::class, $newFamily);
        $formFamily->handleRequest($request);
        if ($formFamily->isSubmitted() && $formFamily->isValid()){
            $em->persist($newFamily);
            $em->flush();

            $this->addFlash("success", "New family (species) added");

        return $this->redirectToRoute('main');
        }
        return $this->render("formulaires/family.html.twig", ["formFamily"=> $formFamily->createView()]);
    }


}


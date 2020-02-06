<?php

namespace App\Controller;

use App\Entity\Family;
use App\Form\FamilyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FamilyController extends AbstractController
{
    /**
     * @Route("/family", name="family")
     */
    public function index()
    {
        return $this->render('family/addAnimals.html.twig', [
            'controller_name' => 'FamilyController',
        ]);
    }

    /**
     * @Route("/family/form", name="familyForm")
     */
    public function familyForm(EntityManagerInterface $em, Request $request)
    {
        $newFamily = new Family();
        $formFamily = $this->createForm(FamilyType::class, $newFamily);
        $formFamily->handleRequest($request);
        if ($formFamily->isSubmitted() && $formFamily->isValid()) {
            $em->persist($newFamily);
            $em->flush();

            $this->addFlash("success", "New species : ".$newFamily->getName()." added");

            return $this->redirectToRoute('main');
        }
        return $this->render("formulaires/family.html.twig", ["formFamily" => $formFamily->createView()]);
    }

    /**
     * @Route("/family/list", name="familyList")
     */
    public function familyList(EntityManagerInterface $em)
    {
    $familyRepository = $em->getRepository(Family::class);
    $familyList = $familyRepository->findAll();

    return $this->render("gestion/familyList.html.twig", ["familyList" => $familyList]);
    }

    /**
     * @Route("/family/list/delete/{id}", name="familyDelete")
     */
    public function familyDelete($id=null, EntityManagerInterface $em, Request $request)
    {
        $family = $em->getRepository(Family::class)->find($id);
        $em->remove($family);
        $em->flush();
        $this->addFlash("success", "Species : ".$family->getName()." deleted");
        return $this->redirectToRoute('familyList');
    }

}

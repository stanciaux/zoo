<?php

namespace App\Controller;

use App\Entity\Zone;
use App\Form\ZoneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/zone/add", name="addZone")
     */
    public function addZone(EntityManagerInterface $em, Request $request)
    {
        $zone = new Zone();
        $form = $this->createForm(ZoneType::class, $zone);

        // Traitement du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($zone);
            $em->flush();

            $this->addFlash('success', "Une zone vient d'être ajoutée.");
            return $this->redirectToRoute('main');
        }

        // Préparation du formulaire à envoyer au twig
        return $this->render('formulaires/zone.html.twig', [
            "zoneForm" => $form->createView()]);
    }

    /**
     * @Route("/zone/delete/{id}", name="deleteZone")
     */
    public function deleteZone(EntityManagerInterface $em, Request $request, $id)
    {
        // Sélectionner la zone par son id sur la page
        $zoneRepository = $em->getRepository(Zone::class);
        $zone =$zoneRepository->find($id);
        $zone->remove();
        $zone->flush();

        $form = $this->createForm(ZoneType::class, $zone);

        $form->handleRequest($request);

    }
}

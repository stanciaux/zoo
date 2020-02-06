<?php

namespace App\Controller;

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
     * @Route("/addZone", name="addZone")
     */
    public function addZone(EntityManagerInterface $em, Request $request)
    {
        $zone = new Zone();
        $form = $this->createForm(ZoneType::class, $zone);

        // Traitement du formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/deleteZone/{id}", name="deleteZone")
     */
    public function deleteZone(EntityManagerInterface $em, Request $request, $id)
    {
        // Sélectionner la zone par son id sur la page
        $zone = $em->getRepository(Zone::class)->find($id);

        // Affichage d'un message de succès de suppression
        $this->addFlash('success', 'La zone ' . $zone->getName() . ' a été supprimée avec succès.');

        // Préparation de la suppression et suppression en bdd
        $zone->remove();
        $zone->flush();

        $this->redirectToRoute('main');

    }
}


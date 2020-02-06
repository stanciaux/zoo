<?php

namespace App\Controller;

use App\Entity\Zone;
use App\Form\ZoneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ZoneController extends AbstractController
{


    /**
     * @Route("/zone/list/delete/{id}", name="zoneDelete")
     */

    public function deleteZone(EntityManagerInterface $em, $id)
    {
        // Sélectionner la zone par son id sur la page
        $zone = $em->getRepository(Zone::class)->find($id);

        // Préparation de la suppression et suppression en bdd
        $em->remove($zone);
        $em->flush();

        // Affichage d'un message de succès de suppression
        $this->addFlash("success", "Zone : ".$zone->getName()." deleted");

        return $this->redirectToRoute('zonesList');
    }

    /**
     * @Route("/zone/list", name="zonesList")
     */
    public function zonesList(EntityManagerInterface $em)
    {
        $zones = $em->getRepository(Zone::class)->findBy([], ["name" => "ASC"]);

        return $this->render('gestion/zonesList.html.twig', [
            'zones' => $zones
        ]);
    }

    /**
     * @Route("/zone/form", name="zoneForm")
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
            return $this->redirectToRoute('zonesList');
        }

        // Préparation du formulaire à envoyer au twig
        return $this->render('formulaires/addZone.html.twig', [
            "zoneForm" => $form->createView()]);
    }

}

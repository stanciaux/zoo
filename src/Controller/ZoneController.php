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
     * @Route("/zone", name="zone")
     */
    public function index()
    {
        return $this->render('zone/addAnimals.html.twig', [
            'controller_name' => 'ZoneController',
        ]);
    }


    /**
     * @Route("/zonesList", name="zonesList")
     */

    public function zonesList(EntityManagerInterface $em)
    {
        $zones = $em->getRepository(Zone::class)->findAll();

        return $this->render('/gestion/zonesList.html.twig', [
            'zones' => $zones
        ]);
    }
//
// public function list(EntityManagerInterface $em)
//    {
//
//        $ideesRepository = $em->getRepository(idees::class);
//        $idees =$ideesRepository->findBy([], ["dateCreated" => "DESC"]);
//
//        return $this->render('idea/idea.html.twig', [
//            'idees' => $idees
//        ]);
//    }
//
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
        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($zone);
            $em->flush();

            $this->addFlash('success', "Une zone vient d'être ajoutée.");
            return $this->redirectToRoute('main');
        }

        // Préparation du formulaire à envoyer au twig
        return $this->render('formulaires/addZone.html.twig', [
            "zoneForm" => $form->createView()]);
    }

    /**
     * @Route("/deleteZone/{id}", name="deleteZone")
     */
    public function deleteZone(EntityManagerInterface $em, $id)
    {
        // Sélectionner la zone par son id sur la page
        $zone = $em->getRepository(Zone::class)->find($id);

        // Préparation de la suppression et suppression en bdd
        $em->remove($zone);
        $em->flush();

        // Affichage d'un message de succès de suppression
        $this->addFlash("success", "La zone ".$zone->getName()." a été supprimée avec succès.");

        return $this->redirectToRoute('main');
    }
}

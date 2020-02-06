<?php

namespace App\Controller;

use App\Entity\Animal;

use App\Form\AnimalType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    /**
     * @Route("/animal", name="animalAdd")
     */
    public function addAnimal(EntityManagerInterface $em, Request $request)
    {
        $animal = new Animal();
        //on crée un formulaire à partir de l'objet ci-dessus
        $form = $this->createForm(AnimalType::class, $animal);
        //traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($animal);
            //on crée un flash à afficher
            $this->addFlash("success", "A new animal has been added");

            $em->flush();
            return $this->redirectToRoute('animalList');
        }




        return $this->render('formulaires/addAnimals.html.twig', [
            'animalForm'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/animal/list", name="animalList")
     */
    public function listAnimal(EntityManagerInterface $em)
    {
        $animalRepository = $em->getRepository(Animal::class);
        $animals = $animalRepository->findAll();

        return $this->render('gestion/listAnimals.html.twig', [
                'animals' => $animals,
        ]);
    }

    /**
     * @Route("/animal/delete/{id}", name="animalDelete")
     */
    public function delete($id = null, EntityManagerInterface $em, Request $request)
    {
        $animal = $em->getRepository(Animal::class)->find($id);
        $em->remove($animal);
        $em->flush();
        $this->addFlash('success', 'Animal ' . $animal->getName() . ' Removed');
        return $this->redirectToRoute('animalList');
    }
}

<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    /**
     * @Route("/employee/add", name="employee")
     */
    public function addEmployee($id = null, EntityManagerInterface $em, Request $request)
    {
        if ($id == null){
            //on instancie un objet Employee
            $newEmployee = new Employee();
        }else{
            //sinon on va le chercher en bdd
            $newEmployee = $em->getRepository(Employee::class)->find($id);
        }

        //on crée un formulaire à partir de l'objet ci-dessus
        $form = $this->createForm(EmployeeType::class, $newEmployee);
        //traitement du formulaire
        $form->handleRequest($request);

        //vérifier la soumission et la validité des informations
        if ($form->isSubmitted() && $form->isValid()){
            if ($id == null){
                $em->persist($newEmployee);
                //on crée un flash à afficher
                $this->addFlash("success", "Un nouvel employé a été ajouté");
            }else{
                $this->addFlash("success", "Un employé a été modifié");
            }
            //on envoie l'objet en bdd
            $em->flush();
            return $this->redirectToRoute('main');
        }

        return $this->render('formulaires/addEmployee.html.twig', [
            'employeeForm'=> $form->createView(),

        ]);
    }

    /**
     * @Route("/employee/list", name="listEmployee")
     */
    public function listEmployees(EntityManagerInterface $em){

        $employeeRepository = $em->getRepository(Employee::class);
        $employees = $employeeRepository->findAll();

        return $this->render('gestion/listEmployees.html.twig', [
            'employees'=> $employees
        ]);
    }
}

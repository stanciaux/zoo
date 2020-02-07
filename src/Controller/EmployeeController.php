<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmployeeController extends AbstractController
{
    /**
     * @Route("/employee/form", name="employeeForm")
     * @param $function
     * @param null $id
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addEmployee(UserPasswordEncoderInterface $passwordEncoder, $id = null, EntityManagerInterface $em, Request $request)
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
            $hash = $passwordEncoder->encodePassword($newEmployee, $newEmployee->getPassword());
            $newEmployee->setPassword($hash);
            if ($id == null){
                $em->persist($newEmployee);

                //on crée un flash à afficher
                $this->addFlash("success", "A new employee has been added");
            }else{
                $this->addFlash("success", "The employee ".$newEmployee->getName()." has been modified");
            }
            //on envoie l'objet en bdd
            $em->flush();
            return $this->redirectToRoute('employeeList');
        }

        return $this->render('formulaires/addEmployee.html.twig', [
            'employeeForm'=> $form->createView(),

        ]);
    }

    /**
     * @Route("/employee/list", name="employeeList")
     */
    public function listEmployees(EntityManagerInterface $em){

        $employeeRepository = $em->getRepository(Employee::class);
        $employees = $employeeRepository->findAll();

        return $this->render('gestion/listEmployees.html.twig', [
            'employees'=> $employees
        ]);
    }

    /**
     * @Route("/employee/delete/{id}", name="employeeDelete")
     */
    public function delete($id = null, EntityManagerInterface $em, Request $request)
    {
        $employee = $em->getRepository(Employee::class)->find($id);
        $em->remove($employee);
        $em->flush();
        $this->addFlash('success', 'Employee ' . $employee->getName() . ' Removed');
        return $this->redirectToRoute('employeeList');
    }
}

<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('username')
            //affichage des choix possibles
            ->add('roles', ChoiceType::class,
                [
                    // Clef => Valeurs
                    'choices' => [
                        'Etho' => "ROLE_ETHOLOGUE",
                        'User' => "ROLE_USER",
                        'Admin' => "ROLE_ADMIN"
                    ],
            //transforme les choix en "checkbox"
                    'multiple' => true,
            // permet la selection multiple
                    'expanded' => true
                ]
            )
            ->add('password', PasswordType::class)
            ->add('Add', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'roles', ChoiceType::class, [
                    'choices' => ['Usuario Administrador' => 'ROLE_ADMIN', 'Usuario Normal' => 'ROLE_USER'],
                    'expanded' => true,
                    'multiple' => true,
                ]
            )
            ->add('Salvar', SubmitType::class,[

            ]);

    }
    public function configureOptions(OptionsResolver $resolver)
{
    //$resolver->setDefaults([
      //  'data_class' => User::class,
    //]);
}
}
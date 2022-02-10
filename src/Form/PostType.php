<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Postagem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imagem',FileType::class,[
                'label' => 'Imagem',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Imagem',
                    'multiple' => true
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label'=>'Album',
                'multiple' => true,
            ])
            ->add('Submit', SubmitType::class,[

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Postagem::class,
        ]);
    }
}

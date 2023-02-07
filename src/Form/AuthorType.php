<?php

namespace App\Form;

use App\Document\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, ['label' => 'Name','attr' => ['placeholder' => 'Enter the Name of the author'], ])
            ->add('Sexe', TextType::class, ['label' => 'Sexe', 'attr' => ['placeholder' => 'Enter the Sexe of the author'],])
            ->add('BirthDate', DateType::class, [ 'label' => 'Birth Date',])
            ->add('Nationality', TextType::class, ['label' => 'Nationality', 'attr' => ['placeholder' => 'Enter the Nationality of the author'],]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}

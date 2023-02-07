<?php

namespace App\Form;

use App\Document\Books;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Title', 'attr' => ['placeholder' => 'Enter the title of the book'],])
            ->add('pages', IntegerType::class, ['label' => 'Pages', 'attr' => ['placeholder' => 'Enter the number of pages'],])
            ->add('publicationDate', DateType::class, ['label' => 'Publication Date',])
            ->add('genre', TextType::class, ['label' => 'Genre', 'attr' => ['placeholder' => 'Enter the genre of the book'],])
            ->add('author', AuthorType::class, ['label' => false,])
            ->add("Add",SubmitType::class)
            ->add("Reset",ResetType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Books::class,
        ]);
    }
}
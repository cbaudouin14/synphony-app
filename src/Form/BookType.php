<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('author')
            ->add('stock', IntegerType::class, [
                'attr' => ['min' => 0]
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo du livre',
                'mapped' => false,  // ne mappe pas directement sur l'entité
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void // ici options suppl'
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'required' => false
        ]);
    }
}

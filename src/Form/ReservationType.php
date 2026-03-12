<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\BookRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateTimeType::class, [
                'label'   => 'Date de début',
                'widget' => 'single_text',
            ])

            ->add('expectedEndDate', DateTimeType::class, [
                'label'  => 'Date de retour prévue',
                'widget' => 'single_text',
            ])

            ->add('book', EntityType::class, [
                'class' => Book::class,
                'choice_label' => 'title', // plus logique que id
                'multiple' => true,
                'by_reference' => false,
                'query_builder' => function (BookRepository $br) {
                    return $br->createQueryBuilder('b')
                        ->where('b.available = :val')
                        ->setParameter('val', true);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'required' => false
        ]);
    }
}
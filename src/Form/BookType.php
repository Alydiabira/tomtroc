<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('author', TextType::class, [
                'label' => 'Auteur',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            
            ->add('available', ChoiceType::class, [
                'label' => 'Disponibilité',
                'choices' => [
                    'Disponible' => true,
                    'Non disponible' => false,
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-select rounded-pill'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}

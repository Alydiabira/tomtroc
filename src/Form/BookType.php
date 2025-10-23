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
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Roman' => 'Roman',
                    'BD' => 'BD',
                    'Essai' => 'Essai',
                    'Thriller' => 'Thriller',
                    'Fantasy' => 'Fantasy',
                    'Science-fiction' => 'Science-fiction',
                    'Développement personnel' => 'Développement personnel',
                ],
            ])
            ->add('available', CheckboxType::class, [
                'label' => 'Disponible',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}

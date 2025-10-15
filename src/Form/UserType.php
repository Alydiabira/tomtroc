<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Nom complet',
                'attr' => ['class' => 'form-control rounded-pill'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => ['class' => 'form-control rounded-pill'],
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => ['class' => 'form-control rounded-pill'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message personnel',
                'required' => false,
                'attr' => ['class' => 'form-control rounded-pill', 'rows' => 4],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'required' => false,
                'attr' => ['class' => 'form-control rounded-pill'],
            ]);
            // Tu peux ajouter 'avatar' ici si tu veux gérer l'upload dans ce formulaire
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class GuestAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('guestName', TextType::class, ["mapped"=>false,'label' => false, 'attr' => ['placeholder' => 'Prénom de l\'invité(e)']])
        ->add('email', EmailType::class, ['label' => false, 'attr' => ['placeholder' => 'son adresse email']])
        ->add('hash', HiddenType::class, ['label' => false, 'attr' => ['value' => 'password']])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

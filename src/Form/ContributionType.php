<?php

namespace App\Form;

use App\Entity\Contribution;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContributionType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('type', TextType::class, $this->getConfiguration('Categorie', 'Choisissez une categorie'))
            ->add('type', ChoiceType::class, [
                'label' => 'Categorie',
                'attr' => [
                    'placeholder' => 'Choisissez une categorie',
                    'class' => 'form-control form-control-sm',
                ],
                'choices' => [
                    'Entrée' => 'Entree',
                    'Plats' => 'Plats',
                    'Dessert' => 'Desserts',
                    'Aperos' => 'Aperos',
                    'Boissons' => 'Boissons',
                    'Jeux' => 'Jeux',
                ],
            
            ])

            ->add('designation', TextType::class, $this->getConfiguration('Description', ''))
            ->add('quantity', TextType::class, $this->getConfiguration('Quantité', ''))
            ->add('unit', ChoiceType::class,[
                'label' => 'Unité',
                'choices' => [
                    '- -' => '- -',
                    'Bouteille(s)' => 'Bouteille(s)',
                    'Pack' => 'Pack',
                    'Part(s)' => 'Part(s)',
                    'Portio' => 'Portio',
                    'Litre(s)' => 'Litre(s)',
                    'kg' => 'kg',
                ],
            ])

            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => "Ajouter",
                    'attr' => [
                        'class' => 'btn validationButton bgColorTwo border-0'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contribution::class,
            'attr' => [
                'class' => 'formulaireStyle',
            ]
             ]);
    }
}

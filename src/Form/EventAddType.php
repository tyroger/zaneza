<?php

namespace App\Form;

use App\Entity\Event;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventAddType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'date',
                DateTimeType::class,
                [
                    'placeholder' => [
                        'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                        'hour' => 'Heure', 'minute' => 'Minute',
                    ],
                    'format' => 'n-j-Y kk:mm',
                    'html5' => false,
                    'attr' => [
                        'class' => 'd-flex justify-content-center'
                    ]
                ]
            )
            ->add('theme', TextType::class, $this->getConfiguration("un thème? : ", "Choississez un thème"))
            ->add('address', TextareaType::class, $this->getConfiguration("L'adresse : ", "Entrez une adresse"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

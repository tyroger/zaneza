<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom : ", "Entrez votre prenom"))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom : ", "Entrez votre Nom"))
            ->add('email', EmailType::class, $this->getConfiguration("Email : ", "Entrez votre adresse Email"))
            // ->add('birthday', BirthdayType::class,$this->getConfiguration("Date de naissance : ",""))
            ->add('birthday', BirthdayType::class,[
                "widget" => 'single_text',
                'label'=> "Date de naissance :",
                "format" => 'yyyy-MM-dd',
                ])
            ->add('department',IntegerType::class,$this->getConfiguration('Département : ',"Le numéro de votre département"))
            ->add('city', TextType::class,$this->getConfiguration("Ville : ","Entrez le nom de votre ville"));
            // ->add('avatar',UrlType::class, $this->getConfiguration("Photo de profil","télécharger une photo"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class,[
                'attr' => ['class' => 'form-control', 'placeholder' => 'Adresse Email', 'autocomplete'=>'off'],
                'label' => "Adresse email",
                'constraints' => [
                    new Assert\NotBlank(['message' => "L'adresse email est obligatoire"]),
                    new Assert\Email(['message' => 'L\'adresse {{ value }} n\'est pas une adresse valide. '])
                ]

            ])
            ->add('roles', ChoiceType::class,[
                'attr' => ['class' => 'form-check-input'],
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Super administrateur' => 'ROLE_SUPER_ADMIN',
                    'Utilisateur' => 'ROLE_USER'
                ],
                'multiple'=> true,
                'expanded'=>true,
                'constraints' => [
                    new Assert\NotBlank(['message' => "Veuillez choisir un rôle pour cet utilisateur."])
                ]
            ])
            ->add('password', PasswordType::class,[
                'attr' => ['class' => 'form-control', 'placeholder'=>"Mot de passe"],
                'label' => "Mot de passe",
                'constraints' => [
                    new Assert\NotBlank(['message' => "Le mot de passe ne peut être vide."])
                ]
            ])
//            ->add('connexion')
//            ->add('lastConnectedAt', null, [
//                'widget' => 'single_text',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

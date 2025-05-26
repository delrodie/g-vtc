<?php

namespace App\Form;

use App\Entity\Chauffeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChauffeurForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('matricule')
            ->add('nom', TextType::class,[
                'attr' => ['class' => 'form-control', 'placeholder'=>"Nom & prenom", "autocomplete"=>"off"],
                'label' => "Idenité"
            ])
            ->add('telephone', TelType::class,[
                'attr' => ['class' => 'form-control', 'placeholder'=>"Téléphone", "autocomplete"=>"off"],
                'label' => "Numéro de téléphone"
            ])
            ->add('permis', TextType::class,[
                'attr' =>['class' => "form-control", 'placeholder'=>"Permis de conduire", "autocomplete"=>"off"],
                'label' => "Numero du permis de conduire"
            ])
            ->add('nouvelleAttribution', AttributionForm::class,[
                'mapped' => false,
                'label' => "Attribution du véhicule",
                'required' => false,
                'label_attr' => [
                    'class' => 'form-label mt-4 mb-3 fst-italic fw-bold',
                    'style'=>'color: var(--color-gdark30);'
                ],
                'data' => $options['conduire']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chauffeur::class,
            'conduire' => null
        ]);
        $resolver->setAllowedTypes('conduire', ['App\Entity\Conduire', 'null']);
    }
}

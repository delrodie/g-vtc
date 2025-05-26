<?php

namespace App\Form;

use App\Entity\Chauffeur;
use App\Entity\Conduire;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebutAt', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date debut',
//                'format' => 'yyyy-MM-dd',
            ])
            ->add('montantRecette', IntegerType::class,[
                'attr' => ['class' => 'form-control', 'placeholder'=>"Montant de la recette", 'autocomplete'=>"off"],
                'label' => "Recette à verser"
            ])
            ->add('statut', CheckboxType::class,[
                'attr'=>['class' =>'form-check-input', 'checked'=>true],
                'label' => 'Activé'
            ])
//            ->add('chauffeur', EntityType::class, [
//                'class' => Chauffeur::class,
//                'choice_label' => 'id',
//            ])
            ->add('vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => 'immatriculation',
                'autocomplete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conduire::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Medecin;
use App\Entity\Vente;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('medecin', EntityType::class, [
                'class' => Medecin::class,
                'choice_label' => function($medecin){
                    return $medecin->getNom();
                },
                'multiple' => false,
                'placeholder' => 'Selectionnez un medecin',
                'label' => false,
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vente::class,
        ]);
    }
}

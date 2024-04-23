<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Fournisseur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference')
            ->add('designation')
            ->add('prix')
            ->add('description')
            ->add('Fournisseur', EntityType::class, [
                'class' => Fournisseur::class,
                'choice_label' => function($fournisseur){
                    return $fournisseur->getNom();}
            ])
//            ->add('quantite')
//            ->add('lot')
//            ->add('peremption', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('prixpublic')
//            ->add('stock')
//            ->add('creation', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('mincommande')
            ->add('tva')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Produit;
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
            ->add('telephone')
            ->add('adresse')
            ->add('prix')
            ->add('description')
            ->add('fabricant')
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

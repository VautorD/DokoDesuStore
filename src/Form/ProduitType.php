<?php

namespace App\Form;

use App\Entity\Boutique;
use App\Entity\CategorieP;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('marque')
            ->add('description')
            ->add('img')
            ->add('categorieP', EntityType::class, [
                'class' => CategorieP::class,
                'choice_label' => 'id',
            ])
            ->add('boutique', EntityType::class, [
                'class' => Boutique::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

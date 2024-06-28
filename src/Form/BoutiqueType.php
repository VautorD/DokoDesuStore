<?php

namespace App\Form;

use App\Entity\Boutique;
use App\Entity\CategorieB;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoutiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('num_siret')
            ->add('adresse')
            ->add('cp')
            ->add('ville')
            ->add('img')
            ->add('tel')
            ->add('description')
            ->add('categorieB', EntityType::class, [
                'class' => CategorieB::class,
                'label' => 'Categorie',
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boutique::class,
        ]);
    }
}

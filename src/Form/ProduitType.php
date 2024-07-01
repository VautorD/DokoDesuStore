<?php

namespace App\Form;

use App\Entity\Boutique;
use App\Entity\CategorieP;
use App\Entity\Produit;
use App\Repository\CategoriePRepository;
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
            ->add('categorie_p', EntityType::class, [
                'class' => CategorieP::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie',
                'group_by' => 'parent.nom',
                'query_builder' => function(CategoriePRepository $categoriePRepository)
                {
                    return $categoriePRepository->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL')
                        ->orderBy('c.nom', 'ASC');
                }
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

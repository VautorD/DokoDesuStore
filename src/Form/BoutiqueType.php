<?php

namespace App\Form;

use App\Entity\Boutique;
use App\Entity\CategorieB;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class BoutiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => 'Nom de la boutique',
            ])
            ->add('num_siret', null, [
                'label' => 'Numéro SIRET',
            ])
            ->add('adresse', null, [
                'label' => 'Adresse',
            ])
            ->add('cp', null, [
                'label' => 'Code Postal',
            ])
            ->add('ville', null, [
                'label' => 'Ville',
            ])
            ->add('img', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPG or PNG)',
                    ])
                ],
            ])
            ->add('tel', null, [
                'label' => 'Téléphone',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
            ->add('categorieB', EntityType::class, [
                'class' => CategorieB::class,
                'label' => 'Catégorie',
                'choice_label' => 'nom',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boutique::class,
        ]);
    }
}

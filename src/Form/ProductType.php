<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Aroma;
use App\Entity\Height;
use App\Entity\Product;
use App\Entity\Nicotine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name'  )
            ->add('price' )
            ->add('detail' )
            ->add('reference')
            ->add('Aroma', EntityType::class, [
                'class' => Aroma::class,
                'choice_label' => 'category_name',
            ])
            ->add('Nicotine', EntityType::class, [
                'class' => Nicotine::class,
                'choice_label' => 'proportioning',
            ])
            ->add('capacity', EntityType::class, [
                'class' => Height::class,
                'choice_label' => 'milliliter',
                'mapped' => false
            
            ])
            ->add('picture', FileType::class,[
                'label' => 'image',
                'multiple' => true,
                'mapped' => false,
                
                'required' => false,
                'attr' => [
                    'accept' => '.jpg, .jpeg'
                ] 
            ])
            ->add('Valider' , SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

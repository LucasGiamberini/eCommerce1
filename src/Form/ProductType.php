<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Aroma;
use App\Entity\Height;
use App\Entity\Product;
use App\Entity\Nicotine;
use App\Form\NicotineChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
            ->add('Nicotines', EntityType::class, [
                'class' => Nicotine::class,
                'choice_label' => 'proportioning',
                'label' => 'nicotine',
              'multiple' => true,
              'expanded' => true,
            ])
              ->add('capacity', EntityType::class, [
                'class' => Height::class,
                'choice_label' => 'milliliter',
                'mapped' => false,
                'multiple' => false,
            ])
            ->add('picture', FileType::class,[
                'label' => 'image',
                'multiple' => true,
                'mapped' => false,
                
                'required' => false,
                'attr' => [              
                    'accept' => '.jpg, .jpeg , .webp'
                ] ,
      //          'constraints' => [
        //           new File([
            //           'maxSize' => '400k', // Limite de taille de l'image (ici, 400 Ko)
          //            'maxSizeMessage' => 'La taille de l\'image ne doit pas dépasser 400 Ko.',
           //         ])
             //   ],
            ])
            ->add('Valider' , SubmitType::class,[
                'attr' => [ 'id' => 'blueButton']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Review;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note',IntegerType::class, ['label' => 'Note/5','attr' => [ 'min' => 1 , 'max' => 5 ], 'label_attr' => ['class' => 'label']])
            ->add('commentary',TextareaType::class ,[ 'label' => 'Commentaire', 'label_attr' => ['class' => 'label']])
       //     ->add('reviewDate')
       //     ->add('account', EntityType::class, [

    //        ->add('Product', EntityType::class, [
 
           
        ->add('Valider', SubmitType::Class,
            [ 'attr' => ['class' => 'blueButton' ] ])   
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}

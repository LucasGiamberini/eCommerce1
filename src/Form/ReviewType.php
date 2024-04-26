<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Review;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note',IntegerType::class, [ 'attr' => [ 'min' => 1 , 'max' => 5 ]])
            ->add('commentary')
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
